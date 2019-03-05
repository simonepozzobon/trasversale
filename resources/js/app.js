require('./bootstrap');

import Vue from 'vue'

import VuePackeryPlugin from 'vue-packery-plugin'
import BootstrapVue from 'bootstrap-vue'

Vue.use(VuePackeryPlugin)
Vue.use(BootstrapVue)

import MainContent from './containers/MainContent.vue'
import MainGridContainer from './containers/MainGridContainer.vue'
import SidebarCalendar from './components/SidebarCalendar.vue'
import SidebarInformation from './components/SidebarInformation.vue'

const app = new Vue({
    components: {
        MainContent,
        MainGridContainer,
        SidebarCalendar,
        SidebarInformation,
    }
}).$mount('#app');