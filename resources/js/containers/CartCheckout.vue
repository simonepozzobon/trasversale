<template>
<div class="checkout">
    <b-modal
        ref="modal"
        size="xl"
        hide-header
        hide-footer
    >

        <div class="checkout__container">
            <ui-title
                title="Checkout"
                :is-column="true"
                class="checkout__title"
            />
            <div class="checkout__row">
                <div class="checkout__resume-container">
                    <div class="checkout__resume">
                        <div class="sub-section sub-section--light">
                            <div class="sub-section__title">
                                <ui-title
                                    title="Riepilogo"
                                    :is-column="true"
                                    tag="h4"
                                    font-size="h4"
                                    color="gray"
                                />
                            </div>
                        </div>
                        <cart-item
                            v-for="item in $root.cart"
                            :key="item.uuid"
                            :item="item.item"
                        />
                        <cart-item :item="totalItem" />
                    </div>
                </div>
                <div class="checkout__short">
                    <payment />
                </div>
            </div>
            <div class="cart-actions">
                <ui-button
                    title="Continua lo shopping"
                    color="primary"
                    :has-container="false"
                    :has-margin="false"
                    display="inline-block"
                />
            </div>
        </div>
    </b-modal>
</div>
</template>

<script>
import {
    UiBlock,
    UiButton,
    UiRow,
    UiTitle,
}
from '../ui'

import CartItem from './CartItem.vue'
import Payment from './Payment.vue'

export default {
    name: 'CartCheckout',
    components: {
        CartItem,
        Payment,
        UiBlock,
        UiButton,
        UiRow,
        UiTitle,
    },
    data: function () {
        return {
            total: 0,
            totalItem: {
                title: null,
                bold: true,
                price: 0,
            },
            totalItemShort: {
                title: null,
                bold: true,
                price: 0,
            }
        }
    },
    watch: {
        '$root.cart': {
            handler: function () {
                this.calculateTotal()
                this.showCart()
            },
            deep: true
        }
    },
    methods: {
        init: function () {

        },
        calculateTotal: function () {
            if (this.$root.cart && this.$root.cart.length > 0) {
                let total = 0
                let totalQ = 0

                let items = this.$root.cart.map(item => {
                    total = total + (item.item.price * item.item.quantity)
                    totalQ = totalQ + item.item.quantity

                })
                this.total = total

                this.totalItem = {
                    title: 'Sub Totale',
                    hasDescription: false,
                    hasQuantity: false,
                    bold: true,
                    price: total,
                    quantity: totalQ,
                }

                this.totalItemShort = {
                    ...this.totalItem,
                    title: 'Totale',
                    hasQuantity: false
                }

            }
        },
        showCart: function () {
            this.$refs.modal.show()
        },
        hideCart: function () {
            this.$refs.modal.hide()
        }
    },
    mounted: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.checkout {
    &__title {
        padding: 0 ($spacer / 1.8) ($spacer * 1.618);
    }

    &__row {
        display: flex;
    }

    &__resume-container {
        flex: 1 1 60%;
        padding-top: $spacer;
        padding-bottom: $spacer;
    }

    &__resume {
        background-color: darken($white, 5);
        padding-right: $spacer;
        padding-left: $spacer;
    }

    &__short {
        flex: 1 1 40%;
        background-color: lighten($dark, 25);
        @include box-shadows($black);
    }
}

.sub-section {
    width: 100%;
    padding: ($spacer / 1.8) ($spacer * 2);

    &__border {
        margin-top: 2px;
        @include border-gradient(1px, rgba($yellow, 1), rgba($yellow, 0));
    }

    &__action {
        margin-top: $spacer;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    &--no-padding {
        padding: ($spacer / 1.8) 0;
    }
}

.cart-actions {
    margin-top: $spacer;
    width: 100%;
    display: flex;
    justify-content: space-around;
}
</style>
