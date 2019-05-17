<template lang="html">
    <div class="grid-news">
        <div class="grid-news__image">
            <img
                class="grid-news__figure"
                :src="content.thumb"
                :alt="content.title" />
        </div>
        <div class="grid-news__details">
            <div class="grid-news__category">
                <span class="badge badge-light">{{ content.category.title }}</span>
            </div>
            <div class="grid-news__title">
                {{ content.title }}
            </div>
            <div class="grid-news__author">
                {{ content.author }}
            </div>
            <div class="grid-news__date">
                <!-- {{ content.created_at }} -->
                26.05.19
            </div>
            <div class="grid-news__description">
                <!-- {{ content.created_at }} -->
                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud...
            </div>
        </div>
    </div>
</template>

<script>
import UiBlock from './UiBlock.vue'

export default {
    name: 'UiSimpleGrid',
    components: {
        UiBlock,
    },
    props: {
        block: {
            type: Object,
            default: function() {},
        },
    },
    computed: {
        content: function() {
            return JSON.parse(this.block.content)
        },
    },
    methods: {
        goToProduct: function() {
            // console.log(this.$route.params.page);
            this.$root.goToWithParams('item', {
                page: this.$route.params.page,
                subpage: this.$route.params.subpage,
                item: this.content.slug.slug
            })
        },
    },
    mounted: function() {
        // console.log(this.block, this.content);
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.grid-news {
    margin-bottom: $spacer * $line-height-base;

    &__image {}

    &__figure {
        max-width: 100%;
        max-height: 100%;
        width: 100%;
        height: auto;
    }

    &__details {
        display: flex;
        height: 100%;
        flex-direction: column;
        align-items: flex-start;
        justify-content: flex-start;
        padding-top: $spacer;
        padding-bottom: $spacer;
        padding-left: $spacer * $line-height-base / 2;
        padding-right: $spacer * $line-height-base / 2;
    }

    &__title {
        font-size: $font-size-lg * 0.8;
        font-weight: $font-weight-bold;
        letter-spacing: 0.05em;
        margin-bottom: $spacer * $line-height-base / 2;
    }

    &__author {
        font-size: $font-size-base * 0.8;
        font-weight: $font-weight-bold;
        color: $orange;
        cursor: pointer;
    }

    &__category {
        margin-bottom: $spacer * $line-height-base / 2;

        .badge {
            font-family: $font-family-sans-serif-var;
            font-size: $font-size-base * 0.6;
            letter-spacing: 0.05em;
            text-transform: uppercase;

            &.badge-light {
                background-color: #DCDEE0;
            }
        }
    }

    &__date {
        font-size: $font-size-base * 0.8;
        margin-bottom: $spacer * $line-height-base / 2;
    }
}
</style>
