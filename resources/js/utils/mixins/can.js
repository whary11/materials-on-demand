import { can } from "../functions/auth"

let p = {
    methods: {
        can(permissions){
            let result = []
            console.log("---------------------INIT CAN MIXIN---------------------------");
            console.log(permissions);
            console.log("---------------------FIN CAN MIXIN---------------------------");
            permissions.map(per => {
                result.push(can(per))
            })
            if (result.filter(item => item == true).length > 0) {
                return true
            }
        }
    },
}


export default p
