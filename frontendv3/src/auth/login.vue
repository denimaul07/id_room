<template>
    <!-- <div class="loader-wrapper" v-if="loading">
        <img class="img-fluid for-light" src="../assets/images/logo/loading.gif" width="30%" alt="looginpage" />
    </div> -->
    <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
        <div style="align-items:center;justify-content: center;display: flex;" width="100px">
            <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
        </div>

        <div style="align-items:center;justify-content: center;display: flex;">
            {{ pesan }}
        </div>
    </a-modal>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 b-center bg-size">
                <img class="bg-img-cover bg-center" :src="backgroundImage" alt="looginpage"  />
            </div>
            <div class="col-xl-4 p-0">
                <div class="login-card">
                    <div>
            
                        <form class="login-main" @submit.prevent="login">
                            <h4 class="text-center">Sign in to account</h4>
                            <p>Enter your username & password to login</p>
                            <div class="form-group">
                                <label class="col-form-label">User Name</label>
                                <input class="form-control" type="text" v-model="user.email"
                                    placeholder="Username" required />
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">Password</label>
                                <div class="input-group ">
                                    <input class="form-control" :type="active ? 'password' : 'text'" v-model="user.password"
                                        placeholder="*********" required />
                                    <button type="button" class="input-group-text pointer-event" id="basic-addon1"
                                        @click.prevent="show">
                                        <i :class="active ? 'fa fa-eye' : 'fa fa-eye-slash'" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="form-group mb-0">
                                <div class="checkbox p-0">
                                    <input id="checkbox1" type="checkbox" />
                                    <label class="text-muted" for="checkbox1">Remember password</label>
                                </div>

                                <button type="submit" class="btn btn-dark btn-block">
                                    Sign in
                                </button>
                            </div>
                            <!-- <p class="mt-4 mb-0">
                                Forgot Password?
                                <a class="ms-2 fw-semibold" style="cursor:pointer" @click="showModal">
                                    Reset Password
                                </a>
                            </p>
                            <p class="mt-4 mb-0">
                                Before Sign In, please read to our
                                <a class="ms-2 fw-semibold" style="cursor:pointer" @click="showModalTerm">
                                    Terms and Conditions & Privacy Policy
                                </a>
                            </p> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a-modal v-model:open="open" title="Forgot Password ?" @ok="forgot">
        <template #footer>
            <a-button key="back" @click="handleCancel">Return</a-button>
            <a-button key="submit" type="primary" :loading="loading" style="background-color:#1e3a8a" @click="forgot">Reset Password</a-button>
        </template>
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form class="row g-3 needs-validation">
                        <div class="col-12 px-2">
                            <label for="email_reset" class="form-label">Email Address</label>
                            <input type="email" v-model="user.email" id="email_reset" placeholder="youremail@email.com"
                                class="form-control" required>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </a-modal>

    <a-modal width="1000px" style="top: 20px" v-model:open="openTerm" title="Term Of Service" :footer="null">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="mb-1">Dengan ini Saya menyatakan telah membaca dan menyetujui semua peraturan yang tertuang
                        pada Term of Service
                        sebelum menjalankan Aplikasi ini.</p>
                    <ol class="list-decimal list-outside">
                        <li class="mb-1 font-bold">
                            PENERIMAAN LAYANAN
                            <p class="font-normal">IT Imora menyediakan layanan ini hanya untuk user sesuai
                                dengan apa yang tertulis pada
                                ketentuan yang berlaku di dalam Terms of Service (TOS) ini, dimana dapat diperbaharui oleh
                                IT Imora
                                dari waktu ke waktu tanpa adanya pemberitahuan sebelumnya kepada users.</p>
                        </li>
                        <li class="mb-1 font-bold">
                            PASSWORD DAN SECURITY
                            <p class="font-normal">Users akan menerima Password dan User ID , setelah menyelesaikan
                                pemasangan sistem. Users bertanggung
                                jawab untuk menjaga kerahasiaan Password dan User ID serta bertanggung jawab secara penuh
                                terhadap
                                segala aktifitas yang berkaitan dengan penggunaannya. Users wajib menginformasikan kepada
                                IT Imora
                                perihal penggunaan Password yang tidak semestinya.</p>
                        </li>
                        <li class="mb-1 font-bold">
                            DILARANG MEMPERJUALBELIKAN LAYANAN
                            <p class="font-normal">Users menyetujui untuk tidak memperjual belikan ataupun mempublikasikan
                                sistem dengan tujuan apapun,
                                secara keseluruhan atau sebagian dari sistem (termasuk Password dan User ID).</p>
                        </li>
                        <li class="mb-1 font-bold">
                            MODIFIKASI SISTEM
                            <p class="font-normal">IT Imora berhak untuk memodifikasi sistem (atau beberapa bagian di
                                dalamnya) dengan atau tidak
                                adanya pemberitahuan terlebih dahulu.</p>
                        </li>
                        <li class="mb-1 font-bold">
                            PELANGGARAN
                            <p class="font-normal">Mohon menginformasikan kepada IT Imora bila terjadi pelanggaran TOS.
                            </p>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </a-modal>

    <a-modal v-model:open="alert" :footer=null style="top: 60px">
        <a-result status="500" title="Uppss, Terjadi Kesalahan" :sub-title="pesan" v-if="status_alert == 'error'">
            <template #extra>
                <button class="btn btn-primary" @click="close"><i class="fa fa-check pe-2"></i> OKE</button>
            </template>
        </a-result>

        <a-result status="success" title="Yeyyy Successfully  " :sub-title="pesan" v-else>
            <template #extra>
                <button class="btn btn-primary" @click="close"><i class="fa fa-check pe-2"></i> OKE</button>
            </template>
        </a-result>
    </a-modal>

    
