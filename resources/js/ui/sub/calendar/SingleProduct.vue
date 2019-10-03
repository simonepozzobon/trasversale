<template>
<div class="single-product">
    <div class="single-product__title">
        {{ product.title }}
    </div>
</div>
</template>

<script>
export default {
    name: 'SingleProduct',
    props: {
        product: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.single-product {}
</style>
