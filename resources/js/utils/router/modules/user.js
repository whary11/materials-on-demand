import Admin from '../../../layouts/Admin'
import Dashboard from '../../../views/Dashboard'
import ManageUsers from '../../../views/ManageUsers'

export default [
  {
    path: "/gateway",
    redirect: { name: 'dashboard' },
    component: Admin,
    children: [
      {
        path: "dashboard",
        name: "dashboard",
        components: {
          default: Dashboard
        },
      },
      {
        path: "manage-users",
        name: "manage-users",
        components: {
          default: ManageUsers
        },
      },
    ]
  }
]