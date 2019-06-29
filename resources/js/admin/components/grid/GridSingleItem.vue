<template>
<div
    class="grid-single-item"
    ref="el"
>
</div>
</template>

<script>
export default {
    name: 'GridSingleItem',
    components: {},
    props: {
        item: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    methods: {
        setImage: function () {
            this.$refs.el.style.backgroundImage = 'url(' + this.item.thumb + ')'
        }
    },
    mounted: function () {
        this.setImage()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.grid-single-item {
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;
}
</style>
