<template>
    <div>
        <modal :name="name" :width="500"
         :height="200"
         :adaptive="true"
         class="modal-dialog"
         role="document">
            <div class="modal-content" ref="add_roles">
                <div class="modal-header" >
                    <p class="modal-title">Agregar roles para  <b>( {{ userdd.fullname }} )</b></p>
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
                                v-model="roles" 
                                :options="options"
                                @search="getRoles"
                                label="description" 
                                placeholder="Seleccionar roles">


                                <template #no-options>
                                    Buscar roles
                                </template>
                                <template #open-indicator="{ attributes }">
                                    <span v-bind="attributes">🔽</span>
                                </template>
                                

                            </v-select>
                        </div>
                        <div>
                            <button style="height:34px !important;" class="btn btn-primary" @click.prevent="addRoles">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </modal>
        
    </div>
</template>

<script>
import { addRolesToUser, getRoles } from '../../utils/services/user'
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
            roles:[],
            options:[],
        }
    },
    mounted() {
        console.log(this.user_headquarter_id)
    },
    methods: {
        async getRoles(search, loading){
            let data = {
                user_id: this.userdd.id,
                search
            }
            loading(true)
            let resp = await getRoles(data, this)
            loading(false)
            this.options = resp.data
        },
        async addRoles(){

            console.log(this.headquarter);
            if (this.roles.length > 0) {
                let roles = this.roles.map(r => r.id);
                let user_headquarter_id = this.user_headquarter_id
                    console.log("headquarter: ",this.headquarter);
                let resp = await addRolesToUser({user_headquarter_id,roles}, this);
                if (resp.transaction.status) {
                    this.$modal.hide("add_roles");
                    this.$emit("addRoles", this.roles)
                    this.$modal.hide('add_roles')
                    this.notification('show', {
                        title: `<b class="text-success">Excelente !</b>`,
                        content: 'Roles agregados con éxito.',
                    })
                }
            }

            this.options = []
        }
    },
}
</script>