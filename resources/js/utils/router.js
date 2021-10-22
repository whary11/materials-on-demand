import VueRouter from 'vue-router'
import Auth from '../layouts/Auth'
import Admin from '../layouts/Admin'
import Login from '../views/Login'
import Dashboard from '../views/Dashboard'
import { beforeEnter } from './functions/routerFunctions.js'

const routes =  [
    {
      path: "/gateway/auth",
      redirect: { name: 'login' },
      component: Auth,
      children: [
        {
          path: "login",
          name: "login",
          // beforeEnter: (to, from, next) => beforeEnter({to, from, next, permission:'dashboard'}),
          components: {
            default: Login
          },
        },
      ]
    },
    {
      path: "/gateway",
      component: Admin,
      children: [
        {
          path: "dashboard",
          name: "dashboard",
          components: {
            default: Dashboard
          },
        },
      ]
    }
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
