<template lang="html">
    <div class="ui-checkbox">
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
</template>

<script>
import { RadioCheck, RadioUncheck } from '../icons'

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
        }
    },
    methods: {
        clicked: function() {
            this.$emit('click', !this.value)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
