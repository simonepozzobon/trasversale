<template>
<div class="dropin">
    <div
        class="dropin__loader"
        ref="loader"
    >
        <div
            class="spinner-border text-white"
            role="status"
        >
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div
        class="dropin__fields"
        ref="fields"
    >
        <div class="form-group row">
            <label
                for="card-number"
                class="col-12 dropin__label"
                ref="card"
            >
                Numero della carta
                <div
                    id="number"
                    class="form-control dropin_input"
                ></div>
            </label>
        </div>

        <div class="form-group row">
            <label
                for="cvv"
                class="col-md-3 dropin__label"
                ref="cvv"
            >
                CVV
                <div
                    id="cvv"
                    class="form-control dropin_input"
                ></div>
            </label>

            <label
                for="expiration-date"
                class="col-md-9 dropin__label"
                ref="date"
            >
                Data di scadenza
                <div
                    id="expiration-date"
                    class="form-control dropin_input"
                ></div>
            </label>
        </div>
    </div>
</div>
</template>

<script>
import {
    gsap
}
from 'gsap'

const client = require('braintree-web/client')
const hostedFields = require('braintree-web/hosted-fields')
const vaultManager = require('braintree-web/vault-manager')

export default {
    props: {
        order: {
            type: Object,
            default: function () {
                return {}
            }
        }
    },
    data: function () {
        return {
            auth: null,
            errorMessage: '',
            dropinInstance: null,
            clientInstance: null,
            hfInstance: null,
            vaultedPaymentMethods: null,
            paymentPayload: '',
            dataCollectorPayload: '',
            master: null
        }
    },
    methods: {
        initAnim: function () {
            let container = this.$refs.fields
            let loader = this.$refs.loader
            let fields = [
                this.$refs.card,
                this.$refs.cvv,
                this.$refs.date
            ]

            this.master = gsap.timeline({
                paused: true,
                yoyo: true,
            })

            this.master.fromTo(loader, .6, {
                autoAlpha: 1,
            }, {
                autoAlpha: 0,
            }, 0)

            this.master.fromTo(container, .6, {
                opacity: 0,
                yPercent: 10,
            }, {
                opacity: 1,
                yPercent: 0,
            }, 0)

            this.master.staggerFromTo(fields, .6, {
                autoAlpha: 0,
                yPercent: 10,
                xPercent: -1,
                scale: 0.95,
            }, {
                autoAlpha: 1,
                yPercent: 0,
                xPercent: 0,
                scale: 1,
            }, .1, 0)

            this.master.progress(1).progress(0)

            this.init()
        },
        init: function () {
            this.$http.get('/api/payment/auth').then(response => {
                // console.log(response.data);
                let auth = response.data.token
                client.create({
                    authorization: auth,
                }, (clientErr, clientInstance) => {
                    if (clientErr) {
                        console.log(clientErr);
                    }
                    else {
                        this.clientInstance = clientInstance
                        this.$nextTick(() => {
                            this.initHostedFields()
                            this.initVaultManager()
                        })
                    }
                })
            })
        },
        initHostedFields: function () {
            hostedFields.create({
                client: this.clientInstance,
                fields: {
                    number: {
                        selector: '#number',
                        placeholder: '4111 1111 1111 1111',
                        // prefill: '4111111111111111'
                    },
                    cvv: {
                        selector: '#cvv',
                        placeholder: '123',
                        // prefill: '400'
                    },
                    expirationDate: {
                        selector: '#expiration-date',
                        placeholder: '10/19',
                        // prefill: '09/20'
                    },
                }
            }, (hostedFieldsErr, hfInstance) => {
                if (hostedFieldsErr) {
                    console.log(hostedFieldsErr)
                }
                else {
                    this.master.play()

                    let state = hfInstance.getState()
                    let fields = state.fields

                    for (let key in fields) {
                        if (fields.hasOwnProperty(key)) {
                            let el = fields[key]['container']
                            el.style.boxShadow = 'inset 0px 1px 4px rgba(83, 88, 95, 0.4)'
                            el.style.backgroundImage = 'linear-gradient(-225deg, rgba(255, 255, 255, 1), rgba(242, 242, 242, .8))'
                            el.style.backgroundRepeat = 'repeat-x'
                            el.style.border = '0 solid transparent'
                        }
                    }

                    this.hfInstance = hfInstance

                    // this.$nextTick(() => {
                    //     // this.debug()
                    // })
                }
            })
        },
        debug: function () {
            this.tokenize()
        },
        initVaultManager: function () {
            vaultManager.create({
                client: this.clientInstance,
            }, (err, vaultInstance) => {
                if (err) {
                    this.errorMessage = 'There was an error setting up the client instance. Message: ' + err.message;
                    this.$emit('bthferror', this.errorMessage);
                    return;
                }
                // console.log(vaultInstance);
                vaultInstance.fetchPaymentMethods((err, paymentMethods) => {
                    console.log(paymentMethods);
                    this.vaultedPaymentMethods = paymentMethods;
                });
            });
        },
        tokenize: function () {
            if (this.hfInstance) {

                this.master.eventCallback('onReverseComplete', () => {
                    this.hfInstance.tokenize((err, payload) => {
                        if (err) {
                            console.error('Some fields are invalid:', err.details);
                            this.master.play()
                        }
                        else {
                            console.log(err, payload);
                            this.startTransaction(payload)
                        }
                    })
                })
                this.master.reverse()
            }
        },
        startTransaction: function (payload) {
            let data = new FormData()
            data.append('nonce', payload.nonce)
            data.append('order_id', this.order.id)

            this.$http.post('/api/payment/transaction', data).then(response => {
                console.log(response);
                if (response.data.success) {
                    this.$emit('completed')
                }
                else {
                    this.master.play()
                }
            }).catch(err => {
                this.master.play()
            })
        }
    },
    created: function () {

    },
    mounted: function () {
        this.initAnim()
    },
}
</script>

<style lang="scss">
@import '~styles/shared';
.dropin {
    position: relative;

    &__label {
        color: $white;
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

    &__fields {
        opacity: 0;
        overflow: hidden;
    }
}
</style>
