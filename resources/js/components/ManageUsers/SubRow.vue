<template>
    <div>
        <div v-for="(item, key) in row.headquarters" :key="key">
            <h3>{{ item.name }}</h3>
            <h5>Permisos especiales</h5>
            
            <button class="btn btn-dark btn-sm rounded-pill" type="button" @click.prevent="show('add_permissions', item)">Agregar permisos</button>
            <p>
                <span class="rounded-pill badge bg-success m-1" v-for="(permission) in item.permissions">
                    {{ permission.permission_name }}
                </span>

            </p>

            <h5>Roles</h5>
            <button class="btn btn-dark btn-sm rounded-pill" type="button" @click.prevent="show('add_roles', item)">Agregar roles</button>
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
        <div v-if="user_headquarter_id">
            <AddPermissions :userdd="row" :user_headquarter_id="user_headquarter_id" @addPermissions="addPermissions"/>
        </div>

        <div v-if="user_headquarter_id">
            <AddRoles :userdd="row" :user_headquarter_id="user_headquarter_id" @addRoles="addRoles"/>
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
            user_headquarter_id: null
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
            setTimeout(() => {
                this.$modal.show(name)
            }, 10);
        }
    },
}
</script>