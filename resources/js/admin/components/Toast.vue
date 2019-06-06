<template>
<div
    class="toast"
    style=""
>
    <div class="toast-header">
        <strong class="mr-auto">{{ title }}</strong>
        <small>{{ date }}</small>
        <button
            type="button"
            class="ml-2 mb-1 close"
            data-dismiss="toast"
            aria-label="Close"
            @click="dismissToast"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        {{ message }}
    </div>
</div>
</template>

<script>
export default {
    name: 'Toast',
    props: {
        title: {
            type: String,
            default: 'titolo',
        },
        date: {
            type: String,
            default: null,
        },
        message: {
            type: String,
            default: null,
        },
        uuid: {
            type: String,
            default: null,
        }
    },
    methods: {
        dismissToast: function () {
            this.$emit('dismiss', this.uuid)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.toast {
    opacity: 1;
}
</style>
