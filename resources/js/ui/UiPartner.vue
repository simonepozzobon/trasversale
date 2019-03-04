<template lang="html">
    <div ref="container">
        <img :src="img" :alt="title" class="img-fluid">
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
</style>
