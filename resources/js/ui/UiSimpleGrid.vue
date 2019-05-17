<template lang="html">
    <div class="ui-simple-grid">
        <ui-row>
            <ui-simple-grid-item
                v-for="block in blocks"
                :key="block.id"
                :block="block"/>
        </ui-row>
    </div>
</template>

<script>
import UiSimpleGridItem from './UiSimpleGridItem.vue'
import UiRow from './UiRow.vue'
export default {
    name: 'UiSimpleGrid',
    components: {
        UiRow,
        UiSimpleGridItem,
    },
    props: {
        blocks: {
            type: Array,
            default: function() {},
        },
    },
    mounted: function() {
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
