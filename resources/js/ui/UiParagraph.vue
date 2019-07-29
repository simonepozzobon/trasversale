<template lang="html">
    <div
        class="ui-paragraph"
        :class="[
            sizeClass,
            alignClass,
            noPaddingClass,
            padding,
            fullWidthClass,
            paddingTopClass
        ]">
        <p
            v-html="content"
            class="ui-paragraph__content"
            ref="paragraph">
            <slot></slot>
        </p>
    </div>
</template>

<script>
export default {
    name: 'UiParagraph',
    props: {
        align: {
            type: String,
            default: null,
        },
        hasPadding: {
            type: Boolean,
            default: true,
        },
        paddingTop: {
            type: Boolean,
            default: true,
        },
        padding: {
            type: String,
            default: null,
        },
        color: {
            type: String,
            default: null,
        },
        bgColor: {
            type: String,
            default: null,
        },
        size: {
            type: String,
            default: null,
        },
        fullWidth: {
            type: Boolean,
            default: false,
        },
        content: {
            type: String,
            default: null,
        },
    },
    watch: {
        color: function (color) {
            this.setColor()
        },
        bgColor: function (bgColor) {
            this.setBgColor()
        },
    },
    computed: {
        alignClass: function () {
            if (this.align == 'center') {
                return 'ui-paragraph--align-center'
            }
            else if (this.align == 'justify') {
                return 'ui-paragraph--align-justify'
            }
            return null
        },
        noPaddingClass: function () {
            if (!this.hasPadding) {
                return 'ui-paragraph--no-padding'
            }
        },
        paddingTopClass: function () {
            if (!this.paddingTop) {
                return 'ui-paragraph--no-padding-top'
            }
        },
        // colorClass: function () {
        //     if (this.color) {
        //         return 'text-' + this.color
        //     }
        // },
        sizeClass: function () {
            if (this.size) {
                return 'ui-paragraph--size-' + this.size
            }
        },
        fullWidthClass: function () {
            if (this.fullWidth) {
                return 'ui-paragraph--full-width'
            }
        },
    },
    methods: {
        setColor: function () {
            if (this.color) {
                this.$refs.paragraph.style.color = this.color
            }
        },
        setBgColor: function () {
            if (this.bgColor) {
                this.$refs.paragraph.style.backgroundColor = this.bgColor
            }
        }
    },
    mounted: function () {
        this.setColor()
        this.setBgColor()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-paragraph {
    $self: &;
    // padding-left: $spacer * 4;
    // padding-right: $spacer * 4;
    // margin-bottom: $spacer * 1.5;
    z-index: 1;

    &__content {
        padding: $spacer * $line-height-base 0 0;
        margin: 0;
    }

    &--align-center {
        text-align: center;
    }

    &--no-padding &__content {
        padding: 0;
    }

    &--size-small {
        font-size: $font-size-sm;
    }

    &--align-justify {
        text-align: justify;
    }

    &--full-width {
        width: 100%;
    }

    &--no-padding-top &__content {
        padding-top: 0 !important;
    }
}
</style>
