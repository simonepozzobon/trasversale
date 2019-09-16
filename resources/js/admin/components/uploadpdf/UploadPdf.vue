<template>
<div class="upload-pdf">
    <file-input
        ref="file"
        name="file"
        accept="application/pdf"
        placeholder="Seleziona un pdf"
        :has-crop="false"
        @update="previewFile"
        @is-loading-preview="showLoader"
    />

    <div class="form-group row">
        <label class="col-md-3">Concludi l'upload</label>
        <div class="col-md-9">
            <button
                class="btn mb-3"
                align="center"
                :disabled="isDisable"
                :class="colorClass"
                @click="upload"
            >
                Carica
                <div
                    v-if="hasLoader"
                    class="spinner-border spinner-border-sm"
                    :class="loaderColor"
                    role="status"
                >
                    <span class="sr-only">Loading...</span>
                </div>
            </button>
        </div>
    </div>
</div>
</template>

<script>
import FileInput from '../fileinput/FileInput.vue'
export default {
    name: 'UploadPdf',
    components: {
        FileInput,
    },
    data: function () {
        return {
            src: null,
            file: null,
            isDisable: true,
            hasLoader: false,
        }
    },
    computed: {
        colorClass: function () {
            if (this.isDisable) {
                return 'btn-outline-gray'
            }
            else {
                return 'btn-outline-primary'
            }
        },
        loaderColor: function () {
            if (this.isDisable) {
                return 'text-gray'
            }
            else {
                return 'text-primary'
            }
        },
    },
    methods: {
        showLoader: function () {
            this.hasLoader = true
        },
        previewFile: function (file, src) {
            this.src = src
            this.file = file

            this.$nextTick(() => {
                this.hasLoader = false
                this.isDisable = false
            })
        },
        upload: function () {
            this.hasLoader = true

            let data = new FormData()
            data.append('filename', this.file.name)
            data.append('pdf', this.file)


            this.$http.post('/api/admin/utilities/upload-pdf', data).then(response => {
                // console.log(response.data);
                if (response.data.success) {
                    this.$emit('update', response.data.pdf)
                    this.hasLoader = false
                    this.isDisable = true
                }
            })

        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
