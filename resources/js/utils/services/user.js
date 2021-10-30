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

export const getHeadquartersNotUser = async (data, vue) => {
    try {

        let route = '/api/user/get_headquarters_not_user'
        
        let result = await post({route, data})
    
        return result
    } catch (error) {
        return error
    }
}

export const addHeadquartersToUser = async (data, vue) => {
    try {
        let loading = vue.$loading.show({container:vue.$refs.add_headquarter})

        let route = '/api/user/add_headquarter_to_user'
        
        let result = await post({route, data})
    
        loading.hide()
        return result
    } catch (error) {
        return error
    }
}


export const getPermissions = async (data, vue) => {
    try {

        let route = '/api/permission/get_permissions'
        
        let result = await post({route, data})
    
        return result
    } catch (error) {
        return error
    }
}


export const addPermissionsToUser = async (data, vue) => {
    try {
        let loading = vue.$loading.show({container:vue.$refs.add_headquarter})

        let route = '/api/user/add_permissions_to_user'
        
        let result = await post({route, data})
    
        loading.hide()
        return result
    } catch (error) {
        return error
    }
}






