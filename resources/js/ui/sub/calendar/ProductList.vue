<template>
<div class="prod-list">
    <single-product
        v-for="product in products"
        :key="product.id"
        :product="product"
    />
</div>
</template>

<script>
import SingleProduct from './SingleProduct.vue'

export default {
    name: 'ProductList',
    components: {
        SingleProduct,
    },
    props: {
        products: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.prod-list {
    margin-bottom: $spacer;
}
</style>
