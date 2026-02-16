<template>
    <div>
        <h2 style="font-size: 28px; font-weight: 700; margin-bottom: 18px;">Akun</h2>
        <div class="form mx-auto px-2 sm:px-4">
            <div class="px-6 pt-6 pb-4 text-center" :style="{ backgroundColor: navBarColor }">
                <div class="flex items-center justify-center gap-2">
                    <img :src="imageBaseUrl + currentInfo?.logo" alt="ID Room" class="h-12" />
                </div>
            </div>
            <div class="p-6 pb-4 text-center">
                <h3 class="text-xl font-bold text-gray-900">Informasi Akun Anda</h3>
            </div>
            <form class="px-2 sm:px-6 pb-6 space-y-4" @submit.prevent="onSubmit">
                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-user text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.name" type="text" class="input input-icon" placeholder="Enter Name" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-envelope text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.email" type="email" class="input input-icon"
                            placeholder="Enter your email" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        No Telp <span class="text-red-500">*</span>
                    </label>
                    <div class="col-span-8 flex gap-2">
                        <div ref="countryMenuRef" class="relative w-36">
                            <button type="button" class="input w-full bg-white text-left pr-8 flex items-center gap-2"
                                :disabled="countryCodes.length === 0" @click="toggleCountryMenu">
                                <img v-if="selectedCountry && getCountryFlagUrl(selectedCountry)"
                                    :src="getCountryFlagUrl(selectedCountry)" alt="" class="w-5 h-4 rounded-sm" />
                                <span>{{ selectedCountryLabel }}</span>
                            </button>
                            <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                            <div v-if="isCountryOpen"
                                class="absolute z-10 mt-2 w-56 max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                <button v-for="(item, index) in countryCodes" :key="getCountryKey(item, index)"
                                    type="button"
                                    class="w-full px-3 py-2 text-left flex items-center gap-2 hover:bg-gray-50"
                                    @click="selectCountry(item)">
                                    <img v-if="getCountryFlagUrl(item)" :src="getCountryFlagUrl(item)" alt=""
                                        class="w-5 h-4 rounded-sm" />
                                    <span class="text-sm">{{ getCountryCodeLabel(item) }}</span>
                                </button>
                            </div>
                        </div>
                        <div class="relative flex-1">
                            <i class="fas fa-phone text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                            <input v-model="form.phone" type="tel" class="input input-icon" placeholder="81234567890"
                                @input="onPhoneInput" />
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Tanggal lahir <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.birthDate" type="date" class="input input-icon" />
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Password Lama <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-lock text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input :type="showPassword ? 'text' : 'password'" v-model="form.password"
                            class="input input-icon input-icon-right" placeholder="Masukkan password lama" />
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                            @click="showPassword = !showPassword">
                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-lock text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input :type="showConfirm ? 'text' : 'password'" v-model="form.confirmPassword"
                            class="input input-icon input-icon-right" placeholder="Masukkan password baru" />
                        <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"
                            @click="showConfirm = !showConfirm">
                            <i class="fas" :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Foto Profil
                    </label>
                    <div class="relative col-span-8 flex items-center gap-4">
                        <img
                            :src="form.photoUrl"
                            alt="Foto Profil"
                            class="w-16 h-16 rounded-full object-cover border"
                        />
                        <input type="file" accept="image/*" @change="onPhotoChange" />
                    </div>
                </div>


                <button type="submit"
                    class="w-full font-semibold py-3 rounded-lg transition disabled:opacity-70 disabled:cursor-not-allowed"
                    :style="{
                background: primaryColor,
                color: primaryText,
                border: 'none',
                boxShadow: '0 2px 8px 0 rgba(30,42,80,0.08)'
            }" @mouseover="hovering = true" @mouseleave="hovering = false" :disabled="isSubmitting">
                    <span v-if="isSubmitting" class="inline-flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        Processing...
                    </span>
                    <span v-else>Simpan Perubahan</span>
                </button>

                <div v-if="isSubmitting" class="w-full h-2 bg-gray-200 rounded overflow-hidden">
                    <div class="h-full animate-pulse" :style="{ backgroundColor: primaryColor }"></div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive, ref, watch, onMounted, onUnmounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'
import { useAuthStore } from '@/store/auth'
import { apiGetData, apiPostData, Swal, Api } from '@/store/action'
import profileImage from '@/assets/user/user.png'
const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const props = defineProps({
    open: Boolean
})
const emit = defineEmits(['close', 'open-login'])

