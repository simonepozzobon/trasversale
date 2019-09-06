<template>
<div class="ui-contact-form">
    <div class="form-group row">
        <label
            for="name"
            class="col-md-2"
        >
            Nome
        </label>
        <div class="col-md-10">
            <input
                type="text"
                class="form-control"
                name="name"
                v-model="name"
                placeholder="Il tuo nome ..."
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="email"
            class="col-md-2"
        >
            Email
        </label>
        <div class="col-md-10">
            <input
                type="email"
                class="form-control"
                name="email"
                v-model="email"
                placeholder="La mail per contattarti ..."
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="message"
            class="col-md-2"
        >
            Messaggio
        </label>
        <div class="col-md-10">
            <textarea
                name="message"
                class="form-control"
                placeholder="Il tuo messaggio ..."
            ></textarea>
        </div>
    </div>
    <div class="form-group row">
        <label
            for="privacy"
            class="col-md-2"
        >
            Privacy
        </label>
        <div class="col-md-10">
            <ui-checkbox
                @click="setPrivacy"
                :value="privacy"
            />
        </div>
    </div>
    <div class="row">
        <div class="col">
            <button class="btn btn-outline-primary">
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
    data: function () {
        return {
            name: null,
            email: null,
            message: null,
            privacy: false,
        }
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
