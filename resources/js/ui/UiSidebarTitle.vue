<template lang="html">
    <div class="sidebar-title">
        <h2 class="sidebar-title__content">{{ title }}</h2>
    </div>
</template>

<script>
export default {
    name: 'UiSidebarTitle',
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

.sidebar-title {
    margin-bottom: $spacer * $line-height-base / 2;
    border-top: 2px solid $black;

    &__content {
        padding-top: $spacer / 6;
        font-size: $font-size-sm;
        text-transform: uppercase;
    }
}
</style>
