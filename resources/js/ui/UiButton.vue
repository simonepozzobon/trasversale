<template lang="html">
    <div>
        <button class="btn btn-outline-primary ui-button">{{ text }}</button>
    </div>
</template>

<script>
export default {
    name: 'UiButton',
    props: {
        text: {
            type: String,
            default: 'Pulsante'
        }
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-button {
    text-transform: uppercase;
    letter-spacing: 1px;
    color: $black;
}
</style>
