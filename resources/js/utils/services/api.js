let baseUrl = `${window.location.origin}`;
    // this.apiKeyClient = config.apiKeyClient
    let headers = {
        'Content-Type': 'application/json'
    }
    let token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        headers['X-CSRF-TOKEN'] = token.content;
        // window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }


    // constructor() {
    //   }
    /**
         * Method for request api graphql
         *
         * @return Object or Array with the answer of API
         * @param data data with request
         * @param schema can is null is schema for request
    */
    export const post = async({data = {},route = ""})=> {



        if (data.toLocaleString() == "[object Object]") {
            headers['Content-Type'] = 'application/json'

            data = JSON.stringify(data)
        } else {
            delete headers['Content-Type']
        }

        let token = localStorage.getItem('token')
        if (token) {
            headers['Authorization'] = `Bearer ${token}`
        }

        try {
            let response = await fetch(`${baseUrl}${route}`, {
                method: 'POST',
                body: data,
                headers: headers
            });
            let responseJson = await response.json();

            if (response.ok) {
                if (responseJson.code === 401) {
                    localStorage.removeItem('token')
                    localStorage.removeItem('roles')
                    localStorage.removeItem('user')
                    localStorage.removeItem('permissions')
                    window.$routerVueGlobal.push({name:'login'})
                    return responseJson
                }
                
                return responseJson;

            } else {
                let responseJson = response.json();

                return responseJson;

            }
        } catch (error) {

            return error
        }
    }

    export const get = async ({route = ""})=> {
        let token = localStorage.getItem('token')
        if (token) {
            headers['Authorization'] = `Bearer ${token}`
        }

        try {
            let response = await fetch(`${baseUrl}${route}`, {
                method: 'GET',
                headers: headers
            });
            if (response.ok) {
                let responseJson = await response.json();
                if (responseJson.code === 401) {
                    localStorage.removeItem('token')
                    localStorage.removeItem('roles')
                    localStorage.removeItem('user')
                    localStorage.removeItem('permissions')
                    window.$routerVueGlobal.push({name:'login'})
                    console.log("Denegado: ",responseJson, window.$routerVueGlobal);

                    return responseJson
                    
                }
                return responseJson;

            } else {
                let responseJson = response.json();
                console.log("Success Errror: "+responseJson);
                return responseJson;
            }
        } catch (error) {
            console.log('error');

            return error
        }
    }