<template lang="html">
    <div>
        <module-manager
            v-for="module in this.modules"
            :key="module.id"
            :module="module"/>

        <ui-sharing v-if="isNews"/>
    </div>
</template>

<script>
import ModuleManager from '../containers/ModuleManager.vue'
import {
    sortModules
}
from '../Utilities'
import {
    UiSharing
}
from '../ui'
export default {
    name: 'Subpage',
    components: {
        ModuleManager,
        UiSharing,
    },
    data: function () {
        return {
            name: null,
            modules: null,
            isNews: false,
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        }
    },
    methods: {
        init: function () {
            let url = '/api/get-page/' + this.$route.params.page + '/' + this.$route.params.subpage
            this.getData(url)
        },
        getData: function (url) {
            if (url) {
                this.$http.get(url).then(response => {
                    console.log(response.data);
                    if (response.data.success) {
                        this.name = response.data.item.title
                        this.$root.sidebar = response.data.item.sidebar
                        this.modules = sortModules(response.data.item.modules)
                        this.isNews = response.data.item.model == 'news' ? true : false
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
