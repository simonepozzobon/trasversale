<template>
<div
    class="module-manager"
    :class="[
            isAdminClass,
        ]"
    @click="selected"
>

    <ui-title
        v-if="module.type == 'title'"
        ref="title"
        :title="content.content"
        :is-column="content.hasOwnProperty('isColumn') ? content.isColumn : null "
        :uppercase="content.hasOwnProperty('uppercase') ? content.uppercase : null "
        :color="content.hasOwnProperty('color') ? content.color : null "
        :font-size="content.hasOwnProperty('fontSize') ? content.fontSize : null"
    />

    <ui-image
        v-else-if="module.type == 'image'"
        :src="content.src"
        :alt="content.alt"
    />

    <ui-paragraph
        v-else-if="module.type == 'paragraph'"
        :content="content.content"
    />

    <ui-button
        v-else-if="module.type == 'button'"
        :text="content.text"
    />

    <ui-team
        v-else-if="module.type == 'team'"
        :people="content.people"
        :grid-col="content.gridCol"
    />

    <ui-packery-container
        v-else-if="module.type == 'grid' && this.content.type == 'packery' && this.content.blocks.length > 0"
        :items="this.content.blocks"
        :gutter="8"
        :units="12"
    />

    <ui-simple-grid
        v-else-if="module.type == 'grid' && this.content.type == 'simple' && this.content.blocks.length > 0"
        :blocks="this.content.blocks"
    />

    <ui-module-row
        v-else-if="module.type == 'row'"
        :columns="this.content"
    />

    <ui-video
        v-else-if="module.type == 'video'"
        :url="this.content.url"
    />

    <ui-quote
        v-else-if="module.type === 'quote'"
        :quote="content.content"
        :source="content.source"
    />

    <ui-calendar v-else-if="module.type === 'calendar'" />

    <div v-else>
        {{ module }}
    </div>
</div>
</template>

<script>
export default {
    name: 'ModuleManager',
    components: {},
    props: {
        module: {
            type: Object,
            default: function() {}
        },
        isAdmin: {
            type: Boolean,
            default: false,
        },
    },
    data: function() {
        return {}
    },
    computed: {
        content: function() {
            return JSON.parse(this.module.content)
        },
        isAdminClass: function() {
            if (this.isAdmin) {
                return 'module-manager--is-admin'
            }
        }
    },
    methods: {
        selected: function() {
            if (this.isAdmin) {
                this.$emit('selected', this.module)
            }
        }
    },
    beforeCreate: function() {
        this.$options.components.UiModuleRow = require('../ui/UiModuleRow.vue')
            .default
        this.$options.components.UiParagraph = require('../ui/UiParagraph.vue')
            .default
        this.$options.components.UiButton = require('../ui/UiButton.vue')
            .default
        this.$options.components.UiImage = require('../ui/UiImage.vue')
            .default
        this.$options.components.UiPackeryContainer = require('../ui/UiPackeryContainer.vue')
            .default
        this.$options.components.UiTitle = require('../ui/UiTitle.vue')
            .default
        this.$options.components.UiTeam = require('../ui/UiTeam.vue')
            .default
        this.$options.components.UiSimpleGrid = require('../ui/UiSimpleGrid.vue')
            .default
        this.$options.components.UiVideo = require('../ui/UiVideo.vue')
            .default
        this.$options.components.UiQuote = require('../ui/UiQuote.vue')
            .default
        this.$options.components.UiCalendar = require('../ui/UiCalendar.vue')
            .default
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

<style lang="scss" scoped>
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
