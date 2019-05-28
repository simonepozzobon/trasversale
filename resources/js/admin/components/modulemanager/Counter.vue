<template lang="html">
    <div class="input-group col-counter">
        <div class="input-group-prepend">
            <button class="btn btn-outline-primary" @click="addCounter">+</button>
        </div>
        <input
            type="text"
            name="counter"
            class="form-control"
            v-model="value"/>

        <div class="input-group-append">
            <button class="btn btn-outline-primary" @click="removeCounter">-</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'counter',
    props: {
        size: {
            type: Number,
            default: 0,
        },
    },
    data: function() {
        return {
            value: 0,
        }
    },
    watch: {
        size: function(size) {

            this.value = size
        }
    },
    methods: {
        addCounter: function() {
            this.$emit('update', 1)
        },
        removeCounter: function() {
            this.$emit('update', -1)
        },
    },
    created: function() {
        this.value = this.size
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.col-counter {
    max-width: 130px;
}
</style>
