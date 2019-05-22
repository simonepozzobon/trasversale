<template lang="html">
    <div class="module-item">
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
                <swatches
                    v-model="value"
                    :colors="colors"
                    background-color="transparent"
                    :wrapper-style="swatchesWrapperStyle"
                    inline />
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
                <img :src="src" alt="" class="preview-image" v-if="option.mime == 'image'">
                <div class="embed-responsive embed-responsive-16by9" v-else>
                    <iframe
                        class="embed-responsive-item"
                        :src="src"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
        <div class="form-group row" v-else-if="option.type == 'wysiwyg'">
            <label class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <text-editor
                    @update="updateEditor"/>
            </div>
        </div>

        <div class="form-group row" v-else-if="option.type == 'select'">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <select class="form-control" :name="option.key" v-model="value">
                    <option
                        v-for="(children, i) in option.options"
                        :value="children.value">
                        {{ children.label }}
                    </option>
                </select>
                <small class="text-muted" v-if="option.hasOwnProperty('info')">{{ option.info }}</small>
            </div>
        </div>

        <dynamic-module
            v-else-if="option.type == 'multiple'"
            :options="option.childrens"/>

        <div class="form-group row" v-else-if="option.type == 'post-select'">
            <label :for="option.key" class="col-md-3">Anteprima</label>
            <div class="col-md-9">
                <grid-layout
                    ref="gridLayout"
                    :layout="elements"
                    :col-num="12"
                    :row-height="30"
                    :is-draggable="true"
                    :is-resizable="true"
                    :auto-size="true"
                    :is-mirrored="false"
                    :vertical-compact="true"
                    :margin="[10, 10]"
                    :use-css-transforms="true">

                    <grid-item
                        v-for="(element, i) in elements"
                        :key="i"
                        class="element-item"
                        :x="element.x"
                        :y="element.y"
                        :w="element.w"
                        :h="element.h"
                        :i="element.i">

                        <div
                            class="element-item__container"
                            :style="'background-image: url('+ element.thumb +')'">

                            <div class="element-item__tools">
                                <button
                                    class="btn btn-outline-danger"
                                    @click="removeElement(element)">
                                    Rimuovi
                                </button>
                            </div>
                        </div>
                    </grid-item>
                </grid-layout>
            </div>
        </div>

        <div v-else>
            {{ option }}
        </div>

        <div class="form-group row" v-if="option.type == 'post-select'">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <b-table
                    ref="table"
                    striped
                    hover
                    :items="items"
                    :fields="fields">
                    <template slot="thumb" slot-scope="data">
                        <img
                            v-if="data.item.thumb"
                            :src="data.item.thumb"
                            :alt="data.item.title"
                            class="module-item__image-post" />
                    </template>
                    <template slot="selected" slot-scope="data">
                        <ui-checkbox
                            :value="Boolean(data.item.selected)"
                            @click="selectPost(data.item)"/>
                    </template>
                </b-table>
            </div>

        </div>
    </div>
</template>

<script>
import Swatches from 'vue-swatches'
import TextEditor from './TextEditor.vue'
import VueGridLayout from 'vue-grid-layout'
import { UiCheckbox } from '../../ui'

import PostFields from './post-selector/PostFields'

import "vue-swatches/dist/vue-swatches.min.css"

