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
        :padding-top="content.hasOwnProperty('padding_top') && content.padding_top == false ? false : true"
    />

    <ui-button
        v-else-if="module.type == 'button'"
        :text="content.text"
    />

    <ui-team
        v-else-if="module.type == 'team'"
        :people="content.team.people"
        :grid-col="content.team.gridCol"
    />

    <ui-simple-grid
        v-else-if="module && module.type === 'grid' && content.type === 'simple'"
        :blocks="content.blocks | formatblocks"
        :options="this.options"
    />

    <ui-packery-grid
        v-else-if="module && module.type === 'grid' && content.type === 'packery'"
        :blocks="content.blocks"
    />


    <ui-spacer
        v-else-if="module.type === 'spacer' && content && content.hasOwnProperty('spacer')"
        :height="content.spacer"
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
        :source-link="content.hasOwnProperty('source_link') ? content.source_link : null"
    />

    <ui-calendar v-else-if="module.type === 'calendar'" />

    <ui-contact-form
        v-else-if="module.type === 'contact-form'"
        :has-big-btn="content.hasOwnProperty('full_width_btn') && content.full_width_btn == false ? false : true"
    />

    <ui-map
        v-else-if="module.type === 'map' && content.hasOwnProperty('map')"
        :addresses="content.map.addresses"
        :zoom="content.map.zoom"
        :center="content.map.center"
    />

    <ui-uploaded-pdf
        v-else-if="module.type === 'uploadpdf'"
        :src="content.hasOwnProperty('uploadpdf') && content.uploadpdf ? content.uploadpdf.src : null"
        :name="content.hasOwnProperty('uploadpdf') && content.uploadpdf ? content.uploadpdf.original_name : 'filename.pdf'"
        :color="content.hasOwnProperty('color') && content.color ? content.color : null"
    />


    <div v-else>
        {{ module }}
    </div>
</div>
</template>

<script>
import {
    Uuid
}
from '../Utilities'

export default {
    name: 'ModuleManager',
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
    },
    data: function () {
        return {}
    },
    computed: {
        options: function () {
            if (this.content && this.content.options) {
                return JSON.parse(this.content.options)
            }
            return {}
        },
        content: function () {
            return JSON.parse(this.module.content)
        },
        isAdminClass: function () {
            if (this.isAdmin) {
                return 'module-manager--is-admin'
            }
        }
    },
    methods: {
        selected: function () {
            if (this.isAdmin) {
                this.$emit('selected', this.module)
            }
        },
        debug: function () {
            if (this.module.type == 'paragraph') {
                console.log(this.content);
            }
        },
    },
    filters: {
        formatblocks: function (blocks) {
            return blocks.map(block => {
                return {
                    ...block,
                    uuid: Uuid.get(),
                }
            })
        }
    },
    beforeCreate: function () {
        this.$options.components.UiButton = require('../ui/UiButton.vue').default
        this.$options.components.UiCalendar = require('../ui/UiCalendar.vue').default
        this.$options.components.UiContactForm = require('../ui/UiContactForm.vue').default
        this.$options.components.UiImage = require('../ui/UiImage.vue').default
        this.$options.components.UiMap = require('../ui/UiMap.vue').default
        this.$options.components.UiModuleRow = require('../ui/UiModuleRow.vue').default
        this.$options.components.UiPackeryGrid = require('../ui/UiPackeryGrid.vue').default
        this.$options.components.UiParagraph = require('../ui/UiParagraph.vue').default
        this.$options.components.UiQuote = require('../ui/UiQuote.vue').default
        this.$options.components.UiSimpleGrid = require('../ui/UiSimpleGrid.vue').default
        this.$options.components.UiSpacer = require('../ui/UiSpacer.vue').default
        this.$options.components.UiTeam = require('../ui/UiTeam.vue').default
        this.$options.components.UiTitle = require('../ui/UiTitle.vue').default
        this.$options.components.UiUploadedPdf = require('../ui/UiUploadedPdf.vue').default
        this.$options.components.UiVideo = require('../ui/UiVideo.vue').default
    },
    mounted: function () {
        if (this.module.type == 'title') {
            let height = this.$refs.title.$el.offsetHeight + 'px'
            this.$root.sidebarPaddingTop = height
            this.$emit('title', height)
        }
        else if (this.module.type === 'grid') {
            // console.log(this.module);
        }
        // this.$nextTick(() => {
        //     this.debug()
        // })

    },
    beforeDestroy: function () {
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
