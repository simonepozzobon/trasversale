<template>
<page-template
    :title="mainTitle"
    :notifications="notifications"
>
    <subscriber-edit
        :is-new="true"
        :product="this.product"
        @update="update"
    />
</page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import SubscriberEdit from '../containers/SubscriberEdit.vue'

import {
    checkDuplicateInObject,
    Uuid
}
from '../../Utilities'

import {
    UiRow,
    UiBlock,

}
from '../../ui'
import moment from 'moment'


export default {
    name: 'CreateSubscriber',
    components: {
        PageTemplate,
        SubscriberEdit,
        UiRow,
        UiBlock,
    },
    data: function () {
        return {
            subscribers: [],
            product: null,
            fields: [{
                    key: 'order.code',
                    label: 'Numero Ordine',
                    sortable: true
                }, {
                    key: 'payment_type_id',
                    label: 'Modalità',
                    sortable: true
                }, {
                    key: 'order.payment_status_id',
                    label: 'Stato',
                    sortable: true
                }, {
                    key: 'guests',
                    label: 'Iscritti',
                    sortable: true,
                }, {
                    key: 'name',
                    label: 'Nome',
                    sortable: true
                }, {
                    key: 'email',
                    label: 'Email',
                    sortable: true
                },
                {
                    key: 'tools',
                    label: 'tools'
                }
            ],
            notifications: [],
            toBeConfirmed: 0,
            amountConfirmed: 0,
        }
    },
    watch: {
        '$route.params': function (params) {
            this.getSubscribers()
        }
    },
    computed: {
        mainTitle: function () {
            if (this.product && this.product.title) {
                return 'Crea Iscrizione: ' + this.product.title
            }
            else {
                return 'Crea Iscrizione'
            }
        },
    },
    methods: {
        getSubscribers: function () {
            this.subscribers = []
            // this.fields = this.opts.fields
            this.fields.push()
            let url = '/api/admin/subscribers/' + this.$route.params.product_id

            this.$http.get(url)
                .then(response => {
                    if (response.data.success) {
                        let product = response.data.product
                        this.formatSubscribers(product)
                        // this.debug()
                        console.log('ciao', response.data);
                    }
                })
        },
        formatSubscribers: function (product) {
            let subscribers = []
            let toBeConfirmed = 0
            let codes = []
            let amountConfirmed = 0

            if (product) {
                let order_items = product.hasOwnProperty('order_items') ? product.order_items : []

                order_items.forEach(order_item => {
                    let subscriber = order_item.order.subscriber

                    let order = order_item.order
                    delete order.subscriber

                    subscriber['order'] = order

                    let code = order.code
                    let check = codes.includes(code)
                    amountConfirmed = amountConfirmed + (Number(order_item.price) * order_item.quantity)


                    if (check == false) {
                        codes.push(code)
                        subscriber['guests'] = order_item.quantity
                        subscribers.push(subscriber)
                    }
                    else {
                        let idx = subscribers.findIndex(obj => obj.order.code == order.code)
                        if (idx > -1) {
                            subscribers[idx].guests = subscribers[idx].guests + order_item.quantity
                        }
                    }


                    if (Number(order.payment_status_id) != 1) {
                        toBeConfirmed = toBeConfirmed + 1
                    }
                })

            }
            this.product = product
            this.subscribers = subscribers
            this.toBeConfirmed = toBeConfirmed
            this.amountConfirmed = amountConfirmed
        },
        update: function () {
            this.$root.goToWithParams('manage-subscribers', {
                id: this.product.id
            })
        },
    },
    mounted: function () {
        this.getSubscribers()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.news-item {
    &__image {
        max-width: $spacer * 4;
    }
}
</style>
