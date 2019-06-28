<template lang="html">
    <div class="ui-image" ref="container">
        <div class="ui-image__placeholder" ref="holder">
            <div class="spinner-border text-primary" ref="loader"></div>
        </div>
        <!-- <img
            ref="image"
            :src="source"
            :alt="alt"
            class="img-fluid ui-image__content" /> -->
    </div>
</template>

<script>
import {
    SizeUtil
}
from '../Utilities'

export default {
    name: 'UiImage',
    props: {
        src: {
            type: String,
            default: null
        },
        alt: {
            type: String,
            default: 'Image',
        },
    },
    data: function () {
        return {
            ready: false,
            source: null,
        }
    },
    methods: {
        appendToDOM: function (img) {
            this.ready = true

            let container = this.$refs.container
            if (container) {
                img.style.width = '100%'
                container.appendChild(img)

                this.$nextTick(() => {
                    container.classList.add('ui-image--loaded')
                })
            }
        },
        loader: function () {
            let img = new Image()

            img.addEventListener('load', () => {
                if (!this.ready) {
                    this.appendToDOM(img)
                }
            })

            img.src = this.src
            img.alt = this.alt
            img.classList.add('img-fluid', 'ui-image__content')

            if (img.complete) {
                this.$nextTick(() => {
                    this.appendToDOM(img)
                })
            }
        },
    },
    created: function () {
        this.loader()
    },
    mounted: function () {

    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.ui-image {
    margin-top: $spacer * $line-height-base;
    position: relative;
    min-height: 100px;
    height: 100px;
    overflow: hidden;
    transition: $transition-base;

    &__placeholder {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: $light;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: $transition-base;
    }

    &__content {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 0;
        opacity: 0;
        transition: $transition-base;
    }

    &--loaded {
        height: 100%;
        transition: $transition-base;
    }

    &--loaded & {
        &__placeholder {
            height: 0;
            opacity: 0;
            transition: $transition-base;
        }

        &__content {
            opacity: 1;
            width: 100%;
            height: auto;
            transition: $transition-base;
        }
    }
}
</style>
