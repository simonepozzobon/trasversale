<template lang="html">
    <page-template title="Home">
        <ui-module-container
            v-for="item in this.modules"
            :key="item.id"
            :module="item"/>
    </page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import { UiModuleContainer } from '../../ui'

export default {
    components: {
        PageTemplate,
        UiModuleContainer,
    },
    data: function() {
        return {
            modules: [],
        }
    }
}
</script>

<style lang="scss">
@import '~styles/adminshared';
</style>
