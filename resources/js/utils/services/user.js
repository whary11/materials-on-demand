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

export const getUsersManage = async (data, vue) => {
    try {
        let loading = vue.$loading.show({container:vue.$refs.tableUsersManage})

        let route = '/api/user/get_users_manage'
        
        let result = await post({route, data})
        result.data = {
            data: result.data,
            count: result.count
        }
        loading.hide()
        return result
    } catch (error) {
        return error
    }
}


