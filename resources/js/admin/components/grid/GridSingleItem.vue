<template>
<div
    class="grid-single-item"
    ref="el"
>
    {{ text }}
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
    data: function () {
        return {
            text: null,
        }
    },
    methods: {
        setImage: function () {
            if (this.item.thumb) {
                this.$refs.el.style.backgroundImage = 'url(' + this.item.thumb + ')'
            }
            else {
                this.text = 'No-Image'
            }
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

    &--no-image {
        background-color: rgba($primary, .3);
    }
}
</style>
