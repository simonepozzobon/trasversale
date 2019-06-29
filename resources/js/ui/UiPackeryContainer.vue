<template lang="html">
        <!-- class="row no-gutters" -->
    <div
        v-packery="this.packeryOpts"
        class="row no-gutters"
        ref="packery">
        <ui-packery-item
            ref="item"
            v-for="item in this.items"
            :key="item.id | setUUID"
            :type="item.type"
            :sub-type="item.hasOwnProperty('sub_type') ? item.sub_type : null"
            :width="item.width"
            :height="item.height"
            :color="item.hasOwnProperty('color') ? item.color : null"
            :bg-color="item.bgColor"
            :content="item.content"
            :gutter="gutter"
            :img="item.thumb"/>
    </div>
</template>

<script>
import {
    Uuid
}
from '../Utilities'
import UiPackeryItem from './UiPackeryItem.vue'

export default {
    name: 'PackeryContainer',
    components: {
        UiPackeryItem,
    },
    props: {
        items: [Array, Object],
        units: {
            type: Number,
            default: 12,
        },
        gutter: {
            type: Number,
            default: 0,
        }
    },
    data: function () {
        return {
            packeryOpts: {
                itemSelector: ".packery-item",
                percentPosition: true,
                // containerStyle: null,
            },
            count: 0,
            maxHeight: 0,
            unitSize: 0,
        }
    },
    watch: {
        items: function (items) {}
    },
    filters: {
        setUUID: function (value) {
            return Uuid.get()
        }
    },
    methods: {
        setUnitHeight: function () {
            let items = this.$refs.item
            for (let i = 0; i < items.length; i++) {
                items[i].setUnitHeight(this.unitSize)
            }
        },
        getContainerWidth: function () {
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / this.units)
            // console.log(container, this.unitSize);
            this.$nextTick(() => {
                this.setUnitHeight()
            })
        },
    },
    mounted: function () {
        this.getContainerWidth()
    }
}
</script>

<style lang="scss">
@import '~styles/shared';

.packery-container {
    position: relative;
}
</style>
