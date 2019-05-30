<template lang="html">
    <div>
        <dynamic-module-item
            ref="module"
            v-for="(option, i) in options"
            :key="i"
            :initial="setInitial(option)"
            :option="option"
            :data-obj="dataObj"
            :edit="isEdit"
            @changed="changed"/>
    </div>
</template>

<script>
import { clone, isEqual } from '../../Utilities'
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
        },
        values: [Object, Array],
        isEdit: {
            type: Boolean,
            default: false,
        },
        debug: {
            type: Boolean,
            default: false,
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
        setInitial: function(option) {
            let key = option.key
            if (option.hasOwnProperty('childrens') && option.childrens.length > 0) {
                // console.log('children', key,  this.values);
                return this.values
            }
            // console.log('not-children', key,  this.values[key] ? this.values[key] : null);

            return this.values[key] ? this.values[key] : null
        },
        changed: function(key, value, type) {
            let prev = clone(this.dataObj[key])
            // console.log('Dynamic module è diverso', !isEqual(prev, value), key);
            if (!isEqual(prev, value)) {
                this.dataObj[key] = value
                this.$emit('changed', this.dataObj)
            }

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
        // console.log(this.options);
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
