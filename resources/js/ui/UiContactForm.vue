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
            Test
        </ui-checkbox>
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
