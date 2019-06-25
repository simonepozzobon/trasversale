<template lang="html">
    <div>
        <module-manager
            v-for="module in this.modules"
            :key="module.id"
            :module="module"/>
    </div>
</template>

<script>
import ModuleManager from '../containers/ModuleManager.vue'
import {
    sortModules
}
from '../Utilities'
export default {
    name: 'Page',
    components: {
        ModuleManager,
    },
    data: function () {
        return {
            name: null,
            modules: null,
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        }
    },
    methods: {
        init: function () {
            let url = '/api/get-page/' + this.$route.params.page
            this.getData(url)
        },
        getData: function (url) {
            if (url) {
                this.$http.get(url).then(response => {
                    // console.log(response.data);
                    if (response.data.success) {
                        this.$root.sidebar = response.data.item.sidebar
                        this.name = response.data.item.title
                        this.modules = sortModules(response.data.item.modules)
                    }
                })
            }
        }
    },
    created: function () {
        this.init()
    },
    mounted: function () {}
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
