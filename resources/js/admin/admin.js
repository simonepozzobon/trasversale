require('../bootstrap')

import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './adminroutes'
import axios from 'axios'
import MainTemplate from './containers/MainTemplate.vue'

Vue.use(VueRouter)
Vue.prototype.$http = axios

const router = new VueRouter({
    mode: 'history',
    dir: __dirname,
    routes: routes,
    base: '/admin'
})

const admin = new Vue({
    router,
    components: {
        MainTemplate
    },
    data: function() {
        return {
            pages: [],
        }
    }
}).$mount('#admin')
