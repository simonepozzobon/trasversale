<template lang="html">
    <div class="form">
        <div class="form-group row">
            <label for="title" class="col-md-3">Titolo</label>
            <div class="col-md-9">
                <input type="text" name="title" v-model="title" placeholder="Titolo ..." class="form-control">
            </div>
        </div>
        <div class="form-group row">
            <label for="host" class="col-md-3">Host</label>
            <div class="col-md-9">
                <select class="form-control" name="host" v-model="host">
                    <option value="youtube">YouTube</option>
                    <option value="vimeo">Vimeo</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label for="url" class="col-md-3">Url</label>
            <div class="col-md-9">
                <input type="text" name="url" v-model="url" placeholder="http://..." class="form-control">
            </div>
        </div>
        <div class="form-group row" v-if="temp">
            <label for="preview" class="col-md-3">Anteprima</label>
            <div class="col-md-9">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe
                        v-if="temp.type == 'youtube'"
                        class="embed-responsive-item"
                        :src="temp.id | setYoutubeUrl"
                        allowfullscreen>
                    </iframe>
                    <iframe
                        v-if="temp.type == 'vimeo'"
                        class="embed-responsive-item"
                        :src="temp.id | setVimeoUrl"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'MVideo',
    data: function() {
        return {
            title: null,
            host: 'youtube',
            url: null,
            temp: null,
        }
    },
    watch: {
        'title': function(title) {
            this.setObj()
        },
        'host': function(host) {
            this.setObj()
        },
        'url': function(url) {
            this.setObj()
            this.getID(url)
        },
    },
    methods: {
        setObj: function() {
            this.$nextTick(() => {
                this.$emit('changed', {
                    title: this.title,
                    host: this.host,
                    url: this.url
                })
            })
        },
        getYoutubeID: function(url) {
            // https://stackoverflow.com/questions/3452546/how-do-i-get-the-youtube-video-id-from-a-url
            let regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/
            let match = url.match(regExp)
            this.temp = (match&&match[7].length==11)? match[7] : false
        },
        getID: function(url) {
            let type

            url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

            if (RegExp.$3.indexOf('youtu') > -1) {
                type = 'youtube'
            } else if (RegExp.$3.indexOf('vimeo') > -1) {
                type = 'vimeo'
            }

            this.temp = {
                type: type,
                id: RegExp.$6
            }
        },
    },
    filters: {
        setYoutubeUrl: function(id) {
            return 'https://www.youtube.com/embed/' + id
        },
        setVimeoUrl: function(id) {
            return 'https://player.vimeo.com/video/' + id
        }
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
