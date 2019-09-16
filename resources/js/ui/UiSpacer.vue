<template>
<div ref="spacer"></div>
</template>

<script>
export default {
    name: 'UiSpacer',
    props: {
        height: [Number, String],
    },
    watch: {
        height: function (h) {
            this.setHeight()
        }
    },
    methods: {
        setHeight: function () {
            if (this.height) {
                this.$refs.spacer.style.height = this.height + 'px'
            }
        }
    },
    mounted: function () {
        this.setHeight()
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
