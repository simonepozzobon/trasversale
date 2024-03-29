<template>
<new-page-template
    ref="page"
    :has-title="false"
    :custom-save="saveButton"
    :model="model"
    :modelIdx="modelIdx"
    :sidebar-idx="sidebarIdx"
    :sidebarModules="sidebarModules"
    :is-post="true"
    :has-slot="true"
    @before-save="savePost"
    @delete-all="deleteAll"
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
            name="post"
            :uuid="uuid"
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

import moment from 'moment'

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
            sidebarModules: [],
            sidebarIdx: 0,
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
        savePost: function (ref, modules = 0) {
            console.log('saving post');
            let url = '/api/admin/post-type/save'
            this.postObj.model = this.type.model

            if (this.modelIdx) {
                this.postObj.id = this.modelIdx
            }
            // console.log('before save', this.postObj);
            let data = this.formatRequest(this.postObj)
            this.$http.post(url, data).then(response => {
                // console.log('response', response.data);
                if (response.data.success) {
                    this.modelIdx = Number(response.data.post.id)
                    this.model = 'App\\' + this.type.model.charAt(0).toUpperCase() + this.type.model.slice(1)
                    this.$nextTick(() => {
                        let page = this.$refs.page

                        if (modules <= 0) {
                            page.$refs[ref].$emit('notify', {
                                uuid: Uuid.get(),
                                title: 'Pagina Salvata',
                                message: 'Salvataggio Completato'
                            })
                            page.$refs[ref].saveDisabled = false
                            console.log('create');
                        }
                        else {
                            console.log('ci sono dei moduli da salvare');
                            page.$refs[ref].savePage(null, true)
                        }
                    })
                }
            }).catch(err => {
                console.log('err');
                let page = this.$refs.page
                page.$refs[ref].$emit('notify', {
                    uuid: Uuid.get(),
                    title: 'Errore',
                    message: 'Non completato'
                })
                page.$refs[ref].saveDisabled = false
            })
        },
        formatRequest: function (obj) {
            let form = new FormData()
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    if (key === 'thumb' && this.hasFile(obj[key])) {
                        form.append('file', obj[key])
                    }
                    else if (key === 'start_at' || key === 'end_at') {
                        let data = moment(obj[key]).format('YYYY-MM-DD HH:mm:ss')
                        form.append(key, data)
                    }
                    else if (key === 'published_at') {
                        if (obj[key]) {
                            let data = moment(obj[key]).format('YYYY-MM-DD HH:mm:ss')
                            form.append(key, data)
                        }
                        else {
                            let data = moment(obj[key]).format('YYYY-MM-DD HH:mm:ss')
                            form.append(key, data)
                        }
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
        deleteAll: function () {
            this.modules = []
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
