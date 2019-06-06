<template lang="html">
    <div class="ui-video">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe
                class="embed-responsive-item"
                :src="source"
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
    computed: {
        source: function() {
            if (this.url) {
                this.url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

                if (RegExp.$3.indexOf('youtu') > -1) {
                    return 'https://www.youtube.com/embed/' + RegExp.$6
                }
                else if (RegExp.$3.indexOf('vimeo') > -1) {
                    return 'https://player.vimeo.com/video/' + RegExp.$6
                }
                return null
            }
            return null
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
