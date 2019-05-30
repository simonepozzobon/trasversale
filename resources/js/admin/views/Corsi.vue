<template lang="html">
    <page-template title="Corsi">
        <b-table
            ref="table"
            striped
            hover
            :items="products"
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
                Crea Corso
            </button>
        </div>
    </page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
export default {
    name: 'Corsi',
    components: {
        PageTemplate,
    },
    data: function() {
        return {
            products: [],
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
            this.$http.get('/api/admin/post-type/products').then(response => {
                // console.log(response.data);
                if (response.data.success) {
                    this.products = response.data.elements
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
