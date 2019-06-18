require('../bootstrap')

import axios from 'axios'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
import routes from './adminroutes'
import VueDraggabillyPlugin from 'vue-packery-draggabilly-plugin'
import VuejsClipper from 'vuejs-clipper'
import VuePackeryPlugin from './PackeryTest'
import VueLayers from 'vuelayers'
import * as Sentry from '@sentry/browser';
import * as Integrations from '@sentry/integrations';

import 'vuelayers/lib/style.css'

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(VueDraggabillyPlugin)
Vue.use(VuejsClipper)
Vue.use(VueLayers)
Vue.use(VuePackeryPlugin)
Vue.use(VueRouter)

Vue.prototype.$http = axios

Sentry.init({
    dsn: 'https://c9eb05333e3847978f121143675cf80b@sentry.io/1478692',
    integrations: [new Integrations.Vue({
        Vue,
        attachProps: true
    })],
})

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
        data: function () {
            return {
                pages: [],
            }
        },
        methods: {
            goTo: function (name) {
                if (name != this.$route.name) {
                    this.$router.push({
                        name: name
                    })
                }
            },
            goToWithParams: function (name, params) {
                console.log(name, params);
                this.$router.push({
                    name: name,
                    params: params
                })
            }
        }
    })
    .$mount('#admin')
