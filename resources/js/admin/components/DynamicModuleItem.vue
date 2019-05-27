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
                    ref="textEditor"
                    :initial="this.value"
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

        <div class="form-group row" v-else-if="option.type == 'counter' && visible">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <div class="row">
                    <div class="input-group col-md-3">
                        <div class="input-group-prepend">
                            <button class="btn btn-outline-primary" @click="value++">+</button>
                        </div>
                        <input type="text" :name="option.key" class="form-control" v-model="value">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" @click="value--">-</button>
                        </div>
                    </div>
                </div>
                <small class="text-muted" v-if="option.hasOwnProperty('info')">{{ option.info }}</small>
            </div>
        </div>

        <dynamic-module
            v-else-if="option.type == 'multiple'"
            :options="option.childrens"
            :is-edit="edit"
            :values="values"
            :debug="true"
            @changed="changed"/>

        <div class="form-group row" v-else-if="option.type == 'post-select'">
            <label :for="option.key" class="col-md-3">Anteprima</label>
            <div class="col-md-9">
                <grid-layout
                    ref="gridLayout"
                    :layout="elements"
                    :col-num="12"
                    :row-height="60"
                    :is-draggable="true"
                    :is-resizable="true"
                    :auto-size="true"
                    :is-mirrored="false"
                    :vertical-compact="true"
                    :margin="[10, 10]"
                    :use-css-transforms="true">

                    <grid-item
                        ref="gridItem"
                        v-for="(element, i) in elements"
                        :key="i"
                        class="element-item"
                        :x="element.x"
                        :y="element.y"
                        :w="element.w"
                        :h="element.h"
                        :i="element.i"
                        @moved="gridItemMoved"
                        @resized="gridItemResized">

                        <div
                            class="element-item__container"
                            :style="'background-image: url('+ element.thumb +')'">

                            <div class="element-item__tools" v-if="!disableTable">
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

        <!-- <div v-else>
            {{ option }}
        </div> -->

        <div class="form-group row" v-if="option.type == 'post-select' && !disableTable">
            <label :for="option.key" class="col-md-3">{{ option.label }}</label>
            <div class="col-md-9">
                <b-table
                    ref="table"
                    striped
                    hover
                    :items="blocks"
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
        initial: [Object, String, Number, Array, Boolean],
    },
    data: function() {
        return {
            value: null,
            values: null,
            colors: [],
            elements: [], // elements on the grid
            blocks: [], // blocks from post query
            fields: PostFields,
            visible: true,
            disableTable: false,
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
        relatedType: function() {
            if (this.hasRelated) {
                return typeof this.option.relatedKey
            }
            return null
        },
        related: function() {
            if (this.hasRelated) {
                let options = this.$parent.options
                let idx = options.findIndex(option => option.key ==  this.option.relatedKey)

                if (idx > -1) {
                    return this.$parent.$refs.module[idx]
                }
            }
            return null
        },
        relatedValue: function() {
            if (this.hasRelated) {
                if (this.related) {
                    return this.related.value
                }
            }
            return false
        }
    },
    methods: {
        changed: function(subModuleObj) {
            this.value = subModuleObj
        },
        setDefault: function() {
            if (this.option.hasOwnProperty('default') && !this.edit) {
                this.value = this.option.default
            }

            this.setWatchers()
        },
        setWatchers: function() {
            if (this.hasRelated) {
                switch (this.relatedType) {
                    case 'string':
                        this.setWatcher(this.option.relatedKey)
                        break;
                    case 'object':
                        for (let i = 0; i < this.option.relatedKey.length; i++) {
                            this.setWatcher(this.option.relatedKey[i])
                        }
                        break;
                }
            }
        },
        setWatcher: function(relatedKey) {
            if (this.option.type == 'counter') {
            }
            // set watcher for related key
            let watcher = 'dataObj.' + relatedKey

            this.$watch(watcher, (value) => {
                if (this.option.type == 'post-select') {
                    this.getElements(value, relatedKey)
                }

                if (this.option.type == 'counter' && this.relatedValue) {
                    if (this.relatedValue == 'last-mix') {
                        this.visible = true
                    } else {
                        this.visible = false
                    }
                }
            })

            this.setRelated(relatedKey)
        },
        setRelated: function(relatedKey) {
            // set initial value if related has one
            let related = this.dataObj[relatedKey]
            if (related) {
                if (this.option.type == 'post-select') {
                    this.getElements(related, relatedKey)
                }
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
        getElements: function(value, relatedKey) {
            let url = '/api/admin/grid-elements/' + value
            this.$http.get(url).then(response => {
                // console.log(response.data.elements);
                if (response.data.success) {
                    this.blocks = response.data.elements
                    // this.debug()
                    if (relatedKey == 'post_count') {
                        let options = this.$parent.options
                        let idx = options.findIndex(option => option.key == relatedKey)
                        let parent = this.$parent.$refs.module[idx]
                        let parentValue = parent.value
                        // this.elements = []

                        this.$nextTick(() => {
                            for (let i = 0; i < parentValue; i++) {
                                this.selectPost(this.blocks[i])
                            }
                        })
                    }

                    if (relatedKey == 'model' && value == 'last-mix') {
                        this.disableTable = true
                    } else {
                        this.disableTable = false
                    }
                }
            })
        },
        selectPost: function(item) {
            let idx = this.blocks.indexOf(item)
            if (idx > -1) {
                this.blocks[idx].selected = !item.selected
                // console.log(this.blocks[idx].selected);

                // aggiunge elemento dalla tabella
                if (this.blocks[idx].selected) {
                    let i = this.elements.length
                    let element = this.formatElementForGrid(this.blocks[idx], i)
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
            let idx = this.blocks.findIndex(row => row.id == item.id && row.type == item.type)
            if (idx > -1) {
                // console.log(this.blocks[idx]);
                this.blocks[idx].selected = 0
            }
            this.elements.splice(this.elements.indexOf(item), 1);
        },
        formatElementForGrid: function(item, i = 0) {
            let colN = 12
            let w = item.hasOwnProperty('width') ? item.width : 2
            let h = item.hasOwnProperty('height') ? item.height : 2
            let x = item.hasOwnProperty('x')? item.x : (i * w) % colN
            let y = item.hasOwnProperty('y')? item.y : Math.floor((i * w) / colN)

            return {
                i: i,
                x: x,
                y: y,
                w: w,
                h: h,
                order: i,
                ...item,
            }
        },
        gridItemMoved: function(i, newX, newY){
            let item = this.elements[i] // element moved
            for (let i = 0; i < this.elements.length; i++) {
                this.elements[i]
            }
        },
        gridItemResized: function(i, newH, newW, newHPx, newWPx) {
            this.elements[i] = {
                ...this.elements[i],
                w: newW,
                h: newH
            }
        },
        updateGrid: function() {
            // da completare
        },
        debug: function() {
            let test = [
                this.blocks[2],
                this.blocks[1],
                this.blocks[2],
                this.blocks[1],
                this.blocks[2],
                this.blocks[1],
                this.blocks[2],
                this.blocks[1],
            ]

            // for (let i = 0; i < test.length; i++) {
            //     this.elements.push(this.formatElementForGrid(test[i], i))
            // }
        },
        setInitial: function() {
            if (this.initial) {
                this.value = this.initial
            }

            // se ha sottomoduli
            if (this.option.hasOwnProperty('childrens') && this.option.childrens.length > 0) {
                this.values = this.initial
            }

            // se è post-select
            if (this.option.type == 'post-select') {
                for (var i = 0; i < this.initial.length; i++) {
                    this.elements.push(this.formatElementForGrid(this.initial[i], i))
                }
                // console.log('griglia', this.initial[0]);
            }
        },
    },
    beforeCreate: function() {
        this.$options.components.DynamicModule = require('./DynamicModule.vue').default
    },
    created: function() {
        this.getColors()
        this.setDefault()
        this.setInitial()


    },
    mounted: function() {
    }
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
