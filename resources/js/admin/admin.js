require('../bootstrap')

import axios from 'axios'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
import routes from './adminroutes'

Vue.use(VueRouter)
Vue.use(BootstrapVue)
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
    },
    methods: {
        goTo: function(name) {
            this.$router.push({ name: name })
        }
    }
}).$mount('#admin')
