<template>
    <div class="h-full">
        <!-- Hamburger button for mobile -->
        <button class="md:hidden fixed right-4 z-30 bg-white rounded-full shadow p-2 border border-gray-200" style="top:68px;" @click="showSidebar = true">
            <i class="fa fa-bars text-2xl"></i>
        </button>

        <!-- Sidebar Drawer -->
        <transition name="fade">
            <aside v-if="showSidebar" class="sidebar-drawer fixed inset-0 z-40 flex md:hidden">
                <div class="bg-black/40 w-full h-full" @click="showSidebar = false"></div>
                <div class="sidebar bg-white w-64 h-full shadow-lg p-0 overflow-y-auto relative" style="padding-top:56px; min-height:100vh;">
                    <button class="absolute top-3 right-3 text-gray-500 text-2xl" @click="showSidebar = false" style="top:16px;">
                        <i class="fa fa-times"></i>
                    </button>
                    <div class="p-4">
                        <!-- HEADER -->
                        <div class="sidebar-header">
                            <img :src="avatarUrl" class="avatar-lg" />
                            <h3 class="username">
                                {{ user?.name || '-' }}
                                <i class="fa fa-check-circle verified"></i>
                            </h3>
                            <!-- REFERRAL -->
                            <div class="referral-box">
                                <span>Referral</span>
                                <strong class="ref-code">{{ referralCode }}</strong>
                                <button @click="copyReferral(referralCode)">
                                    <i class="fa fa-copy"></i>
                                </button>
                                <button @click="showQr = true" title="Show QR Code">
                                    <i class="fa fa-qrcode"></i>
                                </button>
                            </div>
                        </div>
                        <!-- TIER -->
                        <div class="tier-card">
                            <div class="tier-label">
                                {{ membershipTitle }}
                            </div>
                            <div class="tier-point">{{ formatCurrency(user?.balance || 0) }}</div>
                            <button
                                    class="mt-3 px-4 py-2 rounded-lg font-semibold w-full transition" :style="{ background: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                                    @click="topUpSaldo()"
                            >
                                    Top Up Saldo
                            </button>
                            <!-- Top Up Modal -->
                            <transition name="fade">
                                <div v-if="showTopUp" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                                    <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
                                        <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl" @click="showTopUp = false"><i class="fa fa-times"></i></button>
                                        <h2 class="text-xl font-bold mb-2">Top Up Saldo</h2>
                                        <div class="mb-4">
                                            <div class="text-sm text-gray-500 mb-1">Saldo Anda</div>
                                            <div class="text-2xl font-bold mb-2">Rp {{ saldo.toLocaleString('id-ID') }}</div>
                                        </div>
                                        <div class="mb-4">
                                            <div class="font-semibold mb-2">Pilih Nominal</div>
                                            <div class="flex gap-2 mb-2 flex-wrap">
                                                <button v-for="nom in presetNominals" :key="nom" type="button"
                                                    class="px-4 py-2 rounded-lg border font-semibold"
                                                    :style="topUpNominal == nom ? { background: currentInfo.primaryColor, color: currentInfo.primaryTextColor } : {}"
                                                    :class="topUpNominal == nom ? '' : 'bg-gray-50 text-gray-700 border-gray-200'"
                                                    @click="selectNominal(nom)">
                                                    Rp {{ nom.toLocaleString('id-ID') }}
                                                </button>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span>Atau isi manual</span>
                                                <input type="text" inputmode="numeric" pattern="[0-9]*" class="border rounded px-3 py-2 " :value="manualNominal" @input="handleManualInput" placeholder="Nominal" />
                                            </div>
                                        </div>
                                        <div class="mb-4 flex justify-between font-bold text-lg">
                                            <span>Total Bayar :</span>
                                            <span>Rp {{ topUpNominal ? topUpNominal.toLocaleString('id-ID') : '0' }}</span>
                                        </div>
                                        <button class="w-full py-3 rounded-lg font-semibold transition-colors" :style="topUpNominal ? { background: currentInfo.primaryColor, color: currentInfo.primaryTextColor } : {}" :disabled="!topUpNominal || topUpNominal < 10000" @click="continueTopUp">
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            </transition>
                        
                        </div>
                        
                        <!-- Points -->
                        <div class="points-box">
                            <span>Points Kamu : </span>
                            <strong class="points">{{ user?.wallet_point?.coin_balance || 0 }} Points</strong>
                            <button
                                v-if="user?.wallet_point?.coin_balance >= 5"
                                class="ml-2 px-3 py-1 rounded text-xs"
                                :style="{ background: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                                @click="openExchange"
                            >
                                Tukar Point
                            </button>
                        </div>
                
                        <!-- MENU -->
                        <nav class="menu flex flex-col gap-3 mt-8">
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'dashboard' }" @click="$emit('menu', 'dashboard'); showSidebar = false">
                                <i class="fa fa-dashboard"></i>
                                <span>Dashboard</span>
                            </button>
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'booking' }" @click="$emit('menu', 'booking'); showSidebar = false">
                                <i class="fa fa-book"></i>
                                <span>Pesanan Saya</span>
                            </button>
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'mymembership' }" @click="$emit('menu', 'mymembership'); showSidebar = false">
                                <i class="fa fa-id-card"></i>
                                <span>Membership Saya</span>
                            </button>
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'transactions' }" @click="$emit('menu', 'transactions'); showSidebar = false">
                                <i class="fa fa-credit-card"></i>
                                <span>Daftar Pembelian</span>
                            </button>
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'points' }" @click="$emit('menu', 'points'); showSidebar = false">
                                <i class="fa fa-coins"></i>
                                <span>Point Saya</span>
                            </button>
                            
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg mb-2" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'membership' }" @click="$emit('menu', 'membership'); showSidebar = false">
                                <i class="fa fa-gift"></i>
                                <span>Membership</span>
                            </button>
                            <button class="menu-item w-full flex items-center gap-3 px-4 py-3 rounded-lg" style="justify-content: flex-start; text-align: left;" :class="{ active: activeMenu === 'akun' }" @click="$emit('menu', 'akun'); showSidebar = false">
                                <i class="fa fa-cog"></i>
                                <span>Akun</span>
                            </button>
                        </nav>

                        <footer class="sidebar-footer mt-6">
                            <button class="logout-btn w-full" @click="handleLogout">
                                <i class="fa fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </footer>
                    </div>
                </div>
            </aside>
        </transition>

        <!-- Sidebar for desktop -->
        <aside class="sidebar md:w-[260px] w-full h-full hidden md:flex flex-col items-stretch bg-white z-20 md:border-r border-gray-200" :style="{
                '--primary': primaryColor,
                '--primary-text': currentInfo.primaryTextColor || '#ffffff'
            }">
            <div>
                <!-- HEADER -->
                <div class="sidebar-header">
                    <img :src="avatarUrl" class="avatar-lg" />
                    <h3 class="username">
                        {{ user?.name || '-' }}
                        <i class="fa fa-check-circle verified"></i>
                    </h3>
                    <!-- REFERRAL -->
                    <div class="referral-box">
                        <span>Referral</span>
                        <strong class="ref-code">{{ referralCode }}</strong>
                        <button @click="copyReferral(referralCode)">
                            <i class="fa fa-copy"></i>
                        </button>
                        <button @click="showQr = true" title="Show QR Code">
                            <i class="fa fa-qrcode"></i>
                        </button>
                    </div>

                    <!-- Points -->
                    <div class="points-box">
                        <span>Points Kamu : </span>
                        <strong class="points">{{ user?.wallet_point?.coin_balance || 0 }} Points</strong>
                        <button
                            v-if="user?.wallet_point?.coin_balance >= 5"
                            class="ml-2 px-3 py-1 rounded text-white text-xs"
                            :style="{ background: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"  
                            @click="openExchange"
                        >
                            Tukar Point
                        </button>
                    </div>
                </div>
                <!-- TIER -->
                <div class="tier-card">
                    <div class="tier-label">
                        {{ membershipTitle }}
                    </div>
                    <div class="tier-point">{{ formatCurrency(user?.balance || 0) }}</div>
                    <button
                            class="mt-3 px-4 py-2 rounded-lg font-semibold w-full transition" :style="{ background: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                            @click="topUpSaldo()"
                    >
                            Top Up Saldo
                    </button>
                    <!-- Top Up Modal -->
                    <transition name="fade">
                        <div v-if="showTopUp" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                            <div class="bg-white rounded-2xl shadow-lg w-full max-w-md p-6 relative">
                                <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl" @click="showTopUp = false"><i class="fa fa-times"></i></button>
                                <h2 class="text-xl font-bold mb-2">Top Up Saldo</h2>
                                <div class="mb-4">
                                    <div class="text-sm text-gray-500 mb-1">Saldo Anda</div>
                                    <div class="text-2xl font-bold mb-2">Rp {{ saldo.toLocaleString('id-ID') }}</div>
                                </div>
                                <div class="mb-4">
                                    <div class="font-semibold mb-2">Pilih Nominal</div>
                                    <div class="flex gap-2 mb-2 flex-wrap">
                                        <button v-for="nom in presetNominals" :key="nom" type="button"
                                            class="px-4 py-2 rounded-lg border font-semibold"
                                            :style="topUpNominal == nom ? { background: currentInfo.primaryColor, color: currentInfo.primaryTextColor } : {}"
                                            :class="topUpNominal == nom ? '' : 'bg-gray-50 text-gray-700 border-gray-200'"
                                            @click="selectNominal(nom)">
                                            Rp {{ nom.toLocaleString('id-ID') }}
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span>Atau isi manual</span>
                                        <input type="text" inputmode="numeric" pattern="[0-9]*" class="border rounded px-3 py-2 " :value="manualNominal" @input="handleManualInput" placeholder="Nominal" />
                                    </div>
                                </div>
                                <div class="mb-4 flex justify-between font-bold text-lg">
                                    <span>Total Bayar :</span>
                                    <span>Rp {{ topUpNominal ? topUpNominal.toLocaleString('id-ID') : '0' }}</span>
                                </div>
                                <button class="w-full py-3 rounded-lg font-semibold transition-colors" :style="topUpNominal ? { background: currentInfo.primaryColor, color: currentInfo.primaryTextColor } : {}" :disabled="!topUpNominal || topUpNominal < 10000" @click="continueTopUp">
                                    Continue
                                </button>
                            </div>
                        </div>
                    </transition>

                
                </div>
                <!-- MENU -->
                <nav class="menu mt-6">
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg mb-2" :class="{ active: activeMenu === 'dashboard' }" @click="$emit('menu', 'dashboard')">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </button>
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg mb-2" :class="{ active: activeMenu === 'booking' }" @click="$emit('menu', 'booking')">
                        <i class="fa fa-book"></i>
                        <span>Pesanan Saya</span>
                    </button>
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg mb-2" :class="{ active: activeMenu === 'mymembership' }" @click="$emit('menu', 'mymembership')">
                        <i class="fa fa-id-card"></i>
                        <span>Membership Saya</span>
                    </button>
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg mb-2" :class="{ active: activeMenu === 'transactions' }" @click="$emit('menu', 'transactions')">
                        <i class="fa fa-credit-card"></i>
                        <span>Daftar Pembelian</span>
                    </button>
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg mb-2" :class="{ active: activeMenu === 'points' }" @click="$emit('menu', 'points')">
                        <i class="fa fa-coins"></i>
                        <span>Point Saya</span>
                    </button>
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg mb-2" :class="{ active: activeMenu === 'membership' }" @click="$emit('menu', 'membership')">
                        <i class="fa fa-gift"></i>
                        <span>Membership</span>
                    </button>
                    <button class="menu-item w-full text-left px-4 py-3 rounded-lg" :class="{ active: activeMenu === 'akun' }" @click="$emit('menu', 'akun')">
                        <i class="fa fa-cog"></i>
                        <span>Akun</span>
                    </button>
                </nav>

                <footer class="sidebar-footer mt-6">
                    <button class="logout-btn w-full" @click="handleLogout">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </footer>
            </div>
        </aside>

        
        <!-- Modal Tukar Point Responsive (Bottom Sheet for Mobile) -->
        <transition name="fade">
            <div v-if="showExchange" class="fixed inset-0 z-50 flex items-end md:items-center justify-center bg-black/40">
                <div class="bg-white rounded-t-2xl md:rounded-2xl shadow-lg w-full md:max-w-md p-6 relative animate-slideup-modal" style="max-height:90vh;">
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl z-10" @click="showExchange = false"><i class="fa fa-times"></i></button>
                    <h2 class="text-xl font-bold mb-2">Tukar Point</h2>
                    <div class="mb-4">
                        <div class="text-sm text-gray-500 mb-1">Points Kamu</div>
                        <div class="text-2xl font-bold mb-2">{{ user?.wallet_point?.coin_balance || 0 }} Points</div>
                    </div>
                    <div class="mb-4">
                        <div class="font-semibold mb-2">Masukkan jumlah point (kelipatan 5)</div>
                        <input type="number" min="5" :max="user?.wallet_point?.coin_balance" step="5"
                            v-model.number="exchangeAmount"
                            class="border rounded px-3 py-2 w-full"
                            placeholder="Contoh: 10" />
                    </div>
                    <button
                        class="w-full py-3 rounded-lg font-semibold"
                        :style="canExchange ? { background: currentInfo.primaryColor, color: currentInfo.primaryTextColor } : { background: '#e5e7eb', color: '#9ca3af', cursor: 'not-allowed' }"
                        :disabled="!canExchange || loadingExchange"
                        @click="exchangePoints"
                    >
                        <span v-if="!loadingExchange">Tukar Sekarang</span>
                        <span v-else><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                    </button>
                </div>
            </div>
        </transition>
        <!-- END Modal Tukar Point -->

        <!-- QR Code Modal -->
        <transition name="fade">
            <div v-if="showQr" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60" @click.self="showQr = false">
                <div class="bg-white rounded-2xl shadow-lg p-6 relative flex flex-col items-center" style="min-width:260px;">
                    <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl" @click="showQr = false"><i class="fa fa-times"></i></button>
                    <h3 class="font-bold text-lg mb-4">QR Code</h3>
                    <img
                        v-if="user?.qr_code"
                        :src="imageBaseUrl + user.qr_code"
                        alt="QR Code Referral"
                        class="w-48 h-48 object-contain border rounded mb-3"
                    />
                    <div v-else class="w-48 h-48 flex items-center justify-center border rounded mb-3 text-gray-400 text-sm">QR Code belum tersedia</div>
                    <span class="font-mono text-sm font-semibold tracking-widest">{{ referralCode }}</span>
                </div>
            </div>
        </transition>
        <!-- END QR Code Modal -->
    </div>
