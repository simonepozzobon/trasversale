<template>
<div class="file-input">
    <div class="form-group row">
        <label class="col-md-3">{{ label }}</label>
        <div class="col-md-9">
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input
                        ref="file"
                        type="file"
                        class="custom-file-input"
                        :id="name"
                        :accept="accept"
                        @change="previewFile"
                    />

                    <label
                        class="custom-file-label"
                        :for="name"
                        aria-describedby="inputGroupFileAddon02"
                    >
                        <span>{{ filename }}</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div
        class="crop"
        v-if="this.hasCrop && this.accept === 'image/*' && this.showCrop"
    >
        <div class="form-group row mt-5">
            <div class="col-12">
                <h5>Ritaglio dell'immagine</h5>
                <hr />
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <clipper-fixed
                    ref="cropper"
                    :ratio="ratio"
                    :preview="name"
                    :src="src"
                />
            </div>
            <div class="col-md-6">
                <clipper-preview :name="name" />
            </div>
        </div>
        <div class="form-group row">
            <label class="col-md-3">Taglia Immagine</label>
            <div class="col-md-9">
                <button
                    class="btn btn-outline-primary"
                    @click="crop"
                >
                    Ritaglia
                </button>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import {
    clipperFixed,
    clipperPreview
}
from 'vuejs-clipper'
export default {
    name: 'FileInput',
    components: {
        clipperFixed,
    },
    props: {
        label: {
            type: String,
            default: 'File',
        },
        name: {
            type: String,
            default: 'fileinput',
        },
        accept: {
            type: String,
            default: null,
        },
        hasCrop: {
            type: Boolean,
            default: true
        },
        ratio: {
            type: Number,
            default: 16 / 9
        },
        placeholder: {
            type: String,
            default: 'Seleziona un file',
        },
    },
    data: function () {
        return {
            file: null,
            src: null,
            showCrop: false,
            filename: null,
        }
    },
    watch: {
        src: function (src) {
            this.toggleCrop()
        }
    },
    methods: {
        toggleCrop: function () {
            if (this.src) {
                this.showCrop = true
            }
            else {
                this.showCrop = false
            }
        },
        previewFile: function () {
            this.file = this.$refs.file.files[0]

            if (this.file) {
                this.$emit('is-loading-preview')
                this.filename = this.file.name
                let reader = new FileReader()
                // console.log('preview');
                reader.addEventListener('load', () => {
                    if (this.hasCrop) {
                        this.src = reader.result
                    }
                    else {
                        let src = reader.result
                        let file = this.file

                        this.$emit('update', file, src)
                    }

                })
                reader.readAsDataURL(this.file)
            }
            else {
                this.filename = this.placeholder
            }
        },
        crop: function () {
            // https://developer.mozilla.org/it/docs/Web/API/HTMLCanvasElement/toBlob
            let canvas = this.$refs.cropper.clip()
            canvas.toBlob(blob => {
                // blob.lastModifiedDate = new Date()
                let file = new File([blob], this.file.name)
                console.log(file);

                this.getSrc(file).then(src => {
                    this.$emit('update', file, src)
                })
            })
        },
        getSrc: function (file) {
            return new Promise(resolve => {
                let reader = new FileReader()
                // console.log('preview');
                let src = reader.addEventListener('load', event => {
                    resolve(reader.result)
                })
                reader.readAsDataURL(file)
            })


        },
        reset: function () {
            this.$refs.file.value = ''
            this.file = null
            this.src = null
            if (this.hasCrop) {
                this.toggleCrop()
            }
        }
    },
    created: function () {
        this.filename = this.placeholder
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
