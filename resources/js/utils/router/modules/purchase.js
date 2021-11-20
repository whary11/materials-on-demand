import Admin from '../../../layouts/Admin'
import PurchaseProducts from '../../../views/PurchaseProducts.vue'


export default [
  {
    path: "/gateway/phone-order",
    redirect: { name: 'purchase-products' },
    component: Admin,
    children: [
      {
        path: "purchase-products",
        name: "purchase-products",
        components: {
          default: PurchaseProducts
        },
      }
    ]
  }
]