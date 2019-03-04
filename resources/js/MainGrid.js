require('./bootstrap')

import Vue from 'vue'
import VuePackeryPlugin from 'vue-packery-plugin'

Vue.use(VuePackeryPlugin)

import MainGridContainer from './containers/MainGridContainer.vue'

const MainGrid = new Vue({
    name: 'MainGridRoot',
    components: {
        MainGridContainer
    },
}).$mount('#main-grid')
