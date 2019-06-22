<template lang="html">
    <div
        class="form-group row"
    >
        <label
            :for="name"
            class="col-md-3"
        >
            {{ label }}
        </label>
        <div class="col-md-9">
            <div class="row">
                <div class="input-group col-md-3">
                    <div class="input-group-prepend">
                        <button
                            class="btn btn-outline-primary"
                            @click="addCounter"
                        >
                            +
                        </button>
                    </div>
                    <input
                        type="text"
                        :name="name"
                        class="form-control"
                        v-model="value"
                    />

                    <div class="input-group-append">
                        <button
                            class="btn btn-outline-primary"
                            @click="removeCounter"
                        >
                            -
                        </button>
                    </div>
                </div>
            </div>
            <small
                class="text-muted"
                v-if="info"
            >
                {{ info }}
            </small>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CounterModule',
    props: {
        label: {
            type: String,
            default: 'Titolo',
        },
        name: {
            type: String,
            default: 'name',
        },
        info: {
            type: String,
            default: null,
        },
        max: {
            type: Number,
            default: null,
        },
        min: {
            type: Number,
            default: null,
        },
        initial: {
            type: Number,
            default: null,
        }
    },
    data: function () {
        return {
            value: 0,
        }
    },
    watch: {
        value: function (value) {
            this.$emit('changed', value)
        }
    },
    methods: {
        addCounter: function () {
            if (this.max && this.value < this.max) {
                this.value++
            }
            else if (this.max == null) {
                this.value++
            }
        },
        removeCounter: function () {
            if (this.min && this.value > this.min) {
                this.value--
            }
            else if (this.min == null) {
                this.value--
            }
        },
    },
    created: function () {
        if (this.initial) {
            this.value = this.initial
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
