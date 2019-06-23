<template>
<div class="grid-module">
    <div class="form-group row">
        <div class="col-12">
            <h5>Impostazioni generali modulo</h5>
            <hr />
        </div>
    </div>
    <div class="form-group row">
        <label
            for="title"
            class="col-md-3"
        >
            Titolo
        </label>
        <div class="col-md-9">
            <input
                type="text"
                name="title"
                class="form-control"
                v-model="obj.title"
            >
        </div>
    </div>
    <div class="form-group row">
        <label
            for="type"
            class="col-md-3"
        >
            Tipologia
        </label>
        <div class="col-md-9">
            <select
                type="text"
                name="type"
                class="form-control"
                v-model="obj.type"
            >
                <option value="packery">Mosaico</option>
                <option value="simple">Classica</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label
            for="type"
            class="col-md-3"
        >
            Modalità post
        </label>
        <div class="col-md-9">
            <select
                type="text"
                name="type"
                class="form-control"
                v-model="obj.mode"
            >
                <option value="last">Ultimi Post</option>
                <option value="manual">Selezione Manuale</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label
            for="type"
            class="col-md-3"
        >
            Tipologia di post
        </label>
        <div class="col-md-9">
            <div class="posts-type row">
                <div class="col-md-6">
                    <checkbox-module
                        :val.sync="obj.models[0].products"
                        name="prodotti"
                        label="Prodotti"
                    />
                </div>
                <div class="col-md-6">
                    <checkbox-module
                        :val.sync="obj.models[1].news"
                        name="notizie"
                        label="Notizie"
                    />
                </div>
            </div>
        </div>
    </div>
    <counter-module
        v-if="obj.mode === 'last'"
        label="Numero di post"
        name="post_count"
        :min="0"
        :initial="obj.post_count"
        @changed="updateCounter"
    />
    <grid-builder
        v-if="obj.type === 'packery'"
        :elements.sync="elements"
    />
    <grid-post-selector
        :posts.sync="posts"
        :elements.sync="elements"
        @add-element="addElementToGrid"
    />
</div>
</template>

<script>
import CheckboxModule from '../checkbox/CheckboxModule.vue'
import CounterModule from '../counter/CounterModule.vue'
import GridBuilder from './GridBuilder.vue'
import GridPostSelector from './GridPostSelector.vue'

export default {
    name: 'GridModule',
    components: {
        CheckboxModule,
        CounterModule,
        GridBuilder,
        GridPostSelector,
    },
    data: function () {
        return {
            obj: {
                title: 'Titolo',
                type: 'packery',
                mode: 'last',
                models: [{
                        products: false,
                    },
                    {
                        news: false,
                    },
                ],
                post_count: 0,
            },
            name: 'grid',
            elements: [],
            posts: [],
        }
    },
    watch: {
        'obj.models': {
            handler: function (models) {
                this.resetPostsPool(models)
            },
            deep: true,
        },
        'obj.post_count': function (count) {
            if (this.obj.mode == 'last') {
                // limita i post
                // console.log(this.elements.length);
            }
        },
        elements: {
            handler: function (elements) {
                this.$emit('update', elements)
            },
            deep: true,
        }
    },
    methods: {
        debug: function () {
            this.obj = {
                title: 'Titolo',
                type: 'packery',
                mode: 'last',
                models: [{
                        products: true,
                    },
                    {
                        news: true,
                    },
                ],
                post_count: 10,
            }
        },
        resetPostsPool: function (models) {
            this.posts = []
            let promises = []

            for (let i = 0; i < models.length; i++) {
                let model = models[i]
                let type = Object.keys(model)[0]
                let value = model[type]

                if (value == true) {
                    let url = '/api/admin/grid-elements/' + type
                    let request = this.$http.get(url)
                    promises.push(request)
                }
            }

            this.$http.all(promises).then(results => {
                results.forEach(response => {
                    if (response.data.success) {
                        this.posts = this.posts.concat(response.data.elements)
                        // console.log(this.posts);
                    }
                })
            })
        },
        updateCounter: function (value) {
            this.obj['post_count'] = value
        },
        addElementToGrid: function (element) {
            this.elements.push(element)
        }
    },
    mounted: function () {
        this.$nextTick(() => this.debug())
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
