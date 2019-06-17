<template>
<new-page-template
    ref="page"
    :has-title="false"
    :custom-save="saveButton"
    :model="model"
    :modelIdx="modelIdx"
    @save-page="savePost"
>
    <div class="page-template__content">
        <ui-title
            :title="type.create"
            :is-column="true"
            :has-padding="false"
            class="mb-5"
        />
        <dynamic-module
            v-if="module"
            :uuid="uuid"
            name="post"
            :is-edit="false"
            :options="module.options"
            @changed="setObj"
        />
    </div>
</new-page-template>
</template>

<script>
import DynamicModule from '../components/DynamicModule.vue'
import DynamicParams from '../DynamicParams'
import NewPageTemplate from '../containers/NewPageTemplate.vue'
import PostsOpts from '../postsopts/PostsOpts'
import {
    UiTitle
}
from '../../ui'
import {
    Uuid
}
from '../../Utilities'
export default {
    name: 'PostCreate',
    components: {
        DynamicModule,
        NewPageTemplate,
        UiTitle,
    },
    data: function () {
        return {
            model: null,
            modelIdx: null,
            postObj: {}
        }
    },
    computed: {
        uuid: function () {
            return Uuid.get()
        },
        type: function () {
            return PostsOpts.find(opt => opt.slug === this.$route.params.type)
        },
        module: function () {
            let name = 'post-' + this.type.table
            return DynamicParams.find(params => params.name === name)
        },
        saveButton: function () {
            return 'Salva ' + this.type.single
        }
    },
    methods: {
        setObj: function (obj) {
            this.postObj = obj
        },
        savePost: function () {
            let url = '/api/admin/post-type/save'
            this.postObj.model = this.type.model

            if (this.modelIdx) {
                this.postObj.id = this.modelIdx
            }

            let data = this.formatRequest(this.postObj)

            // for (let value of data) {
            //     console.log('post', value[0], value[1]);
            // }

            let request = this.$http.post(url, data)
                .then(response => {
                    // console.log('response', response);
                    if (response.data.success) {
                        this.modelIdx = Number(response.data.post.id)
                        this.model = 'App\\' + this.type.model.charAt(0)
                            .toUpperCase() + this.type.model.slice(1)

                        this.$nextTick(() => {
                            this.$refs.page.savePage(false)
                        })
                    }
                })
        },
        formatRequest: function (obj) {
            let form = new FormData()
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (key === 'thumb' && this.hasFile(obj[key])) {
                        form.append('file', obj[key])
                    }
                    else {
                        form.append(key, obj[key])
                    }
                }
            }
            return form
        },
        hasFile: function (obj) {
            // https://stackoverflow.com/questions/31525667/check-if-variable-holds-file-or-blob
            if (obj instanceof File) {
                return true
            }
            return false
        },
    },
    mounted: function () {
        // console.log(this.type);
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