</template>

<script setup>
    import { computed, onMounted, ref } from 'vue'
    import { apiGetData, apiPostData, Swal } from '@/store/action'
    import { formatCurrency } from '@/utils/helpers'
    const showSidebar = ref(false)
    const showQr = ref(false)
    import { storeToRefs } from 'pinia'
    import { useAuthStore } from '@/store/auth'
    import { useMembershipStore } from '@/store/membership'
    import { useInfoStore } from '@/store/info'
    import profileImage from '@/assets/user/user.png'

    const membershipStore = useMembershipStore()
    const { data: membership } = storeToRefs(membershipStore)
    const currentMembership = computed(() => membership.value ?? [])
    const membershipTitle = computed(() => {
    return currentMembership.value?.[0]?.membership?.title ?? 'No Membership'
})

    defineProps({
        activeMenu: String
    })

    const authStore = useAuthStore()
    const { user } = storeToRefs(authStore)
    const { data: info } = storeToRefs(useInfoStore())

    const currentInfo = computed(() => info.value?.[0] ?? {})
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    const primaryColor = computed(() => currentInfo.value.primaryColor || '#2563eb')

    const avatarUrl = computed(() =>
        user.value?.foto
            ? imageBaseUrl + user.value.foto
            : profileImage
    )

    const referralCode = computed(() =>
        user.value?.referral_code || 'IDROOM123'
    )

    const mymembership = computed(() => {
        const memberships = user.value?.userMemberships || []
        if (memberships.length === 0) return null

        // Assuming the latest membership is the active one
        return memberships[memberships.length - 1]
    })

    function copyReferral(code) {
        navigator.clipboard.writeText(code)
        showToast('Referral copied!')
    }

    function showToast(text) {
        const el = document.createElement('div')
        el.className = 'toast'
        el.innerText = text
        document.body.appendChild(el)

        requestAnimationFrame(() => {
            el.classList.add('show')
        })
        setTimeout(() => {
            el.classList.remove('show')
            setTimeout(() => el.remove(), 300)
        }, 1800)
    }

     // Top Up Saldo Modal State
    const showTopUp = ref(false)
    const saldo = ref(0)
    const topUpNominal = ref(0)
    const manualNominal = ref('')
    const paymentMethod = ref('Transfer Bank')
    const presetNominals = [100000, 250000, 500000, 1000000]

    function topUpSaldo() {
        showTopUp.value = true
        topUpNominal.value = 0
        manualNominal.value = ''
    }

    function selectNominal(nom) {
        topUpNominal.value = nom
        manualNominal.value = ''
    }

    function handleManualInput(e) {
        let val = e.target.value.replace(/[^\d]/g, '')
        manualNominal.value = val
        topUpNominal.value = val ? parseInt(val) : 0
    }

    function continueTopUp() {
        if (topUpNominal.value < 10000) {
            showToast('Minimal top up Rp 10.000')
            return
        }
        showToast('Proses top up Rp ' + topUpNominal.value.toLocaleString('id-ID'))
        showTopUp.value = false
        // TODO: Kirim ke backend
    }

    // Tukar Point
    const showExchange = ref(false)
    const exchangeAmount = ref(5)
    const canExchange = computed(() => {
        const val = exchangeAmount.value
        const max = user.value?.wallet_point?.coin_balance || 0
        return val >= 5 && val % 5 === 0 && val <= max
    })

    const loadingExchange = ref(false)

    function openExchange() {
        showExchange.value = true
    }

    async function exchangePoints() {
        if (!canExchange.value || loadingExchange.value) return
        loadingExchange.value = true
    
        const payload = {
            amount: exchangeAmount.value
        }
        const res = await apiPostData('/public/tukar_point', payload)
        if (res) {
              // Refresh data user di pinia setelah tukar point
            await authStore.fetchUser()
            showExchange.value = false
            loadingExchange.value = false
        } else {
            loadingExchange.value = false
        }
        
    }

    async function handleLogout() {
        const result = await Swal.fire({
            title: 'Keluar dari akun?',
            text: 'Yakin ingin logout sekarang?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Iya, Logout',
            cancelButtonText: 'Batal',
            reverseButtons: true
        })

        if (!result.isConfirmed) return

        await apiPostData('/auth/logout', {}, false)
        authStore.clearAuth()
        showSidebar.value = false
        window.location.href = '/'
    }

    onMounted(async () => {
        if (!membershipStore.loaded) {
            await membershipStore.fetch()
        }
    })
