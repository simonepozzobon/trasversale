<template lang="html">
    <page-template :title="title">
        <div class="add-module">
            <button class="btn btn-outline-success">
                Aggiungi
            </button>
        </div>
        
        <ui-module-container
            v-for="item in this.modules"
            :key="item.id"
            :module="item"/>

        <div class="add-module">
            <button class="btn btn-outline-success">
                Aggiungi
            </button>
        </div>
    </page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import { UiModuleContainer } from '../../ui'

export default {
    name: 'SubPage',
    components: {
        PageTemplate,
        UiModuleContainer,
    },
    data: function() {
        return {
            title: null,
            modules: [],
        }
    },
    watch: {
        '$route.params': function(params) {
            this.getPage(params.id)
        }
    },
    methods: {
        getPage: function(id) {
            this.$http.get('/api/admin/sub-page/'+id).then(response => {
                this.title = response.data.title
                this.modules = response.data.modules
            })
        }
    },
    mounted: function() {
        this.getPage(this.$route.params.id)
    }

}
</script>

<style lang="css" scoped>
</style>
