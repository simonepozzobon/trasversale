<template lang="html">
    <div v-if="src" class="ui-sidebar-image">
        <img :src="src" :alt="alt" class="img-fluid ui-sidebar-image__content">
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

.ui-sidebar-image {
    padding-top: $spacer * $line-height-base;
}
</style>
