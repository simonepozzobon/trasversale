<template lang="html">
    <div
        ref="item"
        v-packery-item
        :class="'packery-item ' + widthClass + ' ' + colorClass"
        v-html="content">
    </div>
</template>

<script>
export default {
    name: 'UiPackeryItem',
    props: {
        width: {
            type: Number,
            default: null,
        },
        height: {
            type: Number,
            default: null,
        },
        content: {
            type: String,
            default: null,
        },
        color: {
            type: String,
            default: null,
        }
    },
    computed: {
        widthClass: function() {
            if (this.width) {
                return 'col-md-'+this.width
            }
            return 'col'
        },
        heightClass: function() {
            if (this.height) {
                return this.height
            }
            return null
        },
        colorClass: function() {
            if (this.color) {
                return 'bg-'+this.color
            }
            return null
        },
    },
    data: function() {
        return {
            itemHeight: 0,
        }
    },
    watch: {
        itemHeight: function(height) {
            this.$emit('height-changed', height)
        }
    },
    methods: {
        setUnitHeight: function(height) {
            let itemHeight = height * this.height
            this.$refs.item.style.height = itemHeight + 'px'
        },
        getHeight: function() {
            let el = this.$refs.item
            let rect = el.getBoundingClientRect()
            this.itemHeight = rect.height
        }
    },
    mounted: function() {
        this.getHeight()
    }
}
</script>

<style lang="css" scoped>
</style>
