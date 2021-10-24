// requireds
require('./bootstrap');
// Imports
import router from './utils/router'
import App from './App.vue'
import VueRouter from 'vue-router'
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
import canMixin from './utils/mixins/can'
import userMixin from './utils/mixins/user'
import {ServerTable} from 'vue-tables-2';
import VModal from 'vue-js-modal/dist/index.nocss.js'
import 'vue-js-modal/dist/styles.css'





//Uses
window.Vue = require('vue').default;
Vue.use(VueRouter);
Vue.use(Loading);
Vue.use(ServerTable,{
    perPage: 15,
    texts: {
        count: "Mostrando del {from} al {to} de {count} Registros|{count} Registros|1 Registro",
        first: "First",
        last: "Last",
        filter: "",
        records:"......",
        filterPlaceholder: "Buscar registro",
        limit: "",
        page: "Page:",
        noResults: "Sin resultados",
        filterBy: "{column}",
        loading: "Cargando...",
        defaultOption: "Todos",
        columns: "Columnas"
    }

})
Vue.use(VModal)



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
