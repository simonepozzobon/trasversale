<template lang="html">
    <div
        v-packery="this.packeryOpts"
        class="row no-gutters"
        ref="container">
        <ui-packery-item
            ref="item"
            v-for="item in this.items"
            :key="item.id"
            :width="item.width"
            :height="item.height"
            :color="item.bgColor"
            :content="item.content"
            :gutter="gutter"
            :img="item.img"/>
    </div>
</template>

<script>
import UiPackeryItem from './UiPackeryItem.vue'

export default {
    name: 'PackeryContainer',
    components: {
        UiPackeryItem,
    },
    props: {
        items: {
            type: Array,
            default: function() {}
        },
        units: {
            type: Number,
            default: 12,
        },
        gutter: {
            type: Number,
            default: 0,
        }
    },
    data: function() {
        return {
            packeryOpts: {
                itemSelector: ".packery-item",
                percentPosition: true
            },
            count: 0,
            maxHeight: 0,
            unitSize: 0,
        }
    },
    methods: {
        setUnitHeight: function() {
            let items = this.$refs.item
            for (let i = 0; i < items.length; i++) {
                items[i].setUnitHeight(this.unitSize)
            }
        },
        getContainerWidth: function() {
            let container = this.$refs.container.getBoundingClientRect().width
            this.unitSize = Math.round(container / this.units)
            this.$nextTick(() => {
                this.setUnitHeight()
            })
        }
    },
    mounted: function() {
        this.getContainerWidth()
    }
}
</script>

<style lang="css">
</style>
