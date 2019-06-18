<template>
<div
    class="ui-title"
    :class="[
        fontSizeClass,
        isColumnClass,
        uppercaseClass,
        hasPaddingClass,
    ]"
    ref="title"
>
    <component
        :is="tag"
        class="ui-title__content"
        :class="colorClass"
    >
        {{ title }}
    </component>
</div>
</template>

<script>
export default {
    name: 'UiTitle',
    props: {
        title: {
            type: String,
            default: 'Titolo'
        },
        tag: {
            type: String,
            default: 'h1'
        },
        fontSize: {
            type: String,
            default: null,
        },
        isColumn: {
            type: Boolean,
            default: false,
        },
        uppercase: {
            type: Boolean,
            default: false,
        },
        color: {
            type: String,
            default: null
        },
        hasPadding: {
            type: Boolean,
            default: true,
        },
    },
    watch: {
        color: function (color) {
            this.setColor()
        },
    },
    computed: {
        fontSizeClass: function () {
            if (this.fontSize) {
                return 'ui-title--' + this.fontSize
            }
        },
        isColumnClass: function () {
            if (this.isColumn) {
                return 'ui-title--is-column'
            }
        },
        uppercaseClass: function () {
            if (this.uppercase) {
                return 'ui-title--uppercase'
            }
        },
        colorClass: function () {
            // if (this.color) {
            //     return 'text-' + this.color
            // }
        },
        hasPaddingClass: function () {
            if (!this.hasPadding) {
                return 'ui-title--no-padding'
            }
        },
    },
    methods: {
        setColor: function () {
            if (this.color) {
                this.$refs.title.style.color = this.color
            }
        },
    },
    mounted: function () {
        this.setColor()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-title {
    &__content {
        margin-left: 230px;
        font-weight: 800;
        font-size: 2.5rem;
        letter-spacing: 1px;
        margin-bottom: 0;

        // valutare
        // line-height: 1;
    }

    &--h1 &__content {
        font-size: $h1-font-size;
    }
    &--h2 &__content {
        font-size: $h2-font-size;
    }
    &--h3 &__content {
        font-size: $h3-font-size;
    }
    &--h4 &__content {
        font-size: $h4-font-size;
    }
    &--h5 &__content {
        font-size: $h5-font-size;
    }
    &--h6 &__content {
        font-size: $h6-font-size;
    }

    &--is-column &__content {
        padding-top: $spacer;
        margin-left: 0;
    }

    &--uppercase &__content {
        text-transform: uppercase;
    }

    &--no-padding &__content {
        padding: 0;
    }
}
</style>
