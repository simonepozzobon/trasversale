<template lang="html">
    <div
        class="module-manager"
        :class="[
            isAdminClass,
        ]"
        @click="selected">

        <ui-title
            v-if="module.type == 'title'"
            ref="title"
            :title="content.hasOwnProperty('content') ? content.content : 'titolo'"
            :is-column="content.hasOwnProperty('isColumn') ? content.isColumn : null "
            :uppercase="content.hasOwnProperty('uppercase') ? content.uppercase : null "
            :color="content.hasOwnProperty('color') ? content.color : null "
            :font-size="content.hasOwnProperty('fontSize') ? content.fontSize : null"/>

        <admin-ui-image
            v-else-if="module.type == 'image'"
            :src="content.src"
            :alt="content.alt"/>

        <ui-paragraph
            v-else-if="module.type == 'paragraph'"
            :content="content.content"/>

        <ui-button
            v-else-if="module.type == 'button'"
            :text="content.text"/>

        <ui-team
            v-else-if="module.type == 'team'"
            :people="content.people"
            :grid-col="content.gridCol"/>

        <admin-packery-container
            v-else-if="showPackery"
            :items="content.blocks"
            :gutter="8"
            :units="12"/>

        <ui-simple-grid
            v-else-if="showSimpleGrid"
            :blocks="content.blocks"/>

        <admin-ui-module-row
            v-else-if="module.type == 'row'"
            :columns="content"
            :is-open="isOpen"
            @update-size="updateSize"/>

        <div v-else>
            {{ module }}
        </div>
    </div>
</template>

<script>

export default {
    name: 'AdminModuleManager',
    components: {
    },
    props: {
        module: {
            type: Object,
            default: function() {}
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
    data: function() {
        return {
            blocks: [],
        }
    },
    watch: {
        module: function(module) {
            this.listener()
        },
    },
    computed: {
        content: function() {
            return JSON.parse(this.module.content)
        },
        isAdminClass: function() {
            if (this.isAdmin) {
                return 'module-manager--is-admin'
            }
        },
        showPackery: function() {
            // v-else-if="module.type == 'grid' && this.content.type == 'packery' && this.content.blocks.length > 0"
            if (this.module.type == 'grid') {
                if (this.content.hasOwnProperty('type') && this.content.hasOwnProperty('blocks')) {
                    if (this.content.type == 'packery' && this.content.blocks.length > 0) {
                        console.log('packery');
                        return true
                    }
                }
            }
            return false
        },
        showSimpleGrid: function() {
            // v-else-if="module.type == 'grid' && this.content.type == 'packery' && this.content.blocks.length > 0"
            if (this.module.type == 'grid') {
                if (this.content.hasOwnProperty('type') && this.content.hasOwnProperty('blocks')) {
                    if (this.content.type == 'simple' && this.content.blocks.length > 0) {
                        console.log('simple');
                        return true
                    }
                }
            }
            return false
        }
    },
    methods: {
        listener: function() {
            if (this.showPackery) {

            }
            // console.log(this.content.hasOwnProperty('type') && this.content.type == 'packery');
            // console.log('content.type', this.content.type);
            // console.log('blocks.type', this.content.blocks);
        },
        updateSize: function(data) {
            this.$emit('update-size', data)
        },
        selected: function() {
            if (this.isAdmin) {
                this.$emit('selected', this.module)
            }
        }
    },
    beforeCreate: function() {
        this.$options.components.AdminUiModuleRow = require('./modulemanager/AdminUiModuleRow.vue').default
        this.$options.components.UiParagraph = require('../../ui/UiParagraph.vue').default
        this.$options.components.UiButton = require('../../ui/UiButton.vue').default
        this.$options.components.AdminUiImage = require('./modulemanager/AdminUiImage.vue').default
        this.$options.components.AdminPackeryContainer = require('./modulemanager/AdminPackeryContainer.vue').default
        this.$options.components.UiTitle = require('../../ui/UiTitle.vue').default
        this.$options.components.UiTeam = require('../../ui/UiTeam.vue').default
        this.$options.components.UiSimpleGrid = require('../../ui/UiSimpleGrid.vue').default
    },
    mounted: function() {
        // console.log(this.module);
        if (this.module.type == 'title') {
            let height = this.$refs.title.$el.offsetHeight + 'px'
            this.$root.sidebarPaddingTop = height
            this.$emit('title', height)
        }
    },
    beforeDestroy: function() {
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
