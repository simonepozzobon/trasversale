<template>
<div class="row">
    <div
        class="col-md-4"
        ref="info"
    >
        <div class="ui-sidebar-content">
            <div class="ui-sidebar-content__title">
                Date
            </div>
            <div class="ui-sidebar-content__paragraph">
                <span v-if="this.startDate && this.endDate">
                    dal {{ startDate }} al {{ endDate }}<br>
                </span>
                <span v-else-if="this.startDate">
                    dal {{ startDate }}
                </span>

                <!-- dalle ore 9:00 alle ore 13:00 del sabato<br>
                    e dalle 18:00 alle 21:00 del venerdi<br> -->
                <br>
                totale ore <b>{{ this.item.hours }}</b> di formazione<br>
                <!-- <b>attestato</b> di frequenza<br> -->
            </div>
        </div>
        <div class="ui-sidebar-content ui-sidebar-content-address">
            <div class="ui-sidebar-content__title">
                Indirizzo
            </div>
            <div class="ui-sidebar-content__paragraph">
                {{ this.item.address }}
            </div>
        </div>
        <div
            class="ui-sidebar-content"
            v-if="this.item.forwho && this.item.forwho != 'null'"
        >
            <div class="ui-sidebar-content__title">
                Destinatari
            </div>
            <div class="ui-sidebar-content__paragraph">
                {{ this.item.forwho }}
            </div>
        </div>
        <div
            class="ui-sidebar-content"
            v-if="Number(this.item.has_limited_guests) == 1"
        >
            <div class="ui-sidebar-content__title">
                Posti disponibili
            </div>
            <div class="ui-sidebar-content__paragraph">
                {{ this.item.guests_available }} di {{ this.item.guests_total }}
            </div>
        </div>
        <div class="ui-sidebar-content ui-sidebar-content--price">
            <div class="ui-sidebar-content__price-label">
                Prezzo
            </div>
            <div class="ui-sidebar-content__price">
                {{ item.price.toFixed(2) }} € + IVA
            </div>
        </div>
        <div class="ui-sidebar-buy">
            <div class="ui-sidebar-buy__title">
                Info e Prenotazioni
            </div>
            <fieldset class="form-group ui-sidebar-buy__form">
                <div class="custom-control custom-radio">
                    <input
                        type="radio"
                        id="pay-direct"
                        name="pay-direct"
                        value="pay-direct"
                        class="custom-control-input"
                        v-model="payment"
                    >
                    <label
                        class="custom-control-label"
                        for="pay-direct"
                    >Desidero iscrivermi e pagare con paypal o carta di credito</label>
                </div>
                <div class="custom-control custom-radio">
                    <input
                        type="radio"
                        id="pay-transfer"
                        name="pay-transfer"
                        value="pay-transfer"
                        class="custom-control-input"
                        v-model="payment"
                    >
                    <label
                        class="custom-control-label"
                        for="pay-transfer"
                    >Desidero iscrivermi e pagare con bonifico</label>
                </div>
                <div class="custom-control custom-radio">
                    <input
                        type="radio"
                        id="teacher-card"
                        name="teacher-card"
                        value="teacher-card"
                        class="custom-control-input"
                        v-model="payment"
                    >
                    <label
                        class="custom-control-label"
                        for="teacher-card"
                    >Desidero pagare con la teacher card</label>
                </div>
                <div class="custom-control custom-radio">
                    <input
                        type="radio"
                        id="only-info"
                        name="only-info"
                        value="only-info"
                        class="custom-control-input"
                        v-model="payment"
                    >
                    <label
                        class="custom-control-label"
                        for="only-info"
                    >Desidero ricevere informazioni</label>
                </div>
            </fieldset>
            <div class="ui-sidebar-buy__info">
                <p>
                    Note:<br>
                    1) Il pagamento con Paypal o Carta di Credito consente l'iscrizione diretta al seminario<br>
                    2) Il Bonifico Bancario consente l'iscrizione solo dopo la ricezione del pagamento.<br>
                    3) Il pagamento con la Teacher card consente l'iscrizione solo dopo la ricezione del pagamento.<br>
                </p>
            </div>
            <div>
                <ui-button
                    title="prosegui"
                    color="primary"
                    align="center"
                    @click="addToCart"
                />
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <module-manager
            v-for="module in this.modules"
            :key="module.id"
            :module="module"
            @title="changeTitle"
        />
    </div>
    <cart-info
        ref="cartInfo"
        :item="item"
    />
    <teacher-card
        ref="teacherCard"
        :item="item"
    />
    <bank-transfer
        ref="bankTransfer"
        :item="item"
    />
