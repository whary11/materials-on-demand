import VueRouter from 'vue-router'
import Auth from '../layouts/Auth'
import Admin from '../layouts/Admin'
import Login from '../views/Login'

const routes =  [
    {
      path: "/gateway/auth",
      component: Auth,
      children: [
        {
          path: "login",
          name: "login",
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
            default: Login
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


export default router;
