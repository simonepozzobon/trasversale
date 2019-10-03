require('./bootstrap');

import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import VueLayers from 'vuelayers'
// import VuePackeryPlugin from 'vue-packery-plugin'
import VueRouter from 'vue-router'
import * as Sentry from '@sentry/browser';
import * as Integrations from '@sentry/integrations';
import routes from './routes'
import 'vuelayers/lib/style.css'

Vue.config.productionTip = false

Vue.use(VueRouter)
Vue.use(VueLayers)
// Vue.use(VuePackeryPlugin)
Vue.use(BootstrapVue)

Vue.prototype.$http = axios

const router = new VueRouter({
    mode: 'history',
    dir: __dirname,
    routes: routes,
})

const app = new Vue({
        router,
        components: {
            MainTemplate,
        },
        data: function () {
            return {
                sidebarPaddingTop: false,
                sidebar: {
                    modules: []
                },
                cart: [],
                search: null,
                searchResults: [],
                products: [],
            }
        },
        methods: {
            goTo: function (name) {
                this.$router.push({
                    name: name
                })
            },
            goToWithParams: function (name, params) {
                // console.log(name, params);
                this.$router.push({
                    name: name,
                    params: params
                })
            },
            addToCart: function (content) {
                // console.log('add');
                let itemID = content.item.id
                let idx = this.cart.findIndex(item => item.item.id == itemID)
                if (idx > -1) {
                    let item = Object.assign({}, this.cart[idx])
                    item.item.quantity = item.item.quantity + 1

                    this.cart.splice(idx, 1, item)
                }
                else {
                    let item = Object.assign({}, content)
                    item.item.quantity = 1

                    this.cart.push(item)

                }
            },
            showCart: function () {

            }

        },
        created: function () {
            this.$http.get('/api/get-products').then(response => {
                this.products = response.data.products
            })
        },
        mounted: function () {
            // console.log(this.$route.name);
            this.$on('add-to-cart', (content) => {
                this.addToCart(content)
            })
        }
    })
    .$mount('#app');
