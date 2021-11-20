import { can } from "../functions/auth"

let p = {
    methods: {
        notification(type, option){
            console.log("---------------------notification---------------------------");
            console.log(type);
            console.log("---------------------notification---------------------------");
            let show = "show"
            let hide = "hide"
            switch (type) {
                case show:

                    if (option.title && option.content) {
                        document.getElementById("notifications-title").innerHTML = option.title
                        document.getElementById("notifications-content").innerHTML = option.content
                        document.getElementById("notifications-app").classList.remove(hide);
                        document.getElementById("notifications-app").classList.add(show);

                        setTimeout(() => {
                            document.getElementById("notifications-app").classList.remove(show);
                            document.getElementById("notifications-app").classList.add(hide);
                        }, option.time ? option.time : 5000);

                        // alert(option.time ? option.time :100)
                    }else{
                        console.log("---------------------notification---------------------------");
                        console.error(`Debe especificar opciones.`);
                        console.log("---------------------notification---------------------------");
                    }

                    
                    break;
                case hide:
                    document.getElementById("notifications-app").classList.remove(show);
                    document.getElementById("notifications-app").classList.add(hide);
                    break;
                default:
                    console.log("---------------------notification---------------------------");
                    console.error(`La opción "${type}" no está definido.`);
                    console.log("---------------------notification---------------------------");
                    break;
            }


            document.getElementById("notifications-app").classList.toggle('MyClass');
        }
    },
}


export default p