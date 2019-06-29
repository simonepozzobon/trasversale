<template>
<div
    class="module-manager"
    :class="[isAdminClass]"
    @click="selected"
>
    <ui-title
        v-if="module.type === 'title'"
        ref="title"
        :title="content.hasOwnProperty('content') ? content.content : 'titolo'"
        :is-column="content.hasOwnProperty('isColumn') ? content.isColumn : null"
        :uppercase="content.hasOwnProperty('uppercase') ? content.uppercase : null"
        :color="content.hasOwnProperty('color') ? content.color : null"
        :font-size="content.hasOwnProperty('fontSize') ? content.fontSize : null"
    />
    <admin-ui-image
        v-else-if="module.type === 'image'"
        :src="content.src"
        :alt="content.alt"
    />
    <ui-paragraph
        v-else-if="module.type === 'paragraph'"
        :content="content.content"
        :color="content.hasOwnProperty('color') ? content.color : null"
        :bgColor="content.hasOwnProperty('bg_color') ? content.bg_color : null"
    />
    <ui-button
        v-else-if="module.type === 'button'"
        :text="content.text"
    />
    <ui-team
        v-else-if="module.type === 'team'"
        :people="content | filterTeamPeople"
        :grid-col="content | filterTeamColSize"
    />
    <!-- <admin-packery-container
        v-else-if="module.type === 'grid'"
        :items="content.blocks"
        :gutter="8"
        :units="12"
    /> -->
    <ui-packery-grid
        v-else-if="module.type === 'grid'"
        :items="content.blocks"
    />

    <ui-simple-grid
        v-else-if="showSimpleGrid"
        :blocks="content.blocks"
    />
    <admin-ui-module-row
        v-else-if="module.type === 'row'"
        :columns="content"
        :is-open="isOpen"
        @save-row="saveRow"
        @add-component="addComponent"
        @delete-sub-component="deleteSubComponent"
        @update-size="updateSize"
    />
    <ui-video
        v-else-if="module.type === 'video'"
        :url="this.content.url"
    />
    <ui-quote
        v-else-if="module.type === 'quote'"
        :quote="content.content"
        :source="content.source"
    />
    <ui-calendar v-else-if="module.type === 'calendar'" />
    <ui-contact-form v-else-if="module.type === 'contact-form'" />
    <ui-map
        v-else-if="module.type === 'map' && content.hasOwnProperty('map')"
        :addresses="content.map.addresses"
        :zoom="content.map.zoom"
        :center="content.map.center"
    />
    <div v-else>
        {{ module }}
    </div>
</div>
</template>

