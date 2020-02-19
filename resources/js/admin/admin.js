require('../bootstrap')

import axios from 'axios'
import MainTemplate from './containers/MainTemplate.vue'
import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import VueRouter from 'vue-router'
import routes from './adminroutes'
import VuejsClipper from 'vuejs-clipper'
import Cookie from './Cookies'

import VueLayers from 'vuelayers'
import {
    gsap
}
from 'gsap'
import {
    CSSPlugin
}
from 'gsap/CSSPlugin'
gsap.registerPlugin(CSSPlugin)

console.log(gsap)
// import * as Sentry from '@sentry/browser';
// import * as Integrations from '@sentry/integrations';

import 'vuelayers/lib/style.css'

Vue.config.productionTip = false

Vue.use(BootstrapVue)
Vue.use(VuejsClipper)
Vue.use(VueLayers)
Vue.use(VueRouter)

Vue.prototype.$http = axios
Vue.prototype.$cookie = Cookie

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
    base: '/bi0id9B'
})

const redirectLogin = (app) => {
    app.$nextTick(() => {
        app.goTo('login')
    })
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        const app = router.app
        let user = app.user
        let token = app.token

        if ((user && token)) {
            // Procedi
            // console.log('procedi');
            next()
        }
        else if (typeof user == 'undefined' || typeof token == 'undefined') {
            let app = router.app
            let user = app.$cookie.get('trasversale-user')
            user = JSON.parse(user) ? JSON.parse(user) : null

            let auth = app.$http.defaults.headers.common.hasOwnProperty('Authorization')

            if (user && !auth) {
                let data = new FormData()
                data.append('id', user.id)
                data.append('email', user.email)

                app.$http.post('/api/get-token', data).then(response => {
                    if (response.data.success) {
                        app.user = response.data.user
                        app.token = response.data.token
                        app.$cookie.set('trasversale-logged', true)
                        app.$cookie.set('trasversale-user', JSON.stringify(app.user))
                        app.$cookie.set('trasversale-token', JSON.stringify(app.token))
                        app.$http.defaults.headers.common.Authorization = `${app.token.token_type} ${app.token.access_token}`

                        // console.log('procedi dopo riautenticazione');
                        next()
                    }
                    else {
                        app.$cookie.destroy('trasversale-logged')
                        app.$cookie.destroy('trasversale-user')
                        app.$cookie.destroy('trasversale-token')
                        delete app.$http.defaults.headers.common.Authorization

                        // console.log('autenticazione cookies non riuscita');
                        redirectLogin(app)
                    }
                })
            }
            else {
                // console.log('user e headers non ci sono nei cookies ->headers', app);
                if (app) {
                    redirectLogin(app)
                }
                else {
                    console.log('router', router);
                }
                return false
            }
        }
        else {
            // console.log('user e token non esistono', app);
            redirectLogin(app)
            return false
        }
    }
    else {
        // console.log('nessuna autorizzazione');
        next()
    }
})

const admin = new Vue({
        router,
        components: {
            MainTemplate
        },
        data: function () {
            return {
                pages: [],
                hasSidebar: true,
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
            sidebarEl: function () {
                this.init()
            },
            mainEl: function () {
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
            toggleSide: function () {
                if (this.sideIsOpen) {
                    this.hide()
                    this.sideIsOpen = false
                }
                else {
                    this.show()
                    this.sideIsOpen = true
                }
            },
            initLogin: function () {
                let user = this.$cookie.get('trasversale-user')
                user = JSON.parse(user) ? JSON.parse(user) : null

                if (user) {
                    let data = new FormData()
                    data.append('id', user.id)
                    data.append('email', user.email)

                    this.$http.post('/api/get-token', data).then(response => {
                        if (response.data.success) {
                            this.user = response.data.user
                            this.token = response.data.token
                            if (this.$route.name == 'login') {
                                this.login(true)
                            }
                            else {
                                this.login()
                            }
                        }
                        else {
                            this.logout()
                        }
                    })
                }
                else {
                    this.logout()
                }
            },
            login: function (redirectToHome = false) {
                // console.log(this.$cookie);
                this.$cookie.set('trasversale-logged', true)
                this.$cookie.set('trasversale-user', JSON.stringify(this.user))
                this.$cookie.set('trasversale-token', JSON.stringify(this.token))
                this.logged = true
                this.$http.defaults.headers.common.Authorization = `${this.token.token_type} ${this.token.access_token}`

                if (redirectToHome == true) {
                    this.goTo('home')
                }
            },
            logout: function () {
                this.user = null
                this.token = null
                this.logged = false

                delete this.$http.defaults.headers.common.Authorization
                this.goTo('login')
            },
            init: function () {
                if (this.sidebarEl && this.mainEl) {
                    if (!this.anim) {
                        let side = this.sidebarEl
                        let main = this.mainEl
                        let col10 = (10 * 100 / 12) + '%'
                        let col2 = (2 * 100 / 12) + '%'

                        this.anim = gsap.timeline({
                            paused: true,
                            yoyo: true
                        })

                        this.anim.fromTo(side, {
                            duration: .3,
                            css: {
                                flex: '0 0 0',
                                maxWidth: 0,
                            }
                        }, {
                            duration: .3,
                            css: {
                                flex: '0 0 ' + col2,
                                maxWidth: col2,
                            },
                            ease: 'power4.inOut',
                        }, 0)


                        this.anim.fromTo(main, {
                            duration: .3,
                            css: {
                                flex: '0 0 100%',
                                maxWidth: '100%',
                            }
                        }, {
                            duration: .3,
                            css: {
                                flex: '0 0 ' + col10,
                                maxWidth: col10,
                            },
                            ease: 'power4.inOut',
                        }, 0)

                        this.anim.progress(1).progress(0)
                    }
                }
            },
            show: function () {
                if (this.anim) {
                    this.anim.play()
                }
            },
            hide: function () {
                if (this.anim) {
                    this.anim.reverse()
                }
            }
        },
        created: function () {
            this.initLogin()
        },
        mounted: function () {
            this.init()
        }
    })
    .$mount('#admin')
