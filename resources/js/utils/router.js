import VueRouter from 'vue-router'
import Auth from '../layouts/Auth'
import Login from '../views/Login'

const routes =  [
    {
      path: "/gateway/auth",
    //   redirect: { name: 'login' },
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
    }
]


const router = new VueRouter({
    history: true,
    mode: 'history',
    routes // short for `routes: routes`
})


export default router;
