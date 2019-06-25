<template>
<div class="module-item">
    <div
        class="form-group row"
        v-if="option.type === 'text'"
    >
        <label
            :for="option.key"
            class="col-md-3"
        >
            {{ option.label }}
        </label>
        <div class="col-md-9">
            <input
                type="text"
                :name="option.key"
                class="form-control"
                v-model="value"
            >
            <small
                class="text-muted"
                v-if="option.hasOwnProperty('info')"
            >
                {{ option.info }}
            </small>
        </div>
    </div>

    <ui-switch
        v-else-if="option.type === 'switch'"
        :label="option.label"
        @changed="subChanged"
    />

    <div
        class="form-group form__field row"
        v-else-if="option.type === 'color-picker'"
    >
        <label
            :for="option.key"
            class="col-md-3"
        >{{ option.label }}</label>
        <div class="form__input col-md-9">
            <swatches
                v-model="value"
                :colors="colors"
                background-color="transparent"
                :wrapper-style="swatchesWrapperStyle"
                inline
            />
        </div>
    </div>

    <file-input
        v-else-if="option.type === 'file-input'"
        :name="option.key"
        :accept="option.accept"
        :label="option.label"
        @update="previewFile"
    />

    <div
        class="form-group row"
        v-else-if="option.type === 'preview' && this.src"
    >
        <label
            for="preview"
            class="col-md-3"
        >
            Anteprima
        </label>
        <div class="col-md-9">
            <img
                :src="src"
                alt=""
                class="preview-image"
                v-if="option.mime == 'image'"
            >
            <div
                class="embed-responsive embed-responsive-16by9"
                v-else
            >
                <iframe
                    class="embed-responsive-item"
                    :src="src"
                    allowfullscreen
                >
                </iframe>
            </div>
        </div>
    </div>

    <div
        class="form-group row"
        v-else-if="option.type === 'wysiwyg'"
    >
        <label class="col-md-3">{{ option.label }}</label>
        <div class="col-md-9">
            <text-editor
                ref="textEditor"
                :initial="this.value"
                @update="updateEditor"
            />
        </div>
    </div>

    <div
        class="form-group row"
        v-else-if="option.type === 'select'"
    >
        <label
            :for="option.key"
            class="col-md-3"
        >
            {{ option.label }}
        </label>
        <div class="col-md-9">
            <select
                class="form-control"
                :name="option.key"
                v-model="value"
            >
                <option
                    v-for="(children, i) in option.options"
                    :value="children.value"
                >
                    {{ children.label }}
                </option>
            </select>
            <small
                class="text-muted"
                v-if="option.hasOwnProperty('info')"
            >
                {{ option.info }}
            </small>
        </div>
    </div>

    <div
        class="form-group row"
        v-else-if="option.type === 'counter' && visible"
    >
        <label
            :for="option.key"
            class="col-md-3"
        >
            {{ option.label }}
        </label>
        <div class="col-md-9">
            <div class="row">
                <div class="input-group col-md-3">
                    <div class="input-group-prepend">
                        <button
                            class="btn btn-outline-primary"
                            @click="addCounter"
                        >
                            +
                        </button>
                    </div>
                    <input
                        type="text"
                        :name="option.key"
                        class="form-control"
                        v-model="value"
                    />

                    <div class="input-group-append">
                        <button
                            class="btn btn-outline-primary"
                            @click="removeCounter"
                        >
                            -
                        </button>
                    </div>
                </div>
            </div>
            <small
                class="text-muted"
                v-if="option.hasOwnProperty('info')"
            >
                {{ option.info }}
            </small>
        </div>
    </div>

    <dynamic-module
        v-else-if="option.type === 'multiple'"
        :options="option.childrens"
        :is-edit="edit"
        :values="values"
        :debug="true"
        @changed="subChanged"
    />


    <columns-preview
        v-else-if="option.type === 'row-preview'"
        :cols-number="relatedValue"
    />

    <dummy-module
        v-else-if="option.type === 'info'"
        :content="option.label"
    />

    <dynamic-select
        v-else-if="option.type === 'dynamic-select'"
        :request="option.request"
        :name="option.key"
        :label="option.label"
        :info="option.info"
        :initial="initial"
        @changed="subChanged"
    />

    <map-module
        v-else-if="option.type === 'map'"
        :name="option.key"
        :label="option.label"
        :info="option.info"
        :initial="initial"
        @update="subChanged"
    />

    <team-module
        v-else-if="option.type === 'team'"
        :name="option.key"
        :label="option.label"
        :info="option.info"
        :initial="initial"
        @update="teamChanged"
    />

    <grid-module
        v-else-if="option.type === 'grid'"
        @update="subChanged"
        :initial="initial"
    />

    <!-- <div v-else>
        {{ option }}
    </div> -->

    <div
        class="form-group row"
        v-if="option.type === 'post-select' && !disableTable"
    >
        <label
            :for="option.key"
            class="col-md-3"
        >{{ option.label }}</label>
        <div class="col-md-9">
            <b-table
                ref="table"
                striped
                hover
                :items="blocks"
                :fields="fields"
            >
                <template
                    slot="thumb"
                    slot-scope="data"
                >
                    <img
                        v-if="data.item.thumb"
                        :src="data.item.thumb"
                        :alt="data.item.title"
                        class="module-item__image-post"
                    />
                </template>
                <template
                    slot="selected"
                    slot-scope="data"
                >
                    <ui-checkbox
                        :value="Boolean(data.item.selected)"
                        @click="selectPost(data.item)"
                    />
                </template>
            </b-table>
        </div>

    </div>

