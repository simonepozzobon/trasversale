<template>
<div class="search-result">
    <ui-row>
        <div class="col-md-4">
            <ui-image
                :src="result.searchable.thumb"
                :alt="result.title"
            />
        </div>
        <div class="col-md-8">
            <div class="search-result__info">
                <a
                    href="#"
                    @click.prevent="goToResult"
                    class="text-dark d-block"
                >
                    <ui-title
                        font-size="h6"
                        tag="h3"
                        :title="result.title"
                        :is-column="true"
                        class="mb-0"
                    />
                </a>
                <ui-button
                    :title="btnText"
                    color="primary"
                    :has-margin="false"
                    @click="goToResult"
                />
            </div>
        </div>
    </ui-row>
</div>
</template>

<script>
import {
    UiButton,
    UiImage,
    UiRow,
    UiTitle,
}
from '../ui'
export default {
    name: 'SearchResult',
    components: {
        UiButton,
        UiImage,
        UiRow,
        UiTitle,
    },
    props: {
        result: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    computed: {
        type: function () {
            return this.result.type
        },
        btnText: function () {
            if (this.type == 'News') {
                return 'Vai alla News'
            }
            else if (this.type == 'Product') {
                return 'Vai al Corso'
            }
            else if (this.type == 'SubPage' || this.type == 'StaticPage') {
                return 'Vai alla Pagina'
            }

            return 'Apri'
        },
        routeParams: function () {
            if (this.type == 'SubPage') {
                return {
                    name: 'subpage',
                    params: {
                        page: this.result.searchable.page.slug.slug,
                        subpage: this.result.searchable.slug.slug
                    }
                }
            }
            else if (this.type == 'Product') {
                return {
                    name: 'subpage',
                    params: {
                        page: 'corsi',
                        subpage: this.result.searchable.slug.slug
                    }
                }
            }
            else if (this.type == 'News') {
                return {
                    name: 'subpage',
                    params: {
                        page: 'notizie',
                        subpage: this.result.searchable.slug.slug
                    }
                }
            }
            else if (this.type == 'StaticPage') {
                return {
                    name: 'page',
                    params: {
                        page: this.result.searchable.slug.slug,
                    }
                }
            }
        },
    },
    methods: {
        goToResult: function () {
            this.$root.goToWithParams(this.routeParams.name, this.routeParams.params)
        },
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.search-result {
    margin-bottom: $spacer * 3;

    &__info {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
}
</style>
