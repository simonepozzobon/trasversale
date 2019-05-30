<template lang="html">
    <div class="ui-video">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe
                class="embed-responsive-item"
                :src="url"
                allowfullscreen>
            </iframe>
        </div>
    </div>
</template>

<script>
export default {
    name: 'UiVideo',
    props: {
        url: {
            type: String,
            default: null,
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
