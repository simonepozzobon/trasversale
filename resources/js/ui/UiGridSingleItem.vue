<template>
<div
    class="grid-single-item"
    :class="[
        titleSize
    ]"
    ref="el"
>
    <div
        class="grid-single-item__overlay"
        @click.prevent="openItem"
    >
        <div
            class="grid-single-item__title"
            ref="container"
            v-if="title"
        >
            <p
                class="grid-single-item__p"
                ref="text"
            >
                {{ title }}
            </p>
        </div>
    </div>
</div>
</template>

<script>
import {
    checkOverflow
}
from '../Utilities'

export default {
    name: 'UiGridSingleItem',
    components: {},
    props: {
        item: {
            type: Object,
            default: function () {
                return {}
            },
        },
        isAdmin: {
            type: Boolean,
            default: false
        },
    },
    data: function () {
        return {
            counter: 0,
        }
    },
    computed: {
        size: function () {
            return {
                h: this.item.height,
                w: this.item.width
            }
        },
        _h: function () {
            return this.size.h
        },
        _w: function () {
            return this.size.w
        },
        hasTitle: function () {
            if (this._h < 2 || this._w < 2) {
                return false
            }
            return true
        },
        content: function () {
            if (this.item.content) {
                if (typeof this.item.content == 'string') {
                    return JSON.parse(this.item.content)
                }
                else {
                    return this.item.content
                }
            }
        },
        title: function () {
            if (this.content && this.content.title) {
                return this.content.title
            }
            return null
        },
        titleSize: function () {
            if (this._h > 6 || this._w > 6) {
                return 'grid-single-item--title-lg'
            }
            else if (this._h >= 3 && this._h <= 6 && this._w > 4 && this._w <= 6) {
                return 'grid-single-item--title-md'
            }

            return null
        },
    },
    methods: {
        setImage: function () {
            this.$refs.el.style.backgroundImage = 'url(' + this.item.thumb + ')'
        },
        smallerText: function (el) {
            if (el) {
                let text = this.$refs.text
                let style = window.getComputedStyle(text, null).getPropertyValue('font-size')
                let fontSize = parseFloat(style) - 1

                text.style.fontSize = fontSize + 'px'

                this.$nextTick(() => {
                    this.checkText(el)
                })
            }
        },
        checkText: function (el = false) {

            if (el == false) {
                el = this.$refs.container
            }

            this.counter++
            this.$nextTick(() => {
                if (checkOverflow(el)) {
                    this.smallerText(el)
                }
            })
        },
        openItem: function () {
            if (!this.isAdmin) {
                let slugObj = this.content.slug
                let type = slugObj.sluggable_type
                let slug = slugObj.slug

                switch (type) {
                case 'App\\News':
                    this.$root.goToWithParams('subpage', {
                        page: 'notizie',
                        subpage: slug,
                    })
                    break;
                case 'App\Product':
                    this.$root.goToWithParams('subpage', {
                        page: 'corsi',
                        subpage: slug,
                    })
                    break;
                default:
                    alert('no route')
                }
            }
        },
    },
    mounted: function () {
        // console.log(this.item);
        this.setImage()
        this.$nextTick(() => {
            this.checkText()
        })
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
$padding: $spacer / 4 !default;
$paddingWidth: $padding * 2 !default;

.grid-single-item {
    background-position: center;
    background-size: cover;
    background-repeat: no-repeat;
    width: 100%;
    height: 100%;

    &__overlay {
        cursor: pointer;
        width: 100%;
        height: 100%;
        background-color: rgba($yellow, .95);
        opacity: 0;
        transition: $transition-base;
    }

    &__title {
        height: inherit;
    }

    &__p {
        // background-color: rgba($green, .4);
        text-transform: uppercase;
        line-height: 1.4;
        font-family: $font-family-sans-serif-var;
        letter-spacing: 0.05em;
        font-weight: 600;
        padding: $padding;
        font-size: $font-size-base * 0.8;
    }

    &--title-md &__p {
        padding: $spacer / 2;
        font-size: $font-size-base * 1.4;
    }

    &--title-lg &__p {
        font-size: $font-size-base * 1.6;
        padding: $spacer;
    }

    &:hover &__overlay {
        opacity: 1;
        transition: $transition-base;
    }
}
</style>
