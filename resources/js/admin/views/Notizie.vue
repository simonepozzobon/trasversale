<template lang="html">
    <page-template title="Notizie">
        <b-table
            ref="table"
            striped
            hover
            :items="news"
            :fields="fields">
            <template slot="thumb" slot-scope="data">
                <img
                    v-if="data.item.thumb"
                    :src="data.item.thumb"
                    :alt="data.item.title"
                    class="news-item__image" />
            </template>
        </b-table>
        <div class="controls d-flex justify-content-center">
            <button class="btn btn-outline-primary">
                Crea Notizia
            </button>
        </div>
    </page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
export default {
    name: 'Notizie',
    components: {
        PageTemplate,
    },
    data: function() {
        return {
            news: [],
            fields: [{
                    key: 'id',
                    label: 'ID',
                    sortable: true,
                },
                {
                    key: 'title',
                    label: 'Titolo',
                    sortable: true,
                },
                {
                    key: 'thumb',
                    label: 'Immagine',
                    sortable: false
                }
            ],
        }
    },
    methods: {
        getPosts: function() {
            this.$http.get('/api/admin/post-type/news').then(response => {
                // console.log(response.data);
                if (response.data.success) {
                    this.news = response.data.elements
                }
            })
        }
    },
    mounted: function() {
        this.getPosts()
    }
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';

.news-item {
    &__image {
        max-width: $spacer * 4;
    }
}
</style>
