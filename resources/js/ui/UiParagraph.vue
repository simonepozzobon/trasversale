<template lang="html">
    <div v-if="content">
        <p v-html="content"></p>
    </div>
</template>

<script>
export default {
    name: 'UiParagraph',
    props: {
        content: {
            type: String,
            default: null
        }
    }
}
</script>

<style lang="css" scoped>
</style>
