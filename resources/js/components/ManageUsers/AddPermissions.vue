<template>
    <div>
        <modal name="add_permissions" :width="500"
         :height="200"
         :adaptive="true"
         class="modal-dialog"
         role="document">
            <div class="modal-content" ref="add_permissions">
                <div class="modal-header" >
                    <p class="modal-title">Agregar permisos para  <b>( {{ userdd.fullname }} )</b></p>
                    <div slot="top-right">
                        <button @click="$modal.hide('add_permissions')">
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
        <button class="btn btn-dark btn-sm rounded-pill" type="button" @click.prevent="$modal.show('add_permissions')">Agregar permisos</button>
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
        headquarter: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            permissions:[],
            options:[],
        }
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
            if (this.permissions.length > 0) {
                let permissions = this.permissions.map(p => p.name);
                    console.log("headquarter: ",this.headquarter);
                let resp = await addPermissionsToUser({user_headquarter_id:this.headquarter.user_headquarter_id,permissions}, this);
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