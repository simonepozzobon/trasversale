<template>
<div
    class="cart-item"
    :class="[
        isBold,
        themeClass,
    ]"
>
    <div class="cart-item__border">
        <div class="cart-item__container">
            <div
                class="cart-item__info"
                v-if="hasDescription"
            >
                <div class="cart-item__img-container">
                    <img
                        :src="item.thumb"
                        :alt="item.title"
                        class="cart-item__img"
                    />
                </div>
            </div>
            <div class="cart-item__title cart-item__info">
                {{ item.title }}
            </div>
            <!-- <div
                class="cart-item__decription cart-item__info"
                v-if="hasDescription"
            >
                descrizione
            </div> -->
            <div
                class="cart-item__quantity cart-item__info"
                v-if="hasQuantity"
            >
                {{ quantity }}
            </div>
            <div class="cart-item__price cart-item__info">
                {{ item.price.toFixed(2) }} €
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'CartItem',
    props: {
        item: {
            type: Object,
            default: function () {
                return {}
            },
        },
        theme: {
            type: String,
            default: null,
        },
    },
    computed: {
        isBold: function () {
            if (this.item && this.item.hasOwnProperty('bold') && this.item.bold) {
                return 'cart-item--bold'
            }
        },
        hasDescription: function () {
            if (this.item && this.item.hasOwnProperty('hasDescription') && this.item.hasDescription === false) {
                return false
            }
            return true
        },
        hasQuantity: function () {
            if (this.item && this.item.hasOwnProperty('hasQuantity') && this.item.hasQuantity === false) {
                return false
            }
            return true
        },
        hasDescriptionClass: function () {
            if (this.hasDescription) {
                return 'cart-item--no-description'
            }
        },
        quantity: function () {
            if (this.item.hasOwnProperty('quantity')) {
                return this.item.quantity
            }
            return 1
        },
        themeClass: function () {
            if (this.theme == 'light') {
                return 'cart-item--light'
            }
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.cart-item {
    width: 100%;
    padding-left: $spacer;
    padding-right: $spacer;

    &__border {
        @include border-gradient(1px, rgba($primary, .3), rgba($primary, 0));
    }

    &__container {
        padding-top: $spacer / 1.618;
        padding-bottom: $spacer / 1.618;
        display: flex;
        width: 100%;
        min-height: 32px;
        align-items: center;
        transition: $transition-base;
    }

    &__img-container {
        height: 32px;
        max-width: 100%;
        top: 50%;
        left: 0;
        transition: $transition-base;
        transform-origin: center;
        transform: translate(0, -50%);
        position: absolute;
        overflow: hidden;
        @include box-shadows-size($black, 0, 0, 0, 0)

    }

    &__img {
        max-height: 100%;
        max-width: 100%;
    }

    &__info {
        flex: 1 1 20%;
        min-width: 20%;
        cursor: pointer;
        position: relative;
    }

    &__quantity {
        text-align: right;
    }

    &__price {
        text-align: right;
    }

    &--bold & {
        &__container {
            padding-top: $spacer / 1.618;
            padding-bottom: $spacer / 1.618;
            // padding: ($spacer * 1.8) $spacer ($spacer / 1.8);
        }

        &__info {
            font-size: $h5-font-size;
            font-weight: $font-weight-bold;
        }
    }

    &--no-description & {
        &__info {
            flex: 1 1 50%;
            min-width: 50%;
        }
    }

    &--light & {
        &__border {
            @include border-gradient(1px, rgba($light, .3), rgba($light, 0));
        }
    }

    &:hover & {
        &__container {
            min-height: 54px;
            transition: $transition-base;
        }

        &__info {
            text-decoration: underline;
        }

        &__img-container {
            height: 42px;
            top: 50%;
            left: 0;
            position: absolute;
            transform-origin: center;
            transform: translate(-22%, -50%);
            transition: $transition-base;
            @include box-shadows-size($black, 0px, 2px, 4px, 0)
        }
    }
}
</style>
