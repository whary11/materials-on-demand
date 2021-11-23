<template>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card-group d-block d-md-flex row">
              <form class="card col-md-7 p-4 mb-0" @submit.prevent="loginApi">
                <div class="card-body">
                  <h1>Login</h1>
                  <p class="text-medium-emphasis">Sign In to your account</p>
                  <div class="input-group mb-3"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                      </svg></span>
                    <input class="form-control" type="text" placeholder="Username" v-model="login.email">
                  </div>
                  <div class="input-group mb-4"><span class="input-group-text">
                      <svg class="icon">
                        <use xlink:href="vendors/@coreui/icons/svg/free.svg#cil-lock-locked"></use>
                      </svg></span>
                    <input class="form-control" type="password" placeholder="Password" v-model="login.password">
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button class="btn btn-primary px-4" type="submit">Login</button>
                    </div>
                    <div class="col-6 text-end">
                      <button class="btn btn-link px-0" type="button">Forgot password?</button>
                    </div>
                  </div>
                </div>
              </form>
              <div class="card col-md-5 text-white bg-primary py-5">
                <div class="card-body text-center">
                  <div>
                    <h2>Sign up</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <button class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</button>
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
                email:'luis@gmail.com',
                password:'password',
            }
        }
    },
    // luis.raga@gmail.com
    // 5f4dcc3b5aa765d61d8327deb882cf99
    // $2y$10$pMX6.NAkbv.dCICe2PyQSeFJ4N/Vf3XF9Fb5UjyLc0nvAK3MdBlpu
    mounted() {
      // this.alertCustom({
      //   icon: 'warning',
      //   title: 'Oops...',
      //   text: 'Something went wrong!',
      // });
      
    },
    methods: {
        async loginApi(){
            let loading = this.$loading.show({container:this.$refs.loginContainer})
            let resp = await login(this.login)

            if (resp.code == 200) {
                this.saveUserData(resp)
                this.$router.push({ name: "dashboard" })
            }else{

              this.alertCustom({
                icon: 'warning',
                title: 'Oops...',
                text: resp.message.content,
              });
            }

            loading.hide();

            console.log(resp);
        },
        saveUserData(resp){
            localStorage.setItem("token", resp.data.token)
            localStorage.setItem("user", JSON.stringify(resp.data.user))
            localStorage.setItem("roles", JSON.stringify(resp.data.roles))
            localStorage.setItem("permissions", JSON.stringify(resp.data.permissions))
        }
    },
}
</script>
