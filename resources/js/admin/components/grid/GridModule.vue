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
    <div
        class="form-group row"
        v-if="obj.type === 'packery'"
    >
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
    <div
        class="form-group row"
        v-else
    >
        <label
            for="type"
            class="col-md-3"
        >
            Tipologia di post
        </label>
        <div class="col-md-9">
            <select
                class="form-control"
                name="post_type"
                v-model="postType"
            >
                <option value="products">Prodotti</option>
                <option value="news">News</option>
            </select>
        </div>
    </div>

    <counter-module
        v-if="obj.type === 'simple'"
        label="Post per riga"
        name="post_per_row"
        :min="0"
        :max="6"
        :initial="obj.post_per_row"
        @changed="updateRowCounter"
    />
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
        v-if="obj.mode === 'manual'"
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
import {
    formatEl
}
from './GridUtilities'

export default {
    name: 'GridModule',
    components: {
        CheckboxModule,
        CounterModule,
        GridBuilder,
        GridPostSelector,
    },
    props: {
        initial: {
            type: Object,
            default: function () {
                return {}
            },
        },
    },
    data: function () {
        return {
            obj: {
                type: 'simple',
                mode: 'last',
                models: [{
                        products: false,
                    },
                    {
                        news: false,
                    },
                ],
                post_count: 0,
                post_per_row: 0,
            },
            name: 'grid',
            elements: [],
            posts: [],
            postType: null,
        }
    },
    watch: {
        initial: function () {
            this.setInitial()
        },
        postType: function (type) {
            this.elements = []
            for (let i = 0; i < this.obj.models.length; i++) {
                let key = Object.keys(this.obj.models[i])[0]
                if (key == type) {
                    this.obj.models[i][key] = true
                }
                else {
                    this.obj.models[i][key] = false
                }
            }
            this.addElements()

        },
        obj: {
            handler: function (obj) {
                this.updateParent()
            },
            deep: true
        },
        'obj.models': {
            handler: function (models) {
                this.resetPostsPool(models)
            },
            deep: true,
        },
        'obj.type': {
            handler: function (type) {
                if (type === 'simple') {
                    this.elements = []
                }
            },
            deep: true,
        },
        'obj.post_count': function (count) {
            if (this.obj.mode == 'last') {
                // limita i post
                this.addElements()
            }
        },
        'obj.post_per_row': {
            handler: function (count) {

                // console.log('post_per_row');
            },
            deep: true
        },
        elements: {
            handler: function (elements) {
                // this.$emit('update', elements)
                this.updateParent()
            },
            deep: true,
        }
    },
    methods: {
        updateParent: function () {
            let obj = {
                ...this.obj,
                elements: this.elements
            }
            // console.log('update', obj);
            this.$emit('update', obj)
        },
        debug: function () {
            this.obj = {
                type: 'simple',
                mode: 'last',
                models: [{
                        products: true,
                    },
                    {
                        news: false,
                    },
                ],
                post_count: 10,
                post_per_row: 0,
            }
        },
        resetPostsPool: function (models) {
            return new Promise(resolve => {
                let posts = []
                this.posts = []
                let promises = []

                for (let i = 0; i < models.length; i++) {
                    let model = models[i]
                    let type = Object.keys(model)[0]
                    let value = model[type]

                    if (value == true) {
                        let url = '/api/admin/grid-elements/' + type
                        // console.log('uuuuor', url);
                        let request = this.$http.get(url)
                        promises.push(request)
                    }
                }

                this.$http.all(promises).then(results => {
                    results.forEach((response, i) => {
                        if (response.data.success) {
                            posts = posts.concat(response.data.elements)
                            // console.log(this.posts);
                            if (i === promises.length - 1) {
                                this.posts = posts
                                resolve(posts)
                            }
                        }
                    })
                })
            })

        },
        updateCounter: function (value) {
            this.obj['post_count'] = value
        },
        updateRowCounter: function (value) {
            this.obj['post_per_row'] = value
        },
        addElementToGrid: function (element) {
            this.elements.push(element)
        },
        addElements: function () {
            this.resetPostsPool(this.obj.models).then(posts => {
                // console.log('qui', posts);
                let length = this.elements.length
                let count = this.obj.post_count
                // console.log('prima', this.elements.length, length, count);
                if (length == 0) {
                    // console.log('da zero');
                    posts.slice(0, count).forEach((element, i) => {
                        let newElement = formatEl(element, i, this.elements)
                        this.elements.push(newElement)
                    })
                }
                else if (length < count) {
                    // console.log('devo aggiungere un post');
                    let start = length - 1
                    let difference = count - length
                    posts.slice(start, difference).forEach((element, i) => {
                        // console.log(i);
                        let newElement = formatEl(element, this.elements.length, this.elements)
                        this.elements.push(newElement)
                    })
                    // console.log('differenza', start, difference, newpost);

                }
                else if (length >= count) {
                    // console.log('devo togliere un post');
                    let difference = Math.abs(count - length)
                    let idx = this.elements.length - difference
                    this.elements.splice(idx, difference)
                    // console.log(idx, difference);
                }

                else if (count == 0) {
                    this.elements = []
                }
                else {
                    console.log(length);
                    // console.log('nulla', length, count);
                }
                // console.log('dopo', this.elements.length, length, count);
            })
            // console.log('qui', this.elements);

        },
        setInitial: function () {
            // console.log(this.initial);
        }
    },
    mounted: function () {
        this.setInitial()
        this.$nextTick(() => {
            this.debug()
        })
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
