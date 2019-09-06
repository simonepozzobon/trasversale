<template lang="html">
    <ui-block
        class="grid-product"
        :size="postPerRow">

        <ui-simple-grid-product
            v-if="block.type == 'product'"
            :block="block"
            />

        <ui-simple-grid-news
            v-if="block.type == 'news'"
            :block="block"
            @filter-category="filterCategory"/>
    </ui-block>
</template>

<script>
import UiBlock from './UiBlock.vue'
import UiSimpleGridProduct from './UiSimpleGridProduct.vue'
import UiSimpleGridNews from './UiSimpleGridNews.vue'

export default {
    name: 'UiSimpleGridLoop',
    components: {
        UiBlock,
        UiSimpleGridProduct,
        UiSimpleGridNews,
    },
    props: {
        block: {
            type: Object,
            default: function () {
                return {}
            },
        },
        options: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    computed: {
        content: function () {
            if (typeof this.block.content === 'string') {
                return JSON.parse(this.block.content)
            }
            return this.block.content
        },
        postPerRow: function () {
            let postSize = 0
            let size = 0
            if (this.options) {
                postSize = this.options.post_per_row
                if (postSize && postSize > 0) {
                    size = Math.round(12 / postSize)
                    if (size >= 2) {
                        // console.log('si', size);
                        return size
                    }
                    // console.log('no', size);
                    return 2
                }
            }
            // console.log(postSize, size);
            return 3
        },
    },
    methods: {
        filterCategory: function (id) {
            this.$nextTick(() => {
                this.$emit('filter-category', id)
            })
        },
    },
    mounted: function () {
        // console.log('blocco qui', this.block);
        this.$emit('category', this.content.category, this.block.id)
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
