<template lang="html">
    <new-post-template></new-post-template>
</template>

<script>
import NewPostTemplate from '../containers/NewPostTemplate.vue'
export default {
    name: 'PostCreate',
    components: {
        NewPostTemplate,
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
