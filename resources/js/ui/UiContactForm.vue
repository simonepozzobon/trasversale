<template>
<div class="ui-contact-form">
    <div class="form-group">
        <label for="name">
            Nome
        </label>
        <input
            type="text"
            class="form-control"
            :class="nameClass"
            name="name"
            v-model="name"
            placeholder="Il tuo nome ..."
            :disabled="!isActive"
        >
    </div>
    <div class="form-group">
        <label for="email">
            Email
        </label>
        <input
            type="email"
            name="email"
            class="form-control"
            :class="emailClass"
            v-model="email"
            placeholder="La mail per contattarti ..."
            :disabled="!isActive"
        >
    </div>
    <div class="form-group">
        <label for="message">
            Messaggio
        </label>
        <textarea
            name="message"
            class="form-control"
            :class="messageClass"
            :disabled="!isActive"
            placeholder="Il tuo messaggio ..."
            v-model="message"
        ></textarea>
    </div>
    <div class="form-group">
        <label for="privacy">
            Privacy
        </label>
        <ui-checkbox
            @click="setPrivacy"
            :value="privacy"
            :text-class="privacyClass"
        >
            Inviando il form autorizzate il trattamento dei dati personali trasmessi ai sensi della legge 675/96. Si autorizza anche al trattamento dei dati per ricevere informazioni o promozioni di iniziative future.
        </ui-checkbox>
    </div>
    <div class="row">
        <div class="col">
            <button
                class="btn btn-outline-primary"
                :class="hasBigBtnClass"
                :disabled="!isActive"
                @click.prevent="sendMail"
            >
                Invia
                <span
                    class="spinner-border spinner-border-sm"
                    role="status"
                    aria-hidden="true"
                    v-if="isLoading"
                ></span>
            </button>
        </div>
    </div>
    <div
        class="row form-messages pt-5"
        ref="successMsg"
    >
        <div class="col">
            <p class="text-success">
                Il messaggio è stato inviato con successo, riceverai una mail di conferma all'indirizzo indicato e ti risponderemo al più presto.
            </p>
        </div>
    </div>
    <div
        class="row form-messages pt-5"
        ref="errorMsg"
    >
        <div class="col">
            <p class="text-danger">
                C'è stato un problema con l'invio del messaggio, ti preghiamo di riprovare o di scriverci una mail all'indirizzo <a href="mailto:trasversale@trasversale.com">trasversale@trasversale.com</a>
            </p>
        </div>
    </div>
</div>
</template>

<script>
import UiCheckbox from './UiCheckbox.vue'
export default {
    name: 'UiContactForm',
    components: {
        UiCheckbox,
    },
    props: {
        hasBigBtn: {
            type: Boolean,
            default: true,
        },
    },
    data: function () {
        return {
            name: null,
            nameClass: null,
            email: null,
            emailClass: null,
            message: null,
            messageClass: null,
            privacy: false,
            privacyClass: null,
            isActive: true,
            isLoading: false
        }
    },
    watch: {
        name: function (value) {
            if (value && value.length > 3) {
                this.nameClass = 'is-valid'
            }
            else {
                this.nameClass = 'is-invalid'
            }
        },
        message: function (value) {
            if (value && value.length > 50) {
                this.messageClass = 'is-valid'
            }
            else {
                this.messageClass = 'is-invalid'
            }
        },
        email: function (email) {
            let filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/
            if (filter.test(email)) {
                this.emailClass = 'is-valid'
            }
            else {
                this.emailClass = 'is-invalid'
            }
        },
        privacy: function (value) {
            if (value) {
                this.privacyClass = 'text-success'
            }
            else {
                this.privacyClass = 'text-danger'
            }
        }
    },
    computed: {
        hasBigBtnClass: function () {
            if (this.hasBigBtn) {
                return 'btn-block'
            }
            return null
        },
    },
    methods: {
        setPrivacy: function (value) {
            this.privacy = value
        },
        checkFields: function () {
            let check = true

            if (!this.name || this.name.length < 4) {
                check = false
                this.nameClass = 'is-invalid'
            }

            if (!this.message || this.message.length < 50) {
                check = false
                this.messageClass = 'is-invalid'
            }

            if (!this.privacy || this.privacy == false) {
                check = false
                this.privacyClass = 'text-danger'
            }

            if (this.checkEmail(this.email) == false) {
                check = false
                this.emailClass = 'is-invalid'
            }

            return check
        },
        checkEmail: function () {
            let filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (filter.test(this.email)) {
                return true;
            }
            return false;
        },
        sendMail: function () {
            let check = this.checkFields()

            if (check) {
                this.isActive = false
                this.isLoading = true

                let data = new FormData()
                data.append('name', this.name)
                data.append('email', this.email)
                data.append('message', this.message)
                data.append('privacy', this.privacy)

                this.$http.post('/api/mail/send', data).then(response => {
                    if (response.data.success) {
                        this.showSuccess()
                        this.isLoading = false
                    }
                    else {
                        this.showError()
                        this.isActive = true
                        this.isLoading = false
                    }
                })
            }
        },
        showSuccess: function () {
            let success = this.$refs.successMsg
            this.anim(success)
        },
        showError: function () {
            let error = this.$refs.errorMsg
            this.anim(error)
        },
        anim: function (el) {
            let master = new TimelineMax({
                paused: true,
                yoyo: true,
            })

            master.addLabel('start', '+=0')

            master.fromTo(el, .3, {
                display: 'none',
                autoAlpha: 0
            }, {
                display: 'block',
                autoAlpha: 1,
            }, 'start')

            master.to(el, .3, {
                display: 'none',
                autoAlpha: 0,
            }, 'start+=3.3')

            master.progress(1).progress(0)
            master.play()
        }
    },
    mounted: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-contact-form {
    padding-top: $spacer * $line-height-base;
}

.form-messages {
    display: none;
    opacity: 0;
    visibility: hidden;
}
</style>
