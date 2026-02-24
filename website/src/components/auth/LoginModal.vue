<template>
    <div v-if="open" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center px-4"
        @click.self="close">
        <div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden animate-scale" :style="{
        '--primary-color': primaryColor,
        '--primary-color-hover': primaryHover,
        '--primary-text-color': primaryText
    }">
            <div class="px-6 pt-6 pb-4 text-center" :style="{ backgroundColor: navBarColor }">
                <div class="flex items-center justify-center gap-2">
                    <img :src="imageBaseUrl + currentInfo?.logo" alt="ID Room" class="h-12" />
                </div>
            </div>
            <div class="p-6 pb-4 text-center">
                <h3 class="text-xl font-bold text-gray-900">Sign In</h3>
            </div>

            <form class="px-6 pb-6 space-y-4" @submit.prevent="onSubmit">
                <div class="grid grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-envelope text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.email" type="email" class="input input-icon"
                            placeholder="Enter your email" />
                    </div>
                </div>

                <div class="grid grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-lock text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                            class="input input-icon input-icon-right" placeholder="" />
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                            @click="showPassword = !showPassword">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>


                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" v-model="form.remember" class="rounded border-gray-300"
                            :style="{ accentColor: primaryColor }" />
                        Remember Me
                    </label>
                    <button type="button" class="text-xs font-semibold text-blue-600 hover:underline"
                        @click="openForgotPassword">Forgot Password?</button>
                </div>


                <button type="submit"
                    class="w-full bg-[var(--primary-color)] hover:bg-[var(--primary-color-hover)] text-[var(--primary-text-color)] font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 disabled:opacity-60"
                    :disabled="isSubmitting">
                    <span v-if="isSubmitting"
                        class="animate-spin inline-block h-5 w-5 border-2 border-white border-t-transparent rounded-full"></span>
                    <span>Sign In</span>
                </button>

                <!-- Progress bar -->
                <div v-if="isSubmitting" class="w-full h-1 bg-gray-200 rounded overflow-hidden mt-2">
                    <div class="h-full bg-[var(--primary-color)] animate-progress-bar" style="width:100%"></div>
                </div>

                <div class="text-center text-sm text-gray-600">
                    Don't have an account yet? <button type="button" class="font-semibold"
                        :style="{ color: primaryColor }" @click="openRegister">Sign Up</button>
                </div>

                <!-- Forgot Password Modal -->
                <div v-if="forgotOpen" class="fixed inset-0 z-50 bg-black/60 flex items-center justify-center px-4"
                    @click.self="forgotOpen = false">
                    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-6 relative">
                        <h3 class="text-lg font-bold mb-2">Forgot Password</h3>
                        <p class="text-sm text-gray-600 mb-4">Masukkan email Anda untuk menerima link reset password.
                        </p>
                        <input v-model="forgotEmail" type="email" class="input mb-3" placeholder="Email" />
                        <button @click="submitForgotPassword" :disabled="forgotLoading" :style="{
        backgroundColor: primaryColor,
        color: primaryText
    }" class="w-full font-semibold py-2 rounded-lg transition disabled:opacity-60 hover:brightness-90">
                            <span v-if="forgotLoading"
                                class="animate-spin inline-block h-4 w-4 border-2 border-white border-t-transparent rounded-full mr-2"></span>
                            Kirim Link Reset
                        </button>
                        <button class="absolute top-2 right-2 text-gray-400 hover:text-gray-700"
                            @click="forgotOpen = false"><i class="fas fa-times"></i></button>
                    </div>
                </div>
            </form>

            <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-700" @click="close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- BACKDROP -->
    <div id="successModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">

        <!-- MODAL -->
        <div id="successCard"
            class="bg-white w-[420px] rounded-2xl shadow-2xl p-10 text-center scale-90 opacity-0 transition-all duration-500">

            <!-- ICON AREA -->
            <div class="relative flex justify-center mb-6">

                <!-- Circle Animation -->
                <div class="absolute w-28 h-28 rounded-full bg-blue-100 animate-ping opacity-30"></div>
                <img id="checkIcon" src="@/assets/sucess_login.png" alt="Success" class="w-24 h-24 scale-0">
                
            </div>

            <!-- TITLE -->
            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Login Berhasil!
            </h2>

            <!-- DESCRIPTION -->
            <p class="text-gray-500 leading-relaxed">
                Selamat datang kembali. Senang bisa ketemu kamu lagi!
            </p>

            <!-- OPTIONAL BUTTON -->
            <button @click="closeSuccessModal()"
                    class="mt-8 px-6 py-2 rounded-lg shadow transition"
                    :style="{
                        backgroundColor: primaryColor,
                        color: primaryText
                    }">
                Lanjutkan
            </button>
        </div>
    </div>

</template>

