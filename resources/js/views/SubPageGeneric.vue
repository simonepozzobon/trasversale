<template>
<div class="row">
    <div class="col-12">
        <module-manager
            v-for="module in this.modules"
            :key="module.id"
            :module="module"
        />

        <ui-sharing v-if="isNews" />
    </div>
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
    name: 'SubPageGeneric',
    components: {
        ModuleManager,
        UiSharing,
    },
    props: {
        content: {
            type: Object,
            default: function () {
                return {}
            },
        },
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
            console.log(this.content);
            this.name = this.content.item.title
            this.$root.sidebar = this.content.item.sidebar
            this.modules = sortModules(this.content.item.modules)
            this.isNews = this.content.item.model == 'news' ? true : false
        },
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
