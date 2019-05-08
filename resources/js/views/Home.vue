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
    name: 'Home',
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
            let url

            switch (this.$route.name) {
                case 'page':
                    url = '/api/get-page/' + this.$route.params.page
                    break;

                case 'subpage':
                    url = '/api/get-page/' + this.$route.params.subpage
                    break;

                default:
                    // home
            }
            this.getData(url)
        },
        getData: function(url) {
            this.$http.get(url).then(response => {
                if (response.data.success) {
                    this.name = response.data.item.title
                    this.modules = response.data.item.modules
                    console.log(this.modules);
                }
            })
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
