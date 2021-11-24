<template>
    <div class="form-group" ref="search_address">
        <slot name="label">
            <label for="search-address">  Seleccionar dirección</label>
        </slot>
        {{ observerFetch }}
        <v-select :options="addresses" label="address" v-model="address" inputId="search-address" @input="selectedAddress" placeholder="Seleccionar dirección" >
            <template slot="option" slot-scope="option">
                <span style="font-size:12px">
                    {{ option.address }}
                    <b> {{ option.lat }} - {{ option.long }}</b>
                    <br>
                    <!-- <i class="fas fa-street-view"></i> -->
                    <span>{{ option.complement }}</span>
                    <br>
                    <b><font-awesome-icon :icon="['fas', 'map-marked-alt']" /> {{ option.lat }} - {{ option.long }}</b>
                </span>
            </template>
            <template slot="selected-option" slot-scope="option">
                <span style="font-size:12px">
                    {{ option.address }}
                    <br>
                    <span>{{ option.complement }}</span>
                    <br>
                    <b><font-awesome-icon :icon="['fas', 'street-view']" /> {{ option.lat }} - {{ option.long }}</b>
                </span>
            </template>
        </v-select>
    </div>
</template>

<script>
import { getAddressesByUser } from '../../utils/services/user'
export default {
    props:{
        customer:{
            required: true,
            // type: Object,
        }
    },
    data() {
        return {
            addresses:[],
            address:null,
        }
    },

    methods: {
        async getAddressesByUser(){
            this.addresses = []
            this.address = null
            let resp = await getAddressesByUser({customer_id: this.customer.id}, this)
            this.addresses = resp.data
        },
        selectedAddress(val){
            return this.$emit("selectedAddress", val)
        }
    },

    computed:{
        observerFetch(){
            if (this.customer) {
                this.getAddressesByUser()
                // alert("consultar direcciones para : "+this.customer.fullname)
            }
        }
    }
}
</script>