<template>
    <div class="flex items-center justify-center min-h-screen">
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
                <h3 class="text-xl font-bold text-gray-900">Reset Your Password</h3>
            </div>
            <div class="grid grid-cols-12 items-center gap-3 p-6">
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
                <div v-if="form.password" class="col-span-8 col-start-5 mt-1 h-2 w-full bg-gray-200 rounded">
                    <div :style="{
            width: passwordStrengthBarWidth,
            backgroundColor: passwordStrengthColor,
            transition: 'width 0.3s, background-color 0.3s'
        }" class="h-full rounded"></div>
                </div>
            </div>

            <div class="grid grid-cols-12 items-center gap-3 p-6">
                <label class="col-span-4 text-sm font-medium text-gray-700">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative col-span-8">
                    <i class="fas fa-lock text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                    <input :type="showConfirm ? 'text' : 'password'" v-model="form.confirmPassword"
                        class="input input-icon input-icon-right" placeholder="" />
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                        @click="showConfirm = !showConfirm">
                        <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 pt-4 text-center">
                <button :style="{ backgroundColor: primaryColor, color: primaryText }"
                    class="w-full font-semibold py-2 rounded-lg transition hover:brightness-90" @click="submit">
                    Reset Password
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, computed, watch } from 'vue';
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'
import { useRoute } from 'vue-router'
import { Api } from '@/store/action'
import Swal from 'sweetalert2'

const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

const infoStore = useInfoStore()
const { data: info } = storeToRefs(infoStore)
const currentInfo = computed(() => info.value?.[0] ?? {})
const navBarColor = computed(() => currentInfo.value?.navBarColor || '#ffffff')
const primaryColor = computed(() => currentInfo.value?.primaryColor || '#10b981')
const primaryHover = computed(() => currentInfo.value?.primaryColorHover || '#059669')
const primaryText = computed(() => currentInfo.value?.primaryTextColor || '#ffffff')

const form = reactive({
    password: '',
    confirmPassword: ''
});
const showPassword = ref(false);
const showConfirm = ref(false);
const passwordStrength = ref(0);
const passwordStrengthBarWidth = computed(() => {
    return passwordStrength.value + '%';
});
const passwordStrengthColor = computed(() => {
    if (passwordStrength.value < 40) return 'red';
    if (passwordStrength.value < 70) return 'orange';
    return 'green';
});
watch(() => form.password, (newPassword) => {
    let strength = 0;
    if (newPassword.length >= 8) strength += 30;
    if (/[A-Z]/.test(newPassword)) strength += 20;
    if (/[a-z]/.test(newPassword)) strength += 20;
    if (/[0-9]/.test(newPassword)) strength += 15;
    if (/[\W_]/.test(newPassword)) strength += 15;
    passwordStrength.value = Math.min(strength, 100);
});

const route = useRoute()
const token = ref(route.query.token || '')
const email = ref(route.query.email || '')

async function submit() {
    if (!form.password || !form.confirmPassword) {
        Swal.fire({ icon: 'error', title: 'Oops...', html: 'Password dan konfirmasi wajib diisi.' })
        return
    }
    if (form.password !== form.confirmPassword) {
        Swal.fire({ icon: 'error', title: 'Oops...', html: 'Konfirmasi password tidak cocok.' })
        return
    }
    try {
        await Api.post('/auth/reset_password', {
            token: token.value,
            email: email.value,
            password: form.password,
            password_confirmation: form.confirmPassword,
        })
        Swal.fire({ icon: 'success', title: 'Berhasil', html: 'Password berhasil direset. Silakan login.' })
        form.password = ''
        form.confirmPassword = ''
        window.location.href = '/'
    } catch (e) {
        Swal.fire({ icon: 'error', title: 'Gagal', html: e?.response?.data?.message || 'Gagal reset password.' })
    }
}
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
.input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px var(--primary-color);
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

.btn-social {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem; /* gap-2 */
    border: 1px solid #e5e7eb; /* border-gray-200 */
    border-radius: 0.5rem; /* rounded-lg */
    padding-top: 0.5rem; /* py-2 */
    padding-bottom: 0.5rem; /* py-2 */
    font-size: 0.875rem; /* text-sm */
    font-weight: 600; /* font-semibold */
    color: #374151; /* text-gray-700 */
    background-color: transparent;
    transition: background-color 0.2s;
}
.btn-social:hover {
    background-color: #f9fafb; /* bg-gray-50 */
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
