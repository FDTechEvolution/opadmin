<template>
    <div class="wrapper">
        <div v-if="!loading" class="row" style="height: 100vh;">
            <div class="col-8 bg-img">

            </div>
            <div class="col-4 center-screen">
                <div class="w-75" style="margin: 0 auto;">
                    <div class="text-center">
                        <a href="index.html" class="logo-lg"><i class="mdi mdi-radar"></i> <span>OP Admin</span> </a>
                    </div>
                    <b-form @submit.prevent="userLogin" class="form-horizontal m-t-20">
                        
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="mdi mdi-account"></i></span>
                                    <input v-model="form.email" class="form-control" type="text" required="" placeholder="Email" autocomplete="off">
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="mdi mdi-radar"></i></span>
                                    <input v-model="form.password" class="form-control" type="password" required="" placeholder="Password">
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center m-t-20">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-custom w-md waves-effect waves-light" type="submit">Log In
                                </button>
                            </div>
                        </div>

                        <div class="form-group row m-t-30">
                            <div class="col-sm-12 text-right">
                                <a href="#" class="text-muted"><i class="fa fa-lock m-r-5"></i> Forgot your
                                    password?</a>
                            </div>
                        </div>
                    </b-form>
                </div>
            </div>
        </div>
        <div v-else class="row">
            <div class="col-12">
                <div class="text-center">กรุณารอซักครู่....</div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                loading: true
            }
        },
        created() {
            if(localStorage.getItem("_tk")) {
                axios.get(`${process.env.MIX_API_URL}/user-check`, {
                    headers: {
                        'Authorization': `bearer ${localStorage.getItem('_tk')}`
                    }
                })
                .then((res) => {
                    if(res.data) window.location.href = `${process.env.MIX_WEB_URL}/app/dashboard`
                })
                .catch((e) => {
                    this.loading = false
                })
            }else{
                this.loading = false
            }
        },
        methods: {
            userLogin() {
                axios.post(`${process.env.MIX_API_URL}/login`, {
                    'email': this.form.email,
                    'password': this.form.password
                })
                .then((res) => {
                    localStorage.setItem("_tk", res.data.access_token)
                    window.location.href = `${process.env.MIX_WEB_URL}/app/dashboard`
                })
                .catch((e) => {
                    console.log(e)
                })
            }
        }
    }
</script>

<style scoped>
    .bg-img{
        background-image: url('/images/big/img1.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    .center-screen {
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        min-height: 100vh;
    }
</style>