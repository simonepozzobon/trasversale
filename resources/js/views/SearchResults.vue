<template>
<div class="search-results">
    <ui-title
        :title="title"
        font-size="h3"
    />
    <div class="search-results__container">
        <search-result
            v-for="result in results"
            :key="result.uuid"
            :result="result"
        />
    </div>
</div>
</template>

<script>
import SearchResult from '../components/SearchResult.vue'
import {
    UiTitle
}
from '../ui'

export default {
    name: 'SearchResults',
    components: {
        SearchResult,
        UiTitle,
    },
    computed: {
        title: function () {
            if (this.$root.search) {
                return 'Risultati di ricerca per: ' + this.$root.search
            }
        },
        results: function () {
            return this.$root.searchResults
        }
    },
    watch: {
        results: function (results) {
            console.log(this.results[0]);
        }
    },
    methods: {

    },
    mounted: function () {},
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.search-results {
    &__container {
        margin-top: $spacer * 3;
    }
}
</style>