const toRgba = (value, opacity) => {
    if (typeof value !== 'string') return `rgba(16, 185, 129, ${opacity})`
    if (!value.startsWith('#')) return `rgba(16, 185, 129, ${opacity})`
    const hex = value.replace('#', '')
    if (hex.length !== 6) return `rgba(16, 185, 129, ${opacity})`
    const r = parseInt(hex.slice(0, 2), 16)
    const g = parseInt(hex.slice(2, 4), 16)
    const b = parseInt(hex.slice(4, 6), 16)
    return `rgba(${r}, ${g}, ${b}, ${opacity})`
}

const infoStore = useInfoStore()
const { data: info } = storeToRefs(infoStore)
const currentInfo = computed(() => info.value?.[0] ?? {})
const navBarColor = computed(() => currentInfo.value?.navBarColor || '#ffffff')
const primaryColor = computed(() => currentInfo.value?.primaryColor || '#10b981')
const primaryHover = computed(() => currentInfo.value?.primaryColorHover || '#059669')
const primaryText = computed(() => currentInfo.value?.primaryTextColor || '#ffffff')
const primarySoft = computed(() => toRgba(primaryColor.value, 0.12))

const { user } = storeToRefs(useAuthStore())
const form = reactive({
    name: '',
    email: '',
    countryCode: '',
    phone: '',
    birthDate: '',
    referralCode: '',
    password: '',
    confirmPassword: '',
    remember: false,
    photo: null,
    photoPreview: '',
    photoUrl: ''
})

const showPassword = ref(false)
const showConfirm = ref(false)
const countryCodes = ref([])
const isCountryOpen = ref(false)
const countryMenuRef = ref(null)
const isSubmitting = ref(false)

const avatarUrl = computed(() =>
    user.value?.foto
        ? imageBaseUrl + user.value.foto
        : profileImage
)
const close = () => emit('close')
const openLogin = () => {
    emit('open-login')
    close()
}

const onPhotoChange = (event) => {
    const file = event.target.files[0]
    if (file) {
        form.photo = file
    }
}

const onSubmit = async () => {
    isSubmitting.value = true
    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('email', form.email)
    formData.append('country_code', form.countryCode)
    formData.append('phone', form.phone)
    formData.append('birth_date', form.birthDate)
    formData.append('referral_code', form.referralCode)
    formData.append('password', form.password)
    formData.append('confirm_password', form.confirmPassword)
    formData.append('photo', form.photo)
    if (form.photo) {
        formData.append('photo', form.photo)
    }
    const response = await apiPostData('member/update-profile', formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })
    if (response) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Profile updated successfully.',
            confirmButtonColor: primaryColor.value
        })
        isSubmitting.value = false
    } else {
        isSubmitting.value = false
    }

}

const getCountryCodeValue = (item) => {
    return item?.dial_code || item?.code || item?.kode || item?.kode_negara || item?.kodeNegara || item?.phone_code || item?.phoneCode || item?.calling_code || item?.callingCode || ''
}

const getCountryLabelName = (item) => {
    return item?.nama || item?.nama_negara || item?.negara || item?.country || item?.name || ''
}

const getCountryFlagCode = (item) => {
    const raw = item?.flag || item?.flag_code || item?.flagCode || item?.country_code || item?.countryCode || ''
    return String(raw).trim().toUpperCase()
}

const getCountryCodeLabel = (item) => {
    const name = getCountryLabelName(item)
    const code = getCountryCodeValue(item)
    if (name && code) return `${name} (${code})`
    return name || code || '-'
}

const getCountryFlagUrl = (item) => {
    const code = getCountryFlagCode(item).toLowerCase()
    if (!code || code.length !== 2) return ''
    return `https://flagcdn.com/16x12/${code}.png`
}

const selectedCountry = computed(() => {
    return countryCodes.value.find((item) => getCountryCodeValue(item) === form.countryCode) || null
})

const selectedCountryLabel = computed(() => {
    const code = selectedCountry.value ? getCountryCodeValue(selectedCountry.value) : ''
    return code || 'Pilih kode'
})

const getCountryKey = (item, index) => {
    return item?.id || getCountryCodeValue(item) || getCountryLabelName(item) || index
}

