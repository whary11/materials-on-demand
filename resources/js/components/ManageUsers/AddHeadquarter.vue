<template>
    <div>
        <modal name="add_headquarter" :width="500"
         :height="200"
         :adaptive="true"
         class="modal-dialog"
         role="document">
            <div class="modal-content" ref="add_headquarter">
                <div class="modal-header" >
                    <p class="modal-title">Agregar sede para  <b>( {{ userdrdr.fullname }} )</b></p>
                    <div slot="top-right">
                        <button @click="$modal.hide('add_headquarter')">
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
                                v-model="headquarters" 
                                :options="options"
                                @search="getHeadquarters"
                                label="name" 
                                placeholder="Seleccionar sede">


                                <template #no-options>
                                    Busca una sede que el ususario aún no tenga agregada.
                                </template>
                                <template #open-indicator="{ attributes }">
                                    <span v-bind="attributes">🔽</span>
                                </template>
                                

                            </v-select>
                        </div>
                        <div>
                            <button style="height:34px !important;" class="btn btn-primary" @click.prevent="addHeadquarter">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
import { addHeadquartersToUser, getHeadquartersNotUser } from '../../utils/services/user'
export default {
    props:{
        userdrdr: {
            type: Object,
            require: true,
        }
    },
    data() {
        return {
            headquarters:[],
            options:[],
        }
    },
    methods: {
        async getHeadquarters(search, loading){
            let data = {
                user_id: this.user.id,
                search
            }
            loading(true)
            let resp = await getHeadquartersNotUser(data, this)
            loading(false)
            this.options = resp.data
        },
        async addHeadquarter(){

            try {
                
                
                if (this.headquarters.length > 0) {
                    let headquarters = this.headquarters.map(h => h.id);

                    let resp = await addHeadquartersToUser({user_id:this.userdrdr.id,headquarters}, this);
                    if (resp.transaction.status) {
                        this.$modal.hide("add_headquarter");
                        this.$emit("addHeadquarter")
                        this.notification('show', {
                            title: `<b class="text-success">Excelente !</b>`,
                            content: 'Sede agregada con éxito.',
                        })
                    }else{
                        this.$modal.hide("add_headquarter");
                        this.notification('show', {
                            title: `<b class="text-danger">Upps !</b>`,
                            content: resp.message.content,
                        })  
                    }
                }
            } catch (error) {
                console.log(error);
            }
        }
    },
}
</script>