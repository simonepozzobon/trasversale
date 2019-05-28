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
            :values="values"
            @changed="formatTempData"/>
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
            let content

            switch (this.item.type) {
                case 'row':
                    let cols = []
                    for (let i = 0; i < obj.columns; i++) {
                        let col = {
                            id: i,
                            modulable_id: 31,
                            modulable_type: 'App\\Module',
                            modules: [],
                            type: 'column',
                            content: JSON.stringify({
                                size: 6,
                            })
                        }
                        cols.push(col)
                    }
                    content = JSON.stringify(cols)
                    break;
                default:
            }
            // console.log(this.component);
            this.component.content = content
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
                    }
                    columns = columns.map((col, idx))
                    i++
                }
                // let colsCount = columns.length - 1
                // if (colsCount > 0) {
                //     columns = this.reduceCol(columns, difference, colsCount, data)
                // }
            } else {
                // aggiungere da qualche parte
                let content = JSON.parse(columns[data.idx].content)
                content.size = content.size + data.size
                columns[data.idx].content = JSON.stringify(content)
            }

            this.component.content = JSON.stringify(columns)
            // console.log(total);
        },
        setSize: function() {
            let content = JSON.parse(col.content)
            content.size = content.size
            col.content = JSON.stringify(content)
        },
        reduceCol: function(columns, difference, colsCount, data) {
            let quotient = Math.floor(difference / colsCount)
            let modulo =  difference % colsCount

            columns = columns.map((col, idx, cols) => {
                let content = JSON.parse(col.content)

                if (idx == data.idx) {
                    content.size = content.size + data.size
                }

                else if (cols.length - 1 === idx) {
                    content.size = content.size - quotient - modulo
                }

                else {
                    content.size = content.size - quotient
                }
                col.content = JSON.stringify(content)
                return col
            })

            // let remainder =
            console.log(difference, colsCount, '->', quotient, modulo);
            return columns
        },
    },
    created: function() {
        if (this.item) {
            this.component = this.item
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
