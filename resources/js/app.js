// Imports
import router from './utils/router'
import App from './App.vue'
import VueRouter from 'vue-router'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import canMixin from './utils/mixins/can'
import userMixin from './utils/mixins/user'



require('./bootstrap');


//Uses
window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Loading);

// mixins
Vue.mixin(canMixin)
Vue.mixin(userMixin)







const app = new Vue({
    el: '#app',
    router,
    components:{
        App
    },
});
