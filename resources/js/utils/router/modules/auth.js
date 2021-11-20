import Auth from '../../../layouts/Auth'
import Login from '../../../views/Login'

export default [
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
      }
]