export default {
    name: 'DynamicModuleItem',
    components: {
        Swatches,
        TextEditor,
        GridLayout: VueGridLayout.GridLayout,
        GridItem: VueGridLayout.GridItem,
        UiCheckbox,
    },
    props: {
        option: {
            type: Object,
            default: function() { return {} },
        },
        dataObj: {
            type: Object,
            default: function() { return {} },
        },
        edit: {
            type: Boolean,
            default: false,
        },
    },
    data: function() {
        return {
            value: null,
            colors: [],
            elements: [], // elements on the grid
            items: [], // items from post query
            fields: PostFields,
            src: null,
            swatchesWrapperStyle: {
                backgroundColor: 'rgba(255, 255, 255, 0.5)',
                borderRadius: '10px',
                overflow: 'hidden',
            },
            debugEditor: null,
        }
    },
    watch: {
        value: function(value) {
            this.$emit('changed', this.option.key, value, this.option.type)
        },
        elements: function(els) {
            this.value = els
        }
    },
    computed: {
        hasRelated: function() {
            if (this.option.hasOwnProperty('relatedKey')) {
                return true
            }
            return false
        },
        related: function() {
            if (this.option.hasOwnProperty('relatedKey')) {
                let options = this.$parent.options
                let idx = options.findIndex(option => option.key ==  this.option.relatedKey)

                if (idx > -1) {
                    return this.$parent.$refs.module[idx]
                }
            }
            return null
        },
    },
    methods: {
        setDefault: function() {
            if (this.option.hasOwnProperty('default') && !this.edit) {
                this.value = this.option.default
            }
        },
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
        updateEditor: function(json, html) {
            let paragraph = json.content
            this.value = html
            // this.debugEditor = paragraph
            // console.log(html);
        },
        getElements: function(value) {
            // console.log(value);

            let url = '/api/admin/grid-elements/' + value
            this.$http.get(url).then(response => {
                // console.log(response.data);
                if (response.data.success) {
                    this.items = response.data.elements
                    // this.debug()
                }
            })
        },
        selectPost: function(item) {
            let idx = this.items.indexOf(item)
            if (idx > -1) {
                this.items[idx].selected = !item.selected
                // console.log(this.items[idx].selected);

                // aggiunge elemento dalla tabella
                if (this.items[idx].selected) {
                    let i = this.elements.length
                    let element = this.formatElementForGrid(this.items[idx], i)
                    this.elements.push(element)
                }

                // rimuove elemento dalla tabella
                else {
                    let i = this.elements.findIndex(element => element.id == item.id && element.type == item.type)
                    if (i > -1) {
                        this.elements.splice(i, 1)
                    }
                }
            }
        },
        removeElement: function(item) {
            // rimuove l'elemento dalla griglia e lo deseleziona dalla tabella
            let idx = this.items.findIndex(row => row.id == item.id && row.type == item.type)
            if (idx > -1) {
                // console.log(this.items[idx]);
                this.items[idx].selected = 0
            }
            this.elements.splice(this.elements.indexOf(item), 1);
        },
        formatElementForGrid: function(item, i = 0) {
            let colN = 12
            let w = 2
            let h = 2
            let x = (i * w) % colN
            let y = Math.floor((i * w) / colN)

            return {
                i: i,
                x: x,
                y: y,
                w: w,
                h: h,
                ...item,
            }
        },
        updateGrid: function() {
            // da completare
        },
        debug: function() {
            let test = [
                this.items[2],
                this.items[1],
                this.items[2],
                this.items[1],
                this.items[2],
                this.items[1],
                this.items[2],
                this.items[1],
            ]

            for (let i = 0; i < test.length; i++) {
                this.elements.push(this.formatElementForGrid(test[i], i))
            }
        }
    },
    beforeCreate: function() {
        this.$options.components.DynamicModule = require('./DynamicModule.vue').default
    },
    created: function() {
        this.getColors()
        this.setDefault()

        if (this.hasRelated) {
            // set watcher for related key
            let watcher = 'dataObj.' + this.option.relatedKey
            this.$watch(watcher, (value) => {
                this.getElements(value)
            })

            // set initial value if related has one
            let related = this.dataObj[this.option.relatedKey]
            if (related) {
                this.getElements(related)
            }
        }
    },
}
</script>

<style lang="scss">
@import '~styles/shared';

.preview-image {
    max-width: 100%;
    max-height: 100%;
    width: 100%;
    height: auto;
}


.module-item {
    &__image-post {
        width: $spacer * 3;
        height: auto;
    }
}

.element-item {
    width: 100%;
    height: 100%;

    &__container {
        width: 100%;
        height: 100%;
        background-position: center;
        background-size: cover;

        display: flex;
        justify-content: center;
        align-items: center;
    }

}
</style>
