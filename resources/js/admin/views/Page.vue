<template lang="html">
    <new-page-template
        ref="page"
        :title="title"
        :model="model"
        :model-idx="idx"
        :modules="this.modules"
        @saved="saved"
        @deleted="deleted"
        :has-sidebar="true">
    </new-page-template>
</template>

<script>
import NewPageTemplate from '../containers/NewPageTemplate.vue'
import ModuleManager from '../../containers/ModuleManager.vue'

export default {
    name: 'Page',
    components: {
        NewPageTemplate,
        ModuleManager,
    },
    data: function () {
        return {
            title: null,
            modules: [],
            idx: 0,
            model: 'App\\StaticPage',
        }
    },
    watch: {
        '$route.params': function (params) {
            this.modules = []
            this.getPage(params.page)
        }
    },
    methods: {
        getPage: function (id) {
            this.idx = Number(id)
            this.$http.get('/api/admin/page/' + id)
                .then(response => {
                    this.title = response.data.title
                    this.modules = response.data.modules
                })
        },
        // saved: function (module) {
        //     this.modules.push(module)
        // },
        saved: function (module) {
            let idx = this.modules.findIndex(item => item.id == module.id)
            if (idx > -1) {
                this.modules.splice(idx, 1, module)
            }
            else {
                this.modules.push(module)
            }
        },
        deleted: function (module) {
            let idx = this.modules.findIndex(item => item.id == module.id)
            if (idx > -1) {
                this.modules.splice(idx, 1)
            }
        },
        selected: function (module) {
            this.$children[0].setModule(module)
        },
    },
    mounted: function () {
        this.getPage(this.$route.params.page)
    }

}
</script>

<style lang="css" scoped>
</style>
