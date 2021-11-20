<template>
    <div class="card">
        <div class="card-body row justify-content-center">

            <div class="col-md-12">
                <GeneralInfo />
            </div>


            <div class="col-md-6 bg-danger">
                Prsentación para detalles de la compra (productos)
            </div>



            <div class="col-md-6 m-0">

                <div class="col-md-12 m-1 bg-info">
                    Informacion del cliente
                </div>

                <div class="col-md-12 m-1 bg-info">
                    Metodo de pago
                </div>


                <div class="col-md-12 m-1 bg-info">
                    Detalles del pago
                </div>

            </div>




        </div>
    </div>
</template>

<script>
import GeneralInfo from '../components/PurchaseProducts/GeneralInfo'
export default {
    components:{
        GeneralInfo
    }
}
</script>
