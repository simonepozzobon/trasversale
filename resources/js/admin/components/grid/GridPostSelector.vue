<template>
<div class="grid-post-selector pot-4">
    <div class="form-group row">
        <div class="col-12">
            <h5>Gestione dei contenuti</h5>
            <hr />
        </div>
    </div>
    <div class="form-group row">
        <label
            for="post-select"
            class="col-md-3"
        >
            Post da selezionare
        </label>
        <div class="col-md-9">
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
                        class="module-item__image-post"
                    />
                </template>
                <template
                    slot="selected"
                    slot-scope="data"
                >
                    <ui-checkbox
                        :value="Boolean(data.item.selected)"
                        @click="selectPost(data.item)"
                    />
                </template>
            </b-table>
        </div>

    </div>
</div>
</template>

<script>
import PostFields from './PostFields'
import {
    Uuid,
    clone
}
from '../../../Utilities'
import {
    formatEl
}
from './GridUtilities'
import {
    UiCheckbox,
    UiSwitch,
}
from '../../../ui'

export default {
    name: 'GridPostSelector',
    components: {
        UiCheckbox,
        UiSwitch,
    },
    props: {
        posts: {
            type: Array,
            default: function () {
                return []
            },
        },
        elements: {
            type: Array,
            default: function () {
                return []
            },
        }
    },
    data: function () {
        return {
            fields: PostFields
        }
    },
    methods: {
        selectPost: function (post) {
            let idx = this.posts.indexOf(post)
            if (idx > -1) {
                let cachePosts = Object.assign([], this.posts)

                cachePosts[idx].selected = !post.selected
                let cacheElements = Object.assign([], this.elements)

                // aggiunge elemento dalla tabella
                if (cachePosts[idx].selected) {
                    let i = this.elements.length
                    let element = formatEl(cachePosts[idx], i, this.elements)

                    cacheElements.push(element)
                }

                // rimuove elemento dalla tabella
                else {
                    let index = this.elements.findIndex(element => element.type_id == post.id && element.type === post.type)
                    if (index > -1) {
                        // force replace
                        cacheElements.splice(index, 1)
                    }
                }

                this.$emit('update:elements', cacheElements)
            }
        },

    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
