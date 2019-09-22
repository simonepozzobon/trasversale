<template>
<div>
    <dynamic-module-item
        ref="module"
        v-for="(option, i) in options"
        :key="i"
        :initial="option | setInitial(values)"
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
        values: [Object, Array, String],
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
            dataObj: {},
            initialized: false,
        }
    },
    filters: {
        setInitial: function (option, values) {
            let initial = option.hasOwnProperty('default') ? option.default : null
            if (option.type == 'grid') {
                return values
            }
            else {
                if (values) {

                    if (typeof values == 'string') {
                        values = JSON.parse(values)
                    }

                    let key = option.key
                    if (option.hasOwnProperty('childrens') && option.childrens.length > 0) {
                        // console.log('children', key, values);
                        return values
                    }
                    // console.log('not-children', key,  values[key] ? values[key] : null);
                    if (values.hasOwnProperty(key)) {
                        initial = values[key]
                    }

                    return initial
                }

                return initial
            }
        },
        // setModule: function (fields) {
        //     console.log(fields);
        //     if (!fields) {
        //         fields = []
        //     }
        //
        //     let dataObj = {}
        //
        //     for (let i = 0; i < fields.length; i++) {
        //         let field = fields[i]
        //         dataObj[field.key] = field.hasOwnProperty('default') ? field.default : null
        //     }
        //
        //     return dataObj
        // }
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

            // console.log(Object.assign({}, dataObj));
            // for (let key in this.dataObj) {
            //     if (this.dataObj.hasOwnProperty(key)) {
            //         this.emitChanged(key, this.dataObj[key])
            //     }
            // }
        },
        emitChanged: function (key, value) {
            this.dataObj[key] = value
            // console.log(this.dataObj);
            this.$emit('changed', this.dataObj)
        },
        changed: function (key, value, type) {
            let prev = clone(this.dataObj[key])
            // console.log('Dynamic module è diverso', !isEqual(prev, value), key);
            if (!isEqual(prev, value) && type != 'file-input') {
                // console.log('file-input');
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


            // console.log('emit', key, value, type);
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
