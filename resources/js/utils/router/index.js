import VueRouter from 'vue-router'




// Modules
import authRoutes from './modules/auth'
import userRoutes from './modules/user'
import purchase from './modules/purchase'
// import { beforeEnter } from '../../functions/routerFunctions.js.js'

const routes =  [
    ...authRoutes,
    ...userRoutes,
    ...purchase,
]


const router = new VueRouter({
    history: true,
    mode: 'history',
    routes // short for `routes: routes`
})

router.beforeEach((to, from, next) => {
  let token = localStorage.getItem('token')
  console.log(to.name);
  if ((to.name == 'login' || to.name == 'register') && (token && token != 'undefined')) {
    next({name:'dashboard'});
    return
  }
  if ((to.name == 'dashboard') && (!token || token == 'undefined')) {
    next({name:'login'});
    return
  }
  next()
})


export default router;
