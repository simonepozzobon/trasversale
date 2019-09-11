<template>
<div class="personal-data">
    <div class="personal-data__title">
        <ui-title
            ref="title"
            title="Dettagli Fatturazione"
            :is-column="true"
            tag="h4"
            font-size="h4"
            color="white"
        />
    </div>
    <div class="personal-data__form">
        <div
            class="personal-data__loader"
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
            class="personal-data-form"
            ref="form"
        >
            <div class="form-group row">
                <label
                    for="type"
                    class="col-md-12 personal-data-form__label"
                >
                    Tipologia soggetto
                    <select
                        class="form-control personal-data-form__input"
                        name="type"
                        v-model="form.type"
                    >
                        <option value="professionista">Persona fisica con partita iva</option>
                        <option value="azienda">Società</option>
                        <option value="privato">Persona fisica senza partita iva</option>
                    </select>
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="language"
                    class="col-md-12 personal-data-form__label"
                >
                    Residenza
                    <select
                        class="form-control personal-data-form__input"
                        name="language"
                        v-model="form.language"
                    >
                        <option value="italia">Italia</option>
                        <option value="estero">Estero</option>
                    </select>
                </label>
            </div>
            <div
                class="form-group row"
                v-if="form.type === 'privato'"
            >
                <label
                    for="name"
                    class="col-md-6 personal-data-form__label"
                    ref="name"
                >
                    Nome
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="name"
                        v-model="form.name"
                    />
                </label>

                <label
                    for="surname"
                    class="col-md-6 personal-data-form__label"
                    ref="surname"
                >
                    Cognome
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="surname"
                        v-model="form.surname"
                    />
                </label>
            </div>
            <div
                class="form-group row"
                v-else
            >
                <label
                    for="ragione_sociale"
                    class="col-12 personal-data-form__label"
                    ref="ragione_sociale"
                >
                    Ragione Sociale
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="ragione_sociale"
                        v-model="form.ragione_sociale"
                    />
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="email"
                    class="col-12 personal-data-form__label"
                    ref="email"
                >
                    Email
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="email"
                        v-model="form.email"
                    />
                </label>
            </div>
            <div
                class="form-group row"
                v-if="form.type != 'privato'"
            >
                <label
                    for="pec"
                    class="col-12 personal-data-form__label"
                    ref="pec"
                >
                    PEC
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="pec"
                        v-model="form.pec"
                    />
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="phone"
                    class="col-12 personal-data-form__label"
                    ref="phone"
                >
                    Telefono
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="phone"
                        v-model="form.phone"
                    />
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="address"
                    class="col-12 personal-data-form__label"
                    ref="address"
                >
                    Indirizzo
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="address"
                        v-model="form.address"
                    />
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="city"
                    class="col-12 personal-data-form__label"
                    ref="city"
                >
                    Comune
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="city"
                        v-model="form.city"
                    />
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="province"
                    class="col-md-4 personal-data-form__label"
                    ref="province"
                >
                    Provincia
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="province"
                        v-model="form.province"
                    />
                </label>
                <label
                    for="postal_code"
                    class="col-md-8 personal-data-form__label"
                    ref="postal_code"
                >
                    CAP
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="postal_code"
                        v-model="form.postal_code"
                    />
                </label>
            </div>
            <div class="form-group row">
                <label
                    for="country"
                    class="col-12 personal-data-form__label"
                    ref="country"
                >
                    Stato
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="country"
                        v-model="form.country"
                    />
                </label>
            </div>
            <div
                class="form-group row"
                v-if="form.type != 'privato'"
            >
                <label
                    for="vat"
                    class="col-12 personal-data-form__label"
                    ref="vat"
                >
                    P.IVA
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="vat"
                        v-model="form.vat"
                    />
                </label>
            </div>
            <div
                class="form-group row"
                v-if="form.type != 'privato'"
            >
                <label
                    for="cf"
                    class="col-12 personal-data-form__label"
                    ref="cf"
                >
                    Codice fiscale
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="cf"
                        v-model="form.cf"
                    />
                </label>
            </div>
            <div
                class="form-group row"
                v-if="form.type != 'privato'"
            >
                <label
                    for="codice_destinatario"
                    class="col-12 personal-data-form__label"
                    ref="codice_destinatario"
                >
                    Codice destinatario
                    <input
                        type="text"
                        class="form-control personal-data-form__input"
                        name="codice_destinatario"
                        v-model="form.codice_destinatario"
                    />
                </label>
            </div>
        </div>
    </div>
    <div class="personal-data__action">
        <ui-button
            ref="btn"
            title="Procedi al pagamento"
            color="yellow"
            :is-active="true"
            :disable="disableBtn"
            :has-container="false"
            :has-margin="false"
            display="inline-block"
            @click="submitOrder"
        />
    </div>
</div>
</template>

<script>
import {
    UiBlock,
    UiButton,
    UiRow,
    UiTitle,
}
from '../ui'