const pickDefaultCountryCode = (list) => {
    if (!Array.isArray(list) || list.length === 0) return ''
    const byPlus62 = list.find((item) => getCountryCodeValue(item) === '+62')
    if (byPlus62) return getCountryCodeValue(byPlus62)
    const byIndonesia = list.find((item) => /indo/i.test(getCountryLabelName(item)))
    if (byIndonesia) return getCountryCodeValue(byIndonesia)
    const by62 = list.find((item) => /62/.test(getCountryCodeValue(item)))
    if (by62) return getCountryCodeValue(by62)
    return getCountryCodeValue(list[0])
}

const onPhoneInput = (event) => {
    let val = event.target.value.replace(/\D/g, '')
    if (val.startsWith('0')) {
        val = val.replace(/^0+/, '')
    }
    form.phone = val
    event.target.value = val
}

const toggleCountryMenu = () => {
    if (countryCodes.value.length === 0) return
    isCountryOpen.value = !isCountryOpen.value
}

const selectCountry = (item) => {
    form.countryCode = getCountryCodeValue(item)
    isCountryOpen.value = false
}

const onDocumentClick = (event) => {
    if (!isCountryOpen.value || !countryMenuRef.value) return
    if (!countryMenuRef.value.contains(event.target)) {
        isCountryOpen.value = false
    }
}

const passwordStrength = computed(() => {
    const pwd = form.password
    let score = 0
    if (pwd.length >= 6) score++
    if (/[a-z]/.test(pwd)) score++
    if (/[A-Z]/.test(pwd)) score++
    if (/[0-9]/.test(pwd)) score++
    if (/[^A-Za-z0-9]/.test(pwd)) score++
    return score
})


watch(() => props.open, (val) => {
    document.body.style.overflow = val ? 'hidden' : ''
})

const onEsc = (event) => {
    if (event.key === 'Escape') {
        close()
    }
}

onMounted(async () => {
    window.addEventListener('keydown', onEsc)
    document.addEventListener('click', onDocumentClick)
    // Ambil kode negara dulu
    const response = await apiGetData('public/kode-negara')
    countryCodes.value = response?.data || []
    // Isi form dengan data user login jika ada
    if (user.value) {
        form.name = user.value.name || ''
        form.email = user.value.email || ''
        let phone = user.value.phone || ''
        let foundCountry = null
        // Cari kode negara yang cocok di depan nomor telepon
        if (phone && Array.isArray(countryCodes.value) && countryCodes.value.length > 0) {
            for (const item of countryCodes.value) {
                const code = getCountryCodeValue(item)
                if (code && (phone.startsWith(code) || phone.startsWith(code.replace('+', '')))) {
                    foundCountry = code
                    // Hapus kode negara (dengan atau tanpa '+') dari depan nomor telepon
                    if (phone.startsWith(code)) {
                        phone = phone.slice(code.length)
                    } else if (phone.startsWith(code.replace('+', ''))) {
                        phone = phone.slice(code.replace('+', '').length)
                    }
                    break
                }
            }
        }
        form.countryCode = user.value.country_code || foundCountry || pickDefaultCountryCode(countryCodes.value)
        form.phone = phone
        form.birthDate = user.value.birth_date || ''
        form.referralCode = user.value.referral_code || ''
    } else {
        form.countryCode = pickDefaultCountryCode(countryCodes.value)
    }
    form.photoUrl = avatarUrl.value
})
onUnmounted(() => {
    window.removeEventListener('keydown', onEsc)
    document.removeEventListener('click', onDocumentClick)
})
</script>

<style scoped>
.form {
    background: #fff;
    border-radius: 18px;
    padding: 18px 8px;
    max-width: 100%;
}
@media (min-width: 640px) {
    .form {
        padding: 28px 32px;
        max-width: 550px;
    }
}

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
    outline: none;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
}
.input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px var(--primary-color);
    outline: none;
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
    gap: 0.5rem;
    border: 1px solid #e5e7eb; /* border-gray-200 */
    border-radius: 0.5rem; /* rounded-lg */
    padding-top: 0.5rem; /* py-2 */
    padding-bottom: 0.5rem; /* py-2 */
    font-size: 0.875rem; /* text-sm */
    font-weight: 600; /* font-semibold */
    color: #374151; /* text-gray-700 */
    background-color: #fff;
    transition: background-color 0.2s;
}
.btn-social:hover {
    background-color: #f9fafb; /* hover:bg-gray-50 */
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