<script setup>
import { computed, reactive, ref, watch, onMounted, onUnmounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'
import { useAuthStore } from '@/store/auth'
import { Swal, apiPostDataWithReturn, Api } from '@/store/action'
import { startSilentRefresh } from '@/store/authRefresh'
const authStore = useAuthStore()
const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const props = defineProps({
    open: Boolean
})
const emit = defineEmits(['close', 'open-register'])

const infoStore = useInfoStore()
const { data: info } = storeToRefs(infoStore)
const currentInfo = computed(() => info.value?.[0] ?? {})
const navBarColor = computed(() => currentInfo.value?.navBarColor || '#ffffff')
const primaryColor = computed(() => currentInfo.value?.primaryColor || '#10b981')
const primaryHover = computed(() => currentInfo.value?.primaryColorHover || '#059669')
const primaryText = computed(() => currentInfo.value?.primaryTextColor || '#ffffff')

const form = reactive({
    email: '',
    password: '',
    remember: false
})


const showPassword = ref(false)
const isSubmitting = ref(false)

// Forgot password modal state
const forgotOpen = ref(false)
const forgotEmail = ref('')
const forgotLoading = ref(false)

const close = () => emit('close')
const openForgotPassword = () => {
    forgotOpen.value = true
    forgotEmail.value = form.email || ''
}

const submitForgotPassword = async () => {
    if (!forgotEmail.value) {
        Swal.fire({ icon: 'error', title: 'Oops...', html: 'Email wajib diisi.' })
        return
    }
    forgotLoading.value = true
    try {
        await Api.post('/auth/forgot-password', { email: forgotEmail.value })
        Swal.fire({ icon: 'success', title: 'Berhasil', html: 'Link reset password telah dikirim ke email Anda.' })
        forgotOpen.value = false
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', html: e?.response?.data?.message || 'Gagal mengirim email reset password.' })
    }
    forgotLoading.value = false
}

const onSubmit = async () => {
    if (!form.email || !form.password) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... !',
            html: 'Email dan password wajib diisi.'
        })
        return
    }
    isSubmitting.value = true
    const payload = {
        email: form.email,
        password: form.password
    }
    const result = await apiPostDataWithReturn('auth/login_apps', payload, {}, false)
    isSubmitting.value = false
    if (!result?.success) {
        Swal.fire({
            icon: 'error',
            title: 'Login gagal',
            html: result?.data?.message || result?.message || 'Email atau password salah.'
        })
        return
    }
    const token = result.data?.token
    if (token) {
        authStore.setAuth({
            token: result.data?.token,
            refresh_token: result.data?.refresh_token,
            users: result.data?.users,
            permissions: result.data?.permissions,
            expired_in: result.data?.expired_in,
            refresh_exp: result.data?.refresh_exp
        })
        Api.defaults.headers.common['Authorization'] = `Bearer ${token}`
        const modal = document.getElementById('successModal');
        const card = document.getElementById('successCard');
        const check = document.getElementById('checkIcon');

        modal.classList.remove('opacity-0','pointer-events-none');

        setTimeout(() => {
            card.classList.remove('scale-90','opacity-0');
            card.classList.add('scale-100','opacity-100');
        }, 100);

        setTimeout(() => {
            check.classList.remove('scale-0');
            check.classList.add('scale-100');
            check.style.transition = "transform .4s cubic-bezier(.34,1.56,.64,1)";
        }, 400);
            startSilentRefresh()
        }
    close()
}

const closeSuccessModal = () => {
    const modal = document.getElementById('successModal');
    const card = document.getElementById('successCard');
    // Jangan reset checkIcon agar ceklis tetap tampil
    card.classList.add('scale-90','opacity-0');
    setTimeout(() => {
        modal.classList.add('opacity-0','pointer-events-none');
    }, 250);
}

const openRegister = () => {
    emit('open-register')
    close()
}

watch(() => props.open, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})

const onEsc = (event) => {
    if (event.key === 'Escape') {
        close()
    }
}

onMounted(() => {
    window.addEventListener('keydown', onEsc)
    window.closeSuccessModal = closeSuccessModal
})
onUnmounted(() => {
    window.removeEventListener('keydown', onEsc)
    delete window.closeSuccessModal
})
</script>

<style scoped>
.input {
    width: 100%;
    border: 1px solid #e5e7eb; /* border-gray-200 */
    border-radius: 0.5rem; /* rounded-lg */
    padding-left: 0.75rem; /* px-3 */
    padding-right: 0.75rem; /* px-3 */
    padding-top: 0.5rem; /* py-2 */
    padding-bottom: 0.5rem; /* py-2 */
    font-size: 0.875rem; /* text-sm */
    background-color: #f9fafb; /* bg-gray-50 */
    outline: none; /* focus:outline-none */
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}

.animate-progress-bar {
    animation: progressBar 1.2s linear infinite;
}

@keyframes progressBar {
    0% {
        width: 0;
    }

    100% {
        width: 100%;
    }
}

.input-icon {
    padding-left: 2.5rem;
}

.input-icon-right {
    padding-right: 2.5rem;
}

.input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px var(--primary-color);
}

.animate-scale {
    animation: scaleIn 0.2s ease-out;
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.98);
    }

    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>
