<template lang="html">
    <div class="ui-title">
        <h1 class="ui-title__content">{{ title }}</h1>
    </div>
</template>

<script>
export default {
    name: 'UiTitle',
    props: {
        title: {
            type: String,
            default: 'Titolo'
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-title {
    &__content {
        margin-left: 230px;
        font-weight: 800;
        font-size: 2.5rem;
        letter-spacing: 1px;

        // valutare
        // line-height: 1;
    }
}
</style>
