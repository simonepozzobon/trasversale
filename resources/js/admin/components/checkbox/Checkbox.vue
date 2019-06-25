<template>
<div class="custom-control custom-checkbox">
    <input
        type="checkbox"
        class="custom-control-input"
        :id="name"
        v-model="value"
    >
    <label
        class="custom-control-label"
        :for="name"
    >
        {{ label }}
    </label>
</div>
</template>

<script>
export default {
    name: 'CheckboxModule',
    props: {
        name: {
            type: String,
            default: 'name',
        },
        label: {
            type: String,
            default: 'Titolo',
        },
        val: {
            type: Boolean,
            default: false,
        },

    },
    data: function () {
        return {
            value: false,
        }
    },
    watch: {
        value: function (value) {
            this.$emit('update:val', value)
        },
        val: function (val) {
            this.value = val
        },
    },
    created: function () {
        this.value = this.val
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