</div>
</template>

<script>
import {
    clone,
    isEqual
}
from '../../Utilities'
import ColumnsPreview from './rowcolumn/ColumnsPreview.vue'
import DummyModule from './DummyModule.vue'
import DynamicSelect from './dynamicselect/DynamicSelect.vue'
import FileInput from './fileinput/FileInput.vue'
import GridModule from './grid/GridModule.vue'
import MapModule from './map/MapModule.vue'
import PostFields from './grid/PostFields'
import Swatches from 'vue-swatches'
import TeamModule from './team/TeamModule.vue'
import TextEditor from './TextEditor.vue'
import {
    UiCheckbox,
    UiSwitch,
}
from '../../ui'
import "vue-swatches/dist/vue-swatches.min.css"

export default {
    name: 'DynamicModuleItem',
    components: {
        ColumnsPreview,
        DummyModule,
        DynamicSelect,
        FileInput,
        GridModule,
        MapModule,
        Swatches,
        TeamModule,
        TextEditor,
        UiCheckbox,
        UiSwitch,
    },
    props: {
        option: {
            type: Object,
            default: function () {
                return {}
            },
        },
        dataObj: {
            type: Object,
            default: function () {
                return {}
            },
        },
        edit: {
            type: Boolean,
            default: false,
        },
        initial: [Object, String, Number, Array, Boolean],
    },
    data: function () {
        return {
            increments: 0,
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
        value: function (newValue, oldValue) {
            // console.log('è un valore diference', this.option.key, !isEqual(newValue, oldValue));
            if (!isEqual(newValue, oldValue)) {
                // if (this.option.key == 'elements') {
                //     console.log('dynamic item change');
                // }
                this.$emit('changed', this.option.key, newValue, this.option.type)
            }
        },
        elements: function (newEls, oldEls) {
            this.value = clone(newEls)
        }
    },
    computed: {
        options: function () {
            if (this.option.hasOwnProperty('options')) {
                return this.option.options
            }
            return {}
        },
        hasRelated: function () {
            if (this.option.hasOwnProperty('relatedKey')) {
                return true
            }
            return false
        },
        relatedType: function () {
            if (this.hasRelated) {
                return typeof this.option.relatedKey
            }
            return null
        },
        related: function () {
            if (this.hasRelated) {
                let options = this.$parent.options
                let idx = options.findIndex(option => option.key == this.option.relatedKey)

                if (idx > -1) {
                    return this.$parent.$refs.module[idx]
                }
            }
            return null
        },
        relatedValue: function () {
            if (this.hasRelated) {
                if (this.related) {
                    return this.related.value
                }
            }
            return false
        }
    },
    methods: {
        getModuleOption: function (key) {
            if (this.options && this.options.hasOwnProperty(key)) {
                return this.options[key]
            }
            return null
        },
        addCounter: function () {
            if (this.options && this.options.hasOwnProperty('max') && this.value < this.options.max) {
                this.value++
            }
        },
        removeCounter: function () {
            if (this.options && this.options.hasOwnProperty('min') && this.value > this.options.min) {
                this.value--
            }
        },
        subChanged: function (subModuleObj) {
            console.log('fuori', subModuleObj);
            // bisogna risettare l'oggetto altrimenti non aggiorna l'evento
            if (!isEqual(this.value, subModuleObj)) {
                console.log('dentro', subModuleObj);
                this.value = clone(subModuleObj)
            }
        },
        teamChanged: function (teamModule) {
            this.value = Object.assign({}, teamModule)
        },
        // mapUpdate: function (subModule) {
        //     this.value = clone(subModule)
        // },
        setDefault: function () {
            if (this.option.hasOwnProperty('default') && !this.edit) {
                this.value = this.option.default
            }

            this.setWatchers()
        },
        setWatchers: function () {
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
        setWatcher: function (relatedKey) {
            if (this.option.type === 'counter') {}
            // set watcher for related key
            let watcher = 'dataObj.' + relatedKey

            this.$watch(watcher, (value) => {
                if (this.option.type === 'post-select') {
                    this.getElements(value, relatedKey)
                }

                if (this.option.type === 'counter' && this.relatedValue) {
                    if (this.relatedValue == 'last-mix') {
                        this.visible = true
                    }
                    else {
                        this.visible = false
                    }
                }
            })

            this.setRelated(relatedKey)
        },
        setRelated: function (relatedKey) {
            // set initial value if related has one
            let related = this.dataObj[relatedKey]
            if (related) {
                if (this.option.type === 'post-select') {
                    this.getElements(related, relatedKey)
                }
            }
        },
        getColors: function () {
            let themeColors = ['blue', 'indigo', 'purple', 'pink', 'red', 'orange', 'yellow', 'green', 'teal', 'cyan', 'white', 'gray', 'gray-dark', 'black', 'primary', 'secondary', 'success', 'info', 'warning', 'danger', 'light', 'dark']
            let colors = []

            for (let i = 0; i < themeColors.length; i++) {
                let color = this.getColor('--' + themeColors[i])
                let idx = colors.findIndex(item => item == color)
                if (idx < 0) {
                    colors.push(color)
                }
            }

            this.colors = colors
        },
        getColor: function (key) {
            return getComputedStyle(document.documentElement)
                .getPropertyValue(key)
                .trim()
        },
        previewFile: function (value) {
            this.value = value
        },
        updateEditor: function (json, html) {
            let paragraph = json.content
            this.value = html
            // this.debugEditor = paragraph
            // console.log(html);
        },
        getElements: function (value, relatedKey) {
            let url = '/api/admin/grid-elements/' + value
            console.log(url);
            this.$http.get(url)
                .then(response => {
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

                            if (this.blocks.length === 0) {
                                this.$nextTick(() => {
                                    for (let i = 0; i < parentValue; i++) {
                                        this.selectPost(this.blocks[i])
                                    }
                                })
                            }

                        }

                        if (relatedKey == 'model' && value == 'last-mix') {
                            this.disableTable = true
                        }
                        else {
                            this.disableTable = false
                        }
                    }
                })
        },
        selectPost: function (item) {
            let idx = this.blocks.indexOf(item)
            if (idx > -1) {
                this.blocks[idx].selected = !item.selected
                // console.log(this.blocks[idx].selected);

                // aggiunge elemento dalla tabella
                if (this.blocks[idx].selected) {
                    let i = this.elements.length
                    let element = this.formatElementForGrid(this.blocks[idx], i)

                    // force replace
                    const newEls = Object.assign([], this.elements)
                    newEls.push(element)
                    this.elements = Object.assign([], newEls)
                }

                // rimuove elemento dalla tabella
                else {
                    let i = this.elements.findIndex(element => element.type_id == item.id && element.type === item.type)
                    if (i > -1) {
                        // force replace
                        const newEls = Object.assign([], this.elements)
                        newEls.splice(i, 1)
                        this.elements = Object.assign([], newEls)
                    }
                }
            }
        },
        removeElement: function (item) {
            // console.log(item);
            // rimuove l'elemento dalla griglia e lo deseleziona dalla tabella
            let idx = this.blocks.findIndex(row => row.id == item.type_id && row.type === item.type)
            if (idx > -1) {
                // console.log(this.blocks[idx]);
                this.blocks[idx].selected = false
                item.selected = false
            }

            let index = this.elements.indexOf(item)

            // force replace
            const newEls = Object.assign([], this.elements)
            newEls.splice(index, 1)
            this.elements = Object.assign([], newEls)
        },
        formatElementForGrid: function (item, i = 0) {
            let colN = 12
            let w = item.hasOwnProperty('width') ? item.width : 2
            let h = item.hasOwnProperty('height') ? item.height : 2
            let x = item.hasOwnProperty('x') ? item.x : (i * w) % colN
            let y = item.hasOwnProperty('y') ? item.y : Math.floor((i * w) / colN)

            const cache = clone(item)
            delete cache.id

            const newEl = {
                i: i,
                x: x,
                y: y,
                w: w,
                h: h,
                idx: this.increments,
                order: i,
                type_id: item.id,
                ...cache,
            }

            // console.log(newEl);

            this.increments++
            return newEl
        },
        layoutUpdated: function (newLayout) {
            // console.log('layout aggiornato', newLayout);
            // console.log('prima', newLayout.map(o => o.x + ' ' + o.y));

            // https://coderwall.com/p/ebqhca/javascript-sort-by-two-fields
            const sorted = clone(newLayout)
            sorted.sort((a, b) => (a.y - b.y || a.x - b.x))

            // console.log('dopo', sorted.map(o => o.x + ' ' + o.y));
            this.elements = clone(sorted)
        },
        debug: function () {
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
        setInitial: function () {
            // console.log('imposta', this.initial, this.values);
            if (this.initial && this.option.type != 'post-select') {
                this.value = this.initial
                // console.log(clone(this.value));
                // console.log('qui', this.option.key, this.option.type);
            }

            // se ha sottomoduli
            if (this.option.hasOwnProperty('childrens') && this.option.childrens.length > 0) {
                // console.log('children', this.option.key, this.option.type, clone(this.initial));
                // console.log('childres ', this.initial);
                this.values = this.initial
            }

            // se è post-select
            if (this.option.type === 'post-select' && this.initial) {
                // console.log('post-select', this.option.key, this.option.type);
                // console.log('initila', this.initial);
                for (let i = 0; i < this.initial.length; i++) {
                    let formatted = this.formatElementForGrid(this.initial[i], i)
                    // console.log(formatted);
                    this.elements.push(formatted)
                }
                // console.log('griglia', this.initial[0]);
            }
        },
    },
    beforeCreate: function () {
        this.$options.components.DynamicModule = require('./DynamicModule.vue')
            .default
    },
    created: function () {
        this.getColors()
        this.setDefault()
        this.setInitial()
    },
    mounted: function () {}
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
