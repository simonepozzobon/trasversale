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
                v-for="item in formatted"
                ref="item"
                :key="item.uuid"
                :x="item.x"
                :y="item.y"
                :w="item.width | setDimension"
                :h="item.height | setDimension"
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
import {
    formatEl
}
from '../admin/components/grid/GridUtilities'

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
    computed: {
        formatted: function () {
            return this.items.map(item => {
                return formatEl(item)
            })
        },
    },
    methods: {
        getContainerWidth: function () {
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / 12) - (this.gutter)
        }
    },
    filters: {
        setDimension: function (d) {
            console.log(d);
            return Number(d)
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
