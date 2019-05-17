<template lang="html">
    <div ref="container" class="ui-partner">
        <img :src="img" :alt="title" class="ui-partner__img">
    </div>
</template>

<script>
export default {
    name: 'UiPartner',
    props: {
        title: {
            type: String,
            default: null,
        },
        img: {
            type: String,
            default: null,
        },
        size: {
            type: Number,
            default: 100
        }
    },
    methods: {
        setSize: function() {
            this.$refs.container.style.maxWidth = this.size + '%'
        }
    },
    mounted: function() {
        this.setSize()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-partner {
    margin-bottom: $spacer * $line-height-base / 2;

    &__img {
        max-width: 100%;
        max-height: 100%;
    }
}
</style>
