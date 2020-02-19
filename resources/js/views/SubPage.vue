<template>
<div v-if="pageContent">
    <sub-page-product
        v-if="pageContent.item.model == 'product'"
        :content="pageContent"
    />
    <sub-page-generic
        v-else
        :content="pageContent"
    />
</div>
<div v-else-if="isArchive">
    <ui-title
        font-size="h2"
        tag="h1"
        :title="archiveTitle"
    />
    <ui-simple-grid
        class="mt-4"
        :options="gridOptions"
        :blocks="gridBlocks"
    />
</div>
</template>

<script>
import SubPageGeneric from './SubPageGeneric.vue'
import SubPageProduct from './SubPageProduct.vue'

import {
    UiSimpleGrid,
    UiTitle,
}
from '../ui'

export default {
    name: 'SubPage',
    components: {
        SubPageGeneric,
        SubPageProduct,
        UiSimpleGrid,
        UiTitle,
    },
    data: function () {
        return {
            pageContent: null,
            gridOptions: {},
            gridBlocks: [],
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        }
    },
    computed: {
        isArchive: function () {
            if (this.$route.params && this.$route.params.hasOwnProperty('subpage') && this.$route.params.subpage == 'archivio') {
                return true
            }
            return false
        },
        isArchiveCorsi: function () {
            if (this.$route.params && this.$route.params.page == 'corsi' && this.isArchive) {
                return true
            }
            return false
        },
        isArchiveNews: function () {
            if (this.$route.params && this.$route.params.page == 'notizie' && this.isArchive) {
                return true
            }
            return false
        },
        archiveTitle: function () {
            if (this.isArchiveNews) {
                return 'archivio notizie'
            }

            return 'archivio corsi'
        },
    },
    methods: {
        init: function () {
            let url = '/api/get-page/' + this.$route.params.page + '/' + this.$route.params.subpage

            if (this.isArchive) {
                if (this.isArchiveCorsi) {
                    url = '/api/archive/products'
                }
                else if (this.isArchiveNews) {
                    url = '/api/archive/news'
                }
            }

            this.getData(url)
        },
        getData: function (url) {
            this.pageContent = null
            if (url) {
                this.$http.get(url).then(response => {
                    if (response.data.success && !this.isArchive) {
                        this.pageContent = response.data
                    }
                    else if (response.data.success && this.isArchive) {
                        this.gridOptions = {
                            mode: 'last',
                            models: [{
                                    products: this.isArchiveCorsi
                                },
                                {
                                    news: this.isArchiveNews
                                },
                                {
                                    pages: false
                                }
                            ],
                            post_count: response.data.items.length,
                            post_per_row: 2
                        }

                        this.gridBlocks = response.data.items
                    }
                })
            }
        }
    },
    created: function () {
        this.pageContent = null
        this.init()
    },
    mounted: function () {}
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
