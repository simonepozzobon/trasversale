require('./bootstrap');

import Vue from 'vue'

import VuePackeryPlugin from 'vue-packery-plugin'
import BootstrapVue from 'bootstrap-vue'

Vue.use(VuePackeryPlugin)
Vue.use(BootstrapVue)

import MainGridContainer from './containers/MainGridContainer.vue'
import SidebarCalendar from './components/SidebarCalendar.vue'

const app = new Vue({
    components: {
        MainGridContainer,
        SidebarCalendar,
    }
}).$mount('#app');
