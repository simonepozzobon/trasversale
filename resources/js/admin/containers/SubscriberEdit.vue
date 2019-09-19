<template>
<div class="subscriber-edit">
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
            <div class="form-group row">
                <label class="col-md-3">Elimina</label>
                <div class="col-md-9">
                    <button
                        class="btn mb-2 btn-outline-danger"
                        align="center"
                        @click="deleteSubscriber"
                    >
                        Elimina definitivamente
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
            values: null,
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
    },
    methods: {
        setObj: function (obj) {
            this.form = obj
        },
        setInitial: function () {
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

            this.$nextTick(() => {
                this.initialized = true
            })
        },
        saveSubscriber: function () {
            let data = new FormData()

            for (let key in this.form) {
                if (this.form.hasOwnProperty(key)) {
                    data.append(key, this.form[key])
                }
            }

            data.append('subscriber_id', this.subscriber.id)
            data.append('order_id', this.subscriber.order.id)
            data.append('product_id', this.product.id)

            let url = '/api/admin/subscribers/save'
            this.$http.post(url, data).then(response => {
                console.log(response);
                this.$emit('update', response.data.product)
            })
        },
        deleteSubscriber: function () {

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
