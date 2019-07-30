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
                cart: []
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
            addToCart: function(content) {
                let itemID = content.item.id
                let itemCheck = this.cart.findIndex(item => item.item.id == itemID)
                if (itemCheck > -1) {
                    this.cart[itemCheck].item.quantity = this.cart[itemCheck].item.quantity + 1
                } else {
                    let item = Object.assign({}, content)
                    item.item.quantity = 1

                    this.cart.push(item)

                }
            },
            showCart: function() {

            }

        },
        mounted: function () {
            // console.log(this.$route.name);
            this.$on('add-to-cart', (content) => {
                this.addToCart(content)
            })
        }
    })
    .$mount('#app');
