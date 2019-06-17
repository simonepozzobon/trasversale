<template lang="html">
    <div class="grid-news">
        <ui-image
            :src="content.thumb"
            :alt="content.title"/>
        <!-- <div class="grid-news__image">
            <img
                class="grid-news__figure"
                :src="content.thumb"
                :alt="content.title" />
        </div> -->
        <div class="grid-news__details">
            <div class="grid-news__category">
                <span
                    class="badge badge-light grid-news__badge"
                    @click="filterCategory">
                    {{ content.category.title }}
                </span>
            </div>
            <div class="grid-news__title" @click="goToNews">
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
import UiImage from './UiImage.vue'

export default {
    name: 'UiSimpleGridNews',
    components: {
        UiBlock,
        UiImage,
    },
    props: {
        block: {
            type: Object,
            default: function () {},
        },
    },
    computed: {
        content: function () {
            if (typeof this.block.content === 'string') {
                return JSON.parse(this.block.content)
            }
            return this.block.content
        },
    },
    methods: {
        goToNews: function () {
            // console.log({
            //     page: this.$route.params.page,
            //     news: this.content.slug.slug,
            // });
            this.$root.goToWithParams('subpage', {
                page: this.$route.params.page,
                subpage: this.content.slug.slug,
            })
        },
        filterCategory: function () {
            this.$emit('filter-category', this.content.category_id)
        }
    },
    mounted: function () {
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
        padding: $spacer $spacer * $line-height-base / 2;
    }

    &__title {
        font-size: $font-size-lg * 0.8;
        font-weight: $font-weight-bold;
        letter-spacing: 0.05em;
        margin-bottom: $spacer * $line-height-base / 2;
        cursor: pointer;
    }

    &__title:hover {
        text-decoration: underline;
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
                color: color-yiq($light);
                transition: $transition-base;
                cursor: pointer;

                &:hover {
                    background-color: $primary;
                    color: $white;
                    transition: $transition-base;
                }
            }
        }
    }

    &__date {
        font-size: $font-size-base * 0.8;
        margin-bottom: $spacer * $line-height-base / 2;
    }
}
</style>
