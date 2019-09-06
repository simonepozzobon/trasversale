<template>
<div class="checkout">
    <b-modal
        ref="modal"
        size="xl"
        hide-header
        hide-footer
        no-close-on-backdrop
        dialog-class="checkout__modal-container"
        content-class="checkout__modal"
    >
        <div class="checkout__container">
            <!-- <ui-title
                title="Checkout"
                :is-column="true"
                class="checkout__title"
            /> -->
            <div class="checkout__row">
                <div
                    class="checkout__resume-container"
                    ref="resume"
                >
                    <div class="checkout__resume resume">
                        <cart-resume
                            :items="this.$root.cart"
                            :total-item="totalItem"
                        />
                    </div>
                </div>
                <div
                    class="checkout__short"
                    ref="container"
                >
                    <personal-data
                        ref="billing"
                        v-if="process == 1"
                        :items="this.$root.cart"
                        @completed="showPayment"
                    />
                    <payment
                        ref="payment"
                        v-else
                        :order="order"
                        @completed="showSuccess"
                    />
                    <div
                        class="succes-message"
                        ref="success"
                    >
                        <ui-title
                            ref="successTitle"
                            title="Pagamento riuscito"
                            color="white"
                            :is-column="true"
                            align="center"
                        />
                        <ui-paragraph
                            ref="successMessage"
                            color="white"
                            content="A breve riceverà una mail di conferma"
                            align="center"
                        />

                        <ui-button
                            ref="successBtn"
                            class="succes-message__btn"
                            title="Chiudi"
                            color="yellow"
                            :is-active="true"
                            :has-margin="false"
                            display="inline-block"
                            @click="hideCart"
                        />
                    </div>
                </div>
            </div>
            <div class="cart-actions">
                <ui-button
                    title="Continua lo shopping"
                    color="light"
                    :has-container="false"
                    :has-margin="false"
                    :is-active="true"
                    display="inline-block"
                    @click="hideCart"
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
    UiParagraph,
    UiTitle,
}
from '../ui'

import CartItem from './CartItem.vue'
import CartResume from './CartResume.vue'
import Payment from './Payment.vue'
import PersonalData from './PersonalData.vue'

require('gsap/CSSRulePlugin')
require('gsap/CSSPlugin')

export default {
    name: 'CartCheckout',
    components: {
        CartItem,
        CartResume,
        Payment,
        PersonalData,
        UiBlock,
        UiButton,
        UiRow,
        UiParagraph,
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
            },
            process: 1,
            order: null,
        }
    },
    watch: {
        '$root.cart': {
            handler: function (cart) {
                // console.log('cambiato');
                this.calculateTotal()
                this.showCart()
            },
            deep: true
        }
    },
    methods: {
        init: function () {},
        debug: function () {
            setTimeout(() => {
                this.showSuccess()
            }, 1000)
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
                    title: 'Totale',
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
        },
        smoothHeight: function () {
            return new Promise((resolve, reject) => {
                let container = this.$refs.container
                // let payment = this.$refs.payment.$el
                // let billing = this.$refs.billing.$el


                let size = container.getBoundingClientRect()
                let height = size.height

                container.style.height = height + 'px'

                this.$nextTick(() => {
                    resolve()
                })
            });
        },
        showPayment: function (order) {
            this.order = order
            this.smoothHeight().then(() => {
                this.process = 2
            })

        },
        showSuccess: function () {
            let success = this.$refs.success
            let payment = this.$refs.payment

            TweenMax.set(success, {
                display: 'flex'
            })

            let resume = this.$refs.resume
            let panel = this.$refs.container

            let container = payment.$refs.container
            let title = payment.$refs.title.$el
            let dropin = payment.$refs.dropin.$el
            let btn = payment.$refs.btn.$el

            let successTitle = this.$refs.successTitle.$el
            let successMessage = this.$refs.successMessage.$el
            let successBtn = this.$refs.successBtn.$el



            let master = new TimelineMax({
                paused: true,
                yoyo: true,
            })

            master.staggerFromTo([title, dropin, btn], .6, {
                autoAlpha: 1,
            }, {
                autoAlpha: 0,
            }, .1)

            master.addLabel('size', '+=0')

            master.fromTo(resume, .4, {
                css: {
                    overflow: 'hidden',
                    flexBasis: '60%',
                },
            }, {
                css: {
                    overflow: 'hidden',
                    flexBasis: 0,
                },
                ease: Sine.easeIn,
            }, 'size')

            master.fromTo(panel, .5, {
                css: {
                    flexBasis: '40%',
                }
            }, {
                css: {
                    flexBasis: '100%',
                },
                ease: Sine.easeInOut,
            }, 'size')

            master.addLabel('hide-payment', '+=0')

            master.fromTo(payment.$el, .1, {
                className: '-=payment--hidden'
            }, {
                className: '+=payment--hidden'
            }, 'hide-payment')

            master.addLabel('success-panel', '+=0')

            master.fromTo(success, .2, {
                autoAlpha: 0,
            }, {
                autoAlpha: 1,
            }, 'success-panel')

            master.addLabel('show-message', '+=0')

            master.staggerFromTo([successTitle, successMessage, successBtn], .6, {
                autoAlpha: 0,
            }, {
                autoAlpha: 1,
            }, .3, 'show-message')

            master.progress(1).progress(0)

            this.$nextTick(() => {
                console.log('ciao');
                master.play()
            })
        },
    },
    mounted: function () {
        this.init()
        // this.debug()
    },
}
</script>

<style lang="scss">
@import '~styles/shared';

.checkout {
    &__modal {
        background-image: none;
        border: none;
    }

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
        @include gradient-directional(darken($white, 10), darken($white, 5), 305deg);
        @include box-shadows-size(rgba($black, .33), 0px, 2px, 30px, 0);
        height: 100%;
    }

    &__short {
        flex: 1 1 40%;
        @include gradient-directional(lighten($primary, 10), lighten($primary, 0), 125deg);
        @include box-shadows($black);
    }
}

.succes-message {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    display: none;

    &__btn {
        margin-top: $spacer * 2;
    }
}

.cart-actions {
    margin-top: $spacer * 2;
    width: 100%;
    display: flex;
    justify-content: space-around;
}
</style>
