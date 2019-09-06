<template >
<ui-row>
    <ui-module-block
        v-if="columns.length > 0"
        v-for="column in columns"
        :key="column.id"
        :column="column"
        :has-container="false"
    />
</ui-row>
</template>

<script>
import UiModuleBlock from './UiModuleBlock.vue'
import UiRow from './UiRow.vue'

export default {
    name: 'UiModuleRow',
    components: {
        UiModuleBlock,
        UiRow,
    },
    props: {
        columns: {
            type: Array,
            default: function () {},
        },
    },
    mounted: function () {}
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
