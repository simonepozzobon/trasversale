<template lang="html">
    <div class="form-group row">
        <div class="col">
            {{ content }}
        </div>
    </div>
</template>

<script>
export default {
    name: 'DummyModule',
    props: {
        content: {
            type: String,
            default: null,
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
