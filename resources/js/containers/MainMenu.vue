<template lang="html">
    <nav class="navbar navbar-main navbar-expand-lg navbar-light bg-white">
        <div class="navbar-main__head">
            <a class="navbar-brand"
                href="/"
                @click.prevent="$root.goTo('home')">
                <img src="/svg/logo.svg" alt="" style="width: 250px">
            </a>
        </div>

        <div class="navbar-collapse navbar-main__container" id="navbarSupportedContent">
            <div class="navbar-main__topbar">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">F</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">T</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ln</a>
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                </form>
            </div>
            <div class="navbar-main__menubar">
                <ul class="navbar-nav mr-auto">
                    <menu-item
                        v-for="page in pages"
                        :key="page.id"
                        :page="page"/>
                </ul>
            </div>
            <div class="navbar-main__submenu" v-if="this.subpages && this.current">
                <ul class="navbar-nav mr-auto">
                    <li
                        v-for="subpage in subpages"
                        :key="subpage.id"
                        class="nav-item">
                        <a class="nav-link"
                            :href="'/' + current.slug.slug + '/' + subpage.slug.slug"
                            @click.prevent="$root.goToWithParams('subpage', { page: current.slug.slug, subpage: subpage.slug.slug })">
                            {{ subpage.title }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
import MenuItem from './MenuItem.vue'

export default {
    name: 'MainMenu',
    components: {
        MenuItem,
    },
    props: {
        pages: {
            type: Array,
            default: function() {}
        }
    },
    data: function() {
        return {
            current: null,
            subpages: null,
        }
    },
    watch: {
        '$route': function(route) {
            this.checkSubMenu()
        }
    },
    methods: {
        checkSubMenu: function() {
            if (this.$route.hasOwnProperty('params') && this.$route.params.hasOwnProperty('page')) {
                this.current = this.pages.filter(page => page.slug.slug == this.$route.params.page)[0]
                if (this.current && this.current.sub_pages.length > 0) {
                    this.subpages = this.current.sub_pages
                }

                else {
                    this.current = null
                    this.subpages = null
                }
            }

            else {
                this.current = null
                this.subpages = null
            }
        }
    },
    created: function() {
        this.checkSubMenu()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.navbar-main {
    &__head {
        margin-left: -48px;
    }
}
</style>
