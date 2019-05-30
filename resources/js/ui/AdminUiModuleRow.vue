<template lang="html">
    <ui-row>
        <admin-ui-module-block
            v-if="columns.length > 0"
            v-for="column in columns"
            :key="column.id"
            :column="column"
            :is-open="isOpen">
        </admin-ui-module-block>
    </ui-row>
</template>

<script>
import AdminUiModuleBlock from './AdminUiModuleBlock.vue'
import UiRow from './UiRow.vue'

export default {
    name: 'AdminUiModuleRow',
    components: {
        AdminUiModuleBlock,
        UiRow,
    },
    props: {
        columns: {
            type: Array,
            default: function() {},
        },
        isOpen: {
            type: Boolean,
            default: false,
        }
    },
    mounted: function() {
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
