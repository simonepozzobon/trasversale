<template lang="html">
    <div ref="packery">
        <grid-layout
            :layout.sync="formatted"
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
                <ui-grid-single-item :item="item" :is-admin="isAdmin"/>
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

const debounce = require('lodash.debounce')

export default {
    name: 'UiPackeryGrid',
    components: {
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        UiGridSingleItem,
    },
    props: {
        blocks: {
            type: Array,
            default: function () {
                return []
            },
        },
        isAdmin: {
            type: Boolean,
            default: false
        },
    },
    data: function () {
        return {
            unitSize: 0,
            gutter: 10,
            items: [],
        }
    },
    watch: {
        blocks: {
            handler: function (blocks) {
                // console.log('change');
                this.debouncedUpdate(blocks)
            },
            deep: true,
        }
    },
    computed: {
        formatted: function () {
            return this.items.map((item, i) => {
                return formatEl(item, i, this.items)
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
            return Number(d)
        }
    },
    beforeCreate: function () {
        this.debouncedUpdate = debounce((blocks) => {
            this.items = blocks
        }, 150)
    },
    created: function () {
        this.items = this.blocks
        // console.log(Object.assign([], this.blocks));
        // this.debouncedUpdate(blocks)
    },
    mounted: function () {
        this.getContainerWidth()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
