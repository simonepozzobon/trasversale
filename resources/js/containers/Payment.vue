<template>
<div
    class="payment"
    ref="container"
>
    <div class="payment__title">
        <ui-title
            ref="title"
            title="Pagamento"
            :is-column="true"
            tag="h4"
            font-size="h4"
            color="white"
        />
    </div>
    <div class="payment__dropin">
        <dropin
            ref="dropin"
            :order="order"
            @completed="completed"
        />
    </div>
    <div class="payment__action">
        <ui-button
            ref="btn"
            title="Effettua il pagamento"
            color="yellow"
            :is-active="true"
            :has-container="false"
            :has-margin="false"
            display="inline-block"
            @click="submitPayment"
        />
    </div>
</div>
</template>

<script>
import Dropin from './Dropin.vue'

import {
    UiBlock,
    UiButton,
    UiRow,
    UiTitle,
}
from '../ui'

import {
    gsap
}
from 'gsap'

export default {
    name: 'Payment',
    components: {
        Dropin,
        UiBlock,
        UiButton,
        UiRow,
        UiTitle,
    },
    props: {
        order: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            authToken: null,
        }
    },
    methods: {
        init: function () {
            let container = this.$refs.container
            let dropin = this.$refs.dropin.$el
            let btn = this.$refs.btn.$el
            let title = this.$refs.title.$el

            let master = gsap.timeline({
                paused: true
            })

            master.fromTo(container, .2, {
                opacity: 0,
            }, {
                opacity: 1,
            }, 0)

            master.staggerFromTo([btn, dropin, title], .6, {
                autoAlpha: 0,
            }, {
                autoAlpha: 1
            }, .1, 0)

            master.progress(1).progress(0)

            this.$nextTick(() => {
                master.play()
            })
        },
        submitPayment: function () {
            this.$refs.dropin.tokenize()
        },
        completed: function () {
            this.$emit('completed')
        },
    },
    mounted: function () {
        this.$nextTick(() => {
            this.init()
        })
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.payment {
    padding: $spacer * 2;
    opacity: 0;

    &__dropin {
        padding-top: $spacer * 2;
        padding-bottom: $spacer;
    }

    &--hidden {
        display: none;
    }
}
</style>
