<template lang="html">
    <div class="grid-product">
        <div class="grid-product__image">
            <img
                class="grid-product__figure"
                :src="content.thumb"
                :alt="content.title" />
        </div>
        <div class="grid-product__details">
            <div class="grid-product__title">
                {{ content.title }}
            </div>
            <div class="grid-product__reviews">
                *****
            </div>
            <div class="grid-product__action">
                <a
                    href=""
                    class="grid-product__link"
                    @click.prevent="goToProduct">Vedi il programma e iscriviti</a>
            </div>
        </div>
    </div>
</template>

<script>
import UiBlock from './UiBlock.vue'

export default {
    name: 'UiSimpleGridProduct',
    components: {
        UiBlock,
    },
    props: {
        block: {
            type: Object,
            default: function () {},
        },
    },
    computed: {
        price: function () {
            if (this.content && this.content.hasOwnProperty('price')) {
                return this.content.price.toFixed(2)
            }
            return 'error'
        },
        content: function () {
            if (typeof this.block.content === 'string') {
                return JSON.parse(this.block.content)
            }
            return this.block.content
        },
    },
    methods: {
        goToProduct: function () {
            console.log(this.$route.params.page);
            this.$root.goToWithParams('subpage', {
                page: this.$route.params.page,
                subpage: this.content.slug.slug,
            })
        },
    },
    mounted: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.grid-product {

    &__image {}

    &__figure {
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        height: auto;
    }

    &__details {
        display: flex;
        height: 100%;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: $spacer;
    }

    &__action,
    &__price,
    &__reviews,
    &__title {
        text-align: center;
    }

    &__title {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-base * 0.8;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    &__price {
        font-weight: $font-weight-bold;
    }

    &__link {
        color: $orange;
        transition: $transition-base;
    }

    &__link:hover {
        text-decoration: underline;
        transition: $transition-base;
    }
}
</style>
