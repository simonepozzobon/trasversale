<template lang="html">
    <ui-block
        class="grid-product"
        :full-height="true"
        :size="block.hasOwnProperty('width') ? block.width : null">
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
            <div class="grid-product__price">
                {{ content.price.toFixed(2) }} €
            </div>
            <div class="grid-product__action">
                <a href="#" class="grid-product__link">Vedi il programma e iscriviti</a>
            </div>
        </div>
    </ui-block>
</template>

<script>
import UiBlock from './UiBlock.vue'

export default {
    name: 'UiSimpleGrid',
    components: {
        UiBlock,
    },
    props: {
        block: {
            type: Object,
            default: function() {},
        },
    },
    computed: {
        content: function() {
            return JSON.parse(this.block.content)
        },
    },
    mounted: function() {
        console.log(this.content);
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
    }

    &__details {
        display: flex;
        height: 100%;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        padding: $spacer;
    }

    &__title,
    &__reviews,
    &__price,
    &__action {
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