</script>

<style>
@media (max-width: 768px) {
    .animate-slideup-modal {
        animation: slideup-modal 0.25s cubic-bezier(0.4,0,0.2,1);
    }
    @keyframes slideup-modal {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }
}
/* Sidebar Drawer for mobile */
.sidebar-drawer {
    position: fixed;
    inset: 0;
    z-index: 40;
    display: flex;
}
.sidebar-drawer .sidebar {
    width: 256px;
    min-width: 256px;
    max-width: 90vw;
    height: 100vh;
    border-right: 1px solid #f1f1f1;
    border-bottom: none;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: visible;
    padding: 0;
}
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
.fade-enter-to, .fade-leave-from {
    opacity: 1;
}
.sidebar {
    padding: 12px 8px;
    width: 100%;
    border-right: none;
    border-bottom: 1px solid #f1f1f1;
    height: auto;
    flex-direction: row;
    overflow-x: auto;
    overflow-y: visible;
}
@media (min-width: 768px) {
    .sidebar {
        width: 260px;
        border-right: 1px solid #f1f1f1;
        border-bottom: none;
        height: 100% !important;
        min-height: 100% !important;
        flex-direction: column;
        overflow-y: auto;
        overflow-x: visible;
        display: flex !important;
    }
}

/* Custom Scrollbar for Webkit */
.sidebar::-webkit-scrollbar {
    width: 8px;
    background: #fff;
}
.sidebar::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 6px;
    transition: background 0.3s;
}
.sidebar::-webkit-scrollbar-thumb:hover {
    background: #d1d5db;
}

