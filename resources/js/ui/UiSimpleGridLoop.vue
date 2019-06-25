<template lang="html">
    <ui-block
        class="grid-product"
        :size="block.hasOwnProperty('width') ? block.width : null">

        <ui-simple-grid-product
            v-if="block.type == 'product'"
            :block="block"/>

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
            default: function () {},
        },
    },
    computed: {
        content: function () {
            if (typeof this.block.content === 'string') {
                return JSON.parse(this.block.content)
            }
            return this.block.content
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
        this.$emit('category', this.content.category, this.block.id)
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
