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
            console.log(this.height);
            this.$refs.spacer.style.height = this.height + 'px'
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
