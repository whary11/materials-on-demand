<template>
    <div class="form-group">
        <slot name="label">
            <label for="search-customer">Buscar cliente</label>
        </slot>
        <v-select :options="customers" label="fullname" v-model="customer" inputId="search-customer" @search="fetchOptions" @input="selectedCustomer" placeholder="Buscar clientes">
            <template slot="option" slot-scope="option">
                <span style="font-size:12px">
                    {{ option.fullname }}
                </span>
            </template>
            <template slot="selected-option" slot-scope="option">
                <span style="font-size:12px">
                    {{ option.fullname }}
                </span>
            </template>
        </v-select>
    </div>
</template>

<script>
import { getCustomers } from '../../utils/services/user'
export default {
    data() {
        return {
            customers:[],
            customer:null,
        }
    },

    methods: {
        async fetchOptions(search, loading){
            loading(true)
            let resp = await getCustomers({search}, this)
            loading(false)
            this.customers = resp.data
        },
        selectedCustomer(val){
            return val && this.$emit("selectedCustomer", val)
        }
    },
}
</script>