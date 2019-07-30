<template>
<div :class="wrapperClass">
    <div id="dropin-container">

    </div>

    <button
        type="submit"
        style="padding-top: 1rem;"
        id="submitTransaction"
        @click="dropinRequestPaymentMethod"
    >
        Drop-in Test
    </button>
</div>
</template>

<script>
export default {
    props: {
        authToken: {
            type: String,
        },
        wrapperClass: {
            type: String,
        },
        loaderClass: {
            type: String,
        },
        inputClass: {
            type: String,
        },
        collectCardHolderName: {
            type: Boolean,
        },
        collectPostalCode: {
            type: Boolean,
        },
        enableDataCollector: {
            type: Boolean,
        },
        enablePayPal: {
            type: Boolean,
        },
    },
    data: function () {
        return {
            errorMessage: '',
            dropinInstance: '',
            paymentPayload: '',
            dataCollectorPayload: '',
        }
    },
    methods: {
        dropinCreate: function () {
            const dropin = require('braintree-web-drop-in')
            // setup drop-in options
            const dropinOptions = {
                authorization: this.authToken,
                selector: '#dropin-container',
            }
            // if PayPal enabled, add to options settings
            if (this.enablePayPal) {
                dropinOptions.paypal = {
                    flow: 'vault'
                }
            }
            dropin.create(dropinOptions, (dropinError, dropinInstance) => {
                if (dropinError) {
                    this.errorMessage = 'There was an error setting up the client instance. Message: ' + dropinError.message
                    this.$emit('bt.error', this.errorMessage)
                    return
                }
                this.dropinInstance = dropinInstance
            })
        },
        dropinRequestPaymentMethod: function () {
            this.dropinInstance.requestPaymentMethod((requestErr, payload) => {

                if (requestErr) {
                    this.errorMessage = 'There was an error setting up the client instance. Message: ' + requestErr.message
                    this.$emit('bt.error', this.errorMessage)
                    return
                }
                this.paymentPayload = payload
                // do something with the payload/nonce
            })
        },
    },
    created: function () {
        this.dropinCreate()
        this.$parent.$on('tokenize', () => {
            this.dropinRequestPaymentMethod()
        })
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
