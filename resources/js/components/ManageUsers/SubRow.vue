<template>
    <div>
        <div v-for="(item, key) in row.headquarters" :key="key">
            <h3>{{ item.name }}</h3>
            <h5>Permisos especiales</h5>
            <AddPermissions :userdd="row" :headquarter="item" @addPermissions="addPermissions"/>
            <span class="rounded-pill badge bg-success m-1" v-for="(permission) in item.permissions">
                {{ permission.permission_name }}
            </span>
            <h5>Roles</h5>
            <button class="btn btn-dark btn-sm rounded-pill" type="button">Agregar rol</button>
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
    </div>
</template>

<script>
import AddPermissions from './AddPermissions'
export default {
    components:{
        AddPermissions
    },
    props:{
        row:{
            type:Object,
            require:true
        }
    },
    addPermissions(){
        this.$emit("addPermissions", this.permissions)
    }
}
</script>