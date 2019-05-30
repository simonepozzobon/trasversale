<template lang="html">
    <div class="admin-page">
        <top-bar></top-bar>
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <admin-sidebar></admin-sidebar>
                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pr-0">
                    <router-view></router-view>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
import AdminSidebar from './AdminSidebar.vue'
import TopBar from './TopBar.vue'

export default {
    name: 'MainTemplate',
    components: {
        AdminSidebar,
        TopBar,
    },
    props: {
        pages: {
            type: String,
            default: null,
        }
    },
    computed: {
        parsedPages: function() {
            if (this.pages) {
                return JSON.parse(this.pages)
            }
            return []
        }
    },
    mounted: function() {
        this.$root.pages = this.parsedPages
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/adminshared';

.main-container {
    padding: 0;
}
</style>