</template>

<script setup>
    import { Api } from '@/api/Api'
    import { apiGetData, apiPostData, apiDownloadFile, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, pesan } from '@/store/action';
    import { useStore } from 'vuex';
    import { useRouter } from 'vue-router';
    import { reactive, ref } from 'vue';
    import { SmileTwoTone } from '@ant-design/icons-vue';
    import backgroundImages from '@/assets/images/login/login.png';
    const user = reactive({
        email: '',
        password: ''
    })
    const store = useStore()
    const router = useRouter()
    const active = ref(true)
    const alert = ref(false)
    const loading=ref(false)
    const status_alert = ref("")
    const backgroundImage = ref(backgroundImages)

    const show = () => {
        active.value = !active.value
    }

    const open = ref(false);
    const showModal = () => {
        open.value = true;
    };
    

    const openTerm = ref(false);
    const showModalTerm = () => {
        openTerm.value = true;
    };

    const close = () => {
        alert.value=false
    }



    const handleCancel = () => {
        open.value = false;
    };

    const login = async () => {
        pesan.value = "Mohon Tunggu Sebentar Yaa"
        processing.value = true
        //define variable
        let email = user.email
        let password = user.password

        //panggil action "login" dari module "auth" di vuex
    
    await store.dispatch('auth/login', {
        email,
        password
    })
        .then(async () => {
            await new Promise(r => setTimeout(r, 30));
            localStorage.removeItem('loginAttempts');
            processing.value = false
            router.push({
                    name: 'Dashboard'
                }
            )

        }).catch(error => {
            processing.value = false
            const object1 = error.message
            pesan.value = error.message
            status_alert.value = 'error'
            alert.value = true

        })
        
    }




    const forgot = async () =>{
        if (!user.email) {
            status_alert.value = 'error'
            pesan.value = 'Email Anda Belum Di Isi'
            alert.value = true
            return false
        }
        pesan.value = 'Mohon Untuk Menunggu'
        processing.value = true

        await Api.post("/auth/forgot_password", {
        email: user.email
        })
        .then(async (response) => {
            
            pesan.value = response.data.message
            Swal.fire({
                icon: "success",
                title: "Yeyy Successs",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
            processing.value = false
        })
        .catch((error) => {
            console.log(error);
            
            processing.value = false
            pesan.value = error.response.data.message;
            Swal.fire({
                icon: "error",
                title: "Uppps",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });
        });
    
    }


</script>


<style scoped> 
.bg-img-cover {
    width: 100%;
    height: 100vh;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
}


</style>