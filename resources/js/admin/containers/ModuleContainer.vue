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
            :module-id="item.id"
            :values="content"
            @changed="formatTempData"
            @close="closeComponent"/>
    </div>
</template>

<script>
import AdminModuleManager from '../components/AdminModuleManager.vue'
import NewModule from '../components/NewModule.vue'

export default {
    name: 'ModuleContainer',
    components: {
        AdminModuleManager,
        NewModule,
    },
    props: {
        item: {
            type: Object,
            default: function() { return {} },
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
    data: function() {
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
        content: function() {
            if (this.item) {
                return JSON.parse(this.item.content)
            }
            return null
        },
    },
    methods: {
        formatTempData: function(obj) {
            // console.log('formato', this.item.type, obj);
            let content = this.setPreview(obj)
            const newObj = {
                ...this.component,
                content: content
            }

            this.component = Object.assign({}, newObj)
        },
        setPreview: function(obj) {
            switch (this.item.type) {
                // Riga
                case 'row':
                    let cols = []
                    for (let i = 0; i < obj.columns; i++) {
                        let col = {
                            id: i,
                            modulable_id: 31,
                            modulable_type: 'App\\Module',
                            modules: [],
                            type: 'column',
                            content: JSON.stringify({size: 6})
                        }
                        cols.push(col)
                    }
                    return JSON.stringify(cols)

                // Immagine
                case 'image':
                    return JSON.stringify({
                        src: obj.src ? window.URL.createObjectURL(obj.src) : null,
                        alt: obj.alt
                    })

                // Video
                case 'video':
                    let url = obj.url
                    if (url) {
                        url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

                        if (RegExp.$3.indexOf('youtu') > -1) {
                            url = 'https://www.youtube.com/embed/' + RegExp.$6
                        } else if (RegExp.$3.indexOf('vimeo') > -1) {
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

                    blocks = blocks.map(block => {
                        return {
                            ...block,
                            height: block.h,
                            width: block.w,
                            content: JSON.stringify({
                                id: block.id,
                                slug: block.slug,
                                title: block.title,
                            })
                        }
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
        changed: function(obj) {
            this.cache = obj
            // console.log(obj);
        },
        selected: function(obj = null) {
            if (!this.isOpen) {
                this.isOpen = true
                this.show()
            }
        },
        closeComponent: function() {
            this.isOpen = false
            this.hide()
        },
        show: function() {
            this.$refs.panel.show()
            // console.log(this.cache, this.item);
        },
        hide: function() {
            this.$refs.panel.hide()
        },
        updateSize: function(data) {
            // https://stackoverflow.com/questions/1230233/how-to-find-the-sum-of-an-array-of-numbers
            let columns = JSON.parse(this.component.content)
            let total = columns.map((col, idx) => {
                let content = JSON.parse(col.content)
                let size = content.size
                if (idx == data.idx) {
                    size = content.size + data.size
                }
                return size
            }).reduce((a,b) => a + b, 0)


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
        setColSize: function(value, jsonContent) {
            let content = JSON.parse(jsonContent)
            content.size = content.size + value
            return JSON.stringify(content)
        }
    },
    created: function() {
        if (this.item) {
            this.component = this.item
            // console.log(this.values);
        }
    },
    mounted: function() {
        if (this.item.hasOwnProperty('isNew') && this.item.isNew) {
            this.$nextTick(this.selected)
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.module-container {
    margin-bottom: $spacer * 2;
}
</style>
