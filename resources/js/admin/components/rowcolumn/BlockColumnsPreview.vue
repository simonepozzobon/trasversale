<template lang="html">
    <div class="row">
        <block-column-item
            v-for="(item, i) in columns"
            :key="i"
            :idx="i"
            :size="6"/>
    </div>
</template>

<script>
import BlockColumnItem from './BlockColumnItem.vue'

export default {
    name: 'ColumnsPreview',
    components: {
        BlockColumnItem,
    },
    props: {
        colsNumber: {
            type: Number,
            default: 4,
        },
    },
    data: function() {
        return {
            columns: [],
        }
    },
    watch: {
        colsNumber: function(num) {
            this.changeColumns()
        }
    },
    computed: {
        colClass: function() {

        },
    },
    methods: {
        changeColumns: function() {
            if (this.colsNumber != this.columns.length) {
                let difference = this.colsNumber - this.columns.length
                if (difference > 0) {
                    this.bulkCreate(difference)
                } else {
                    this.deleteColumn(difference)
                }
            }
        },
        bulkCreate: function(num = 0) {
            for (let i = 0; i < num; i++) {
                this.createColumn()
            }
        },
        deleteColumn: function(num = 0) {
            num = Math.abs(num)
            let start = this.columns.length - num
            this.columns.splice(start, num)
        },
        createColumn: function() {
            let column = {
                idx: this.columns.length,
                size: 6,
                modules: [],
            }
            this.columns.push(column)
        },
    },
    created: function() {
        this.changeColumns()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
