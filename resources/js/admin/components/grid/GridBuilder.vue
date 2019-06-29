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
                    :items.sync="items"
                    :gutter="8"
                    :units="12"
                    :drag="true"
                />
            </div>
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
        layoutUpdated: function (newLayout) {
            console.log('layout aggiornato', newLayout);

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
