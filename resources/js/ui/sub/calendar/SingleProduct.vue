<template>
<div class="single-product">
    <div class="single-product__title">
        {{ product.title }}
    </div>
    <div class="single-product__description">
        {{ product.forwho }}
    </div>
    <div class="single-product__link">
        <a
            href="#"
            @click.prevent="goToProduct"
        >
            vedi il programma
        </a>
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
    methods: {
        goToProduct: function () {
            this.$root.goToWithParams('subpage', {
                page: this.$route.params.page,
                subpage: this.product.slug.slug,
            })
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.single-product {
    margin-bottom: $spacer;

    &__title {
        font-weight: $font-weight-bold;
        margin-bottom: $spacer / 2;
    }

    &__description {}
}
</style>
