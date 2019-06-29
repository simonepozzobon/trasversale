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
            <div>
                <grid-layout
                    :layout.sync="items"
                    :col-num="12"
                    :row-height="30"
                    :is-draggable="true"
                    :is-resizable="true"
                    :is-mirrored="false"
                    :vertical-compact="true"
                    :margin="[10, 10]"
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
        hub: function () {},
        // layoutUpdated: function (newLayout) {
        //     console.log('layout aggiornato', newLayout);
        //
        //     // https://coderwall.com/p/ebqhca/javascript-sort-by-two-fields
        //     const sorted = Object.assign([], newLayout)
        //     sorted.sort((a, b) => (a.y - b.y || a.x - b.x))
        //     this.$emit('update:elements', sorted)
        // },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
