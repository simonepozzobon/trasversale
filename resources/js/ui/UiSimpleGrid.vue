<template>
<div class="ui-simple-grid">
    <ui-row>
        <ui-block
            :size="12"
            :has-container="false"
        >
            <ui-button
                v-for="(cat, i) in categories"
                :key="i"
                class="mr-3"
                :title="cat.title"
                color="primary"
                :has-container="false"
                :has-margin="true"
                display="inline-block"
                :is-active="cat.id == currentId"
                :event-params="cat.id"
                @click="filterNews"
            />
        </ui-block>
    </ui-row>
    <ui-row>
        <ui-simple-grid-loop
            v-for="(block, i) in filtered"
            :key="block.uuid"
            :block="block"
            :options="options"
            @category="addCategory"
            @filter-category="filterNews"
        />
    </ui-row>
</div>
</template>

<script>
import UiSimpleGridLoop from './UiSimpleGridLoop.vue'
import UiBlock from './UiBlock.vue'
import UiButton from './UiButton.vue'
import UiRow from './UiRow.vue'

const orderBy = require('lodash.orderby')

export default {
    name: 'UiSimpleGrid',
    components: {
        UiBlock,
        UiButton,
        UiRow,
        UiSimpleGridLoop,
    },
    props: {
        blocks: {
            type: Array,
            default: function () {},
        },
        options: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            cached: [],
            categories: [],
            filtered: [],
            currentId: null,
        }
    },
    watch: {
        blocks: function (blocks) {
            this.sortBlocks(blocks)
        },
    },
    methods: {
        setBlocks: function (id = false) {
            // if (id) {
            //     this.filtered = this.blocks.filter(block => block.id == id)
            // }
        },
        sortBlocks: function (blocks) {
            if (this.options.mode == 'last') {
                if (blocks[0].hasOwnProperty('published_at')) {
                    console.log('cie');
                    this.filtered = orderBy(blocks, ['published_at'], ['desc'])
                }
                else {
                    console.log('sdfkdjgjkdg');
                    this.filtered = orderBy(blocks, ['created_at'], ['desc'])
                }
            }
            else {
                this.filtered = blocks
            }

        },
        addCategory: function (category, blockID) {
            if (category && category.hasOwnProperty('id')) {
                let idx = this.categories.findIndex(single => single.id == category.id)
                if (idx < 0) {
                    let categoryFormatted = {
                        ...category,
                        blockIds: [blockID]
                    }
                    this.categories.push(categoryFormatted)
                }
                else {
                    let ids = this.categories[idx].blockIds
                    ids.push(blockID)
                    this.categories[idx] = {
                        ...this.categories[idx],
                        blockIds: ids
                    }
                }
            }

            // console.log(this.categories);
        },
        filterNews: function (id) {
            console.log(id);
            let category = this.categories.filter(category => category.id == id)[0]
            if (category) {
                if (category.id != this.currentId) {
                    // https://stackoverflow.com/questions/4607991/javascript-transform-object-into-array
                    let ids = Object.keys(category.blockIds).map(key => category.blockIds[key])
                    this.currentId = category.id
                    let blocks = this.cached.filter(function (e) {
                        return this.indexOf(e.id) > -1
                    }, ids)

                    this.sortBlocks(blocks)
                }

                else {
                    this.sortBlocks(this.cached)
                    this.currentId = null
                }
            }
        },
    },
    created: function () {
        if (this.options.mode == 'last') {
            this.cached = this.blocks.map(block => {
                let newBlock = Object.assign({}, block)
                if (typeof newBlock.content === 'string') {
                    newBlock.content = JSON.parse(block.content)
                }

                if (newBlock.content.hasOwnProperty('published_at')) {
                    newBlock['published_at'] = newBlock.content.published_at
                }

                return newBlock
            })
        }


        this.sortBlocks(this.cached)
    },
    mounted: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
