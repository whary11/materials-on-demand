<template>
    <div>
        <modal :name="name" :width="500"
         :height="200"
         :adaptive="true"
         class="modal-dialog"
         role="document">
            <div class="modal-content" ref="add_permissions">
                <div class="modal-header" >
                    <p class="modal-title">Agregar permisos para  <b>( {{ userdd.fullname }} )</b></p>
                    <div slot="top-right">
                        <button @click="$modal.hide(name)">
                            ❌
                        </button>
                    </div>
                </div>
                <div class="modal-body">
                    <div style="display:flex !important;
                            flex-direction: row;
                            align-content: stretch;
                            justify-content: space-between;">
                        <div style="width: 430px !important">
                            <v-select 
                                multiple 
                                v-model="permissions" 
                                :options="options"
                                @search="getPermissions"
                                label="description" 
                                placeholder="Seleccionar permisos">


                                <template #no-options>
                                    Buscar permisos
                                </template>
                                <template #open-indicator="{ attributes }">
                                    <span v-bind="attributes">🔽</span>
                                </template>
                                

                            </v-select>
                        </div>
                        <div>
                            <button style="height:34px !important;" class="btn btn-primary" @click.prevent="addPermissions">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </modal>
        
    </div>
</template>

<script>
import { addPermissionsToUser, getPermissions } from '../../utils/services/user'
export default {
    props:{
        userdd: {
            type: Object,
            required: true,
        },
        user_headquarter_id: {
            type: Number,
            required: true,
        },
        name:{
            type: String,
            required: true,
        }
    },
    data() {
        return {
            permissions:[],
            options:[],
        }
    },
    mounted() {
    },
    methods: {
        async getPermissions(search, loading){
            let data = {
                user_id: this.userdd.id,
                search
            }
            loading(true)
            let resp = await getPermissions(data, this)
            loading(false)
            this.options = resp.data
        },
        async addPermissions(){

            console.log(this.headquarter);
            if (this.permissions.length > 0) {
                let permissions = this.permissions.map(p => p.name);
                let user_headquarter_id = this.user_headquarter_id
                    console.log("headquarter: ",this.headquarter);
                let resp = await addPermissionsToUser({user_headquarter_id,permissions}, this);
                if (resp.transaction.status) {
                    this.$modal.hide("add_permissions");
                    this.$emit("addPermissions", this.permissions)
                    this.$modal.hide('add_permissions')
                    this.notification('show', {
                        title: `<b class="text-success">Excelente !</b>`,
                        content: 'Permisos agregados con éxito.',
                    })
                }
            }
        }
    },
}
</script>