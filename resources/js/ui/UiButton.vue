<template lang="html">
    <div
        class="ui-button"
        v-if="hasContainer"
        :class="[
            displayClass,
            alignSelfClass,
            marginClass,
        ]">
        <button
            class="btn ui-button__content"
            :class="[
                colorClass,
                blockClass,
            ]"
            :disabled="disable"
            @click.prevent="clicked">
            {{ title }}
            <slot></slot>
        </button>
    </div>
    <button
        v-else
        class="btn ui-button"
        :class="[
            colorClass,
            blockClass,
            displayClass,
            noContainerClass,
            alignSelfClass,
            marginClass,
        ]"
        :disabled="disable"
        @click.prevent="clicked">
        {{ title }}
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: 'UiButton',
    props: {
        color: {
            type: String,
            default: null,
        },
        block: {
            type: Boolean,
            default: false,
        },
        disable: {
            type: Boolean,
            default: false,
        },
        isActive: {
            type: Boolean,
            default: false,
        },
        hasContainer: {
            type: Boolean,
            default: true,
        },
        alignSelf: {
            type: String,
            default: null,
        },
        hasMargin: {
            type: Boolean,
            default: true,
        },
        title: {
            type: String,
            default: null,
        },
        display: {
            type: String,
            default: null,
        },
        eventParams: [String, Object, Array, Number],
    },
    data: function () {
        return {
            colorClass: null,
        }
    },
    watch: {
        isActive: function (value) {
            this.setStatus()
        },
    },
    computed: {
        // colorClass: function() {
        //     if (this.color) {
        //         return 'btn-outline-'+this.color
        //     }
        // },
        blockClass: function () {
            if (this.block) {
                return 'btn-block'
            }
        },
        displayClass: function () {
            if (this.display) {
                return 'ui-button--' + this.display
            }
        },
        noContainerClass: function () {
            if (!this.hasContainer) {
                return 'ui-button--no-container'
            }
        },
        alignSelfClass: function () {
            if (this.alignSelf == 'start') {
                return 'ui-button--align-self-start'
            }
        },
        marginClass: function () {
            if (!this.hasMargin) {
                return 'ui-button--no-margin'
            }
        },
    },
    methods: {
        clicked: function () {
            if (this.disable == false) {
                this.$emit('click', this.eventParams)
            }
        },
        setStatus: function () {
            if (!this.disable && this.isActive) {
                this.colorClass = 'btn-' + this.color
            }
            else {
                this.colorClass = 'btn-outline-' + this.color
            }
        }
    },
    created: function () {
        this.setStatus()
    },

}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-button {
    margin-bottom: $spacer;

    &__content {
        text-transform: uppercase;
        font-size: $font-size-base * 0.75;
        font-weight: 600;
        letter-spacing: 3px;
    }

    &--no-container {
        text-transform: uppercase;
        font-size: $font-size-base * 0.75;
        font-weight: 600;
        letter-spacing: 3px;
    }

    &--inline-block {
        display: inline-block;
    }

    &--align-self-start {
        align-self: flex-start;
    }

    &--no-margin {
        margin-bottom: 0;
    }
}
</style>
