<template lang="html">
    <div>
        <dynamic-module-item
            ref="module"
            v-for="(option, i) in options"
            :key="i"
            :option="option"
            :data-obj="dataObj"
            @changed="changed"/>
    </div>
</template>

<script>
import DynamicModuleItem from './DynamicModuleItem.vue'

export default {
    name: 'DynamicModule',
    components: {
        DynamicModuleItem,
    },
    props: {
        name: {
            type: String,
            default: null,
        },
        options: {
            type: Array,
            default: function() { return [] },
        }
    },
    data: function() {
        return {
            dataObj: null,
        }
    },
    methods: {
        setModule: function() {
            let fields = this.options
            let dataObj = {}

            for (let i = 0; i < fields.length; i++) {
                let field = fields[i]
                dataObj[field.key] = field.hasOwnProperty('default') ? field.default : null
            }

            this.dataObj = dataObj
        },
        changed: function(key, value, type) {
            this.dataObj[key] = value
            // console.log(key, value, type);
            if (type == 'file-input' || type == 'text') {
                // cerco se ha un modulo preview
                let idx = this.options.findIndex(option => option.parent == key && option.type == 'preview')
                if (idx > -1) {

                    // verifico se e un file o un url
                    let option = this.options[idx]

                    if (option.mime == 'image') {
                        let reader = new FileReader()
                        reader.onload = e => {
                            this.$refs.module[idx].src = e.target.result
                        }
                        reader.readAsDataURL(value)
                    }

                    else if (option.mime == 'video-url') {
                        let url
                        value.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

                        if (RegExp.$3.indexOf('youtu') > -1) {
                            url = 'https://www.youtube.com/embed/' + RegExp.$6
                        } else if (RegExp.$3.indexOf('vimeo') > -1) {
                            url = 'https://player.vimeo.com/video/' + RegExp.$6
                        }
                        this.$refs.module[idx].src = url
                    }
                }
            }
        }
    },
    created: function() {
        this.setModule()
    },
    mounted: function() {
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
