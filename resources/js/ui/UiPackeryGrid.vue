<template lang="html">
    <div ref="packery">
        <grid-layout
            :layout.sync="items"
            :col-num="12"
            :row-height="unitSize"
            :is-draggable="false"
            :is-resizable="false"
            :is-mirrored="false"
            :vertical-compact="true"
            :margin="[this.gutter, this.gutter]"
            :use-css-transforms="true"
        >
            <grid-item
                v-for="item in items"
                ref="item"
                :key="item.uuid"
                :x="item.x"
                :y="item.y"
                :w="item.w"
                :h="item.h"
                :i="item.i"
            >
                <ui-grid-single-item :item="item" />
            </grid-item>
        </grid-layout>
    </div>
</template>

<script>
import VueGridLayout from 'vue-grid-layout'
import UiGridSingleItem from './UiGridSingleItem.vue'

export default {
    name: 'UiPackeryGrid',
    components: {
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        UiGridSingleItem,
    },
    props: {
        items: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            unitSize: 0,
            gutter: 10,
        }
    },
    methods: {
        getContainerWidth: function () {
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / 12) - (this.gutter)
        }
    },
    mounted: function () {
        this.getContainerWidth()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
