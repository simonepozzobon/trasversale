<template lang="html">
    <div>
        <module-manager
            v-for="module in this.modules"
            :key="module.id"
            :module="module"/>
    </div>
</template>

<script>
import ModuleManager from '../containers/ModuleManager.vue'
export default {
    name: 'Page',
    components: {
        ModuleManager,
    },
    data: function() {
        return {
            name: null,
            modules: null,
        }
    },
    watch: {
        '$route': function(v) {
            this.init()
        }
    },
    methods: {
        init: function() {
            // let url
            //
            // switch (this.$route.name) {
            //     case 'page':
            //         url = '/api/get-page/' + this.$route.params.page
            //         break;
            //
            //     case 'subpage':
            //         this.$root.goToWithParams('subpage', {
            //             page: this.$route.params.page,
            //             subpage: this.$route.params.subpage
            //         })
            //
            //         break;
            //
            //     case 'item':
            //     console.log('2354543');
            //         this.$root.goToWithParams('item', {
            //             page: this.$route.params.page,
            //             subpage: this.$route.params.subpage,
            //             item: this.$route.params.item
            //         })
            //         break;
            //
            //     default:
            //         // home
            //         url = '/api/get-page/home'
            // }
            // this.getData(url)
            console.log('page mounted');
        },
        getData: function(url) {
            if (url) {
                this.$http.get(url).then(response => {
                    // console.log(response.data);
                    if (response.data.success) {
                        this.name = response.data.item.title
                        this.modules = response.data.item.modules
                    }
                })
            }
        }
    },
    created: function() {
        this.init()
    },
    mounted: function() {
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
