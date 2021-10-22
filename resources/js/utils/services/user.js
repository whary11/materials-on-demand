import { post, get } from './api'
import sha1 from 'js-sha1'

export const login = async (auth) => {
    try {

        let route = '/api/user/login'
        let data = {
            email: auth.email,
            password: sha1(auth.password)
        }
        let result = await post({route, data})

        return result
    } catch (error) {
        return error
    }
}