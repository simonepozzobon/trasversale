<template lang="html">
    <div
        ref="container"
        v-packery-item
        class="packery-item"
        :class="[
            widthClass,
            bgColorClass
        ]">

        <div
            ref="item"
            class="packery-item__item"
            :class="[
                bgClass,
            ]">
            <ui-paragraph
                v-if="type == 'module' && subType == 'paragraph'"
                class="packery-item__paragraph"
                ref="item"
                :has-padding="false"
                :color="this.color"
                :content="content"/>
        </div>

    </div>
</template>

<script>
import UiParagraph from './UiParagraph.vue'

export default {
    name: 'UiPackeryItem',
    components: {
        UiParagraph,
    },
    props: {
        type: {
            type: String,
            default: null,
        },
        subType: {
            type: String,
            default: null,
        },
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
        },
        bgColor: {
            type: String,
            default: null,
        },
        img: {
            type: String,
            default: null,
        },
        gutter: {
            type: Number,
            default: 0,
        }
    },
    computed: {
        bgClass: function() {
            if (this.img) {
                return 'has-image-bg'
            }
        },
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
        bgColorClass: function() {
            if (this.bgColor && !this.img) {
                return 'bg-'+this.bgColor
            }
            return null
        },
    },
    data: function() {
        return {
            debug: true,
        }
    },
    methods: {
        setBackground: function() {
            if (this.img) {
                this.$refs.item.style.backgroundImage = 'url('+this.img+')'
            }

            if (this.type == 'module' && this.subType) {
                console.log(this.type, this.subType);
            }
        },
        setUnitHeight: function(height) {
            let realGutter = Math.round(this.gutter / 2)
            let itemHeight = height * this.height

            if (this.height > 2) {
                let deltaGutter = this.height * (realGutter / 2)
                itemHeight = itemHeight + deltaGutter
            }


            this.$refs.item.style.height = itemHeight + 'px'
            this.$refs.container.style.padding = realGutter + 'px'
        },
    },
    mounted: function() {
        this.setBackground()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.packery-item {

    &__item {
        max-width: 100%;
        height: 100%;

        &.has-image-bg {
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    }

    &__paragraph {
        padding: $spacer;
    }
}
</style>
