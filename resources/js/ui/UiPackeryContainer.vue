<template lang="html">
    <div v-packery='this.packeryOpts' class="row">
        <ui-packery-item
            ref="item"
            v-for="item in this.items"
            :key="item.id"
            :width="item.width"
            :height="item.height"
            :color="item.bgColor"
            :content="item.content"
            @height-changed="heightChanged"/>
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
        }
    },
    methods: {
        setUnitHeight: function() {
            let items = this.$refs.item
            let height = this.maxHeight
            for (let i = 0; i < items.length; i++) {
                items[i].setUnitHeight(this.maxHeight)
            }
        },
        heightChanged: function(height) {
            if (height > this.maxHeight) {
                this.maxHeight = height
            }

            this.count++

            if (this.items.length == this.count) {
                this.$nextTick(() => {
                    this.setUnitHeight()
                })
            }
        }
    },
}
</script>

<style lang="css">
</style>
