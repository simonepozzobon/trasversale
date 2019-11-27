<template>
<div class="admin-page">
    <top-bar v-if="this.$root.hasSidebar"></top-bar>
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <admin-sidebar
                :size="sidebar"
                v-if="this.$root.hasSidebar"
            ></admin-sidebar>
            <main
                ref="main"
                role="main"
                class="ml-sm-auto pr-0"
                :class="mainSizeClass"
            >
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
    data: function () {
        return {
            sidebar: 2,
        }
    },
    computed: {
        parsedPages: function () {
            if (this.pages) {
                return JSON.parse(this.pages)
            }
            return []
        },
        mainSizeClass: function () {
            return 'col-md-9 col-lg-10'
        }
    },
    mounted: function () {
        this.$root.pages = this.parsedPages
        this.$root.mainEl = this.$refs.main
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/adminshared';

.main-container {
    padding: 0;
}
</style>
