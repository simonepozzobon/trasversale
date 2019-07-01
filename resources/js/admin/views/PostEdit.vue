<template>
<new-page-template
    ref="page"
    :has-title="false"
    :custom-save="saveButton"
    :model="model"
    :modelIdx="modelIdx"
    :modules="modules"
    :sidebar-idx="sidebarIdx"
    :sidebarModules="sidebarModules"
    :is-post="true"
    :has-slot="true"
    @save-page="savePost"
    @before-save="savePost"
>
    <div class="page-template__content">
        <ui-title
            :title="title"
            :is-column="true"
            :has-padding="false"
            class="mb-5"
        />
        <dynamic-module
            v-if="this.module && this.initialised"
            name="post"
            :uuid="uuid"
            :is-edit="false"
            :options="module.options"
            :values="values"
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
            initialised: false,
            model: null,
            modelIdx: null,
            sidebarModules: [],
            sidebarIdx: 0,
            postObj: {},
            modules: [],
            values: null,
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
        },
        title: function () {
            return 'Modifica ' + this.type.single
        }
    },
    methods: {
        getPost: function () {
            this.modelIdx = Number(this.$route.params.id)
            this.model = 'App\\' + this.type.model.charAt(0).toUpperCase() + this.type.model.slice(1)

            let url = '/api/admin/post-type/' + this.type.model + '/' + this.$route.params.id
            this.$http.get(url)
                .then(response => {
                    console.log('get post', response);
                    if (response.data.success) {
                        let post = Object.assign({}, response.data.post)
                        this.setInitialValues(post)
                        this.modules = response.data.post.modules
                        this.sidebarIdx = response.data.sidebar ? response.data.sidebar.id : 0
                        this.sidebarModules = response.data.sidebar ? response.data.sidebar.modules : []
                    }
                })
        },
        setInitialValues: function (post) {
            this.values = {
                title: post.title,
                price: post.price,
                slug: post.slug.slug,
                preview: post.thumb,
                category: post.category.id,
            }

            if (post.hasOwnProperty('hours')) {
                this.values = {
                    ...this.values,
                    hours: post.hours,
                }
            }
            if (post.hasOwnProperty('start_at')) {
                this.values = {
                    ...this.values,
                    start_at: post.start_at,
                }
            }
            if (post.hasOwnProperty('end_at')) {
                this.values = {
                    ...this.values,
                    end_at: post.end_at,
                }
            }

            this.$nextTick(() => {
                this.initialised = true
            })
        },
        setObj: function (obj) {
            this.postObj = obj
        },
        savePost: function (ref) {
            let url = '/api/admin/post-type/save'
            this.postObj.model = this.type.model
            this.postObj.id = this.$route.params.id

            console.log('before save', this.postObj);
            let data = this.formatRequest(this.postObj)
            this.$http.post(url, data)
                .then(response => {
                    console.log('response', response.data);
                    if (response.data.success) {
                        this.modelIdx = Number(response.data.post.id)
                        this.model = 'App\\' + this.type.model.charAt(0).toUpperCase() + this.type.model.slice(1)
                        this.$nextTick(() => {
                            let page = this.$refs.page
                            page.$refs[ref].savePage(null, true)
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
        this.getPost()
        // console.log(this.type);
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
