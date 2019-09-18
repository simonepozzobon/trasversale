<template>
<div
    class="new-page-template"
    :class="[hasTitleClass]"
>
    <notifications-container :toasts="notifications" />
    <div class="new-page-template__container container">
        <div
            class="new-page-template__row row"
            v-if="hasSlot"
        >
            <div class="new-page-template__header col-12">
                <slot></slot>
            </div>
        </div>
        <div class="new-page-template__row row">
            <new-main-template
                ref="main"
                :active="isMainActive"
                :content-height="mainHeight"
                :title="title"
                :has-title="hasTitle"
                :model="model"
                :modelIdx="modelIdx"
                :modules="modules"
                :custom-save="customSave"
                :is-post="isPost"
                @notify="notify"
                @edit-main="toggleActive"
                @before-save="beforeSave"
                @delete-all="deleteAll"
            />
            <new-sidebar-template
                ref="sidebar"
                class="col-3"
                model="App\Sidebar"
                :active="!isMainActive"
                :content-height="sideHeight"
                :model-idx="sidebarIdx"
                :modules="sidebarModules"
                :sidebarable_id="modelIdx"
                :sidebarable_type="model"
                :is-post="isPost"
                @saved="savedSidebar"
                @deleted="deletedSidebar"
                @sidebar-created="sidebarCreated"
                @edit-sidebar="toggleActive"
                @notify="notify"
                @before-save="beforeSave"
                @delete-all="deleteAll"
            />
        </div>
    </div>
</div>
</template>

<script>
import ComponentsList from '../components/ComponentsList.vue'
import draggable from 'vuedraggable'
import DynamicParams from '../DynamicParams'
import EditPanel from '../components/EditPanel.vue'
import ModuleContainer from './ModuleContainer.vue'
import NewSidebarTemplate from './NewSidebarTemplate.vue'
import NewMainTemplate from './NewMainTemplate.vue'
import NotificationsContainer from './NotificationsContainer.vue'

import {
    checkDuplicateInObject,
    SizeUtil,
    Uuid,
}
from '../../Utilities'

const orderBy = require('lodash.orderby')
const elementResizeDetectorMaker = require('element-resize-detector')
const debounce = require('lodash.debounce')

