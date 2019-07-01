<template>
<div class="form-group row">
    <label
        :for="name"
        class="col-md-3"
    >
        {{ label }}
    </label>
    <div class="col-md-9">
        <datepicker
            v-model="value"
            :bootstrap-styling="true"
        />
    </div>
</div>
</template>

<script>
import Datepicker from 'vuejs-datepicker'
export default {
    name: 'AdminDatePicker',
    components: {
        Datepicker,
    },
    props: {
        label: {
            type: String,
            default: 'titolo',
        },
        name: {
            type: String,
            default: 'name',
        },
        initial: {
            type: String,
            default: null,
        },
    },
    data: function () {
        return {
            value: null,
        }
    },
    watch: {
        value: function (value) {
            // console.log(value);
            this.$emit('update', value)
        },
        initial: function () {
            this.setInitial()
        },
    },
    methods: {
        setInitial: function () {
            this.value = this.initial
        }
    },
    mounted: function () {
        this.setInitial()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
