<template>
<page-template
    :title="opts.title"
    :notifications="notifications"
>
    <b-table
        ref="table"
        striped
        hover
        :items="posts"
        :fields="fields"
    >
        <template
            slot="thumb"
            slot-scope="data"
        >
            <img
                v-if="data.item.thumb"
                :src="data.item.thumb"
                :alt="data.item.title"
                class="news-item__image"
            />
        </template>
        <template
            slot="subscribers_tools"
            slot-scope="data"
        >
            <button
                class="btn btn-outline-green"
                @click="manageSubscribers(data.item)"
            >
                Iscritti
            </button>
        </template>
        <template
            slot="tools"
            slot-scope="data"
        >
            <button
                class="btn btn-outline-primary"
                @click="editPost(data.item)"
            >
                Modifica
            </button>
            <button
                class="btn btn-outline-danger"
                @click="deletePost(data.item)"
            >
                Elimina
            </button>
        </template>
    </b-table>
    <div class="controls d-flex justify-content-center">
        <button
            class="btn btn-outline-primary"
            @click="createPost"
        >
            {{ opts.create }}
        </button>
    </div>
</page-template>
</template>

<script>
import PageTemplate from '../containers/PageTemplate.vue'
import PostsOpts from '../postsopts/PostsOpts'
import {
    Uuid
}
from '../../Utilities'

export default {
    name: 'Notizie',
    components: {
        PageTemplate,
    },
    data: function () {
        return {
            posts: [],
            fields: [],
            notifications: [],
        }
    },
    watch: {
        '$route.params': function (params) {
            this.getPosts()
        }
    },
    computed: {
        opts: function () {
            return PostsOpts.find(opt => opt.slug === this.$route.params.type)
        },
    },
    methods: {
        getPosts: function () {
            this.posts = []
            this.fields = this.opts.fields
            this.fields.push({
                key: 'tools',
                label: 'tools'
            })
            let url = '/api/admin/post-type/' + this.opts.table
            this.$http.get(url)
                .then(response => {
                    if (response.data.success) {
                        this.posts = response.data.elements
                    }
                })
        },
        deletePost: function (item) {
            let url = '/api/admin/post-type/' + this.opts.table + '/delete/' + item.id
            this.$http.delete(url)
                .then(response => {
                    if (response.status) {
                        let idx = this.posts.findIndex(post => post.id === response.data.element.id)
                        if (idx > -1) {
                            this.notifications.push({
                                uuid: Uuid.get(),
                                title: 'Post Eliminato',
                                message: response.data.element.title + ' è stato eliminato'
                            })
                            this.posts.splice(idx, 1)
                        }
                    }
                })
        },
        createPost: function () {
            this.$root.goToWithParams('post-create', {
                type: this.$route.params.type
            })
            console.log('crea post');
        },
        editPost: function (item) {
            this.$root.goToWithParams('post-edit', {
                type: this.$route.params.type,
                id: item.id
            })
        },
        manageSubscribers: function (item) {
            this.$root.goToWithParams('manage-subscribers', {
                id: item.id
            })
        },
    },
    mounted: function () {
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
