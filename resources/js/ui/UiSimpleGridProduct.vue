<template>
<div class="grid-product">
    <div class="grid-product__image">
        <img
            v-if="image"
            class="grid-product__figure"
            :src="image"
            :alt="content.title"
            @click.prevent="goToProduct"
        />
    </div>
    <div class="grid-product__details">
        <div class="grid-product__title">
            {{ content.title }}
        </div>
        <div class="grid-product__action">
            <a
                href=""
                class="grid-product__link"
                @click.prevent="goToProduct"
            >
                Vedi il programma e iscriviti
            </a>
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
        options: {
            type: Object,
            default: function () {
                return {}
            },
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
        image: function () {
            if (this.block.thumb && this.block.thumb != 'no-image') {
                return this.block.thumb
            }

            return false
        },
    },
    methods: {
        goToProduct: function () {
            // console.log(this.$route.params.page);
            this.$root.goToWithParams('subpage', {
                page: this.$route.params.page,
                subpage: this.content.slug.slug,
            })
        },
    },
    mounted: function () {
        // console.log('ora', this.block);
    },
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
        cursor: pointer;
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

    // &__title {
    //     font-family: $font-family-sans-serif-var;
    //     font-size: $font-size-base * 0.8;
    //     letter-spacing: 0.05em;
    //     text-transform: uppercase;
    // }

    &__title {
        font-size: $font-size-lg * 0.8;
        font-weight: $font-weight-bold;
        letter-spacing: 0.05em;
        margin-bottom: $spacer * $line-height-base / 2;
        cursor: pointer;
    }

    &__price {
        font-weight: $font-weight-bold;
    }

    &__link {
        color: $primary;
        transition: $transition-base;
    }

    &__link:hover {
        text-decoration: underline;
        transition: $transition-base;
    }
}
</style>
