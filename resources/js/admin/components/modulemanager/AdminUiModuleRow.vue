<template lang="html">
    <ui-row>
        <admin-ui-module-block
            v-if="columns.length > 0"
            v-for="(column, i) in columns"
            :key="column.id"
            :idx="i"
            :column="column"
            :is-open="isOpen"
            @update="updateSize" />
    </ui-row>
</template>

<script>
import AdminUiModuleBlock from './AdminUiModuleBlock.vue'
import {
    UiRow
} from '../../../ui'

export default {
    name: 'AdminUiModuleRow',
    components: {
        AdminUiModuleBlock,
        UiRow,
    },
    props: {
        columns: [Array, Object],
        isOpen: {
            type: Boolean,
            default: false,
        }
    },
    data: function() {
        return {}
    },
    methods: {
        updateSize: function(data) {
            this.$emit('update-size', data)
        },
    },
    created: function() {}
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
