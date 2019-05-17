<template lang="html">
    <div>
        <div class="form-group row" v-if="option.type == 'text'">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <input type="text" :name="option.key" class="form-control" v-model="value">
                <small class="text-muted" v-if="option.hasOwnProperty('info')">{{ option.info }}</small>
            </div>
        </div>
        <div class="form-group row" v-else-if="option.type == 'switch'">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="form-group col-md-9">
                <span class="switch">
                    <input type="checkbox" class="switch" :id="option.key" v-model="value">
                    <label :for="option.key"></label>
                </span>
            </div>
        </div>
        <div class="form-group form__field row" v-else-if="option.type == 'color-picker'">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="form__input col-md-9">
                <swatches v-model="value" :colors="colors" inline></swatches>
            </div>
        </div>
        <div class="form-group row" v-else-if="option.type == 'file-input'">
            <label class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input
                            ref="file"
                            type="file"
                            class="custom-file-input"
                            :id="option.key"
                            :accept="option.accept"
                            @change="previewFile"/>

                        <label
                            class="custom-file-label"
                            :for="option.key"
                            aria-describedby="inputGroupFileAddon02">
                            Seleziona File
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row" v-else-if="option.type == 'preview' && this.src">
            <label for="preview" class="col-md-3">Anteprima</label>
            <div class="col-md-9">
                <img :src="src" alt="" class="preview-image">
            </div>
        </div>


        <div v-else>
            {{ option }}
        </div>
    </div>
</template>

<script>
import Swatches from 'vue-swatches'
import "vue-swatches/dist/vue-swatches.min.css"


export default {
    name: 'DynamicModuleItem',
    components: {
        Swatches,
    },
    props: {
        option: {
            type: Object,
            default: function() {},
        },
    },
    data: function() {
        return {
            value: null,
            colors: [],
            src: null,
        }
    },
    watch: {
        value: function(value) {
            this.$emit('changed', this.option.key, value, this.option.type)
        }
    },
    methods: {
        getColors: function() {
            let themeColors = [ 'blue', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'green', 'teal', 'cyan', 'white', 'gray', 'gray-dark', 'black', 'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark' ]
            let colors = []

            for (let i = 0; i < themeColors.length; i++) {
                let color = this.getColor('--'+themeColors[i])
                let idx = colors.findIndex(item => item == color)
                if (idx < 0) {
                    colors.push(color)
                }
            }

            this.colors = colors
        },
        getColor: function(key) {
            return getComputedStyle(document.documentElement).getPropertyValue(key).trim()
        },
        previewFile: function () {
            this.value = this.$refs.file.files[0]
        },
    },
    created: function() {
        this.getColors()
    }
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