<script>
import {
    clone
}
from '../../Utilities'
export default {
    name: 'AdminModuleManager',
    components: {},
    props: {
        module: {
            type: Object,
            default: function () {}
        },
        isAdmin: {
            type: Boolean,
            default: false,
        },
        isOpen: {
            type: Boolean,
            default: false,
        },
    },
    data: function () {
        return {
            blocks: [],
        }
    },
    watch: {
        module: function (module) {
            // console.log(module);
            this.listener()
        },
    },
    computed: {
        content: function () {
            // console.log('contenuto', this.module.content);
            if (this.module && this.module.hasOwnProperty('content')) {
                return this.module.content
            }
            return {}
        },
        isAdminClass: function () {
            if (this.isAdmin) {
                return 'module-manager--is-admin'
            }
        },
        showPackery: function () {
            // v-else-if="module.type == 'grid' && this.content.type == 'packery' && this.content.blocks.length > 0"
            if (this.module.type == 'grid') {
                if (this.content.hasOwnProperty('type') && this.content.hasOwnProperty('blocks')) {
                    if (this.content.type == 'packery' && this.content.blocks.length > 0) {
                        // console.log('packery');
                        return true
                    }
                }
            }
            return false
        },
        showSimpleGrid: function () {
            // v-else-if="module.type == 'grid' && this.content.type == 'packery' && this.content.blocks.length > 0"
            if (this.module.type == 'grid') {
                if (this.content.hasOwnProperty('type') && this.content.hasOwnProperty('blocks')) {
                    if (this.content.type == 'simple' && this.content.blocks.length > 0) {
                        // console.log('simple');
                        return true
                    }
                }
            }
            return false
        },
    },
    methods: {
        listener: function () {
            console.log('modulo cambiato');
            // if (this.module.type === 'row') {
            //     console.log(this.content);
            // }
            // console.log(this.content.hasOwnProperty('type') && this.content.type == 'packery');
            // console.log('content.type', this.content.type);
            // console.log('blocks.type', this.content.blocks);
            // console.log('type', this.module.type);
        },
        updateSize: function (data) {
            // console.log('dimensione cambiata');
            this.$emit('update-size', data)
        },
        selected: function () {
            if (this.isAdmin) {
                this.$emit('selected', this.module)
            }
        },
        addComponent: function (column, component) {
            this.$emit('add-component', column, component)
        },
        saveRow: function (columns) {
            // console.log('save module', this.module);
            // this.$emit('save-module', this.module)
            // console.log('salvataggio dall AdminModuleManager', this.module);
            this.$emit('save-module', columns)
        },
        deleteSubComponent: function (id, isNew, uuid) {
            this.$emit('delete', id, isNew, uuid)
        }
    },
    filters: {
        filterTeamPeople: function (content) {
            if (content.hasOwnProperty('team')) {
                return content.team.people
            }
            else if (content.hasOwnProperty('people')) {
                return content.people
            }
            else {
                return []
            }
        },
        filterTeamColSize: function (content) {
            if (content.hasOwnProperty('team')) {
                return content.team.gridCol
            }
            else if (content.hasOwnProperty('gridCol')) {
                return content.gridCol
            }
            else {
                return 2
            }
        }
    },
    beforeCreate: function () {
        // this.$options.components.AdminPackeryContainer = require('./modulemanager/AdminPackeryContainer.vue').default
        this.$options.components.AdminUiImage = require('./modulemanager/AdminUiImage.vue').default
        this.$options.components.AdminUiModuleRow = require('./modulemanager/AdminUiModuleRow.vue').default
        this.$options.components.UiButton = require('../../ui/UiButton.vue').default
        this.$options.components.UiCalendar = require('../../ui/UiCalendar.vue').default
        this.$options.components.UiContactForm = require('../../ui/UiContactForm.vue').default
        this.$options.components.UiMap = require('../../ui/UiMap.vue').default
        this.$options.components.UiPackeryGrid = require('../../ui/UiPackeryGrid.vue').default
        this.$options.components.UiParagraph = require('../../ui/UiParagraph.vue').default
        this.$options.components.UiQuote = require('../../ui/UiQuote.vue').default
        this.$options.components.UiSimpleGrid = require('../../ui/UiSimpleGrid.vue').default
        this.$options.components.UiTeam = require('../../ui/UiTeam.vue').default
        this.$options.components.UiTitle = require('../../ui/UiTitle.vue').default
        this.$options.components.UiVideo = require('../../ui/UiVideo.vue').default
    },
    created: function () {
        // console.log('init', clone(this.module));
        console.log('modulo', this.module);
    },
    mounted: function () {
        if (this.module.type == 'title') {
            if (this.$refs.title) {
                let height = this.$refs.title.$el.offsetHeight + 'px'
                this.$root.sidebarPaddingTop = height
                this.$emit('title', height)
            }
        }
    },
    beforeDestroy: function () {
        if (this.module.type == 'title') {
            this.$root.sidebarPaddingTop = false
            this.$emit('title', false)
        }
    }
}
</script>

<style lang="scss">
@import '~styles/shared';

.module-manager {
    &--is-admin {
        cursor: pointer;
        padding: $spacer;
        @include border-radius(10px);
        background-color: rgba($white, .3);
        transition: $transition-base;
    }

    &--is-admin:hover {
        background-color: rgba($black, .1);
        transition: $transition-base;
    }
}
</style>
