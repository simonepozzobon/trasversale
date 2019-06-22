<template>
<div class="team-form">
    <div
        class="form-group row"
        v-if="avatarPreview"
    >
        <label
            for="preview"
            class="col-md-3"
        >
            Avatar
        </label>
        <div class="col-md-9">
            <img
                :src="avatarPreview"
                class="team-form__avatar"
            >
        </div>
    </div>
    <file-input
        ref="file"
        name="img"
        accept="image/*"
        label="Immagine"
        :has-crop="false"
        @update="previewFile"
    />
    <div class="form-group row">
        <label
            for="name"
            class="col-md-3"
        >
            Nome
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="name"
                class="form-control"
                v-model="obj.name"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="role"
            class="col-md-3"
        >
            Ruolo
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="role"
                class="form-control"
                v-model="obj.role"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="description"
            class="col-md-3"
        >
            Descrizione
        </label>
        <div class="col-md-9">
            <textarea
                name="description"
                class="form-control"
                v-model="obj.description"
            ></textarea>
        </div>
    </div>

    <div class="form-group row">
        <label
            for="twitter"
            class="col-md-3"
        >
            Twitter Account
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="twitter"
                class="form-control"
                v-model="social.twitter"
            >
        </div>
    </div>

    <div class="form-group row">
        <label
            for="twitter"
            class="col-md-3"
        >
            Facebook Account
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="twitter"
                class="form-control"
                v-model="social.facebook"
            >
        </div>
    </div>

    <div class="form-group row">
        <label
            for="twitter"
            class="col-md-3"
        >
            Linkedin Account
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="twitter"
                class="form-control"
                v-model="social.linkedin"
            >
        </div>
    </div>


    <div class="form-group row">
        <label
            for="lng"
            class="col-md-3"
        >
            Aggiungi
        </label>
        <div class="col-md-9">
            <button
                class="btn btn-outline-primary"
                @click="addMember"
            >
                Aggiungi
            </button>
        </div>
    </div>
</div>
</template>

<script>
import {
    Uuid
}
from '../../../Utilities'
import FileInput from '../fileinput/FileInput.vue'

export default {
    name: 'TeamForm',
    components: {
        FileInput,
    },
    data: function () {
        return {
            obj: {
                name: null,
                role: null,
                description: null,
                img: null,
                social: [],
                file: null,
            },
            social: {
                facebook: null,
                linkedin: null,
                twitter: null,
            },
            avatarPreview: null,
        }
    },
    methods: {
        previewFile: function (file, src) {
            this.avatarPreview = src
            this.obj['img'] = src
            this.obj['file'] = file
            // console.log(file, src);
        },
        formatSocial: function () {
            let socialArr = []

            for (var key in this.social) {
                if (this.social.hasOwnProperty(key) && this.social[key]) {
                    let newSocial = Object.create({})
                    newSocial[key] = this.social[key]
                    socialArr.push(newSocial)
                }
            }
            return socialArr
        },
        addMember: function () {
            this.obj['social'] = this.formatSocial()

            // assegno un uuid
            if (!this.obj.hasOwnProperty('id')) {
                this.obj['id'] = Uuid.get()
            }

            this.$emit('update-member', this.obj)

            this.$nextTick(() => {
                delete this.obj['id']

                this.$refs.file.reset()

                this.avatarPreview = null
                this.obj = Object.assign({}, {
                    name: null,
                    role: null,
                    description: null,
                    img: null,
                    social: [],
                    file: null,
                })
            })
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.team-form {
    &__avatar {
        max-width: 100%;
        width: 127px;
        height: auto;
    }
}
</style>
