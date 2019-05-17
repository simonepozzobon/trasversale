<template lang="html">
    <div>
        <dynamic-module-item
            ref="module"
            v-for="(option, i) in module.options"
            :key="i"
            :option="option"
            @changed="changed"/>
    </div>
</template>

<script>
import DynamicParams from '../DynamicParams'
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
    },
    data: function() {
        return {
            dataObj: null,
        }
    },
    computed: {
        module: function() {
            return DynamicParams.filter(params => params.name == this.name)[0]
        },
    },
    methods: {
        setModule: function() {
            let fields = this.module.options
            let dataObj = {}

            for (let i = 0; i < fields.length; i++) {
                let field = fields[i]
                dataObj[field.key] = field.hasOwnProperty('default') ? field.default : null
            }

            this.dataObj = dataObj
        },
        changed: function(key, value, type) {
            this.dataObj[key] = value
            console.log(key, value, type);
            if (type == 'file-input') {
                let idx = this.module.options.findIndex(option => option.parent == key && option.type == 'preview')
                if (idx > -1) {

                    let reader = new FileReader()
                    reader.onload = e => {
                        this.$refs.module[idx].src = e.target.result
                    }
                    reader.readAsDataURL(value)
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
