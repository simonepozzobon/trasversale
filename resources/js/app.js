require('./bootstrap');

import axios from 'axios'
import BootstrapVue from 'bootstrap-vue'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import VuePackeryPlugin from 'vue-packery-plugin'
import VueRouter from 'vue-router'

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
    methods: {
        goTo: function(name) {
            this.$router.push({ name: name })
        },
        goToWithParams: function(name, params) {
            this.$router.push({ name: name, params: params })
        }
    },
}).$mount('#app');
