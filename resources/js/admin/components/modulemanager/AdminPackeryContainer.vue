<template lang="html">
        <!-- class="row no-gutters" -->
    <div
        v-packery="{
            itemSelector: '.packery-item',
            percentPosition: true,
            initLayout: true,
            /* resizeContainer: false, */
        }"
        class="row no-gutters"
        ref="packery">
        <admin-packery-item
            ref="item"
            v-for="(item, i) in this.items"
            :key="item.i"
            :type="item.type"
            :sub-type="item.hasOwnProperty('sub_type') ? item.sub_type : null"
            :width="item.width"
            :height="item.height"
            :unit-size="unitSize"
            :color="item.hasOwnProperty('color') ? item.color : null"
            :bg-color="item.bgColor"
            :content="item.content"
            :gutter="gutter"
            :img="item.thumb"/>
    </div>
</template>

<script>
import { Uuid } from '../../../Utilities'
import AdminPackeryItem from './AdminPackeryItem.vue'
import {packeryEvents} from '../../PackeryTest'

export default {
    name: 'PackeryContainer',
    components: {
        AdminPackeryItem,
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
    data: function() {
        return {
            forceRender: true,
            packeryOpts: {
                itemSelector: ".packery-item",
                percentPosition: true,
                resize: false
                // resizeContainer: false,
                // containerStyle: null,
            },
            count: 0,
            maxHeight: 0,
            unitSize: 0,
        }
    },
    watch: {
        items: function(items) {
            console.log('items');
            this.setUnitHeight()
            packeryEvents.$emit('layout', this.$refs.packery)
            // this.forceRender = false
            // this.$nextTick(() => {
            //     this.forceRender = true
            // })
        }
    },
    computed: {
        uuid: function() {
            return Uuid.get()
        }
    },
    filters: {
        setUUID: function(value) {
            return Uuid.get()
        }
    },
    methods: {
        setUnitHeight: function() {
            let items = this.$refs.item
            if (items) {
                for (let i = 0; i < items.length; i++) {
                    items[i].setUnitHeight(this.unitSize)
                }
            }
        },
        getContainerWidth: function() {
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / this.units)
            // console.log(container, this.unitSize);
            this.$nextTick(() => {
                this.setUnitHeight()
            })
        },
    },
    mounted: function() {
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
