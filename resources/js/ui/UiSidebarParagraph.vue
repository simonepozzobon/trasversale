<template lang="html">
    <div v-if="content" class="ui-sidebar-paragraph">
        <p v-html="content" class="ui-sidebar-paragraph__content"></p>
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

<style lang="scss" scoped>
@import '~styles/shared';

.ui-sidebar-paragraph {
    padding-top: $spacer * $line-height-base;

    &__content {}
}
</style>
