require('./bootstrap');

import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import VuePackeryPlugin from 'vue-packery-plugin'
import VueRouter from 'vue-router'
import * as Sentry from '@sentry/browser';
import * as Integrations from '@sentry/integrations';

import routes from './routes'

Vue.use(VueRouter)
Vue.use(VuePackeryPlugin)
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
            }
        },
        mounted: function () {
            // console.log(this.$route.name);
        }
    })
    .$mount('#app');
