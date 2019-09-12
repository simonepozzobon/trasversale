<template>
<div
    class="cart-info__short"
    ref="container"
>
    <ui-title
        ref="title"
        title="Richiedi informazioni"
        :is-column="true"
        tag="h4"
        font-size="h4"
        color="white"
    />
    <div
        class="cart-info__loader"
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
        ref="form"
        class="cart-info__form personal-data-form"
    >
        <div class="form-group row">
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
        <div class="form-group row">
            <label
                for="message"
                class="col-12 personal-data-form__label"
                ref="message"
            >
                Richiesta
                <textarea
                    name="message"
                    rows="6"
                    class="form-control personal-data-form__input"
                    v-model="form.message"
                >
                    </textarea>
            </label>
        </div>
        <div class="personal-data-form__action">
            <ui-button
                ref="btn"
                title="Invia Richiesta"
                color="yellow"
                :is-active="true"
                :has-container="false"
                :has-margin="false"
                display="inline-block"
                @click="sendRequest"
            />
        </div>
    </div>
    <div
        class="succes-message"
        ref="success"
    >
        <ui-title
            ref="successTitle"
            title="Pagamento riuscito"
            color="white"
            :is-column="true"
            align="center"
        />
        <ui-paragraph
            ref="successMessage"
            color="white"
            content="A breve riceverà una mail di conferma"
            align="center"
        />

        <ui-button
            ref="successBtn"
            class="succes-message__btn"
            title="Chiudi"
            color="yellow"
            :is-active="true"
            :has-margin="false"
            display="inline-block"
            @click="hideCart"
        />
    </div>
</div>
</template>

<script>
import {
    UiButton,
    UiParagraph,
    UiTitle
}
from '../ui'

export default {
    name: 'CartInfoData',
    components: {
        UiButton,
        UiParagraph,
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
                name: null,
                surname: null,
                email: null,
                message: null,
            },
            requiredFields: ['name', 'surname', 'email', 'message'],
        }
    },
    methods: {
        initAnim: function () {
            let container = this.$refs.container
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
            })
        },
        hideCart: function () {
            this.$emit('hide-cart')
        },
        checkFields: function () {
            return new Promise((resolve, reject) => {
                let requiredFields = this.requiredFields
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
        sendRequest: function () {
            this.checkFields().then(response => {
                if (response) {
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
    },
    mounted: function () {
        this.$nextTick(() => {
            this.initAnim()
        })
    },
}
</script>

<style lang="scss">
@import '~styles/shared';

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

    &__action {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
}
</style>
