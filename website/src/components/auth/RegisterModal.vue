<template>
    <div v-if="open" class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm flex items-center justify-center px-4"
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
                <h3 class="text-xl font-bold text-gray-900">Sign Up! For New Account</h3>
            </div>

            <form class="px-6 pb-6 space-y-4" @submit.prevent="onSubmit">
                <div class="grid grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-user text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.name" type="text" class="input input-icon" placeholder="Enter Name" />
                    </div>
                </div>

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
                        No Telp <span class="text-red-500">*</span>
                    </label>
                    <div class="col-span-8 flex flex-col sm:flex-row gap-2">
                        <div ref="countryMenuRef" class="relative w-full sm:w-36">
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

                <div class="grid grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">
                        Tanggal lahir <span class="text-red-500">*</span>
                    </label>
                    <div class="relative col-span-8">
                        <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.birthDate" type="date" class="input input-icon" />
                    </div>
                </div>

                <div class="grid grid-cols-12 items-center gap-3">
                    <label class="col-span-4 text-sm font-medium text-gray-700">Referral Code</label>
                    <div class="relative col-span-8">
                        <i class="fas fa-ticket-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input v-model="form.referralCode" type="text" class="input input-icon"
                            placeholder="Masukkan kode referral" />
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
                    <div v-if="form.password" class="col-span-8 col-start-5 mt-1 h-2 w-full bg-gray-200 rounded">
                        <div :style="{
        width: passwordStrengthBarWidth,
        backgroundColor: passwordStrengthColor,
        transition: 'width 0.3s, background-color 0.3s'
    }" class="h-full rounded"></div>
                    </div>
                </div>

                <div class="grid grid-cols-12 items-center gap-3">
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

                <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" v-model="form.remember" class="rounded border-gray-300"
                        :style="{ accentColor: primaryColor }" />
                    Remember Me
                </label>

                <button type="submit"
                    class="w-full bg-[var(--primary-color)] hover:bg-[var(--primary-color-hover)] text-[var(--primary-text-color)] font-semibold py-3 rounded-lg transition disabled:opacity-70 disabled:cursor-not-allowed"
                    :disabled="isSubmitting">
                    <span v-if="isSubmitting" class="inline-flex items-center gap-2">
                        <i class="fas fa-spinner fa-spin"></i>
                        Processing...
                    </span>
                    <span v-else>Sign Up</span>
                </button>

                <div v-if="isSubmitting" class="w-full h-2 bg-gray-200 rounded overflow-hidden">
                    <div class="h-full animate-pulse" :style="{ backgroundColor: primaryColor }"></div>
                </div>

                <div class="text-center text-sm text-gray-600">
                    Don't have an account yet? <button type="button" class="font-semibold"
                        :style="{ color: primaryColor }" @click="openLogin">Sign In</button>
                </div>
            </form>

            <button class="absolute top-4 right-4 text-gray-400 hover:text-gray-700" @click="close">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, reactive, ref, watch, onMounted, onUnmounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'
import { apiGetData, apiPostDataWithReturn, Swal, Api } from '@/store/action'
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

const form = reactive({
    name: '',
    email: '',
    countryCode: '',
    phone: '',
    birthDate: '',
    referralCode: '',
    password: '',
    confirmPassword: '',
    remember: false
})

const showPassword = ref(false)
const showConfirm = ref(false)
const countryCodes = ref([])
const isCountryOpen = ref(false)
const countryMenuRef = ref(null)
const isSubmitting = ref(false)

const close = () => emit('close')
const openLogin = () => {
    emit('open-login')
    close()
}

const resetForm = () => {
    form.name = ''
    form.email = ''
    form.countryCode = ''
    form.phone = ''
    form.birthDate = ''
    form.referralCode = ''
    form.password = ''
    form.confirmPassword = ''
    form.remember = false
    showPassword.value = false
    showConfirm.value = false
}

const onSubmit = async () => {
    if (isSubmitting.value) return
    if (!form.name || !form.email || !form.password) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... !',
            html: 'Nama, email, dan password wajib diisi.'
        })
        return
    }
    const passwordErrors = []
    if (form.password.length < 6) passwordErrors.push('Minimal 6 karakter')
    if (!/[a-z]/.test(form.password)) passwordErrors.push('Minimal 1 huruf kecil')
    if (!/[A-Z]/.test(form.password)) passwordErrors.push('Minimal 1 huruf besar')
    if (!/[0-9]/.test(form.password)) passwordErrors.push('Minimal 1 angka')
    if (!/[^A-Za-z0-9]/.test(form.password)) passwordErrors.push('Minimal 1 simbol')
    if (passwordErrors.length > 0) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... !',
            html: `Password tidak memenuhi syarat:<br>${passwordErrors.join('<br>')}`
        })
        return
    }
    if (form.password !== form.confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Oops... !',
            html: 'Konfirmasi password tidak sama.'
        })
        return
    }
    isSubmitting.value = true
    const payload = {
        name: form.name,
        email: form.email,
        password: form.password,
        confirm_password: form.confirmPassword,
        country_code: form.countryCode || null,
        phone: form.phone || null,
        birth_date: form.birthDate || null,
        referral_code: form.referralCode || null
    }
    const result = await apiPostDataWithReturn('auth/register', payload, {}, false)
    isSubmitting.value = false
    if (!result?.success) return

    const token = result.data?.access_token
    if (token) {
        if (form.remember) {
            localStorage.setItem('token_id_room', token)
            sessionStorage.removeItem('token_id_room')
        } else {
            sessionStorage.setItem('token_id_room', token)
            localStorage.removeItem('token_id_room')
        }
        Api.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        html: 'Registrasi berhasil. Silakan cek email atau WhatsApp Anda untuk verifikasi.',
        confirmButtonColor: primaryColor.value
    })
    resetForm()
    close()
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

const passwordStrengthBarWidth = computed(() => {
    if (!form.password) return '0%'
    if (passwordStrength.value <= 2) return '33%'
    if (passwordStrength.value === 3 || passwordStrength.value === 4) return '66%'
    if (passwordStrength.value === 5) return '100%'
    return '0%'
})

const passwordStrengthColor = computed(() => {
    if (!form.password) return '#6b7280' // gray-500
    if (passwordStrength.value <= 2) return '#ef4444' // red-500
    if (passwordStrength.value === 3 || passwordStrength.value === 4) return '#f59e42' // orange-400
    if (passwordStrength.value === 5) return '#10b981' // green-500
    return '#6b7280'
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
    const response = await apiGetData('public/kode-negara')
    countryCodes.value = response?.data || []
    if (!form.countryCode) {
        form.countryCode = pickDefaultCountryCode(countryCodes.value)
    }
})
onUnmounted(() => {
    window.removeEventListener('keydown', onEsc)
    document.removeEventListener('click', onDocumentClick)
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
    gap: 0.5rem; /* gap-2 */
    border: 1px solid #e5e7eb; /* border-gray-200 */
    border-radius: 0.5rem; /* rounded-lg */
    padding-top: 0.5rem; /* py-2 */
    padding-bottom: 0.5rem;
    font-size: 0.875rem; /* text-sm */
    font-weight: 600; /* font-semibold */
    color: #374151; /* text-gray-700 */
    background-color: #fff;
    transition: background 0.2s;
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
