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
        <div class="grid-sizer"></div>
        <admin-packery-item
            ref="item"
            v-for="(item, i) in this.items"
            :key="item.idx"
            :data-key="item.idx"
            :x="item.x"
            :y="item.y"
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
import { clone, Uuid } from '../../../Utilities'
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
            packeryOpts: {
                itemSelector: ".packery-item",
                percentPosition: false,
                resize: false,
                columnWidth: 100
                // resizeContainer: false,
                // containerStyle: null,
            },
            count: 0,
            maxHeight: 0,
            unitSize: 0,
            cached: [],
        }
    },
    watch: {
        items: function(items) {
            // console.log('verifica', items.map(o => o.x + ' ' + o.y));
            this.setUnitHeight()
            // packeryEvents.$emit('layout', this.$refs.packery)
        }
    },
    computed: {
        uuid: function() {
            return Uuid.get()
        },
        packery: function() {
            return this.$refs.packery.packery
        },
        packeryItems: function() {
            // console.log(this.packery.getItemElements());
            return this.packery.items
        }
    },
    filters: {
        setUUID: function(value) {
            return Uuid.get()
        }
    },
    methods: {
        setItems: function() {
            this.cached = clone(this.items)
        },
        setUnitHeight: function() {
            let items = this.$refs.item
            if (items) {
                for (let i = 0; i < items.length; i++) {
                    let current = items[i]
                    current.setUnitHeight(this.unitSize)
                    //
                    // let x = current.x * this.unitSize
                    // let y = current.y * this.unitSize

                    // console.log(current.$el.packeryNode.packery);
                    // current.$el.packeryNode.packery.fit(current.$el, x, y)
                }

                for (let i = 0; i < this.packeryItems.length; i++) {
                    let current = this.packeryItems[i]
                    let node = current.element
                    let idx = node.getAttribute('data-key')

                    let item = this.items.find(item => Number(item.idx) === Number(idx))

                    let x = item.x * this.unitSize
                    let y = item.y * this.unitSize
                    // console.log('indice', idx, x,y);

                    this.packery.fit(current, x, y)
                    this.packery.reloadItems()
                    this.packery.layout()
                    // packeryEvents.$emit('layout', this.$refs.packery)
                }
                // console.log(items[0]);
            }
        },
        getContainerWidth: function() {
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / this.units)
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
