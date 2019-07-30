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
        Request Payment Method
    </button>
</div>
</template>

<script>
const client = require('braintree-web/client')
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
            auth: null,
            errorMessage: '',
            dropinInstance: null,
            paymentPayload: '',
            dataCollectorPayload: '',
        }
    },
    methods: {
        init: function () {},
        dropinCreate: function () {
            const dropin = require('braintree-web-drop-in')
            // setup drop-in options
            this.$http.get('/api/payment/auth').then(response => {
                // console.log(response.data);
                let auth = response.data.token
                dropin.create({
                    authorization: auth,
                    container: '#dropin-container'
                }, (dropinError, dropinInstance) => {
                    if (dropinError) {
                        this.errorMessage = 'There was an error setting up the client instance. Message: ' + dropinError.message
                        this.$emit('bt.error', this.errorMessage)
                        return
                    }
                    this.dropinInstance = dropinInstance
                })
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
