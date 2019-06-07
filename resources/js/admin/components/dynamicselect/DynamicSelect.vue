<template>
<div class="form-group row">
    <label
        :for="name"
        class="col-md-3"
    >
        {{ label }}
    </label>
    <div class="col-md-9">
        <select
            class="form-control"
            :name="name"
        >
            <option
                v-for="option in options"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
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
    name: 'DynamicSelect',
    props: {
        request: {
            type: Object,
            default: function () {
                return {}
            },
        },
        name: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        info: {
            type: String,
            default: null,
        }
    },
    data: function () {
        return {
            options: []
        }
    },
    methods: {
        getOpts: function () {
            if (this.request) {
                this.$http.get(this.request.url)
                    .then(response => {
                        this.options = this.request.handleResponse(response)
                    })
            }
        }
    },
    created: function () {
        this.getOpts()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
