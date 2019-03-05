<template lang="html">
    <div v-if="src">
        <img :src="src" :alt="alt" class="img-fluid">
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

<style lang="css" scoped>
</style>
