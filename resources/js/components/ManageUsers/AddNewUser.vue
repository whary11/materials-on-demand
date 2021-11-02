<template>
    <div>
        <modal :name="name" :width="800"
         height="auto"
         :adaptive="true"
         class="modal-dialog"
         role="document">
            <form class="modal-content" ref="add_roles" @submit.prevent="addNewUserMethod">
                <div class="modal-header" >
                    <p class="modal-title"><b> Crear usuario</b></p>
                    <div slot="top-right">
                        <button @click="$modal.hide(name)">
                            ❌
                        </button>
                    </div>
                </div>
                <div class="modal-body row">
                    <div class="form-group col-md-6">
                        <label for="name">Nombre</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="Nombre" v-model="user_edit.name" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="last_name">Apellidos</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Apellidos" v-model="user_edit.last_name" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Email" v-model="user_edit.email" required>
                    </div>

                    <!-- Agregar sedes -->
                    <div class="form-group col-md-6">
                        <label for="add_headquarter">Agregar sedes</label>
                        <v-select 
                            multiple 
                            v-model="headquarters" 
                            :options="options"
                            @search="getHeadquarters"
                            label="name" 
                            placeholder="Seleccionar sede">
                            <template #no-options>
                                Busca una sede
                            </template>
                            <template #open-indicator="{ attributes }">
                                <span v-bind="attributes">🔽</span>
                            </template>
                            

                        </v-select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Contraseña</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Contraseña" v-model="user_edit.password" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Repetir contraseña</label>
                        <input type="password" class="form-control" name="repeat_password" id="repeat_password" placeholder="Repetir contraseña" v-model="user_edit.repeat_password" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
            </form>
        </modal>
        
    </div>
</template>

<script>
import { addNewUser, getHeadquartersNotUser } from '../../utils/services/user'
export default {
    props:{
        name:{
            type: String,
            required: true,
        }
    },
    data() {
        return {
            user_edit:{},
            headquarters:[],
            options:[],
        }
    },
    mounted() {
        console.log(this.user_headquarter_id)
    },
    methods: {
        async getHeadquarters(search, loading){
            let data = {
                user_id: null,
                search
            }
            loading(true)
            let resp = await getHeadquartersNotUser(data, this)
            loading(false)
            this.options = resp.data
        },

        async addNewUserMethod(){
            if (this.headquarters.length == 0) {
                alert("Debe seleccionar una o mas sedes.")
                return
            }
            if (this.user_edit.password != this.user_edit.repeat_password) {
                alert("La contraseña no corresponde.")

                return 
            }

            this.user_edit.headquarters = this.headquarters.map(he => he.id)
            let resp = await addNewUser(this.user_edit, this)


            console.log(resp);
        }
    },
}
</script>