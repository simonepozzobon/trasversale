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
                <admin-packery-container
                    :items.sync="this.elements"
                    :gutter="8"
                    :units="12"
                    :drag="true"
                />
            </div>
            <!-- <grid-layout
                ref="gridLayout"
                :layout="elements"
                :col-num="12"
                :row-height="60"
                :is-draggable="true"
                :is-resizable="true"
                :auto-size="true"
                :is-mirrored="false"
                :vertical-compact="true"
                :margin="[10, 10]"
                :use-css-transforms="true"
                @layout-updated="layoutUpdated"
            >

                <grid-item
                    ref="gridItem"
                    v-for="(element, i) in elements"
                    :key="element.uuid"
                    class="element-item"
                    :x="element.x"
                    :y="element.y"
                    :w="element.w"
                    :h="element.h"
                    :i="element.i"
                >

                    <div
                        class="element-item__container"
                        :style="'background-image: url('+ element.thumb +')'"
                    >

                        <div class="element-item__tools">
                            <button
                                class="btn btn-outline-danger"
                                @click="removeElement(element)"
                            >
                                Rimuovi
                            </button>
                        </div>
                    </div>
                </grid-item>
            </grid-layout> -->
        </div>
    </div>
</div>
</template>

<script>
import AdminPackeryContainer from '../modulemanager/AdminPackeryContainer.vue'

import VueGridLayout from 'vue-grid-layout'
import {
    clone
}
from '../../../Utilities'

export default {
    name: 'GridModule',
    components: {
        GridItem: VueGridLayout.GridItem,
        GridLayout: VueGridLayout.GridLayout,
        AdminPackeryContainer,
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
        }
    },
    methods: {
        layoutUpdated: function (newLayout) {
            // console.log('layout aggiornato', newLayout);

            // https://coderwall.com/p/ebqhca/javascript-sort-by-two-fields
            const sorted = Object.assign([], newLayout)
            sorted.sort((a, b) => (a.y - b.y || a.x - b.x))
            this.$emit('update:elements', sorted)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
