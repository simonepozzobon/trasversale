<template>
<div class="module-container">
    <!-- MODULE -->
    <admin-module-manager
        v-if="component"
        :is-admin="true"
        :module="component"
        :isOpen="isOpen"
        @selected="selected"
        @add-component="addComponent"
        @update-size="updateSize"
        @delete="deleteComponent"
        @save-module="saveSubModule"
    />

    <!-- PANNELLO -->
    <new-module
        ref="panel"
        :name="item.type"
        :uuid="item.uuid"
        :model="item.modulable_type"
        :model-idx="item.modulable_id"
        :is-edit="isEdit"
        :is-new="item.isNew"
        :module-id="item.id"
        :values="content"
        @changed="formatTempData"
        @save="saveComponent"
        @saved="savedComponent"
        @close="closeComponent"
        @delete="deleteComponent"
        @deleted="deletedComponent"
    />
</div>
</template>

<script>
import {
    clone,
    isEqual,
    isFile,
    Uuid,
}
from '../../Utilities'

import AdminModuleManager from '../components/AdminModuleManager.vue'
import NewModule from '../components/NewModule.vue'
import DynamicParams from '../DynamicParams'

export default {
    name: 'ModuleContainer',
    components: {
        AdminModuleManager,
        NewModule,
    },
    props: {
        item: {
            type: Object,
            default: function () {
                return {}
            },
        },
        model: {
            type: String,
            default: null,
        },
        modelIdx: {
            type: Number,
            default: 0,
        },
    },
    data: function () {
        return {
            isEdit: false,
            component: null,
            values: [],
            isOpen: false,
            hasUnsavedChanges: false,
            cache: null,
        }
    },
    computed: {
        content: function () {
            if (this.item) {
                return this.item.content
            }
            return null
        },
    },
    methods: {
        addComponent: function (column, component) {
            let columns = this.component.content
            let colID = columns.findIndex(col => col.uuid === column.uuid)
            // console.log('aggiungi componente nella colonna', column, colID, component);
            if (colID > -1) {
                // aggiunge il componente alla colonna
                columns[colID].content.modules.push(component)
            }

            this.component = {
                ...this.component,
                content: columns
            }
            // console.log('prima', newComponent);
            // this.component = Object.assign({}, newComponent)
            // console.log('dopo', this.component);
        },
        saveSubModule: function (columns) {
            // console.log('save sub module', this.item);
            // this.saveComponent(null)
            this.component.content = columns

            this.$emit('save-sub-module', this.component)
            // console.log(columns, this.component);
        },
        formatTempData: function (obj) {
            // console.log('formatTempData');
            this.component.content = this.setPreview(obj)
            // console.log(this.component);
        },
        deleteComponent: function (id, isNew, uuid = false) {
            // console.log('module container delete', this.component);
            if (!uuid) {
                uuid = this.component.uuid
            }
            this.$emit('delete', id, isNew, uuid)
        },
        deletedComponent: function (module) {
            this.isOpen = false
            this.hide()
            this.$emit('deleted', this.item)
        },
        saveComponent: function (obj) {
            // console.log('module container save', this.component);
            this.$emit('save', this.component)
        },
        savedComponent: function (component) {
            // console.log(component);
            // console.log('module container save', component);
            this.item.modulable_type = component.modulable_type
            this.item.modulable_id = Number(component.modulable_id)
            this.item.id = Number(component.id)
            this.item.isNew = false
        },
        setPreview: function (obj) {
            // console.log('setting preview', obj);
            switch (this.item.type) {
            case 'row':
                // Riga
                // console.log('setting preview della row', obj);
                let cols = []
                let newCols = []
                let content = this.component.content

                let length = content.length ? content.length : 0
                let colsToGenerate = obj.columns - length

                if (colsToGenerate > 0) {
                    newCols = this.generateColumns(colsToGenerate)
                    cols = Object.assign([], content)
                        .concat(newCols)
                }
                else if (colsToGenerate < 0) {
                    content.pop()
                    cols = newCols.concat(content)
                }

                return cols

            case 'image':
                // Immagine
                let src = null
                if (obj.src && isFile(obj.src)) {
                    // let file = new File(obj.src)
                    src = window.URL.createObjectURL(obj.src)
                }
                else if (obj.src) {
                    // console.log('non è un file');
                    src = obj.src
                }

                return {
                    src: src,
                        alt: obj.alt
                }
                break;

            case 'video':
                // Video
                let url = obj.url
                if (url) {
                    url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
                    if (RegExp.$3.indexOf('youtu') > -1) {
                        url = 'https://www.youtube.com/embed/' + RegExp.$6
                    }
                    else if (RegExp.$3.indexOf('vimeo') > -1) {
                        url = 'https://player.vimeo.com/video/' + RegExp.$6
                    }
                }
                return {
                    ...obj,
                    url: url,
                }
                break;

            case 'grid':
                console.log(obj);
                let blocks = []
                if (obj.elements) {
                    if (obj.elements.hasOwnProperty('blocks') && obj.elements.blocks) {
                        blocks = obj.elements.blocks
                    }
                }
                return {
                    options: null,
                        title: obj.title,
                        type: obj.type,
                        blocks: blocks.map((block, i) => {
                            return {
                                ...block,
                                height: block.h,
                                width: block.w,
                                content: {
                                    id: block.id,
                                    slug: block.slug,
                                    title: block.title,
                                }
                            }
                        })
                }
                break;

            default:
                // DEfault
                return obj
            }
        },
        changed: function (obj) {
            // console.log('changed', obj);
            this.cache = obj
        },
        selected: function (obj = null) {
            if (!this.isOpen) {
                this.isOpen = true
                this.show()
            }
        },
        closeComponent: function () {
            this.isOpen = false
            this.hide()
        },
        show: function () {
            if (this.$refs.hasOwnProperty('panel') && this.$refs.panel) {
                this.$refs.panel.show()
            }
        },
        hide: function () {
            if (this.$refs.hasOwnProperty('panel') && this.$refs.panel) {
                this.$refs.panel.hide()
            }
        },
        updateSize: function (data) {
            // https://stackoverflow.com/questions/1230233/how-to-find-the-sum-of-an-array-of-numbers
            let columns = this.component.content
            let total = columns.map((col, idx) => {
                    let content = col.content
                    let size = content.size
                    if (idx == data.idx) {
                        size = content.size + data.size
                    }
                    return size
                })
                .reduce((a, b) => a + b, 0)


            if (total > 12) {
                // rimuovere da qualche parte
                let i = 0
                while (total > 12) {
                    if (i != data.idx) {
                        columns[i].content = this.setColSize(-1, columns[i].content)
                        total = total - 1
                    }

                    i = (i >= columns.length - 1) ? 0 : (i + 1)
                }
            }
            columns[data.idx].content = this.setColSize(data.size, columns[data.idx].content)
            this.component.content = columns
            // console.log(total);
        },
        setColSize: function (value, jsonContent) {
            let content = jsonContent
            content.size = content.size + value
            return content
        },
        generateColumns: function (colsNum) {
            let newColumns = []

            for (let i = 0; i < colsNum; i++) {
                const newCol = this.generateColumn(i)
                newColumns.push(newCol)
            }

            return newColumns
        },
        generateColumn: function (i = 0) {
            return {
                uuid: Uuid.get(),
                type: 'column',
                isNew: true,
                modulable_type: 'App\\Module',
                order: i,
                content: {
                    size: 6,
                    modules: []
                }
            }
        }
    },
    created: function () {
        this.component = this.item
        // console.log('init module container', this.item.content);

        this.$root.$on('close-all-panels', () => {
            this.closeComponent()
        })
    },
    mounted: function () {
        if (this.item.hasOwnProperty('isNew') && this.item.isNew) {
            this.$nextTick(this.selected)
        }
        else {
            this.item.isNew = false
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.module-container {
    max-width: 100%;
    margin-bottom: $spacer * 2;
}
</style>