</div>
</template>

<script>
import {
    debounce,
    sortModules,
    Uuid,
}
from '../Utilities'
import {
    UiButton
}
from '../ui'


import BankTransfer from '../containers/BankTransfer.vue'
import CartInfo from '../containers/CartInfo.vue'
import ModuleManager from '../containers/ModuleManager.vue'
import moment from 'moment'
import TeacherCard from '../containers/TeacherCard.vue'

export default {
    name: 'ProductSubPage',
    components: {
        BankTransfer,
        CartInfo,
        ModuleManager,
        TeacherCard,
        UiButton,
    },
    props: {
        content: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            name: null,
            modules: null,
            payment: 'pay-direct',
            title: false,
            paddingTopTitle: 0,
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        },
        title: function () {

        },
    },
    computed: {
        item: function () {
            if (this.content && this.content.item) {
                return this.content.item
            }
            else {
                return {}
            }
        },
        startDate: function () {
            if (this.item.hasOwnProperty('start_at')) {
                return moment(this.item.start_at).locale('it').format('DD MMMM YYYY')
            }
            else {
                return null
            }
        },
        endDate: function () {
            if (this.item.hasOwnProperty('end_at')) {
                return moment(this.item.end_at).locale('it').format('DD MMMM YYYY')
            }
            else {
                return null
            }
        },
        startHours: function () {
            if (this.item.hasOwnProperty('start_at')) {
                return moment(this.item.start_at).locale('it').format('hh:mm')
            }
            else {
                return null
            }
        },
        endHours: function () {
            if (this.item.hasOwnProperty('end_at')) {
                return moment(this.item.end_at).locale('it').format('hh:mm')
            }
            else {
                return null
            }
        },
    },
    methods: {
        init: function () {
            this.name = this.content.item.title
            this.$root.sidebar = this.content.item.sidebar
            this.modules = sortModules(this.content.item.modules)
        },
        changeTitle: function (height) {
            if (!this.title) {
                this.title = true
                this.$refs.info.style.paddingTop = height
            }
        },
        addToCart: function (eventParams) {
            if (this.payment === 'pay-direct') {
                this.debounceCart()
            }
            else if (this.payment === 'pay-transfer') {
                // Pagamento con bonifico
                this.$refs.bankTransfer.showCart(this.content.item)
            }
            else if (this.payment === 'teacher-card') {
                // teacher card
                this.$refs.teacherCard.showCart(this.content.item)
            }
            else if (this.payment === 'only-info') {
                // contact form
                // console.log(this.content);
                this.$refs.cartInfo.showCart(this.content.item)
            }
        },
        debug: function () {
            for (let i = 0; i < 10; i++) {
                this.$root.$emit('add-to-cart', {
                    ...this.content,
                    uuid: Uuid.get()
                })
            }
        },
    },
    created: function () {
        this.init()
        this.debounceCart = debounce(() => {
            this.$root.$emit('add-to-cart', {
                ...this.content,
                uuid: Uuid.get()
            })
        }, 50)
    },
    mounted: function () {
        // Only for debug
        // this.$nextTick(() => {
        //     this.addToCart()
        // })
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-sidebar-content {
    margin-bottom: $spacer;

    &__title {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-lg;
        font-weight: $font-weight-bold;
    }

    &__price-label {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-lg;
        font-weight: $font-weight-bold;
        padding-right: $spacer;
    }

    &__paragraph {
        font-family: $font-family-sans-serif-var;
    }

    &__price {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-lg;
    }

    &--price {
        display: flex;
    }
}

.ui-sidebar-buy {
    margin-bottom: $spacer;

    &__title {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-lg;
        font-weight: $font-weight-bold;
        margin-bottom: $spacer / 2;
    }

    &__form {
        font-family: $font-family-sans-serif-var;
        margin-bottom: $spacer;
    }

    &__info {
        font-size: $font-size-sm;
        color: $text-muted;
        margin-bottom: $spacer;
    }

}
</style>
