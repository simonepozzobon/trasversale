<template>
<draggable
    v-model="cached"
    class="row"
    @change="sortColumns"
>
    <admin-ui-module-block
        v-if="columns.length > 0"
        v-for="(column, i) in cached"
        :key="column.uuid"
        :idx="i"
        :column="column"
        :is-open="isOpen"
        @save-column="saveColumn"
        @add-component="addComponent"
        @delete-sub-component="deleteSubComponent"
        @update="updateSize"
    />
</draggable>
</template>

<script>
import AdminUiModuleBlock from './AdminUiModuleBlock.vue'
import draggable from 'vuedraggable'
import {
    UiRow
}
from '../../../ui'

export default {
    name: 'AdminUiModuleRow',
    components: {
        AdminUiModuleBlock,
        draggable,
        UiRow,
    },
    props: {
        columns: [Array, Object],
        isOpen: {
            type: Boolean,
            default: false,
        }
    },
    data: function () {
        return {
            cached: []
        }
    },
    watch: {
        columns: {
            handler: function (columns) {
                // console.log('colonne cambiate deep', columns);
                this.setColumns(columns)
            },
            deep: true
        },
    },
    methods: {
        setColumns: function (columns) {
            this.cached = Object.assign([], columns)
        },
        updateSize: function (data) {
            this.$emit('update-size', data)
        },
        saveColumn: function (column) {
            // console.log('save row', this.columns);
            this.$emit('save-row', this.cached)
        },
        addComponent: function (column, component) {
            this.$emit('add-component', column, component)
        },
        deleteSubComponent: function (id, isNew, uuid) {
            this.$emit('delete-sub-component', id, isNew, uuid)
        },
        sortColumns: function (columns) {
            this.cached = this.cached.map((cache, i) => {
                let newColumn = Object.assign({}, cache)
                newColumn['order'] = i
                return newColumn
            })

            this.saveColumn(null)
        }
    },
    created: function () {
        this.setColumns(this.columns)
    },
    mounted: function () {
        // console.log('ciao', this.columns, this.isOpen);
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
