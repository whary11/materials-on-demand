
function can({permission}, opciones={superAdmin:"SUPER_ADMIN"}) {
    let permissionsUser = (JSON.parse(localStorage.getItem('permissions'))) ? JSON.parse(localStorage.getItem('permissions')) : []//consultar los permisos del localStorage
    let rolesUser = (JSON.parse(localStorage.getItem('roles'))) ? JSON.parse(localStorage.getItem('roles')) : [] //consultar los roles del localStorage
    let count = 0
    let superAdmin = opciones.superAdmin /// Role que no necesita la validación de los permisos, ya que es el rol con todos los permisos por defecto
    let countRole = 0

    rolesUser.map(item => {
        if (item.name == superAdmin) {            
            countRole++
        }
    })

    if (countRole == 0) { // Si no es superAdmin
        permissionsUser.map((per) => {
            if (per == permission) {
                count++
            }
        })
        if (count > 0) {
            return true
        } else {
            return false
        }
    } else {
        return true
    }
}


function canAll(permissions){
    let result = []
    permissions.map(permission => {
        result.push(can({permission}))
    })

    if (result.filter(item => item == true).length > 0) {
        return true
    }
    return false

}





export { can, canAll }