<template lang="html">
    <div class="page-template">
        <div class="page-template-container">
            <h1 class="pt-3">{{ title }}</h1>
            <hr>
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: 'PageTemplate',
    props: {
        title: {
            type: String,
            default: 'titolo'
        }
    }
}
</script>

<style lang="scss">
@import '~styles/adminshared';

.page-template {
    padding-top: $spacer;
    background-color: $dark;
    @include gradient-directional($gray-400, $gray-300, 135deg);
    min-height: 100%;

    .page-template-container {
        @include gradient-directional($gray-300, $light, 135deg);
        margin: $spacer * 4;
        padding: $spacer * 2;
        @include border-radius($spacer / 2);
        @include box-shadows($gray-500);
    }
}
</style>
