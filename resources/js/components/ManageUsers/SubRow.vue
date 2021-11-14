<template>
    <div>
        <div v-for="(item, key) in row.headquarters" :key="key">
            <h3>{{ item.name }}</h3>
            <h5>Permisos especiales</h5>
            
            <button class="btn btn-dark btn-sm rounded-pill" type="button" @click.prevent="show('permissions', item)">Agregar permisos</button>
            <p>
                <span class="rounded-pill badge bg-success m-1" v-for="(permission) in item.permissions">
                    {{ permission.permission_name }}
                </span>

            </p>

            <h5>Roles</h5>
            <button class="btn btn-dark btn-sm rounded-pill" type="button" @click.prevent="show('roles', item)">Agregar roles</button>
            <div v-for="(role, key) in item.roles" :key="key*100">
                <span class="rounded-pill badge bg-danger m-1" >
                    {{ role.name }}
                </span>
                <br>
                <span class="rounded-pill badge bg-success m-1" v-for="(permission) in role.permissions">
                    {{ permission.name }}
                </span>
            </div>
        </div>
        <div v-if="can(['add_permissions'], 'Agregar permisos.') && user_headquarter_id">
            <AddPermissions :userdd="row" :user_headquarter_id="user_headquarter_id" @addPermissions="addPermissions" :name="name_modal_permissions"/>
        </div>

        <div v-if="can(['add_roles'], 'Agregar roles.') &&  user_headquarter_id">
            <AddRoles :userdd="row" :user_headquarter_id="user_headquarter_id" @addRoles="addRoles" :name="name_modal_roles"/>
        </div>
    </div>
</template>

<script>
import AddPermissions from './AddPermissions'
import AddRoles from './AddRoles'
export default {
    components:{
        AddPermissions,
        AddRoles
    },
    props:{
        row:{
            type:Object,
            require:true
        }
    },
    data() {
        return {
            user_headquarter_id: null,
            name_modal_permissions:"modal_permissions_",
            name_modal_roles:"modal_roles_",
        }
    },
    methods: {
        addPermissions(permissions){
            this.$emit("addPermissions", permissions)
        },
        addRoles(roles){
            this.$emit("addRoles", roles)
            this.user_headquarter_id = null
        },
        show(name,data = {}){
            this.user_headquarter_id = data.user_headquarter_id
            console.log("show",this.user_headquarter_id);


            let nameModal = ''
            if (name == 'roles') {
                nameModal =  this.name_modal_roles+Math.round(100, 100000)
                this.name_modal_roles = nameModal
            }else if(name == 'permissions') {
                nameModal = this.name_modal_permissions+Math.round(100, 100000)
                this.name_modal_permissions = nameModal
            }
            setTimeout(() => {
                this.$modal.show(nameModal)
            }, 10);

            console.log(nameModal);

        }
    },
}
</script>