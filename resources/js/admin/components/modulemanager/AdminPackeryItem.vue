<template>
<div
    ref="packeryItem"
    v-draggabilly
    v-packery-item
    class="packery-item ui-resizable"
    :class="[
            widthClass,
            hoverableClass,
        ]"
>

    <div
        ref="item"
        class="packery-item__item"
        :class="[
                bgClass,
            ]"
    >
        <ui-paragraph
            v-if="type == 'module' && subType == 'paragraph'"
            class="packery-item__paragraph"
            ref="item"
            :has-padding="false"
            :color="this.color"
            :content="content"
        />

        <div
            class="packery-item__overlay"
            v-if="hoverable"
            @click="goToItem"
        >
            <div class="packery-item__title">
                {{ obj.title }}
            </div>
        </div>
    </div>

</div>
</template>

<script>
import {
    UiParagraph
}
from '../../../ui'

// console.log(packeryEvents);

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
            default: 2,
        },
        content: {
            type: Object,
            default: function () {
                return {}
            },
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
        },
        unitSize: {
            type: Number,
            default: 0,
        },
        x: {
            type: Number,
            default: 0,
        },
        y: {
            type: Number,
            default: 0,
        },
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
            // if (this.width) {
            //     return 'col-md-' + this.width
            // }
            // return 'col'
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
        }
    },
    data: function () {
        return {
            obj: null,
        }
    },
    watch: {
        width: function (w) {
            this.setUnitWidth(this.unitSize)
        },
        height: function (h) {
            this.setUnitHeight(this.unitSize)
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
                this.obj = this.content
            }
        },
        setUnitWidth: function (unitSize) {
            if (unitSize) {
                // console.log(unitSize, this.width);
                let itemWidth = unitSize * this.width
                let realGutter = Math.round(this.gutter / 2)
                // console.log(realGutter, itemWidth);

                if (this.width > 2) {
                    let deltaGutter = this.width * (realGutter / 2)
                    itemWidth = itemWidth + deltaGutter
                }

                this.$refs.item.style.width = itemWidth + 'px'

                // console.log('--------');
                this.$refs.packeryItem.style.padding = realGutter + 'px'
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
        },
        goToItem: function () {
            // this.$root.goToWithParams('subpage', {
            //     page: 'post',
            //     subpage: this.obj.slug.slug,
            // })
        },
        addListener: function () {
            // console.log('listener');
            // console.log(this.$refs.packeryItem);
            this.$refs.packeryItem.addEventListener('dragItemPositioned', (event) => {
                console.log('event');
            })
            this.$refs.packeryItem.addEventListener('dragend', (event) => {
                console.log('event end');
            })
        }
    },
    mounted: function () {
        this.setUnitWidth()
        this.setBackground()
        this.addListener()
        // console.log('width', this.width, 'height', this.height);
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
        color: $white;
        font-family: $font-family-sans-serif-var;
        font-size: $font-size-base * 0.8;
        letter-spacing: 0.05em;
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
            background-color: rgba($primary, .9);
            transition: $transition-base;
        }
    }
}
</style>
