<template lang="html">
    <page-template
        ref="page"
        :title="title"
        :model="model"
        :model-idx="idx"
        @saved="saved"
        @deleted="deleted">

        <module-manager
            v-for="item in this.modules"
            :key="item.id"
            :is-admin="true"
            :module="item"
            @selected="selected"/>
    </page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import ModuleManager from '../../containers/ModuleManager.vue'

export default {
    name: 'SubPage',
    components: {
        PageTemplate,
        ModuleManager,
    },
    data: function() {
        return {
            title: null,
            modules: [],
            idx: 0,
            model: 'App\\SubPage',
        }
    },
    watch: {
        '$route.params': function(params) {
            this.getPage(params.sub)
        }
    },
    methods: {
        getPage: function(id) {
            this.idx = Number(id)

            this.$http.get('/api/admin/sub-page/'+id).then(response => {
                this.title = response.data.title
                this.modules = response.data.modules
                // console.log(this.modules);
            })
        },
        saved: function(module) {
            this.modules.push(module)
        },
        saved: function(module) {
            let idx = this.modules.findIndex(item => item.id == module.id)
            if (idx > -1) {
                this.modules.splice(idx, 1, module)
            } else {
                this.modules.push(module)
            }
        },
        deleted: function(module) {
            let idx = this.modules.findIndex(item => item.id == module.id)
            if (idx > -1) {
                this.modules.splice(idx, 1)
            }
        },
        selected: function(module) {
            this.$children[0].setModule(module)
        },
    },
    mounted: function() {
        this.getPage(this.$route.params.sub)
    }

}
</script>

<style lang="css" scoped>
</style>
