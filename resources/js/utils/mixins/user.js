
let p = {
    methods: {
        user(){
            let user = localStorage.getItem('user')
            return user ? JSON.parse(user) : {}
        }
    },
}


export default p
