require('../bootstrap')

import axios from 'axios'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
import routes from './adminroutes'
import VuejsClipper from 'vuejs-clipper'

import VueLayers from 'vuelayers'
// import * as Sentry from '@sentry/browser';
// import * as Integrations from '@sentry/integrations';

import 'vuelayers/lib/style.css'

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(VuejsClipper)
Vue.use(VueLayers)
Vue.use(VueRouter)

Vue.prototype.$http = axios

// Sentry.init({
//     dsn: 'https://c9eb05333e3847978f121143675cf80b@sentry.io/1478692',
//     integrations: [new Integrations.Vue({
//         Vue,
//         attachProps: true
//     })],
// })

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
                sideIsOpen: false,
                sidebarEl: null,
                mainEl: null,
                initialized: false,
                anim: null,
            }
        },
        watch: {
            // '$route': function(route) {
            //     // console.log(route.path);
            // },
            sidebarEl: function() {
                this.init()
            },
            mainEl: function() {
                this.init()
            }
        },
        methods: {
            goTo: function (name) {
                // console.log(name);
                if (name != this.$route.name) {
                    this.$router.push({
                        name: name
                    })
                }
            },
            goToWithParams: function (name, params) {
                // console.log(name, params);
                this.$router.push({
                    name: name,
                    params: params
                })
            },
            toggleSide: function() {
                if (this.sideIsOpen) {
                    this.hide()
                    this.sideIsOpen = false
                } else {
                    this.show()
                    this.sideIsOpen = true
                }
            },
            init: function() {
                if (this.sidebarEl && this.mainEl) {
                    if (!this.anim) {
                        let side = this.sidebarEl
                        let main = this.mainEl
                        let col10 = (10 * 100 / 12) + '%'
                        let col2 = (2 * 100 / 12) + '%'

                        this.anim = new TimelineMax({
                            paused: true,
                            yoyo: true
                        })

                        this.anim.fromTo(side, .3, {
                            css: {
                                flex: '0 0 0',
                                maxWidth: 0,
                            }
                        }, {
                            css: {
                                flex: '0 0 ' + col2,
                                maxWidth: col2,
                            },
                            ease: Power4.easeInOut
                        }, 0)


                        this.anim.fromTo(main, .3, {
                            css: {
                                flex: '0 0 100%',
                                maxWidth: '100%',
                            }
                        }, {
                            css: {
                                flex: '0 0 ' + col10,
                                maxWidth: col10,
                            },
                            ease: Power4.easeInOut
                        }, 0)

                        this.anim.progress(1).progress(0)
                    }
                }
            },
            show: function() {
                if (this.anim) {
                    this.anim.play()
                }
            },
            hide: function() {
                if (this.anim) {
                    this.anim.reverse()
                }
            }
        },
        mounted: function() {
            this.init()
        }
    })
    .$mount('#admin')
