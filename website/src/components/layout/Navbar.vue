<script setup>
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'
import { useAuthStore } from '@/store/auth'
import { ref, watch, computed } from 'vue'
import NavItem from '@/components/ui/NavItem.vue'
import RegisterModal from '@/components/auth/RegisterModal.vue'
import LoginModal from '@/components/auth/LoginModal.vue'
import profileImage from '@/assets/user/user.png'
import { Api } from '@/api/Api'
import Swal from 'sweetalert2'


const { data: info } = storeToRefs(useInfoStore())
const currentInfo = computed(() => info.value?.[0] ?? {})
const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const drawerOpen = ref(false)
const registerOpen = ref(false)
const loginOpen = ref(false)


const authStore = useAuthStore()
const { token, user } = storeToRefs(authStore)
const profileMenuOpen = ref(false)

const avatarUrl = computed(() =>
    user.value?.foto
        ? imageBaseUrl + user.value.foto
        : profileImage
)


async function handleLogout() {
    const result = await Swal.fire({
        title: 'Keluar dari akun...',
        text: 'Yah, kamu akan kelewatan banyak hal kalau keluar akun, seperti dapat Points dan akses ke promo & fitur khusus member. Yakin mau keluar akun?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Iya, Keluar',
        cancelButtonText: 'Tidak',
        reverseButtons: true,
        customClass: {
            confirmButton: 'bg-red-600 text-white border border-red-600 rounded-full px-6 py-2 mr-2 font-medium hover:bg-red-700',
            cancelButton: 'bg-gray-200 text-gray-700 rounded-full px-6 py-2 font-medium hover:bg-gray-300',
        },
        buttonsStyling: false
    })
    if (result.isConfirmed) {
        let success = false
        let message = 'Anda telah logout.'
        try {
            const res = await Api.post('/auth/logout', {}, { withCredentials: true })
            if (res && res.data && res.data.message) {
                message = res.data.message
            }
            success = true
        } catch (e) {
            message = e?.response?.data?.message || 'Logout gagal, silakan coba lagi.'
        }
    
        const modal = document.getElementById('logoutModal');
        const card  = document.getElementById('logoutCard');
        const icon  = document.getElementById('logoutIcon');

        modal.classList.remove('opacity-0','pointer-events-none');

        setTimeout(() => {
            card.classList.remove('scale-90','opacity-0');
            card.classList.add('scale-100','opacity-100');
        }, 100);

        setTimeout(() => {
            icon.classList.remove('scale-0');
            icon.classList.add('scale-100');
            icon.style.transition = "transform .4s cubic-bezier(.34,1.56,.64,1)";
        }, 400);

        // auto close + stay di login
        setTimeout(() => {
            card.classList.add('scale-90','opacity-0');
            modal.classList.add('opacity-0');
            authStore.clearAuth()
            window.location.href = '/'
        }, 4000);

    }
}

watch(drawerOpen, (val) => {
    if (val) {
        document.body.style.overflow = 'hidden'
        document.body.style.position = 'fixed'
        document.body.style.width = '100%'
    } else {
        document.body.style.overflow = ''
        document.body.style.position = ''
        document.body.style.width = ''
    }
})
</script>


