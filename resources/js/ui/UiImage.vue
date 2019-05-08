<template lang="html">
    <div v-if="src" class="ui-image">
        <img :src="src" :alt="alt" class="img-fluid ui-image__content">
    </div>
</template>

<script>
export default {
    name: 'UiImage',
    props: {
        src: {
            type: String,
            default: null
        },
        alt: {
            type: String,
            default: 'Image',
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-image {
    padding-top: $spacer * $line-height-base;
}
</style>
