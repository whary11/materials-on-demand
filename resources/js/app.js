// Imports
import router from './utils/router'
import App from './App.vue'
import VueRouter from 'vue-router'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';


require('./bootstrap');


//Uses
window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Loading);




const app = new Vue({
    el: '#app',
    router,
    components:{
        App
    },
});
