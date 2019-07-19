
import Vuex from 'vuex'
import VueRouter from 'vue-router'
import BootstrapVue from 'bootstrap-vue'
import Vuetify from 'vuetify'


import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import 'vuetify/dist/vuetify.min.css'

import routers from './route';
import storage from './store';



require('./bootstrap');


window.Vue = require('vue');

Vue.use(VueRouter)
Vue.use(BootstrapVue)
Vue.use(Vuex)
Vue.use(Vuetify)

Vue.component('home-layout', require('./components/front/home.vue').default);
Vue.component('dashboard-layout', require('./components/back/dashboard.vue').default);


const app = new Vue({
    el: '.main',
    router: new VueRouter(routers),
    store: new Vuex.Store(storage)
});


