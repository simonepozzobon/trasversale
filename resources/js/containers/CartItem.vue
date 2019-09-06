<template>
<div
    class="cart-item"
    :class="[
        isBold,
        themeClass,
        animationClass,
        borderClass
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
            <div
                class="cart-item__quantity cart-item__info"
                v-if="hasQuantity"
            >
                <button
                    class="btn btn-link"
                    @click.prevent="addItem"
                >
                    +
                </button>
                {{ quantity }}
                <button
                    class="btn btn-link"
                    @click.prevent="removeItem"
                >
                    -
                </button>
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
        quantity: {
            type: Number,
            default: 0,
        },
        hasAnimation: {
            type: Boolean,
            default: true,
        },
        hasBorder: {
            type: Boolean,
            default: true,
        },
    },
    data: function () {
        return {}
    },
    watch: {},
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
        themeClass: function () {
            if (this.theme == 'light') {
                return 'cart-item--light'
            }
        },
        computed_q: function () {
            return this.item.quantity
        },
        animationClass: function () {
            if (this.hasAnimation == false) {
                return 'cart-item--no-anim'
            }
        },
        borderClass: function () {
            if (this.hasBorder == false) {
                return 'cart-item--no-border'
            }
        },
    },
    methods: {
        addItem: function () {
            let idx = this.$root.cart.findIndex(item => item.item.id == this.item.id)
            if (idx > -1) {
                let cart = Object.assign([], this.$root.cart)
                let newItem = cart[idx]

                newItem.item.quantity = newItem.item.quantity + 1

                this.$root.cart[idx] = newItem
                this.$root.cart.splice()
                // this.$root.cart = Object.assign([], cart)
            }
        },
        removeItem: function () {
            let idx = this.$root.cart.findIndex(item => item.item.id == this.item.id)
            if (idx > -1) {
                let cart = Object.assign([], this.$root.cart)
                let newItem = Object.assign({}, cart[idx])

                if (newItem.item.quantity > 0) {
                    newItem.item.quantity = newItem.item.quantity - 1

                    cart[idx] = newItem
                    this.$root.cart = Object.assign([], cart)
                }
            }
        },
    },
    created: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.cart-item {
    width: 100%;

    &__border {
        $color: lighten($black, 45);
        @include border-bottom-gradient-three(1px, rgba($color, .2), rgba($color, .5), 50%, rgba($color, .3));
    }

    &__container {
        padding: $spacer;
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
        @include box-shadows-size(rgba($black, .33), 0, 0, 0, 0)

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
        display: flex;
        align-items: center;
        justify-content: center;
    }

    &__price {
        text-align: right;
    }

    &--bold & {
        &__container {
            padding-top: $spacer;
            padding-bottom: $spacer;
            // padding: ($spacer * 1.8) $spacer ($spacer / 1.8);
        }

        &__info {
            font-size: $h4-font-size;
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
            @include box-shadows-size(rgba($black, .33), 0px, 2px, 4px, 0)
        }
    }

    &--no-anim:hover & {
        &__container {
            min-height: 32px;
            transition: $transition-base;
        }

        &__info {
            text-decoration: none;
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
            @include box-shadows-size(rgba($black, .33), 0, 0, 0, 0)
        }
    }

    &--no-border & {
        &__border {
            border: none;
        }
    }
}
</style>
