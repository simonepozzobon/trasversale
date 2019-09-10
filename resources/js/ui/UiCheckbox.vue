<template lang="html">
    <div class="ui-checkbox">
        <div class="ui-checkbox__icon">
            <radio-check
                v-if="value"
                width="32px"
                height="32px"
                color="primary"
                :hoverable="true"
                hover-color="danger"
                @click="clicked"/>
            <radio-uncheck
                v-else
                width="32px"
                height="32px"
                :hoverable="true"
                hover-color="primary"
                @click="clicked"/>
        </div>
        <div class="ui-checkbox__text" :class="textClass">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import {
    RadioCheck,
    RadioUncheck
}
from '../icons'

export default {
    name: 'UiCheckbox',
    components: {
        RadioCheck,
        RadioUncheck
    },
    props: {
        value: {
            type: Boolean,
            default: false,
        },
        textClass: {
            type: String,
            default: null,
        }
    },
    methods: {
        clicked: function () {
            this.$emit('click', !this.value)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-checkbox {
    display: flex;
    align-items: flex-start;
    // justify-content: flex-start;

    &__text {
        padding-left: $spacer / 2;
        font-size: $font-size-sm;
    }
}
</style>