/* HEADER */
.sidebar-header {
    text-align: center;
    margin-bottom: 28px;
}

.username {
    font-weight: 700;
    margin-top: 10px;
    font-size: 16px;
}

.verified {
    color: var(--primary);
    margin-left: 4px;
}

/* AVATAR */
.avatar-lg {
    width: 82px;
    height: 82px;
    border-radius: 50%;
    object-fit: cover;
    object-position: top;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.avatar-sm {
    width: 40px;
    height: 40px;
    border-radius: 50%;
}

/* REFERRAL */
.referral-box {
    margin-top: 10px;
    background: #f6f7fb;
    border-radius: 10px;
    padding: 6px 10px;
    font-size: 13px;
    display: inline-flex;
    gap: 6px;
    align-items: center;
}

.ref-code {
    color: var(--primary);
}

.referral-box button {
    border: none;
    background: none;
    cursor: pointer;
    color: var(--primary);
}

/* TIER */
.tier-card {
    background: linear-gradient(135deg, #eef2ff, #ffffff);
    border-radius: 16px;
    padding: 16px;
    margin-bottom: 20px;
}

.tier-point {
    font-size: 22px;
    font-weight: 700;
    color: var(--primary);
}

/* MENU */
.menu {
    display: block;
}

.menu-item {
    border: none;
    background: none;
    padding: 12px;
    border-radius: 12px;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    cursor: pointer;
    transition: background 0.3s, color 0.3s;
    margin-bottom: 8px;
    justify-content: flex-start;
}
@media (max-width: 767px) {
    .menu-item {
        font-size: 13px;
        padding: 8px 0;
    }
}

.avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: block;
    margin-left: auto;
    margin-right: auto;
    object-fit: cover;
}

.menu-item:hover {
    background: rgba(0, 0, 0, 0.04);
}

.menu-item.active {
    background: var(--primary);
    color: var(--primary-text);
    font-weight: 600;
}

/* FOOTER */
.sidebar-footer {
    display: flex;
    gap: 10px;
    align-items: center;
    padding-top: 14px;
    border-top: 1px solid #eee;
}

.logout-btn {
    border: 1px solid #ef4444;
    color: #ef4444;
    background: #fff;
    border-radius: 10px;
    padding: 10px 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.2s;
}

.logout-btn:hover {
    background: #ef4444;
    color: #fff;
}

/* TOAST */
.toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%);
    background: #111827;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    opacity: 0;
    transition: .3s;
}

.toast.show {
    opacity: 1;
}
</style>