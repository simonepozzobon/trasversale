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
                        :w="item.w"
                        :h="item.h"
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

export default {
    name: 'GridModule',
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
            this.items = els
        },
        items: function (items) {
            this.$emit('update:elements', items)
        }
    },
    methods: {
        getContainerWidth: function () {
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / 12) - (this.gutter)
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
