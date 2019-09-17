<template lang="html">
    <div v-if="pageContent">
        <sub-page-product v-if="pageContent.item.model == 'product'" :content="pageContent"/>
        <sub-page-generic v-else :content="pageContent"/>
    </div>
</template>

<script>
import SubPageGeneric from './SubPageGeneric.vue'
import SubPageProduct from './SubPageProduct.vue'

export default {
    name: 'Subpage',
    components: {
        SubPageGeneric,
        SubPageProduct,
    },
    data: function () {
        return {
            pageContent: null,
        }
    },
    watch: {
        '$route': function (v) {
            this.init()
        }
    },
    methods: {
        init: function () {
            let url = '/api/get-page/' + this.$route.params.page + '/' + this.$route.params.subpage
            this.getData(url)
        },
        getData: function (url) {
            this.pageContent = null
            if (url) {
                this.$http.get(url).then(response => {
                    if (response.data.success) {
                        this.pageContent = response.data
                        // console.log('contyainer', this.pageContent);
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
