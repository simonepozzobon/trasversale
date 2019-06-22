<template lang="html">
    <div
        v-packery="{
            itemSelector: '.packery-item',
            percentPosition: true,
            /* initLayout: true, */
            /* columnWidth: '.grid-sizer', */
            /* columnWidth: 80, */
            /* rowHeight: 80, */
            /* resizeContainer: false, */
        }"
        ref="packery"
        @dragItemPositioned="test">
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
            :width="item.w"
            :height="item.h"
            :unit-size="unitSize"
            :color="item.hasOwnProperty('color') ? item.color : null"
            :bg-color="item.bgColor"
            :content="item.content"
            :gutter="gutter"
            :img="item.thumb"/>
    </div>
</template>

<script>
import {
    clone,
    Uuid
}
from '../../../Utilities'
import AdminPackeryItem from './AdminPackeryItem.vue'
import {
    packeryEvents
}
from '../../PackeryTest'

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
    data: function () {
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
        items: function (items) {
            // console.log('verifica', items.map(o => o.x + ' ' + o.y));
            this.$nextTick(() => this.setUnitHeight())
            // packeryEvents.$emit('layout', this.$refs.packery)
        }
    },
    computed: {
        uuid: function () {
            return Uuid.get()
        },
        packery: function () {
            if (this.$refs.packery && this.$refs.packery.hasOwnProperty('packery')) {
                return this.$refs.packery.packery
            }

            return false
        },
        packeryItems: function () {
            // console.log(this.packery.getItemElements());
            if (this.packery) {
                return this.packery.items
            }
            return []
        }
    },
    filters: {
        setUUID: function (value) {
            return Uuid.get()
        },
        setSize: function (w, h) {

        }
    },
    methods: {
        setItems: function () {
            this.cached = Object.assign([], this.items)
        },
        setUnitHeight: function () {
            // console.log('setUnitHeight');
            let items = this.$refs.item
            if (items) {
                for (let i = 0; i < items.length; i++) {
                    let current = items[i]
                    current.setUnitHeight(this.unitSize)
                    current.setUnitWidth(this.unitSize)
                }
                for (let i = 0; i < this.packeryItems.length; i++) {
                    let current = this.packeryItems[i]
                    let node = current.element
                    let idx = node.getAttribute('data-key')

                    let item = this.items.find(item => Number(item.idx) === Number(idx))
                    // console.log(item, current);
                    if (item) {
                        // this.bindUiEvents()

                        let x = item.x * this.unitSize
                        let y = item.y * this.unitSize

                        this.packery.fit(current, x, y)
                        this.packery.reloadItems()
                        this.packery.layout()
                    }
                }
            }
        },
        getContainerWidth: function () {
            // console.log('container');
            let container = this.$refs.packery.getBoundingClientRect().width
            this.unitSize = Math.round(container / this.units)
            this.$nextTick(() => {
                this.setUnitHeight()
            })
        },
        // bindUiEvents: function () {
        //     let elements = this.packeryItems
        //     console.log('elements', elements);
        //     // this.packery.bindUIDraggableEvents(elements)
        // },
        test: function (event) {
            // console.log('evento', event);
        }
    },
    mounted: function () {
        this.getContainerWidth()
        // packeryEvents.$on('draggie', (data) => {
        //     console.log('jfhskjhfjkdshkfsf', data);
        // })
        packeryEvents.$on('customEmit', (data) => {
            console.log('jfhskjhfjkdshkfsf', data);
        })
    }
}
</script>

<style lang="scss">
@import '~styles/shared';

.packery-container {
    position: relative;
}
</style>
