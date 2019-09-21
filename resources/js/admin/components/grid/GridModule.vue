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
            for="grid_type"
            class="col-md-3"
        >
            Tipologia
        </label>
        <div class="col-md-9">
            <select
                type="text"
                name="grid_type"
                class="form-control"
                v-model="gridType"
            >
                <option value="packery">Mosaico</option>
                <option value="simple">Classica</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label
            for="mode"
            class="col-md-3"
        >
            Modalità post
        </label>
        <div class="col-md-9">
            <select
                type="text"
                name="mode"
                class="form-control"
                v-model="mode"
            >
                <option value="last">Ultimi Post</option>
                <option value="manual">Selezione Manuale</option>
            </select>
        </div>
    </div>
    <div
        class="form-group row"
        v-if="gridType === 'packery'"
    >
        <label
            for="type"
            class="col-md-3"
        >
            Tipologia di post
        </label>
        <div class="col-md-9">
            <div class="posts-type row">
                <div class="col-md-4">
                    <checkbox-module
                        :val.sync="products"
                        name="prodotti"
                        label="Prodotti"
                    />
                </div>
                <div class="col-md-4">
                    <checkbox-module
                        :val.sync="news"
                        name="notizie"
                        label="Notizie"
                    />
                </div>
                <div class="col-md-4">
                    <checkbox-module
                        :val.sync="pages"
                        name="pages"
                        label="Pagine"
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
        v-if="gridType === 'simple'"
        label="Post per riga"
        name="post_per_row"
        :min="0"
        :max="6"
        :initial="post_per_row"
        @changed="updateRowCounter"
    />
    <counter-module
        v-if="mode === 'last'"
        label="Numero di post"
        name="post_count"
        :min="0"
        :initial="post_count"
        @changed="updateCounter"
    />
    <grid-builder
        v-if="gridType === 'packery'"
        :elements="elements"
        @update="updatePackeryGrid"
    />
    <grid-post-selector
        v-if="mode === 'manual'"
        :posts.sync="posts"
        :elements="elements"
        @update-elements="updateElements"
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
                        products: true,
                    },
                    {
                        news: false,
                    },
                    {
                        pages: false,
                    },
                ],
                post_count: 2,
                post_per_row: 2,
            },
            name: 'grid',
            elements: [],
            posts: [],
            postType: 'products',
            initialized: false,

            gridType: 'simple',
            mode: 'last',
            products: true,
            news: false,
            pages: false,
            post_count: 2,
            post_per_row: 2,
            counter: 0,
        }
    },
    watch: {
        postType: function (type, prev) {
            if (prev != type) {
                //update
                if (type == 'products') {
                    this.products = true
                    this.news == false
                }
                else if (type == 'news') {
                    this.news = true
                    this.products = false
                }
            }
        },
        obj: {
            handler: function (obj, prev) {
                // console.log('handlerrr', this.initialized);
                // console.log('handlerrr', Object.assign([], this.elements));
                if (this.initialized == true) {
                    let newObj = Object.assign({}, obj)
                    newObj['elements'] = Object.assign([], this.elements)
                    // console.log(obj.elements, newObj['elements']);
                    this.$emit('update', newObj)
                }
            },
            deep: true
        },
        mode: function (mode, prev) {
            if (mode != prev) {
                // update
                this.counter = this.counter + 1
                this.updatePosts().then(() => {
                    this.obj.mode = mode
                })
            }
        },
        gridType: function (type, prev) {
            if (prev != type) {
                //update
                this.counter = this.counter + 1
                // console.log('gridType', type);
                this.updatePosts().then(() => {
                    this.obj.type = type
                })
            }
        },
        post_count: function (count, prev) {
            if (prev != count) {
                //update
                this.counter = this.counter + 1
                this.updatePosts(false).then(() => {
                    this.obj.post_count = count
                })
            }
        },
        post_per_row: function (count, prev) {
            if (prev != count) {
                //update
                this.counter = this.counter + 1
                this.updatePosts(false).then(() => {
                    this.obj.post_per_row = count
                })
            }
        },
        products: function (state, prev) {
            if (prev != state) {
                //update
                this.counter = this.counter + 1
                this.updatePosts().then(() => {
                    this.obj.models[0].products = state
                })
            }
        },
        news: function (state, prev) {
            if (prev != state) {
                //update
                this.counter = this.counter + 1
                this.updatePosts().then(() => {
                    this.obj.models[1].news = state
                })
            }
        },
        pages: function (state, prev) {
            if (prev != state) {
                //update
                this.counter = this.counter + 1
                this.updatePosts().then(() => {
                    this.obj.models[2].pages = state
                })
            }
        },
        counter: function (value) {
            if (value <= 0) {
                this.initialized = true
            }
        },
        initialized: function (value) {
            // console.log('initialized', value, this.initial);
            if (this.initial && this.initial.blocks && this.initial.blocks.length > 0) {
                if (this.mode == 'manual') {
                    let elements = this.initial.blocks.map((block, i) => {
                        return formatEl(block, i, this.initial.blocks)
                    })

                    let posts = Object.assign([], this.posts)
                    let idxs = []

                    elements.forEach(element => {
                        let idx = posts.findIndex(post => post.id == element.type_id && post.type == element.type)
                        if (idx > -1) {
                            posts[idx].selected = 1
                        }
                    })

                    this.posts = Object.assign([], posts)
                    this.updateElements(elements)

                    // this.elements = this.initial.blocks
                }
            }
        },
    },
    methods: {
        updateElements: function (elements) {
            // console.log('quii', elements);
            this.elements = elements
            this.obj = {
                ...this.obj,
                elements: elements
            }
        },
        updatePackeryGrid: function (elements) {
            this.obj = {
                ...this.obj,
                elements: elements
            }
        },
        updatePosts: function (resetPost = true) {
            return new Promise((resolve, reject) => {
                // console.log('updating');
                // console.log(this.obj.models);
                // console.log('update posts', this.obj.post_count);
                // console.log(this.initialized);
                if (resetPost) {
                    this.posts = []
                }

                let models = [{
                        products: this.products,
                    },
                    {
                        news: this.news,
                    },
                    {
                        pages: this.pages,
                    }
                ]

                this.resetPostsPool(models, resetPost).then(posts => {
                    // console.log('updated', posts, resetPost, this.posts);

                    if (resetPost) {
                        this.elements = []
                        this.posts = []
                        this.posts = posts
                    }
                    // else {
                    //     let selected = this.posts.filter(post => post.selected == 1)
                    //     console.log(selected);
                    // }

                    if (this.mode == 'last') {

                        let elements = []

                        posts.forEach((element, i) => {
                            if (i < this.post_count) {
                                let newElement = formatEl(element, i, elements)
                                elements.push(newElement)
                            }
                        })

                        this.elements = elements
                        // console.log('updated elemets');
                    }

                    this.$nextTick(() => {
                        this.counter = this.counter - 1
                        resolve()
                    })
                    // this.updateParent()
                })
            });
        },
        updateParent: function () {
            // let obj = {
            //     ...this.obj,
            //     elements: this.elements
            // }
            // // console.log('update', obj);
            // this.$emit('update', obj)
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
        resetPostsPool: function (models, resetPost = true) {
            return new Promise(resolve => {
                let posts = []
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
                                resolve(posts)
                            }
                        }
                    })
                })
            })

        },
        updateCounter: function (value) {
            this.post_count = value
            // this.obj = {
            //     ...this.obj,
            //     post_count: value
            // }
        },
        updateRowCounter: function (value) {
            this.post_per_row = value
            // this.obj = {
            //     ...this.obj,
            //     post_per_row: value
            // }
        },
        addElementToGrid: function (element) {
            // console.log('aggiungo alla griglia');
            this.elements.push(element)
            this.obj.elements = this.elements
        },
        addElements: function () {
            this.resetPostsPool(this.obj.models).then(posts => {
                // console.log('qui', posts, this.obj);
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
            // console.log('initial', this.initial);
            if (this.initial) {
                let options

                if (this.initial.options) {
                    options = JSON.parse(this.initial.options)
                    // console.log('abbiamo opzioni');
                }

                // console.log('opzioni', options);

                this.gridType = this.initial.type ? this.initial.type : 'simple'
                this.mode = options.mode ? options.mode : 'last'
                this.products = options.models ? options.models[0].products : false
                this.news = options.models ? options.models[1].news : false
                this.pages = options.models ? options.models[2].pages : false
                this.post_count = options.post_count ? options.post_count : 2
                this.post_per_row = options.post_per_row ? options.post_per_row : 2

                // console.log('initial', newObj);

                // this.obj = this.initial

                // if (newObj.mode == 'last') {
                //     this.updatePosts()
                // }
                // else {
                //     this.updatePosts(false)
                // }
            }
            else {
                console.log('no initial');
            }
        }
    },
    mounted: function () {
        this.$nextTick(() => {
            this.setInitial()
        })
        // this.$nextTick(() => {
        //     this.debug()
        // })
    },
}
</script>

<style lang="scss" scoped>
@import '~styles/shared';
</style>
