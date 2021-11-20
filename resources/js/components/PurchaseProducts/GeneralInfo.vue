<template>
    <div class="row">
        <div class="col-md-4">
            <SearchCustomer @selectedCustomer="selectedCustomer"/>
        </div>
        <div class="col-md-4">
            <SearchAddresses :customer="customer"/>
        </div>
    </div>
</template>

<script>
import SearchCustomer from '../Globals/SearchCustomer'
import SearchAddresses from '../Globals/SearchAddresses'
export default {
    components:{
        SearchCustomer,
        SearchAddresses,
    },
    data() {
        return {
            sendFetch:false,
            customer: null
        }
    },

    methods: {
        selectedCustomer(customer){
            this.customer = customer
        }
    }
}
</script>