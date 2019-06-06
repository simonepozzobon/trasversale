<template lang="html">
    <div class="notifications-container">
        <toast
            v-for="toast in cached"
            :key="toast.uuid"
            :uuid="toast.uuid"
            :title="toast.title"
            :message="toast.message"
            @dismiss="dismiss"
        />
    </div>
</template>

<script>
import Toast from '../components/Toast.vue'
export default {
    name: 'NotificationsContainer',
    components: {
        Toast
    },
    props: {
        toasts: [Array, Object]
    },
    data: function () {
        return {
            cached: []
        }
    },
    watch: {
        toasts: function (toasts) {
            this.setToasts()
        }
    },
    methods: {
        setToasts: function () {
            this.cached = this.toasts
        },
        dismiss: function (uuid) {
            let idx = this.cached.findIndex(cache => cache.uuid === uuid)
            if (idx > -1) {
                this.cached.splice(idx, 1)
            }
        }
    },
    mounted: function () {
        this.setToasts()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
.notifications-container {
    position: fixed;
    top: $spacer * 2;
    right: $spacer * 2;
    z-index: $zindex-tooltip;
}
</style>
