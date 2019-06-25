<template>
<nav class="navbar navbar-main navbar-expand-lg navbar-light bg-white">
    <div class="navbar-main__head">
        <a
            class="navbar-brand"
            href="/"
            @click.prevent="$root.goTo('home')"
        >
            <img
                src="/svg/logo.svg"
                style="width: 250px"
            >
        </a>
    </div>

    <div
        class="navbar-collapse navbar-main__container"
        id="navbarSupportedContent"
    >
        <div class="navbar-main__topbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <ui-link
                        class="nav-link"
                        :has-margin="false"
                        :is-simple="true"
                        url="https://www.facebook.com/Trasversale/"
                        target="_blank"
                    >
                        <facebook
                            width="32px"
                            height="32px"
                            :hoverable="true"
                            hover-color="primary"
                        />
                    </ui-link>
                    <!-- <a class="nav-link" href="#">F</a> -->
                </li>
                <li class="nav-item">
                    <ui-link
                        class="nav-link"
                        :has-margin="false"
                        :is-simple="true"
                        url="https://twitter.com/Trasversale_srl "
                        target="_blank"
                    >
                        <twitter
                            width="32px"
                            height="32px"
                            :hoverable="true"
                            hover-color="primary"
                        />
                    </ui-link>
                    <!-- <a class="nav-link" href="#">T</a> -->
                </li>
                <li class="nav-item">
                    <ui-link
                        class="nav-link"
                        :has-margin="false"
                        :is-simple="true"
                        url="https://www.linkedin.com/company/trasversale-srl"
                        target="_blank"
                    >
                        <linkedin
                            width="32px"
                            height="32px"
                            :hoverable="true"
                            hover-color="primary"
                        />
                    </ui-link>
                    <!-- <a class="nav-link" href="#">Ln</a> -->
                </li>
                <li class="nav-item">
                    <ui-link
                        class="nav-link"
                        :has-margin="false"
                        :is-simple="true"
                        url="https://www.linkedin.com/company/trasversale-srl"
                        target="_blank"
                    >
                        <youtube
                            width="32px"
                            height="32px"
                            :hoverable="true"
                            hover-color="primary"
                        />
                    </ui-link>
                    <!-- <a class="nav-link" href="#">Ln</a> -->
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input
                    class="form-control mr-sm-2"
                    type="search"
                    placeholder="Search"
                    aria-label="Search"
                >
            </form>
        </div>
        <div class="navbar-main__menubar">
            <ul class="navbar-nav mr-auto">
                <menu-item
                    v-for="(page, i) in pages"
                    :key="i"
                    :page="page"
                />
            </ul>
        </div>
        <div
            class="navbar-main__submenu main-submenu"
            v-if="this.subpages && this.current"
        >
            <ul class="navbar-nav mr-auto main-submenu__navbar">
                <li
                    v-for="(subpage, j) in subpages"
                    :key="j"
                    class="nav-item main-submenu__item"
                >
                    <span
                        v-if="subpage.hasOwnProperty('divider')"
                        class="main-submenu__divider"
                    >
                        |
                    </span>
                    <main-menu-subitem
                        v-else
                        :title="subpage.title"
                        :page-slug="current.slug.slug"
                        :slug="subpage.slug.slug"
                    />
                </li>
            </ul>
        </div>
    </div>
</nav>
</template>

<script>
import MainMenuSubitem from './MainMenuSubitem.vue'
import MenuItem from './MenuItem.vue'
import {
    UiLink
}
from '../ui'
import {
    Facebook,
    Linkedin,
    Twitter,
    Youtube
}
from '../icons'

export default {
    name: 'MainMenu',
    components: {
        Facebook,
        Linkedin,
        MainMenuSubitem,
        MenuItem,
        Twitter,
        UiLink,
        Youtube
    },
    props: {
        pages: {
            type: Array,
            default: function () {}
        }
    },
    data: function () {
        return {
            current: null,
            subpages: null,
        }
    },
    watch: {
        '$route': function (route) {
            this.checkSubMenu()
        }
    },
    methods: {
        checkSubMenu: function () {
            if (this.$route.hasOwnProperty('params') && this.$route.params.hasOwnProperty('page')) {
                this.current = this.pages.filter(page => page.slug.slug == this.$route.params.page)[0]
                if (this.current && this.current.sub_pages.length > 0) {
                    let subpages = this.current.sub_pages
                    let temp = []

                    for (let i = 0; i < subpages.length; i++) {
                        temp.push(subpages[i])
                        if (i < (subpages.length - 1)) {
                            temp.push({
                                id: new Date().getUTCMilliseconds(),
                                divider: true
                            })
                        }
                    }
                    this.subpages = temp
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
    created: function () {
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

.main-submenu {
    &__link:first-child {
        padding-left: 0;
        padding-right: 0;
    }

    &__link {
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-base * 0.9;
        letter-spacing: 0.1em;
        font-weight: 400;
        padding: 0;
        // position: relative;
    }

    &__navbar {
        flex-wrap: wrap;
        padding-top: $spacer / 2;
        align-items: center;
    }

    &__item {
        padding-bottom: $spacer / 2;
    }

    &__divider {
        font-weight: 100;
        color: rgba($black, .5);
        padding-left: $spacer / 2;
        padding-right: $spacer / 2;
    }
}
</style>
