<template>
    <nav class="navbar navbar-main navbar-expand-lg navbar-light bg-white">
        <div class="navbar-main__head">
            <a
                class="navbar-brand"
                href="/"
                @click.prevent="$root.goTo('home')"
            >
                <img src="/svg/logo.svg" class="navbar-main__logo" />
            </a>
        </div>

        <div
            class="navbar-collapse navbar-main__container"
            id="navbarSupportedContent"
        >
            <div class="navbar-main__topbar">
                <ul class="navbar-nav ml-lg-auto">
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
                            url="https://twitter.com/Trasversale_srl"
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
                            url="https://www.youtube.com/channel/UCFIPNXBLcaXO7JU9sUBebag"
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
                    <li class="nav-item">
                        <ui-link
                            class="nav-link"
                            :has-margin="false"
                            :is-simple="true"
                            url="//www.instagram.com/trasversale_srl"
                            target="_blank"
                        >
                            <instagram
                                class="mt-2 mr-2"
                                width="18px"
                                height="18px"
                                :hoverable="true"
                                hover-color="primary"
                            />
                        </ui-link>
                        <!-- <a class="nav-link" href="#">Ln</a> -->
                    </li>
                </ul>
                <simple-search></simple-search>
                <div class="burger d-block d-lg-none" @click="toggleMenu">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 448 512"
                        class="burger__svg"
                    >
                        <path
                            d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"
                        ></path>
                    </svg>
                </div>
            </div>
            <div class="navbar-main__menubar d-none d-lg-flex">
                <ul class="navbar-nav mr-auto">
                    <menu-item
                        v-for="(page, i) in pages"
                        :key="i"
                        :page="page"
                    />
                </ul>
            </div>
            <div
                class="navbar-main__submenu main-submenu d-none d-lg-flex"
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
        <div class="navbar-main__menubar-mob d-block d-lg-none" v-if="isOpen">
            <ul class="navbar-nav mr-auto">
                <menu-item v-for="(page, i) in pages" :key="i" :page="page" />
            </ul>
        </div>
    </nav>
</template>

<script>
import MainMenuSubitem from "./MainMenuSubitem.vue";
import MenuItem from "./MenuItem.vue";
import { UiLink } from "../ui";
import { Facebook, Instagram, Linkedin, Twitter, Youtube } from "../icons";

import SimpleSearch from "../components/SimpleSearch.vue";

export default {
    name: "MainMenu",
    components: {
        Facebook,
        Instagram,
        Linkedin,
        MainMenuSubitem,
        MenuItem,
        SimpleSearch,
        Twitter,
        UiLink,
        Youtube
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
            isOpen: false
        };
    },
    watch: {
        $route: function(route) {
            this.isOpen = false;
            this.checkSubMenu();
        }
    },
    methods: {
        toggleMenu: function() {
            if (this.isOpen) {
                this.isOpen = false;
            } else {
                this.isOpen = true;
            }
        },
        checkSubMenu: function() {
            if (
                this.$route.hasOwnProperty("params") &&
                this.$route.params.hasOwnProperty("page")
            ) {
                this.current = this.pages.filter(
                    page => page.slug.slug == this.$route.params.page
                )[0];
                if (this.current && this.current.sub_pages.length > 0) {
                    let subpages = this.current.sub_pages;
                    let temp = [];

                    for (let i = 0; i < subpages.length; i++) {
                        temp.push(subpages[i]);
                        if (i < subpages.length - 1) {
                            temp.push({
                                id: new Date().getUTCMilliseconds(),
                                divider: true
                            });
                        }
                    }
                    this.subpages = temp;
                } else {
                    this.current = null;
                    this.subpages = null;
                }
            } else {
                this.current = null;
                this.subpages = null;
            }
        }
    },
    created: function() {
        this.checkSubMenu();
    }
};
</script>

<style lang="scss" scoped>
@import "~styles/shared";

.burger {
    padding-left: $spacer;
    &__svg {
        width: 24px;

        path {
            fill: $black;
        }
    }
}

.navbar-main {
    display: flex;
    flex-wrap: wrap;
    padding: 0.46875rem 0;

    @include media-breakpoint-up("lg") {
        padding: 0.46875rem 0.9375rem;
    }

    &__head {
        margin-left: 0;
        .navbar-brand {
            margin-right: 0;
        }

        @include media-breakpoint-up("sm") {
            margin-left: 0;
            .navbar-brand {
                margin-right: 0;
            }
        }

        @include media-breakpoint-up("md") {
            margin-left: -48px;
            .navbar-brand {
                margin-right: 0.9375rem;
            }
        }

        @include media-breakpoint-up("lg") {
            margin-left: -48px;
        }
    }

    &__logo {
        width: 110px;

        @include media-breakpoint-up("sm") {
        }

        @include media-breakpoint-up("md") {
            width: 180px;
        }

        @include media-breakpoint-up("lg") {
            width: 250px;
        }
    }

    &__container {
        flex-basis: auto;
        padding-top: 0;
        flex-direction: row;
        justify-content: flex-end;

        @include media-breakpoint-up("lg") {
            flex-direction: column;
            justify-content: flex-start;
        }

        @include media-breakpoint-up("lg") {
            padding-top: 1.171875rem;
        }
    }

    &__topbar {
        display: flex;
        flex-wrap: wrap;
        width: auto;
        align-items: center;

        @include media-breakpoint-up("lg") {
            width: 100%;
        }

        .navbar-nav {
            // flex-direction: column;

            // @include media-breakpoint-down("lg") {
            flex-direction: row;
            // }
        }
    }

    &__menubar-mob {
        width: 100%;
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
        color: rgba($black, 0.5);
        padding-left: $spacer / 2;
        padding-right: $spacer / 2;
    }
}
</style>
