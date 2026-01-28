<template>
    <div>
        <div class="page-wrapper">
            <div class="container-fluid p-0">
                <div class="row">
                    <div class="col-12">
                        <div class="login-card">
                            <div>
                                <div>
                                    <a class="logo">
                                        <img class="img-fluid for-light" src="../assets/images/logo/logo.svg" alt="looginpage" />
                                        <img class="img-fluid for-dark" src="../assets/images/logo/logo_dark.png"
                                            alt="looginpage" />
                                    </a>
                                </div>
                                <div class="login-main ">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    
                                            Password Minimal <strong>8 characters </strong> Wajib Berisi <strong> Huruf Besar </strong> dan <strong> Huruf Kecil </strong> 
                                            Wajib mengandung <strong>Symbol </strong> 
                                            dan mengandung <strong> Angka</strong> <br>
                                            contoh <strong>H0nd4Im@ra</strong> 
                                        
                                        <button class="btn-close" type="button" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    <div class="theme-form">
                                        <h4>Change Your Password</h4>
                                        <div class="form-group">
                                            <label class="col-form-label">New Password</label>
                                            <div class="form-input position-relative">

                                                <input class="form-control" :type="active?'password':'text'" v-model="new_password" required="" placeholder="*********" />
                                                <div class="show-hide"><span :class="active?'show':'hide'" @click.prevent="show"> </span></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Retype Password</label>
                                            <div class="form-input position-relative">

                                                <input class="form-control" :type="activeReType?'password':'text'" v-model="confirmasi_password" required="" placeholder="*********" />
                                                <div class="show-hide"><span :class="activeReType?'show':'hide'" @click.prevent="showReType"> </span></div>
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="checkbox p-0">
                                                <input id="checkbox1" type="checkbox">
                                                <label class="text-muted" for="checkbox1">Remember password</label>
                                            </div>
                                            <button class="btn btn-primary btn-block"  @click="changePassword">Done </button>
                                        </div>
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

<script setup>
    import { Api } from '@/api/Api'
    import { useStore } from 'vuex';
    import { useRouter } from 'vue-router';
    import { reactive, ref, onMounted } from 'vue';
    import { notification } from 'ant-design-vue';
    import backgroundImages from '@/assets/images/login/login.png';
    import Swal from 'sweetalert2';

    const store = useStore()
    const user = store.getters['auth/currentUser']
    const router = useRouter()
    import { useRoute } from 'vue-router'
    const route = useRoute()
    const active = ref(true)
    const activeReType = ref(true)
    const pesan = ref("")
    const loading = ref(false)
    const backgroundImage = ref(backgroundImages)
    const token  = ref(route.query.token)
    const email  = ref(route.query.email)
    
    const new_password = ref("")
    const confirmasi_password  = ref("")

    const show = () => {
        active.value = !active.value
    }

    const showReType = () => {
        activeReType.value = !activeReType.value
    }

    const open = ref(false);
    const showModal = () => {
        open.value = true;
    };

    const openTerm = ref(false);
    const showModalTerm = () => {
        openTerm.value = true;
    };



    const handleCancel = () => {
        open.value = false;
    };


    const changePassword = async () => {
        const tok = localStorage.getItem('access_token_iss')
        Api.defaults.headers.common['Authorization'] = "Bearer " +tok
        await Api.post('/auth/reset_password', {
            token : token.value,
            email : email.value,
            password : new_password.value,
            password_confirmation : confirmasi_password.value,
        }).then(async (response) => {

    
            pesan.value =  response.data.message
            Swal.fire({
                icon: "success",
                title: "Yeyy Suksess",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
            store.dispatch('auth/logout')
            router.push({ name: 'login'})


            
        }).catch(error => {
        
    
            pesan.value = error.response.data.message;
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
        })
    }

</script>
