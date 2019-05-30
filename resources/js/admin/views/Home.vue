<template lang="html">
    <page-template title="Amministrazione">
    </page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import {
    UiModuleContainer
} from '../../ui'

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
