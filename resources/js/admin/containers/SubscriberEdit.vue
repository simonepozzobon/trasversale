<template>
<div
    class="subscriber-edit"
    :class="isNewClass"
>
    <div class="container">
        <dynamic-module
            v-if="this.module && this.initialized"
            name="subscriber"
            :uuid="uuid"
            :is-edit="false"
            :options="module.options"
            :values="values"
            @changed="setObj"
        />
        <div class="subscriber-edit__actions">
            <div class="form-group row">
                <label class="col-md-3">Salva</label>
                <div class="col-md-9">
                    <button
                        class="btn mb-2 btn-outline-success"
                        align="center"
                        @click="saveSubscriber"
                    >
                        Salva modifiche
                    </button>
                </div>
            </div>
            <div
                class="form-group row"
                v-if="!isNew"
            >
                <label class="col-md-3">Annulla Iscrizione</label>
                <div class="col-md-9">
                    <button
                        class="btn mb-2 btn-outline-danger"
                        align="center"
                        @click="deleteSubscriber"
                    >
                        Annulla Iscrizione
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import DynamicModule from '../components/DynamicModule.vue'
import DynamicParams from '../DynamicParams'
import {
    UiTitle
}
from '../../ui'
import {
    Uuid
}
from '../../Utilities'

export default {
    name: 'SubscriberEdit',
    components: {
        DynamicModule,
        UiTitle,
    },
    props: {
        isNew: {
            type: Boolean,
            default: false,
        },
        subscriber: {
            type: Object,
            default: function () {
                return {}
            },
        },
        product: {
            type: Object,
            default: function () {
                return {}
            }
        }
    },
    data: function () {
        return {
            initialized: false,
            values: {
                name: null,
                surname: null,
                email: null,
                payment_type_id: null,
                payment_status_id: null,
                address: null,
                city: null,
                province: null,
                postal_code: null,
                ragione_sociale: null,
                cf: null,
                codice_destinatario: null,
                language: null,
                pec: null,
                phone: null,
                type: null,
                vat: null,
            },
            form: null
        }
    },
    computed: {
        uuid: function () {
            return Uuid.get()
        },
        module: function () {
            let name = 'subscriber-edit'
            let options = DynamicParams.find(params => params.name === name)
            return options
        },
        isNewClass: function () {
            if (this.isNew == true) {
                return 'subscriber-edit--new'
            }
            else {
                return null
            }
        },
    },
    methods: {
        setObj: function (obj) {
            this.form = obj
        },
        setInitial: function () {
            if (this.isNew == false) {
                this.values = {
                    name: this.subscriber.name,
                    surname: this.subscriber.surname,
                    email: this.subscriber.email,
                    payment_type_id: this.subscriber.payment_type_id,
                    payment_status_id: this.subscriber.order.payment_status_id,
                    address: this.subscriber.order.address,
                    city: this.subscriber.order.city,
                    province: this.subscriber.order.province,
                    postal_code: this.subscriber.order.postal_code,
                    ragione_sociale: this.subscriber.order.ragione_sociale,
                    cf: this.subscriber.order.cf,
                    codice_destinatario: this.subscriber.order.codice_destinatario,
                    language: this.subscriber.order.language,
                    pec: this.subscriber.order.pec,
                    phone: this.subscriber.order.phone,
                    type: this.subscriber.order.type,
                    vat: this.subscriber.order.vat,
                }
            }

            this.$nextTick(() => {
                this.initialized = true
            })
        },
        saveSubscriber: function () {
            let data = new FormData()

            for (let key in this.form) {
                if (this.form.hasOwnProperty(key) && key != 'undefined') {
                    data.append(key, this.form[key])
                }
            }

            data.append('product_id', this.product.id)

            if (this.isNew == true) {
                console.log('è nuovo');
                data.append('is_new', 1)
            }
            else {
                data.append('is_new', 0)
                data.append('subscriber_id', this.subscriber.id)
                data.append('order_id', this.subscriber.order.id)
            }

            for (let value of data) {
                console.log(value[0], value[1]);
            }

            this.$http.post('/api/admin/subscribers/save', data).then(response => {
                console.log(response.data);
                if (response.data.success) {
                    this.$nextTick(() => {
                        this.$emit('update', response.data.product)
                    })
                }
            })
        },
        deleteSubscriber: function () {
            let data = new FormData()

            data.append('subscriber_id', this.subscriber.id)
            data.append('order_id', this.subscriber.order.id)
            data.append('product_id', this.product.id)

            this.$http.post('/api/admin/subscribers/cancel', data).then(response => {
                console.log(response.data);
                if (response.data.success) {
                    this.$nextTick(() => {
                        this.$emit('update', response.data.product)
                    })
                }
            })

        },
    },
    mounted: function () {
        this.setInitial()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
.subscriber-edit {
    min-height: 100%;
    max-width: 100%;
    height: 100%;
    padding: $spacer * 3;
    margin-top: $spacer;
    margin-bottom: $spacer * 2.618;
    @include gradient-directional(rgba($gray-300, .6), $light, 135deg);
    @include border-radius($spacer / 2);
    @include box-shadows($gray-500);

    &--new {
        padding: 0;
        background-color: transparent !important;
        box-shadow: 0 0 0 transparent;
        @include border-radius(0);
    }
}
</style>

<style lang="scss">
@import '~styles/shared';
$opacity-test: 0.6 !default;

.b-table-details {
    background: rgba($white, 0) !important;
    transition: $transition-base;

    td {
        padding: 0;
    }
}

.b-table-has-details,
.table-striped tbody tr:hover {
    @include gradient-directional($light, lighten($light, 10), 135deg);
    @include box-shadows($gray-500);
    transition: $transition-base;
    position: relative;
    max-width: 100%;

    td:first-child {
        @include border-left-radius($spacer / 2);
        transition: $transition-base;
    }

    td:last-child {
        @include border-right-radius($spacer / 2);
        transition: $transition-base;
    }
}
</style>
