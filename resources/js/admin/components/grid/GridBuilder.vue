<template>
<div class="grid-builder pt-4">
    <div class="form-group row">
        <div class="col-12">
            <h5>Composizione della griglia</h5>
            <hr />
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-12">
            <div ref="packery">
                <grid-layout
                    :layout.sync="items"
                    :col-num="colsNum"
                    :row-height="unitSize"
                    :is-draggable="true"
                    :is-resizable="true"
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
                        :w="Number(item.w)"
                        :h="Number(item.h)"
                        :i="item.i"
                    >
                        <grid-single-item :item="item" />
                    </grid-item>
                </grid-layout>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    clone
}
from '../../../Utilities'
import VueGridLayout from 'vue-grid-layout'
import GridSingleItem from './GridSingleItem.vue'
const debounce = require('lodash.debounce')

import {
    formatEl
}
from './GridUtilities'
export default {
    name: 'GridBuilder',
    components: {
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        GridSingleItem,
    },
    props: {
        elements: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            obj: {},
            name: 'grid',
            items: [],
            unitSize: 0,
            gutter: 10,
            colsNum: 12
        }
    },
    watch: {
        elements: function (els) {
            // console.log('grid builder elements changed', els);
            this.items = Object.assign([], els)
            this.getContainerWidth()
        },
        items: {
            handler: function (items) {
                this.$emit('update', items)
                // this.$emit('update:elements', items)
            },
            deep: true,
        },
    },
    methods: {
        getContainerWidth: function () {
            if (this.$refs.packery) {
                let container = this.$refs.packery.getBoundingClientRect().width
                let unitSize = Math.round(container / 12) - (this.gutter)
                if (unitSize > 0) {
                    this.unitSize = unitSize
                }
                else {
                    this.unitSize = 12
                }
            }
        },
    },
    mounted: function () {
        this.getContainerWidth()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