<template>
    <nav :style="{ backgroundColor: currentInfo?.navBarColor, color: currentInfo?.navBarTextColor }"
        class="fixed top-0 left-0 right-0 w-full z-50">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

            <!-- Logo -->
            <router-link to="/" class="flex items-center">
                <img :src="imageBaseUrl + currentInfo?.logo" alt="ID Room" class="h-12" />
            </router-link>

            <!-- Desktop Menu -->
            <ul class="hidden md:flex gap-6 font-medium" :style="{ color: currentInfo?.navBarTextColor }">
                <NavItem label="Beranda" to="/" />
                <NavItem label="Sewa Properti" to="/sewa-properti" />
                <NavItem label="Jual Properti" to="/jual-properti" />
                <NavItem label="Interior & Renovasi Properti" to="/interior-renovation" />
                <NavItem label="Tentang Kami" to="/tentang-kami" />
                <NavItem label="Hubungi Kami" to="/hubungi-kami" />
            </ul>

            <!-- Actions Desktop -->
            <div class="hidden md:flex gap-3 items-center relative">
                <template v-if="token">
                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button @click="profileMenuOpen = !profileMenuOpen"
                            class="flex items-center gap-2 px-3 py-2 rounded-full bg-gray-100 hover:bg-gray-200 text-black transition-colors duration-300">
                            <img :src="avatarUrl" alt="User" class="h-6 w-6 rounded-full object-fit" />
                            <span>{{ user?.name || 'Profile' }} | {{ user?.wallet_point?.coin_balance || 0 }} Points</span>
                        </button>
                        <div v-if="profileMenuOpen" class="absolute right-0 mt-2 w-40 bg-white rounded shadow-lg z-50">
                            <router-link to="/dashboard" class="block px-4 py-2 hover:bg-gray-100 text-black">My
                                Profile</router-link>
                            <button @click="handleLogout"
                                class="block w-full text-left px-4 py-2 hover:bg-gray-100 text-black">Logout</button>
                        </div>
                    </div>
                </template>

                <template v-else>
                    <button :style="{
        '--btn-bg': currentInfo.primaryColor,
        '--btn-bg-hover': currentInfo.primaryColorHover,
        '--btn-text-color': currentInfo.primaryTextColor
    }" class="px-4 py-2 text-sm bg-[var(--btn-bg)] text-[var(--btn-text-color)] hover:bg-[var(--btn-bg-hover)] transition-colors duration-300"
                        @click="loginOpen = true">
                        Sign In
                    </button>
                    <button :style="{
        '--btn-bg': currentInfo.secondColor,
        '--btn-bg-hover': currentInfo.secondColorHover,
        '--btn-text-color': currentInfo.secondTextColor
    }" class="px-4 py-2 text-sm bg-[var(--btn-bg)] text-[var(--btn-text-color)] hover:bg-[var(--btn-bg-hover)] transition-colors duration-300"
                        @click="registerOpen = true">
                        Register
                    </button>
                </template>
            </div>

            <!-- Hamburger -->
            <button @click="drawerOpen = true" class="md:hidden p-2 rounded hover:bg-gray-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

        </div>
    </nav>

    <!-- BACKDROP -->
    <transition name="fade">
        <div v-if="drawerOpen" @click="drawerOpen = false" class="fixed inset-0 bg-black/60 z-[60]" />
    </transition>

    <!-- DRAWER -->
    <transition name="drawer">
        <aside v-if="drawerOpen" class="fixed inset-0 bg-gray-900 text-white z-[61] flex flex-col">

            <!-- Header -->
            <div class="flex items-center justify-between px-6 h-16 border-b border-gray-800">
                <img src="@/assets/navbar/logo.png" alt="ID Room" class="h-10" />
                <button @click="drawerOpen = false" class="p-2 hover:bg-gray-800 rounded">
                    ✕
                </button>
            </div>

            <!-- Menu -->
            <ul class="flex-1 px-6 py-8 space-y-6 text-lg font-medium">
                <NavItem label="Beranda" to="/" @click="drawerOpen = false" />
                <NavItem label="Sewa Properti" to="/sewa-properti" @click="drawerOpen = false" />
                <NavItem label="Jual Properti" to="/jual-properti" @click="drawerOpen = false" />
                <NavItem label="Interior & Renovasi Properti" to="/interior-renovation" @click="drawerOpen = false" />
                <NavItem label="Tentang Kami" to="/tentang-kami" @click="drawerOpen = false" />
                <NavItem label="Hubungi Kami" to="/hubungi-kami" @click="drawerOpen = false" />
            </ul>

            <!-- Actions -->
            <div class="px-6 pb-8 space-y-3">

                <template v-if="token">
                    <button
                        class="w-full bg-gray-100 text-gray-900 py-3 text-sm hover:bg-gray-200 flex items-center gap-2 justify-center"
                        @click="$router.push('/dashboard'); drawerOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        My Profile
                    </button>
                    <button
                        class="w-full bg-white text-gray-900 py-3 text-sm hover:bg-gray-100 flex items-center gap-2 justify-center"
                        @click="handleLogout; drawerOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7" />
                        </svg>
                        Logout
                    </button>
                </template>

                <template v-else>
                    <button class="w-full bg-[#d5bd7d] py-3 text-sm hover:bg-[#d5b356]"
                        @click="loginOpen = true; drawerOpen = false">
                        Sign In
                    </button>
                    <button class="w-full bg-white text-gray-900 py-3 text-sm hover:bg-gray-100"
                        @click="registerOpen = true; drawerOpen = false">
                        Register
                    </button>
                </template>
            </div>

        </aside>
    </transition>

    <RegisterModal :open="registerOpen" @close="registerOpen = false" @open-login="loginOpen = true" />
    <LoginModal :open="loginOpen" @close="loginOpen = false" @open-register="registerOpen = true" />

    
    <!-- BACKDROP -->
    <div id="logoutModal"class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">

        <div id="logoutCard"
            class="bg-white w-[420px] rounded-2xl shadow-2xl p-10 text-center scale-90 opacity-0 transition-all duration-500">

            <!-- ICON -->
            <div class="relative flex justify-center mb-6">
                <div class="absolute w-28 h-28 rounded-full bg-blue-100 animate-ping opacity-30"></div>
                <img id="logoutIcon" src="@/assets/success_logout.png" alt="Success" class="w-24 h-24 scale-0">
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                Logout Berhasil
            </h2>

            <p class="text-gray-500">
                Kamu telah keluar dari sistem.
            </p>
        </div>
    </div>

</template>


<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.drawer-enter-active,
.drawer-leave-active {
    transition: transform 0.3s ease;
}

.drawer-enter-from,
.drawer-leave-to {
    transform: translateX(100%);
}
</style>
