<template lang="html">
    <a
        :href="url"
        :target="target"
        class="ui-link"
        :class="[
            btnClass,
            colorClass,
            blockClass,
            spacingClass,
            marginClass,
            disabledClass
        ]"
        @click="clicked">
        {{ text }}
        <slot></slot>
    </a>
</template>

<script>
export default {
    name: 'UiLink',
    props: {
        color: {
            type: String,
            default: null,
        },
        block: {
            type: Boolean,
            default: false,
        },
        isDisabled: {
            type: Boolean,
            default: false
        },
        url: {
            type: String,
            default: '#'
        },
        target: {
            type: String,
            default: '_self'
        },
        text: {
            type: String,
            default: null
        },
        isSimple: {
            type: Boolean,
            default: false,
        },
        hasSpace: {
            type: Boolean,
            default: true,
        },
        hasMargin: {
            type: Boolean,
            default: true,
        },
        hasUppercase: {
            type: Boolean,
            default: true,
        },
        prevent: {
            type: Boolean,
            default: false,
        },
        clickParams: [String, Array, Object, Number],
    },
    computed: {
        colorClass: function() {
            if (this.color && !this.isSimple) {
                return 'btn-'+this.color
            } else if (this.color && this.isSimple) {
                return 'text-'+this.color
            }
        },
        blockClass: function() {
            if (this.block) {
                return 'btn-block'
            }
        },
        btnClass: function() {
            if (!this.isSimple) {
                return 'btn'
            }
        },
        spacingClass: function() {
            if (!this.hasSpace) {
                return 'ui-link--no-spacing'
            }
        },
        marginClass: function() {
            if (!this.hasMargin) {
                return 'ui-link--no-margin'
            }
        },
        disabledClass: function() {
            if (this.isDisabled) {
                return 'disabled'
            }
        },
        simpleClass: function() {
            if (this.isSimple) {
                return 'ui-link--is-simple'
            }
        }
    },
    methods: {
        clicked: function(event) {
            if (this.prevent) {
                event.preventDefault()
            }

            this.$emit('click', this.clickParams)
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-link {
    $self: &;
    margin-bottom: $spacer;
    z-index: 1;
    text-transform: uppercase;
    font-size: $font-size-base;
    font-weight: 600;
    // letter-spacing: 3px;

    &--no-spacing {
        letter-spacing: normal;
    }

    &--no-margin {
        margin-bottom: 0;
    }

    &--is-simple {
        text-transform: none;
    }
}
</style>
