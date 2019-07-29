<template>
<div>
    <dynamic-module-item
        ref="module"
        v-for="(option, i) in options"
        :key="i"
        :initial="setInitial(option)"
        :option="option"
        :data-obj="dataObj"
        :edit="isEdit"
        @changed="changed"
    />
</div>
</template>

<script>
import {
    clone,
    isEqual
}
from '../../Utilities'
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
        uuid: {
            type: String,
            default: null,
        },
        options: [Object, Array],
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
    data: function () {
        return {
            dataObj: null,
        }
    },
    methods: {
        setModule: function () {
            let fields = this.options
            let dataObj = {}
            for (let i = 0; i < fields.length; i++) {
                let field = fields[i]
                dataObj[field.key] = field.hasOwnProperty('default') ? field.default : null
            }
            this.dataObj = dataObj

            // for (let key in this.dataObj) {
            //     if (this.dataObj.hasOwnProperty(key)) {
            //         this.emitChanged(key, this.dataObj[key])
            //     }
            // }
        },
        setInitial: function (option) {
            // set initial with default value if there is one
            let initial = option.hasOwnProperty('default') ? option.default : null

            if (this.values) {

                let key = option.key
                if (option.hasOwnProperty('childrens') && option.childrens.length > 0) {
                    // console.log('children', key, this.values);
                    return this.values
                }
                // console.log('not-children', key,  this.values[key] ? this.values[key] : null);
                if (this.values.hasOwnProperty(key)) {
                    initial = this.values[key]
                }

                return initial
            }

            return initial
        },
        emitChanged: function (key, value) {
            this.dataObj[key] = value
            this.$emit('changed', this.dataObj)
        },
        changed: function (key, value, type) {
            let prev = clone(this.dataObj[key])
            // console.log('Dynamic module è diverso', !isEqual(prev, value), key);
            if (!isEqual(prev, value) && type != 'file-input') {
                this.emitChanged(key, value)
            }
            else if (key == 'columns') {
                // console.log('Dynamic module è diverso', prev, value);
                this.emitChanged(key, value)

            }
            else if (type === 'file-input') {
                this.emitChanged(key, value)
                // console.log(this.uuid, value);
                this.$root.$emit('add-file-to-upload', {
                    uuid: this.uuid,
                    file: value
                })
            }

            // console.log(key, value, type);
            if (type == 'file-input' || type == 'text') {
                // console.log(key, value, type);
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
                        }
                        else if (RegExp.$3.indexOf('vimeo') > -1) {
                            url = 'https://player.vimeo.com/video/' + RegExp.$6
                        }
                        this.$refs.module[idx].src = url
                    }
                }
            }
        }
    },
    created: function () {
        this.setModule()
    },
    mounted: function () {}
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
