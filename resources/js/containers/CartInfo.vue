<template>
<div class="cart-info">
    <b-modal
        ref="modal"
        size="xl"
        hide-header
        hide-footer
        no-close-on-backdrop
        dialog-class="cart-info__modal-container"
        content-class="cart-info__modal"
    >
        <div class="cart-info__container">
            <div class="cart-info__row">
                <cart-info-data
                    :items="this.$root.cart"
                    @hide-cart="hideCart"
                />
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
    UiButton,
}
from '../ui'

import CartInfoData from './CartInfoData.vue'

export default {
    name: 'CartInfo',
    components: {
        CartInfoData,
        UiButton,
    },

    methods: {
        showCart: function () {
            this.$refs.modal.show()
        },
        hideCart: function () {
            this.$refs.modal.hide()
        },
    },
    mounted: function () {
        this.$nextTick(() => {
            this.showCart()
        })
    },
}
</script>

<style lang="scss">
@import '~styles/shared';

.cart-info {
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

        padding: $spacer * 2;
    }

    &__form {
        margin-top: $spacer * 2;
        padding-bottom: $spacer;
        position: relative;
    }

    &__loader {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
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
