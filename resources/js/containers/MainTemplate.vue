<template>
<div class="main">
    <div class="container mb-4">
        <main-menu :pages="parsedPages" />
        <div>
            <div class="row custom-blue main-content">
                <div class="col-md-9 custom-green main-content__content">
                    <router-view />
                </div>
                <div
                    class="col-md-3 custom-red main-content__sidebar"
                    ref="sidebar"
                    v-if="this.$root.sidebar"
                >
                    <module-manager
                        v-for="module in this.modules"
                        :key="module.id"
                        :module="module"
                    />
                </div>
            </div>
        </div>
    </div>
    <main-footer />
</div>
</template>

<script>
// import Partners from '../contents/Partners'
import MainMenu from './MainMenu.vue'
import MainFooter from './MainFooter.vue'
import ModuleManager from './ModuleManager.vue'
import {
    sortModules
}
from '../Utilities'
// import {
//     UiCalendar,
//     UiPartner,
//     UiSidebarImage,
//     UiSidebarParagraph,
//     UiSidebarTitle,
//     UiTitle,
// }
// from '../ui'

export default {
    name: 'MainTemplate',
    components: {
        MainMenu,
        MainFooter,
        ModuleManager,
        // UiCalendar,
        // UiPartner,
        // UiSidebarImage,
        // UiSidebarParagraph,
        // UiSidebarTitle,
        // UiTitle,
    },
    props: {
        pages: {
            type: String,
            default: function () {}
        }
    },
    data: function () {
        return {
            // partners: Partners,
            modules: []
        }
    },
    watch: {
        '$root.sidebarPaddingTop': function (h) {
            // if (h) {
            //     if (this.$refs.sidebar) {
            //         this.$refs.sidebar.style.paddingTop = h
            //     }
            // }
            // else {
            //     if (this.$refs.sidebar) {
            //         delete this.$refs.sidebar.style.paddingTop
            //     }
            // }
        },
        '$root.sidebar': {
            handler: function (sidebar) {
                if (sidebar && sidebar.hasOwnProperty('modules')) {
                    this.modules = Object.assign([], sortModules(sidebar.modules))
                }
                else {
                    this.modules = []
                }
            },
            deep: true,
        },
    },
    computed: {
        parsedPages: function () {
            if (this.pages) {
                const pages = JSON.parse(this.pages)
                    .filter(page => page.title !== 'home')
                return pages
            }
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
$debug-opacity: 0 !default;

.main {
    min-height: 100vh;
    max-width: 100vw;
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.main-content {
    &__content {
        padding-top: $spacer;
    }

    &__sidebar {
        padding-top: $spacer;
    }
}

.custom-blue {
    background-color: rgba($blue, $debug-opacity);
}

.custom-green {
    background-color: rgba($teal, $debug-opacity);
}

.custom-red {
    background-color: rgba($red, $debug-opacity);
}
</style>
