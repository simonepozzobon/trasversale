<template lang="html">
    <div class="form">
        <div class="form-group row">
            <label for="title" class="col-md-3">Titolo Immagine</label>
            <div class="col-md-9">
                <input type="text" name="title" v-model="title" placeholder="Titolo ..." class="form-control">
                <small class="text-muted">Non sarà visibile ma utile per il SEO</small>
            </div>
        </div>
        <div class="form-group row">
            <label for="title" class="col-md-3">Immagine</label>
            <div class="col-md-9">
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input
                            ref="image"
                            type="file"
                            class="custom-file-input"
                            id="admin-image"
                            accept="image/*"
                            @change="previewImage"/>

                        <label
                            class="custom-file-label"
                            for="admin-image"
                            aria-describedby="inputGroupFileAddon02">
                            Seleziona File
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row" v-if="temp">
            <label for="preview" class="col-md-3">Anteprima</label>
            <div class="col-md-9">
                <img :src="temp" alt="" class="preview-image">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MImage',
    data: function() {
        return {
            src: null,
            title: null,
            temp: null,
        }
    },
    watch: {
        'src': function(src) {
            this.$emit('changed', {
                src: src,
                title: this.title
            })
        },
        'title': function(title) {
            this.$emit('changed', {
                src: this.src,
                title: title,
            })
        }
    },
    methods: {
        previewImage: function() {
            this.src = this.$refs.image.files[0]

            let reader = new FileReader()
            reader.onload = e => {
                this.temp = e.target.result
            }
            reader.readAsDataURL(this.src)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.preview-image {
    max-width: 100%;
    max-height: 100%;
    width: 100%;
    height: auto;
}
</style>