export default {
    name: 'PersonalData',
    components: {
        UiBlock,
        UiButton,
        UiRow,
        UiTitle,
    },
    props: {
        items: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            master: null,
            form: {
                type: 'privato',
                language: 'italia',
                name: null,
                surname: null,
                ragione_sociale: null,
                address: null,
                city: null,
                province: null,
                postal_code: null,
                country: 'Italia',
                email: null,
                phone: null,
                pec: null,
                vat: null,
                codice_destinatario: null,
                cf: null,
            },
            requiredFields: {
                privato: ['language', 'name', 'surname', 'email', 'phone', 'address', 'city', 'province', 'postal_code', 'country'],
                professionista: ['language', 'ragione_sociale', 'email', 'pec', 'phone', 'address', 'city', 'province', 'postal_code', 'country', 'vat', 'cf', 'codice_destinatario'],
                azienda: ['language', 'ragione_sociale', 'email', 'pec', 'phone', 'address', 'city', 'province', 'postal_code', 'country', 'vat', 'cf', 'codice_destinatario'],
            },
            disableBtn: false,
        }
    },
    methods: {
        debug: function () {
            setTimeout(() => {
                this.form = {
                    name: 'simone',
                    surname: 'pozzobon',
                    address: 'Ripa di porta ticinese, 99',
                    city: 'Milano',
                    province: 'Milano',
                    postal_code: '20143',
                }

                this.$nextTick(() => {
                    this.submitOrder()
                })
            }, 500)
        },
        initAnim: function () {
            let container = this.$refs.form
            let loader = this.$refs.loader
            let fields = container.getElementsByClassName('personal-data-form__label')

            this.master = new TimelineMax({
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

            this.$nextTick(() => {
                this.master.play()
                // this.debug()
            })
        },
        checkFields: function () {
            return new Promise((resolve, reject) => {
                let requiredFields = this.requiredFields[this.form.type]
                let check = true

                // reset classes
                let container = this.$refs.form
                let inputs = container.getElementsByClassName('personal-data-form__input')
                for (let i = 0; i < inputs.length; i++) {
                    let input = inputs[i]
                    input.classList.remove('not-valid')
                    input.classList.remove('valid')
                }

                for (let i = 0; i < requiredFields.length; i++) {
                    let field = requiredFields[i]
                    let input = document.getElementsByName(field)

                    if (input.length > 0) {
                        input = input[0]

                        if (this.form[field]) {
                            input.classList.add('valid')
                        }
                        else {
                            input.classList.add('not-valid')
                            check = false
                        }
                    }
                }

                resolve(check)
            })
        },
        submitOrder: function () {
            this.checkFields().then((response) => {
                if (response) {
                    this.disableBtn = true

                    this.$nextTick(() => {
                        let data = new FormData()

                        for (let key in this.form) {
                            if (this.form.hasOwnProperty(key)) {
                                data.append(key, this.form[key])
                            }
                        }
                        data.append('items', JSON.stringify(this.items))

                        this.master.reverse()

                        this.$http.post('/api/payment/order', data).then(response => {
                            console.log(response.data);
                            if (response.data.success) {
                                setTimeout(() => {
                                    this.leavingAnim().then(() => {
                                        this.$emit('completed', response.data.order)
                                    })
                                }, 1000)
                            }
                            else {
                                this.disableBtn = false
                            }

                        })
                    })
                }
            })
        },
        leavingAnim: function () {
            return new Promise((resolve, reject) => {
                let loader = this.$refs.loader
                let btn = this.$refs.btn.$el
                let title = this.$refs.title.$el

                let master = new TimelineMax({
                    paused: true
                })

                TweenMax.set(loader, {
                    clearProps: 'all'
                })

                master.staggerFromTo([btn, title], .6, {
                    autoAlpha: 1,
                }, {
                    autoAlpha: 0
                }, .1)

                master.progress(1).progress(0)

                master.eventCallback('onComplete', () => {
                    resolve()
                })

                this.$nextTick(() => {
                    master.play()
                })
            });
        }
    },
    mounted: function () {
        this.$nextTick(() => {
            this.initAnim()
        })
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
.personal-data {
    padding: $spacer * 2;

    &__form {
        padding-top: $spacer * 2;
        padding-bottom: $spacer;
        position: relative;
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
}

.personal-data-form {
    position: relative;

    &__label {
        color: $white;
    }

    &__fields {
        opacity: 0;
        overflow: hidden;
    }

    &__input {
        box-shadow: inset 0 1px 4px rgba(83, 88, 95, 0.4);
        background-image: linear-gradient(-225deg, rgba(255, 255, 255, 1), rgba(242, 242, 242, .8));
        background-repeat: repeat-x;
        border: 0 solid transparent;
        border-radius: 0 !important;

        &.not-valid {
            border: 2px solid $red;
        }

        &.valid {
            border: 2px solid $green;
        }
    }
}
</style>
