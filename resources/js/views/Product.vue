<template lang="html">
    <div class="row">
        <div class="col-md-4" ref="info">
            <div class="ui-sidebar-content">
                <div class="ui-sidebar-content__title">
                    Date
                </div>
                <div class="ui-sidebar-content__paragraph">
                    <span v-if="startDate && endDate">
                        dal 31 ottobre ’18 al 31 marzo 2019<br>
                    </span>
                    dalle ore 9:00 alle ore 13:00 del sabato<br>
                    e dalle 18:00 alle 21:00 del venerdi<br>
                    <br>
                    frequenza <b>bisettimanale</b><br>
                    totale ore <b>145</b> di formazione in aula<br>
                    <b>attestato</b> di frequenza<br>
                </div>
            </div>
            <div class="ui-sidebar-content">
                <div class="ui-sidebar-content__title">
                    Destinatari
                </div>
                <div class="ui-sidebar-content__paragraph">
                    Persone motivate a lavorare con bambini/ragazzi con disturbi specifici dell’apprendimento
                </div>
            </div>
            <div class="ui-sidebar-content ui-sidebar-content--price">
                <div class="ui-sidebar-content__price-label">
                    Prezzo
                </div>
                <div class="ui-sidebar-content__price">
                    350,00 € + IVA
                </div>
            </div>
            <div class="ui-sidebar-buy">
                <div class="ui-sidebar-buy__title">
                    Info e Prenotazioni
                </div>
                <fieldset
                    class="form-group ui-sidebar-buy__form">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="pay-direct" name="pay-direct" value="pay-direct" class="custom-control-input" v-model="booking">
                        <label class="custom-control-label" for="pay-direct">Desidero iscrivermi e pagare con paypal o carta di credito</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="pay-transfer" name="pay-transfer" value="pay-transfer" class="custom-control-input" v-model="booking">
                        <label class="custom-control-label" for="pay-transfer">Desidero iscrivermi e pagare con bonifico</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="only-info" name="only-info" value="only-info" class="custom-control-input" v-model="booking">
                        <label class="custom-control-label" for="only-info">Desidero ricevere informazioni</label>
                    </div>
                </fieldset>
                <div class="ui-sidebar-buy__info">
                    <p>
                        Note:<br>
                        1) Il pagamento con Paypal o Carta di Credito consente l'iscrizione diretta al seminario<br>
                        2) Il Bonifico Bancario consente l'iscrizione solo dopo la ricezione del pagamento.<br>
                    </p>
                </div>
                <div>
                    <ui-button
                        title="prosegui"
                        color="primary"
                        align="center"/>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <module-manager
                v-for="module in this.modules"
                :key="module.id"
                :module="module"
                @title="changeTitle"/>
        </div>
    </div>
</template>

<script>
import {
    sortModules
}
from '../Utilities'
import {
    UiButton
}
from '../ui'
import ModuleManager from '../containers/ModuleManager.vue'
import moment from 'moment'

export default {
    name: 'ProductPage',
    components: {
        ModuleManager,
        UiButton,
    },
    data: function () {
        return {
            name: null,
            modules: null,
            booking: 'pay-direct',
            title: false,
            paddingTopTitle: 0,
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        },
        title: function () {

        }
    },
    computed: {
        startDate: function () {
            return null
        },
        endDate: function () {
            return null
        }
    },
    methods: {
        init: function () {
            let url = '/api/get-page/' + this.$route.params.page + '/' + this.$route.params.subpage + '/' + this.$route.params.item
            this.getData(url)
        },
        getData: function (url) {
            if (url) {
                this.$http.get(url).then(response => {
                    // console.log(response.data);
                    if (response.data.success) {
                        this.name = response.data.item.title
                        this.$root.sidebar = response.data.item.sidebar
                        this.modules = sortModules(response.data.item.modules)
                    }
                })
            }
        },
        changeTitle: function (height) {
            if (!this.title) {
                this.title = true
                this.$refs.info.style.paddingTop = height
            }
        }
    },
    created: function () {
        this.init()
    },
    mounted: function () {}
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
