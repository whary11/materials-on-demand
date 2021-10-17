// Imports
import router from './utils/router'
import App from './App.vue'
import VueRouter from 'vue-router'


require('./bootstrap');


//Uses
window.Vue = require('vue').default;
Vue.use(VueRouter);




const app = new Vue({
    el: '#app',
    router,
    components:{
        App
    },
});
