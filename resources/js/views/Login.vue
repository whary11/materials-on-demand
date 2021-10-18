<template>
    <div class="page-wrapper">
        <div class="authentication-box">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-7" ref="loginContainer">
                        <div class="card tab2-card">
                            <div class="card-body">
                                <ul
                                    class="nav nav-tabs nav-material"
                                    id="top-tab"
                                    role="tablist"
                                >
                                    <li class="nav-item">
                                        <a
                                            class="nav-link active"
                                            id="top-profile-tab"
                                            data-bs-toggle="tab"
                                            href="#top-profile"
                                            role="tab"
                                            aria-controls="top-profile"
                                            aria-selected="true"
                                            ><span class="icon-user me-2"></span
                                            >Login</a
                                        >
                                    </li>
                                    
                                </ul>
                                <div class="tab-content" id="top-tabContent">
                                    <div
                                        class="tab-pane fade show active"
                                        id="top-profile"
                                        role="tabpanel"
                                        aria-labelledby="top-profile-tab"
                                    >
                                        <form class="form-horizontal auth-form" @submit.prevent="loginApi">
                                            <div class="form-group">
                                                <input
                                                    required
                                                    name="email"
                                                    type="email"
                                                    class="form-control"
                                                    placeholder="Email"
                                                    id="email"
                                                    v-model="login.email"
                                                />
                                            </div>
                                            <div class="form-group">
                                                <input
                                                    required
                                                    name="password"
                                                    type="password"
                                                    class="form-control"
                                                    placeholder="Contraseña"
                                                    v-model="login.password"
                                                />
                                            </div>
                                            <div class="form-terms">
                                                <div class="form-check mesm-2">
                                                    
                                                    <a
                                                        href="#"
                                                        class="btn btn-default forgot-pass"
                                                        >Recuperar contraseña</a
                                                    >
                                                </div>
                                            </div>
                                            <div class="form-button">
                                                <button
                                                    class="btn btn-primary"
                                                    type="submit"
                                                >
                                                    Iniciar sesión
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { login } from '../utils/services/user'
export default {
    data(){
        return {
            login:{
                email:'luis.raga@gmail.com',
                password:'password',
            }
        }
    },
    // luis.raga@gmail.com
    // 5f4dcc3b5aa765d61d8327deb882cf99
    // $2y$10$pMX6.NAkbv.dCICe2PyQSeFJ4N/Vf3XF9Fb5UjyLc0nvAK3MdBlpu
    methods: {
        async loginApi(){
            let loading = this.$loading.show({container:this.$refs.loginContainer})
            let resp = await login(this.login)

            if (resp.code == 200) {
                this.saveUserData(resp)
            }else{
                alert(resp.message.content)
            }

            loading.hide();

            console.log(resp);
        },
        saveUserData(resp){
            localStorage.setItem("token", resp.data.token)
            localStorage.setItem("user", JSON.stringify(resp.data.user))
        }
    },
}
</script>
