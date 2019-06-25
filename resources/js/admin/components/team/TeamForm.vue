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
        :ratio="1/1"
        @update="previewFile"
    />
    <div class="form-group row pt-4">
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
                v-if="!isEdit"
                class="btn btn-outline-primary"
                @click="addMember"
            >
                Aggiungi
            </button>
            <button
                v-if="isEdit"
                class="btn btn-outline-success"
                @click="addMember"
            >
                Salva Modifiche
            </button>
            <button
                v-if="isEdit"
                class="btn btn-outline-warning"
                @click="undoEdit"
            >
                Annulla Modifiche
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
    props: {
        obj: {
            type: Object,
            default: function () {
                return {
                    name: null,
                    role: null,
                    description: null,
                    img: null,
                    social: [],
                    file: null,
                }
            },
        },
        isEdit: {
            type: Boolean,
            default: false,
        },
    },
    data: function () {
        return {
            social: {
                facebook: null,
                linkedin: null,
                twitter: null,
            },
            avatarPreview: null,
            btnSave: 'Aggiungi',
        }
    },
    watch: {
        obj: {
            handler: function (obj) {
                if (obj.hasOwnProperty('img') && obj.img) {
                    this.avatarPreview = obj.img
                }

            },
            deep: true
        },
        isEdit: function (value) {
            if (value) {
                this.btnSave = 'Salva Modifiche'
            }
            else {
                this.btnSave = 'Aggiungi'
            }
        }
    },
    methods: {
        previewFile: function (file, src) {
            this.$emit('update:obj', {
                ...this.obj,
                img: src,
                file: file,
            })
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
        reset: function () {
            this.$nextTick(() => {
                let cacheObj = Object.assign({}, this.obj)
                this.$refs.file.reset()

                this.avatarPreview = null
                delete cacheObj['id']

                cacheObj = Object.assign({}, {
                    name: null,
                    role: null,
                    description: null,
                    img: null,
                    social: [],
                    file: null,
                })
                this.$emit('update:obj', cacheObj)
            })
        },
        addMember: function () {
            let cacheObj = Object.assign({}, this.obj)

            cacheObj['social'] = this.formatSocial()

            // assegno un uuid
            if (!cacheObj.hasOwnProperty('id')) {
                cacheObj['id'] = Uuid.get()
            }
            console.log(cacheObj);

            this.$emit('update-member', cacheObj)
            this.reset()
        },
        undoEdit: function () {
            this.$emit('undo')
            this.reset()
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
