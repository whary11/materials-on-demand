<template>
    <div>
        <!-- <router-view name="header"></router-view> -->
        <main>
            <!-- <fade-transition origin="center" mode="out-in" :duration="250"> -->
                <router-view />
            <!-- </fade-transition> -->
        </main>
        <!-- <router-view name="footer"></router-view> -->


        <!-- Notificaciones -->
        <Notification />
    </div>
</template>
<script>

import Notification from './components/Globals/Notification'
export default {
    components:{
        Notification
    }
}
</script>