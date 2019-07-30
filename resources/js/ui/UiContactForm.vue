<template>
<div class="ui-contact-form">
    <div class="form-group">
        <label for="name">
            Nome
        </label>
        <input
            type="text"
            class="form-control"
            name="name"
            v-model="name"
            placeholder="Il tuo nome ..."
        >
    </div>
    <div class="form-group">
        <label for="email">
            Email
        </label>
        <input
            type="email"
            class="form-control"
            name="email"
            v-model="email"
            placeholder="La mail per contattarti ..."
        >
    </div>
    <div class="form-group">
        <label for="message">
            Messaggio
        </label>
        <textarea
            name="message"
            class="form-control"
            placeholder="Il tuo messaggio ..."
        ></textarea>
    </div>
    <div class="form-group">
        <label for="privacy">
            Privacy
        </label>
        <ui-checkbox
            @click="setPrivacy"
            :value="privacy"
        >
            Inviando il form autorizzate il trattamento dei dati personali trasmessi ai sensi della legge 675/96. Si autorizza anche al trattamento dei dati per ricevere informazioni o promozioni di iniziative future.
        </ui-checkbox>
    </div>
    <div class="row">
        <div class="col">
            <button
                class="btn btn-outline-primary"
                :class="hasBigBtnClass"
            >
                Invia
            </button>
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
            email: null,
            message: null,
            privacy: false,
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
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-contact-form {
    padding-top: $spacer * $line-height-base;
}
</style>