export default {
    name: 'NewPageTemplate',
    components: {
        ComponentsList,
        draggable,
        EditPanel,
        ModuleContainer,
        NewSidebarTemplate,
        NewMainTemplate,
        NotificationsContainer,
    },
    props: {
        hasSlot: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            default: 'titolo'
        },
        model: {
            type: String,
            default: null,
        },
        modelIdx: {
            type: Number,
            default: 0,
        },
        modules: {
            type: Array,
            default: function () {
                return []
            },
        },
        hasSidebar: {
            type: Boolean,
            default: false,
        },
        hasTitle: {
            type: Boolean,
            default: true,
        },
        customSave: {
            type: String,
            default: 'Salva Pagina'
        },
        isPost: {
            type: Boolean,
            default: false,
        },
        sidebarIdx: {
            type: Number,
            default: 0,
        },
        sidebarModules: {
            type: Array,
            default: function () {
                return []
            },
        },
    },
    data: function () {
        return {
            component: null,
            moduleType: null,
            notifications: [],
            isMainActive: true,
            mainHeight: 0,
            sideHeight: 0,
            observer: null,
        }
    },
    computed: {
        contentClass: function () {
            if (this.hasSidebar) {
                return 'col-md-9'
            }
        },
        hasTitleClass: function () {
            if (!this.hasTitle) {
                return 'new-page-template--no-title'
            }
        }
    },
    watch: {
        '$route.params': function (params) {
            // console.log('params cambiato', params);
        },
        modules: function (modules) {
            // this.debug()
            // console.log('moduli', modules);
        }
    },
    methods: {
        initAnim: function () {
            if (!this.anim) {
                let side = this.$refs.sidebar.$el
                let main = this.$refs.main.$el

                let col3 = (3 * 100 / 12) + '%'
                let col4 = (4 * 100 / 12) + '%'
                let col8 = (8 * 100 / 12) + '%'
                let col9 = (9 * 100 / 12) + '%'

                this.anim = new TimelineMax({
                    paused: true,
                    yoyo: true
                })

                this.anim.addLabel('start', '+=0')

                this.anim.fromTo(side, .6, {
                    css: {
                        flex: '0 0 ' + col3,
                        maxWidth: col3,
                    }
                }, {
                    css: {
                        flex: '0 0 ' + col8,
                        maxWidth: col8,
                    },
                    ease: Power4.easeInOut
                }, 'start')


                this.anim.fromTo(main, .6, {
                    css: {
                        flex: '0 0 ' + col9,
                        maxWidth: col9,
                    }
                }, {
                    css: {
                        flex: '0 0 ' + col4,
                        maxWidth: col4,
                    },
                    ease: Power4.easeInOut
                }, 'start')

                this.anim.progress(1).progress(0)
            }
            // this.debug()
        },
        show: function () {
            if (this.anim) {
                this.anim.play()
            }
        },
        debug: function () {
            // if (this.modelIdx !== 0) {
            this.$nextTick(() => {
                this.$refs.main.newComponent('uploadpdf')
            })
            // }
        },
        hide: function () {
            if (this.anim) {
                this.anim.reverse()
            }
        },
        toggleActive: function () {
            if (this.isMainActive) {
                this.show()
                this.isMainActive = false
            }
            else {
                this.hide()
                this.isMainActive = true
            }
        },
        setModule: function (module) {
            console.log('deprecata');
        },
        deleted: function (module) {
            this.moduleType = null
            this.$emit('deleted', module)
        },
        savedSidebar: function (module) {
            let idx = this.sidebarModules.findIndex(item => item.id == module.id)
            if (idx > -1) {
                this.sidebarModules.splice(idx, 1, module)
            }
            else {
                this.sidebarModules.push(module)
            }
        },
        deletedSidebar: function (module) {
            let idx = this.sidebarModules.findIndex(item => item.id == module.id)
            if (idx > -1) {
                this.sidebarModules.splice(idx, 1)
            }
        },
        sidebarCreated: function (sidebar) {
            this.sidebarIdx = sidebar.id
        },
        notify: function (notification) {
            this.notifications.push(notification)
        },
        addListenerResize: function () {
            // console.log('resize');
            this.$nextTick(() => {
                this.initAnim()
            })
            // let sidebarContainer = this.$refs.sidebar
            // let mainContainer = this.$refs.main
            //
            // if (sidebarContainer && mainContainer) {
            //     let sidebar = sidebarContainer.$refs.content
            //     let main = mainContainer.$refs.content
            //
            //     if (sidebar && main) {
            //         let mainHeight = SizeUtil.get(main).h
            //         let sideHeight = SizeUtil.get(sidebar).h
            //
            //         let initialHeight = Math.max(mainHeight, sideHeight)
            //         this.mainHeight = initialHeight
            //         this.sideHeight = initialHeight
            //
            //         this.observer.listenTo(sidebar, debounce(element => {
            //             let height = SizeUtil.get(element).h
            //             if (height != this.sideHeight && height > this.mainHeight) {
            //                 this.sideHeight = height
            //             }
            //             // console.log('fuori side', this.sideHeight);
            //         }, 150))
            //
            //         this.observer.listenTo(main, debounce(element => {
            //             let height = SizeUtil.get(element).h
            //             if (height != this.mainHeight && height > this.sideHeight) {
            //                 this.sideHeight = height
            //             }
            //             // console.log('fuori main', height);
            //         }, 150))
            //     }
            // }
        },
        beforeSave: function (ref) {
            this.$emit('before-save', ref)
        },
        deleteAll: function () {
            this.$emit('delete-all')
        },
    },
    created: function () {
        this.observer = elementResizeDetectorMaker({
            strategy: 'scroll'
        })

    },
    mounted: function () {
        this.addListenerResize()
        // console.log('new page template montato');
    },
}
</script>

<style lang="scss">
@import '~styles/adminshared';
$opacity-test: 0.6 !default;

.new-page-template {
    padding: $spacer * 2;
    min-height: 100vh;
    max-width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;

    background: linear-gradient(45deg, rgba($green, $opacity-test), rgba($teal, $opacity-test), rgba($cyan, $opacity-test), rgba($blue, $opacity-test), rgba($indigo, $opacity-test), rgba($purple, $opacity-test), rgba($pink, $opacity-test), rgba($red, $opacity-test), rgba($orange, $opacity-test), rgba($yellow, $opacity-test),);
    background-size: 800% 800%;
    animation: Gradient 360s ease infinite;

    &__content {
        margin-top: $spacer * 4;
        margin-bottom: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }

    &__header {
        margin-top: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }

    &__head {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
    }

    &__footer {
        margin-top: $spacer * 4;
        margin-bottom: $spacer * 4;
        padding: $spacer * 2;
        @include gradient-directional($gray-300, $light, 135deg);
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    &--no-title &__head {
        justify-content: center;
    }
}

@keyframes Gradient {
    0% {
        background-position: 0 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0 50%;
    }
}
</style>
