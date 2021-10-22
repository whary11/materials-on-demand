import { can } from "./auth"


function beforeEnter({to, from, next, permission}) {
    if (can({permission}) || !permission) { // si, tiene el permiso
        next(true) //Sigue adelante e ingresa a la vista
    } else {// no tiene el permiso

        if(from.name){ // Validamos si viene de una ruta vue
            next(from.name)// Lo dejamos en la misma ruta ya que no puede acceder a la ruta solicitada
        }else{
            next('/gateway/dashboard')// Lo redireccionamos al dashboar ya que no puede acceder a nuestra ruta protegida
        }
    }
}








export { beforeEnter }