<template>
<div class="form-group row">
    <label
        :for="uuid"
        class="col-md-3"
    >
        {{ label }}
    </label>
    <div class="form-group col-md-9">
        <span class="switch">
            <input
                type="checkbox"
                class="switch"
                :id="uuid"
                v-model="value"
            />
            <label :for="uuid"></label>
        </span>
    </div>
</div>
</template>

<script>
import {
    Uuid
}
from '../Utilities'

export default {
    name: 'UiSwitch',
    props: {
        label: {
            type: String,
            default: null,
        },
        initial: {
            type: Boolean,
            default: false,
        },
    },
    data: function () {
        return {
            value: null
        }
    },
    watch: {
        value: function (v) {
            this.$emit('changed', v)
        }
    },
    computed: {
        uuid: function () {
            return Uuid.get()
        },
    },
    mounted: function () {
        if (this.initial) {
            this.value = this.initial
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
