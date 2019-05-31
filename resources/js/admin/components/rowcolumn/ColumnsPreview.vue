<template lang="html">
    <div class="form-group row">
        <label for="" class="col-md-3">Anteprima</label>
        <div class="col-9">
            <div class="row">
                <column-item
                    v-for="(item, i) in columns"
                    :key="i"
                    :idx="i"
                    :size="6"/>
            </div>
        </div>
    </div>
</template>

<script>
import ColumnItem from './ColumnItem.vue'

export default {
    name: 'ColumnsPreview',
    components: {
        ColumnItem,
    },
    props: {
        colsNumber: {
            type: Number,
            default: 2,
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
                }
                else {
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
    mounted: function() {
        console.log('colonne');
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
