<template>
<page-template
    :title="mainTitle"
    :notifications="notifications"
    :has-sub-header="true"
>
    <template slot="sub-header">
        <ui-row
            min-width="100%"
            v-if="product"
        >
            <div class="col-md-3">
                <h5>Posti Disponibili</h5>
                {{ product.guests_available }}
            </div>
            <div class="col-md-3">
                <h5>Iscritti Da verificare</h5>
                {{ toBeConfirmed }}
            </div>
            <div class="col-md-3">
                <h5>Iscritti confermati</h5>
                {{ product.guests_confirmed }}
            </div>
            <div class="col-md-3">
                <h5>Durata del corso in ore</h5>
                {{ product.hours }}
            </div>
        </ui-row>
        <hr class="my-4">
        <ui-row
            min-width="100%"
            v-if="product"
        >
            <div class="col-md-3">
                <h5>Data di inizio</h5>
                {{ startDate }}
            </div>
            <div class="col-md-3">
                <h5>Data di fine</h5>
                {{ endDate }}
            </div>
            <div class="col-md-3">
                <h5>Importo incassato</h5>
                {{ amountConfirmed.toFixed(2) }}
            </div>
            <div class="col-md-3">
                <h5>Giorni all'inizio</h5>
                <span :class="daysColors">{{ daysFromStart }}</span>
            </div>
        </ui-row>
    </template>
    <b-table
        ref="table"
        striped
        hover
        :items="subscribers"
        :fields="fields"
    >
        <template
            slot="payment_type_id"
            slot-scope="data"
        >
            <span
                v-if="data.item.payment_type_id == 1"
                class="text-success"
            >
                Carta
            </span>
            <span
                v-else-if="data.item.payment_type_id == 3"
                class="text-warning"
            >
                Carta Docente
            </span>
            <span
                v-else-if="data.item.payment_type_id == 4"
                class="text-danger"
            >
                Richiesta di informazioni
            </span>
        </template>
        <template
            slot="order.payment_status_id"
            slot-scope="data"
        >
            <span
                v-if="data.item.order.payment_status_id == 1"
                class="text-success"
            >
                Pagato
            </span>
            <span
                v-else-if="data.item.order.payment_status_id == 2"
                class="text-warning"
            >
                Da verificare
            </span>
            <span
                v-else-if="data.item.order.payment_status_id == 3"
                class="text-danger"
            >
                Non Pagato
            </span>
            <span
                v-else-if="data.item.order.payment_status_id == 5"
                class="text-danger"
            >
                Ordine Cancellato
            </span>
            <span
                v-else
                class="text-danger"
            >
                Ordine Non Completato
            </span>
        </template>
        <template
            slot="name"
            slot-scope="data"
        >
            <span>
                {{ data.item.name }} {{ data.item.surname }}
            </span>
        </template>
        <template
            slot="tools"
            slot-scope="data"
        >
            <button
                class="btn btn-outline-primary"
                @click="data.toggleDetails"
            >
                Modifica
            </button>
        </template>

        <template v-slot:row-details="row">
            <subscriber-edit
                :subscriber="row.item"
                :product="product"
                @update="update"
            />
        </template>
    </b-table>
    <div class="controls d-flex justify-content-center">
        <button
            class="btn btn-outline-primary"
            @click="addSubs"
        >
            Aggiungi Partecipante
        </button>
    </div>
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
    name: 'ManageSubscribers',
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
                return 'Gestione iscrizioni: ' + this.product.title
            }
            else {
                return 'Gestisci iscrizioni'
            }
        },
        opts: function () {
            // return PostsOpts.find(opt => opt.slug === this.$route.params.type)
            return []
        },
        daysFromStart: function () {
            let start = moment(this.product.start_at)
            let now = moment()

            let diff = moment.duration(start.diff(now)).get('days')

            if (diff > 0) {
                return diff
            }
            else {
                return 'già iniziato'
            }
        },
        daysColors: function () {
            if (this.daysFromStart > 0) {
                return 'text-default'
            }
            else {
                return 'text-danger'
            }
        },
        startDate: function () {
            return moment(this.product.start_at).format('DD-MM-YYYY')
        },
        endDate: function () {
            return moment(this.product.end_at).format('DD-MM-YYYY')
        },
    },
    methods: {
        getSubscribers: function () {
            this.subscribers = []
            // this.fields = this.opts.fields
            this.fields.push()
            let url = '/api/admin/subscribers/' + this.$route.params.id

            this.$http.get(url)
                .then(response => {
                    if (response.data.success) {
                        let product = response.data.product
                        this.formatSubscribers(product)
                        // this.debug()
                    }
                })
        },
        update: function (product) {
            this.formatSubscribers(product)
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
        debug: function () {
            this.subscribers[5]['_showDetails'] = true
        },
        deletePost: function (item) {
            let url = '/api/admin/post-type/' + this.opts.table + '/delete/' + item.id
            this.$http.delete(url)
                .then(response => {
                    if (response.status) {
                        let idx = this.subscribers.findIndex(post => post.id === response.data.element.id)
                        if (idx > -1) {
                            this.notifications.push({
                                uuid: Uuid.get(),
                                title: 'Post Eliminato',
                                message: response.data.element.title + ' è stato eliminato'
                            })
                            this.subscribers.splice(idx, 1)
                        }
                    }
                })
        },
        manageSubscribers: function (item) {
            this.$root.goToWithParams('manage-subscribers', {
                id: item.id
            })
        },
        addSubs: function () {

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
