<template lang="html">
    <div class="module-container">
        <!-- MODULE -->
        <admin-module-manager
            v-if="component"
            :is-admin="true"
            :module="component"
            :isOpen="isOpen"
            @selected="selected"
            @update-size="updateSize"/>

        <!-- PANNELLO -->
        <new-module
            ref="panel"
            :name="item.type"
            :model="item.modulable_type"
            :model-idx="item.modulable_id"
            :is-edit="isEdit"
            :is-new="item.isNew"
            :module-id="item.id"
            :values="content"
            @changed="formatTempData"
            @saved="saveComponent"
            @close="closeComponent"
            @deleted="deleteComponent"/>
    </div>
</template>

<script>
import {
    clone,
    isEqual,
    isFile,
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
                return JSON.parse(this.item.content)
            }
            return null
        },
    },
    methods: {
        formatTempData: function (obj) {
            // console.log('formato', this.item.type, obj);
            let newObj = clone(this.component)

            // console.log(clonedObj);
            if (this.item.type === 'image') {
                newObj.content = this.setPreview(obj)
            }
            else {
                let clonedObj = clone(obj)
                newObj.content = this.setPreview(clonedObj)
            }

            this.component = clone(newObj)
        },
        deleteComponent: function () {
            this.isOpen = false
            this.hide()
            this.$emit('deleted', this.item)
        },
        saveComponent: function (component) {
            // console.log(component);
            this.item.modulable_type = component.modulable_type
            this.item.modulable_id = Number(component.modulable_id)
            this.item.id = Number(component.id)
            this.item.isNew = false
        },
        setPreview: function (obj) {
            console.log('setting preview');
            switch (this.item.type) {
                // Riga
            case 'row':
                let cols = []
                let newCols = []
                let content = JSON.parse(this.component.content)
                let colsToGenerate = obj.columns - content.length

                if (content.length > 0 && colsToGenerate > 0) {
                    newCols = this.generateColumns(colsToGenerate)
                    cols = content.concat(newCols)
                }
                else if (content.length > 0 && colsToGenerate < 0) {
                    content.pop()
                    cols = newCols.concat(content)
                }

                return JSON.stringify(cols)

                // Immagine
            case 'image':
                let src
                if (obj.src && isFile(obj.src)) {
                    // let file = new File(obj.src)
                    src = window.URL.createObjectURL(obj.src)
                }
                else if (obj.src) {
                    // console.log('non è un file');
                    src = obj.src
                }
                else {
                    // console.log('non esiste');
                    src = null
                }

                return JSON.stringify({
                    src: src,
                    alt: obj.alt
                })

                // Video
            case 'video':
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

                return JSON.stringify({
                    ...obj,
                    url: url,
                })

            case 'grid':
                let blocks = []
                if (obj.elements) {
                    if (obj.elements.hasOwnProperty('blocks') && obj.elements.blocks) {
                        blocks = obj.elements.blocks
                    }
                }

                // console.log(blocks);

                blocks = blocks.map((block, i) => {
                    let newBlock = {
                        ...block,
                        height: block.h,
                        width: block.w,
                        content: JSON.stringify({
                            id: block.id,
                            slug: block.slug,
                            title: block.title,
                        })
                    }

                    // if (i == 2) {
                    //     console.log(newBlock, block);
                    // }

                    return newBlock
                })

                // console.log('blocchi', blocks);
                return JSON.stringify({
                    blocks: blocks,
                    options: null,
                    title: obj.title,
                    type: obj.type,
                })

                // DEfault
            default:
                return JSON.stringify(obj)
            }
        },
        changed: function (obj) {
            this.cache = obj
            // console.log(obj);
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
            this.$refs.panel.show()
            // console.log(this.cache, this.item);
        },
        hide: function () {
            this.$refs.panel.hide()
        },
        updateSize: function (data) {
            console.log('update size');
            // https://stackoverflow.com/questions/1230233/how-to-find-the-sum-of-an-array-of-numbers
            let columns = JSON.parse(this.component.content)
            let total = columns.map((col, idx) => {
                    let content = JSON.parse(col.content)
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
            this.component.content = JSON.stringify(columns)
            // console.log(total);
        },
        setColSize: function (value, jsonContent) {
            let content = JSON.parse(jsonContent)
            content.size = content.size + value
            return JSON.stringify(content)
        },
        initColumns: function () {
            let params, colsOpts
            let colsDefault = 2
            let newColumns = []

            params = DynamicParams.find(params => params.name === 'row')
            if (params && params.hasOwnProperty('options')) {
                colsOpts = params.options.find(option => option.key === 'columns')
                if (colsOpts) {
                    colsDefault = colsOpts.default
                }
            }

            newColumns = this.generateColumns(colsDefault)

            let content = JSON.stringify(newColumns)
            this.component = {
                ...this.item,
                content: content
            }
        },
        generateColumns: function (colsNum) {
            let newColumns = []

            for (let i = 0; i < colsNum; i++) {
                const newCol = this.generateColumn()
                newColumns.push(newCol)
            }

            return newColumns
        },
        generateColumn: function () {
            return {
                type: 'column',
                isNew: true,
                modulable_type: 'App\\Module',
                content: JSON.stringify({
                    size: 6,
                    modules: []
                })
            }
        }
    },
    created: function () {
        if (this.item.type == 'row' && this.item.isNew) {
            this.initColumns()
        }
        else if (this.item) {
            this.component = this.item
            // console.log(this.values);
        }
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
