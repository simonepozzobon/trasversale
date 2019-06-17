<template lang="html">
    <div
        ref="packeryItem"
        v-packery-item
        class="packery-item"
        :class="[
            widthClass,
            hoverableClass,
            titleClass
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

            <div class="packery-item__overlay" v-if="hoverable" @click="goToItem">
                <div class="packery-item__title">
                    {{ obj.title }}
                </div>
            </div>
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
        bgClass: function () {
            if (this.img) {
                return 'has-image-bg'
            }
            else if (this.bgColor) {
                return 'bg-' + this.bgColor
            }
        },
        widthClass: function () {
            if (this.width) {
                return 'col-md-' + this.width
            }
            return 'col'
        },
        heightClass: function () {
            if (this.height) {
                return this.height
            }
            return null
        },
        bgColorClass: function () {
            if (this.bgColor && !this.img) {
                return 'bg-' + this.bgColor
            }
            return null
        },
        hoverableClass: function () {
            if (this.obj) {
                return 'packery-item--hoverable'
            }
        },
        hoverable: function () {
            if (this.obj) {
                return true
            }

            return false
        },
        titleClass: function () {
            if (this.width <= 2) {
                return 'packery-item--title-small'
            }
            else if (this.width > 2 && this.width <= 6) {
                return 'packery-item--title-medium'
            }
            return null
        }
    },
    data: function () {
        return {
            debug: true,
            obj: null,
        }
    },
    methods: {
        setBackground: function () {
            if (this.img) {
                this.$refs.item.style.backgroundImage = 'url(' + this.img + ')'
            }

            if (this.type == 'module' && this.subType) {
                console.log(this.type, this.subType);
            }

            if (this.type !== 'module') {
                this.obj = JSON.parse(this.content)
            }
        },
        setUnitHeight: function (height) {
            let realGutter = Math.round(this.gutter / 2)
            let itemHeight = height * this.height

            if (this.height > 2) {
                let deltaGutter = this.height * (realGutter / 2)
                itemHeight = itemHeight + deltaGutter
            }

            this.$refs.item.style.height = itemHeight + 'px'
            this.$refs.packeryItem.style.padding = realGutter + 'px'

            // console.log(itemHeight);
            // this.$nextTick(() => {
            //     this.$emit('loaded')
            // })
        },
        goToItem: function () {
            this.$root.goToWithParams('subpage', {
                page: 'post',
                subpage: this.obj.slug.slug,
            })
        }
    },
    mounted: function () {
        this.setBackground()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.packery-item {
    &__item {
        position: relative;
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

    &__title {
        text-transform: uppercase;
        color: $black;
        line-height: 1.4;
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-base * 1.8;
        letter-spacing: 0.05em;
        padding: $spacer;
        font-weight: 600;
    }

    &--title-medium &__title {
        padding: $spacer / 2;
        font-size: $font-size-base * 1.4;
    }

    &--title-small &__title {
        padding: $spacer / 4;
        font-size: $font-size-base * 0.8;
    }

    &--hoverable &__overlay {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 1;
        transition: $transition-base;

        padding: $spacer;
    }

    &--hoverable {
        cursor: pointer;
    }

    &--hoverable:hover & {
        &__overlay {
            opacity: 1;
            background-color: rgba($yellow, .95);
            transition: $transition-base;
        }
    }
}
</style>
