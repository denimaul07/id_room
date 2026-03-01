<template>
  <div class="min-h-screen bg-[#f7fafc] py-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-8">
      <!-- LEFT: FORM -->
      <div class="flex-1">
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
          <h2 class="text-2xl font-bold mb-1 flex items-center gap-2">
            <i class="fas fa-envelope"></i> Data pemesan
          </h2>
          <p class="text-gray-500 mb-4 text-sm">Isi semua kolom dengan benar untuk menerima konfirmasi pemesanan.</p>
          <form class="space-y-4">
            <div>
              <label class="block font-semibold mb-1">Nama Lengkap<span class="text-red-500">*</span></label>
              <input type="text" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Sesuai KTP/paspor/SIM (tanpa tanda baca atau gelar)" v-model="form.name" required />
            </div>
            <div class="flex gap-4">
              <div class="flex-1">
                <label class="block font-semibold mb-1">No. Handphone<span class="text-red-500">*</span></label>
                <div class="flex">
                  <select class="border rounded-l-lg px-2 py-2 bg-gray-50" v-model="form.countryCode">
                    <option value="+62">+62</option>
                  </select>
                  <input type="tel" class="flex-1 border-t border-b border-r rounded-r-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="0812345678" v-model="form.phone" required />
                </div>
                <div class="text-xs text-gray-400 mt-1">Contoh: +62812345678, untuk Kode Negara (+62) dan No. Handphone 0812345678</div>
              </div>
              <div class="flex-1">
                <label class="block font-semibold mb-1">Email<span class="text-red-500">*</span></label>
                <input type="email" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Contoh: email@example.com" v-model="form.email" required />
              </div>
            </div>
            <div class="flex items-center mt-2">
              <input type="checkbox" id="forMe" v-model="form.forMe" class="mr-2" />
              <label for="forMe" class="text-sm">Pesanan ini untuk saya</label>
            </div>
          </form>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
          <h2 class="text-2xl font-bold mb-1 flex items-center gap-2">
            <i class="fas fa-user"></i> Informasi Tamu
          </h2>
          <p class="text-gray-500 mb-4 text-sm">Isi semua kolom dengan benar untuk menerima konfirmasi pesanan</p>
          <div>
            <div v-for="(guest, idx) in form.guestList" :key="idx" class="mb-4 p-3 border rounded-lg bg-gray-50">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Nama Lengkap Tamu</label>
            <input type="text" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Nama Lengkap Tamu" v-model="guest.guest_name" required />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">Gender <span class="text-gray-400 text-xs">(opsional)</span></label>
            <select v-model="guest.guest_gender" class="w-full border rounded-lg px-4 py-2">
              <option value="">Pilih Gender</option>
              <option value="0">Laki-laki</option>
              <option value="1">Perempuan</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">No. HP Tamu <span class="text-gray-400 text-xs">(opsional)</span></label>
            <input type="text" class="w-full border rounded-lg px-4 py-2" placeholder="No. HP Tamu" v-model="guest.guest_phone" />
          </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
          <div>
            <label class="block text-sm font-semibold mb-1">Email Tamu <span class="text-gray-400 text-xs">(opsional)</span></label>
            <input type="email" class="w-full border rounded-lg px-4 py-2" placeholder="Email Tamu" v-model="guest.guest_email" />
          </div>
          <div>
            <label class="block text-sm font-semibold mb-1">NIK Tamu <span class="text-gray-400 text-xs">(opsional)</span></label>
            <input type="text" class="w-full border rounded-lg px-4 py-2" placeholder="NIK Tamu" v-model="guest.guest_nik" />
          </div>
          <div class="flex items-end">
            <button type="button" @click="removeGuest(idx)" class="text-red-500 hover:text-red-700 px-2 py-1 rounded border border-red-200 bg-red-50 w-full"><i class="fas fa-trash"></i> Hapus</button>
          </div>
              </div>
            </div>
            <button type="button" @click="addGuest" class="mt-2 px-3 py-1 rounded bg-blue-100 text-blue-700 text-sm font-semibold"><i class="fas fa-plus"></i> Tambah Tamu</button>
          </div>
        </div>
      </div>
      <!-- RIGHT: SUMMARY -->
      <div class="w-full md:w-[400px] flex-shrink-0">
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
          <div class="flex items-center gap-2 mb-2">
            <span class="inline-block bg-blue-100 text-blue-700 font-bold text-xs px-2 py-1 rounded">Pilihan tepat untuk tempat menginapmu.</span>
          </div>
          <div class="font-bold text-xl mb-2">(1x) {{ booking.propertyName }} - {{ booking.roomName }}</div>
          <div class="bg-blue-50 rounded-lg p-4 mb-3 flex flex-col gap-2">
            <div class="flex justify-between items-center">
              <div>
                <div class="text-xs text-gray-500">Check-In</div>
                <div class="font-bold">{{ formatDate(booking.checkIn) }}</div>
                <div class="text-xs text-gray-400">Dari 14:00</div>
              </div>
              <div class="text-center text-xs text-gray-500">{{ booking.nights }} malam <span class="mx-1">→</span></div>
              <div>
                <div class="text-xs text-gray-500">Check-Out</div>
                <div class="font-bold">{{ formatDate(booking.checkOut) }}</div>
                <div class="text-xs text-gray-400">Sebelum 12:00</div>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-2 text-gray-700 mb-2">
            <i class="fas fa-user-friends"></i> {{ booking.capacity }} Tamu
            <span v-if="booking.breakfast" class="ml-2 inline-flex items-center gap-1 text-green-600 text-xs bg-green-50 px-2 py-1 rounded"><i class="fas fa-utensils"></i> Sarapan</span>
          </div>
          <div class="flex items-center gap-2 text-gray-700 mb-4">
            <i class="fas fa-bed"></i> {{ booking.roomType }} Bed
          </div>
          <div class="flex items-center gap-2 text-gray-700 mb-4">
            <i class="fas fa-star text-yellow-400"></i> {{ booking.rating }} / 5.0
          </div>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
          <div class="font-bold text-lg mb-2">Rincian harga</div>
          <div class="flex justify-between text-sm mb-1">
            <span>Harga Kamar</span>
            <span class="font-semibold">Rp {{ formatNumber(booking.price) }}</span>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <span>(1x) {{ booking.roomName }} - {{ booking.breakfast ? 'Include Breakfast' : 'No Breakfast' }} ({{ booking.nights }} malam)</span>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <span>Pajak</span>
            <span>Rp {{ formatNumber(booking.ppn) }}</span>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <span>Biaya Layanan</span>
            <span>Rp {{ formatNumber(booking.fee) }}</span>
          </div>
          <div class="flex justify-between text-sm mb-1">
            <span>Diskon Membership</span>
            <span class="text-green-600">- Rp {{ formatNumber(booking.disc) }}</span>
          </div>
          <div class="border-t my-2"></div>
          <!-- Coupon Input & Modal -->
          <div class="flex items-center gap-2 mb-2">
            <input v-model="couponCode" type="text" placeholder="Masukkan kode coupon" class="border rounded-lg px-2 py-1 text-sm" />
            <button type="button" class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-semibold" @click="showCouponModal = true">Pilih Coupon</button>
            <button type="button" class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold" @click="applyCoupon(couponCode ,totalPrice)">Terapkan</button>
          </div>
          <div v-if="appliedCoupon" class="text-xs text-green-700 mb-2">
            Coupon diterapkan: <b>{{ appliedCoupon.code }}</b>
            <span v-if="couponCashback > 0"> • Cashback: {{ formatNumber(couponCashback) }}</span>
            <button @click="removeCoupon" class="text-red-500 ml-2">Hapus</button>
          </div>

          <!-- Modal Coupon List (Mobile/Responsive) -->
          <div v-if="showCouponModal" class="fixed inset-0 bg-black bg-opacity-30 flex items-end md:items-center justify-center z-50">
            <div class="bg-white rounded-t-2xl md:rounded-2xl shadow-xl p-0 w-full md:max-w-md relative overflow-hidden animate-slideup-modal" style="max-height:90vh;">
              <button class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl z-10" @click="showCouponModal = false"><i class="fas fa-times"></i></button>
              <div class="bg-gradient-to-r from-pink-100 to-yellow-50 px-6 pt-6 pb-3 rounded-t-2xl">
                <div class="font-bold text-lg mb-1 flex items-center gap-2"><i class="fas fa-gift text-pink-400"></i> Promo</div>
                <div class="text-base font-semibold text-pink-700 mb-1">Kamu Dapat Diskon
                  <span v-if="listCoupon.length && listCoupon[0].type === 'percentage'">{{ listCoupon[0].value }}%</span>
                  <span v-else-if="listCoupon.length">Rp{{ formatNumber(listCoupon[0].value) }}</span>
                  <span v-if="listCoupon.length && listCoupon[0].maximum_discount"> s.d {{ formatNumber(listCoupon[0].maximum_discount) }}</span>
                  <span v-if="listCoupon.length && listCoupon[0].type === 'percentage'">rb</span>
                  <span v-if="listCoupon.length"> 🎉</span>
                </div>
                <div class="text-xs text-gray-500 mb-2">Voucher yang bisa diklaim</div>
              </div>
              <div class="bg-white px-6 pb-6 pt-2 rounded-b-2xl max-h-[60vh] md:max-h-[400px] overflow-y-auto">
                <div v-if="listCoupon.length === 0" class="text-gray-400 py-8 text-center">Tidak ada coupon tersedia.</div>
                <ul>
                  <li v-for="coupon in listCoupon" :key="coupon.id" class="mb-4 flex items-center gap-3 bg-yellow-50 border border-yellow-200 rounded-lg p-3 relative">
                    <div class="flex flex-col flex-1">
                      <div class="flex items-center gap-2 mb-1">
                        <span class="bg-green-500 text-white text-xs px-2 py-0.5 rounded font-bold">PLUS</span>
                        <span class="font-semibold text-yellow-700">{{ coupon.title || coupon.code }}</span>
                      </div>
                      <div class="text-sm font-bold text-yellow-800">
                        <template v-if="coupon.jenis === 'cashback'">
                          Cashback
                          <span>Rp{{ formatNumber(coupon.value_cashback || 0) }}</span>
                        </template>
                        <template v-else>
                          Diskon
                          <span v-if="coupon.type === 'percentage'">{{ coupon.value }}%<span v-if="coupon.maximum_discount"> s.d {{ formatNumber(coupon.maximum_discount) }}</span></span>
                          <span v-else>Rp{{ formatNumber(coupon.value) }}</span>
                        </template>
                      </div>
                      <div v-if="Number(coupon.value_cashback || 0) > 0" class="text-xs font-semibold text-green-700 mt-1">
                        Cashback: Rp{{ formatNumber(coupon.value_cashback) }}
                      </div>
                      <div class="text-xs text-gray-500 mt-1">
                        <span v-if="!coupon.minimum_transaction || coupon.minimum_transaction == 0">Tanpa min. belanja</span>
                        <span v-else>Min. transaksi Rp{{ formatNumber(coupon.minimum_transaction) }}</span>
                        <span v-if="coupon.end_date && couponTimers[coupon.end_date]" class="ml-2 text-pink-600 font-bold">
  Sisa
  <span>{{ couponTimers[coupon.end_date] }}</span>
</span>
                      </div>
                    </div>
                    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded font-bold text-xs shadow" @click="selectCoupon(coupon, totalPrice)">Klaim</button>
                  </li>
                </ul>
              </div>
            </div>
          </div>

          <div class="flex justify-between text-sm mb-1">
            <span>Total Diskon</span>
            <span class="text-green-600">- Rp {{ formatNumber(totalDiscount) }}</span>
          </div>

          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>Rp {{ formatNumber(totalPrice) }}</span>
          </div>

          <button
            class="mt-6 w-full py-3 rounded-lg font-semibold transition-colors flex items-center justify-center gap-2"
            :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
            :disabled="loading"
            @click="proses"
          >
            <span v-if="loading">
              <svg class="animate-spin h-5 w-5 mr-2 inline-block" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
              </svg>
              Memproses...
            </span>
            <span v-else>Konfirmasi Pemesanan</span>
          </button>
          <div v-if="loading" class="w-full h-2 bg-gray-200 rounded mt-2 overflow-hidden">
            <div class="h-full animate-pulse" style="width: 100%" :style="{ backgroundColor: currentInfo.primaryColor }"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
  import { ref, computed, onMounted, watch, onUnmounted } from 'vue'
  import { useRoute } from 'vue-router'
  import { apiGetData, apiPostData, Swal } from '@/store/action'
  import { useInfoStore } from '@/store/info'
  import { useMembershipStore } from '@/store/membership'
  import { useAuthStore } from '@/store/auth'
  import { storeToRefs } from 'pinia'
  const infoStore = useInfoStore()
  const { data: info, loaded } = storeToRefs(infoStore)
  const currentInfo = computed(() => info.value?.[0] ?? {})

  const membershipStore = useMembershipStore()
  const { data: membership } = storeToRefs(membershipStore)
  const currentMembership = computed(() => membership.value ?? [])

  const { user } = storeToRefs(useAuthStore())

  const route = useRoute()
  const listCoupon = ref([])
  const couponTimers = ref({})
  // Coupon logic
  const couponCode = ref('')
  const showCouponModal = ref(false)
  const appliedCoupon = ref(null)

  function selectCoupon(coupon, price) {
    
      // Cari coupon berdasarkan kode
    const found = listCoupon.value.find(c => c.code === coupon.code)
    if (!found) {
      Swal.fire('Error', 'Coupon tidak ditemukan.', 'error')
      return
    }

    // Cek status aktif
    if (found.is_active==1) {
      Swal.fire('Error', 'Coupon tidak aktif.', 'error')
      return
    }
    // Cek tanggal berlaku
    const today = new Date().toISOString().slice(0, 10)
    if (found.start_date && today < found.start_date) {
      Swal.fire('Error', 'Coupon belum aktif.', 'error')
      return
    }
    if (found.end_date && today > found.end_date) {
      Swal.fire('Error', 'Coupon sudah kadaluarsa.', 'error')
      return
    }
    // Cek minimum transaksi
    const subtotal = Number(booking.value.price || 0) + Number(booking.value.ppn || 0) + Number(booking.value.fee || 0) - Number(booking.value.disc || 0)
    if (found.minimum_transaction && subtotal < found.minimum_transaction) {
      Swal.fire('Error', 'Transaksi belum memenuhi minimum penggunaan coupon.', 'error')
      return
    }

    if (appliedCoupon.value && appliedCoupon.value.code === found.code) {
      Swal.fire('Info', 'Coupon sudah diterapkan.', 'info')
      return
    }

    apiGetData('/public/cek_coupon', { code: found.code, total: price }).then((response) => {
      if (!response.data.valid) {
        Swal.fire('Error', response.data.message || 'Coupon tidak valid.', 'error')
        return
      }

      couponCode.value = coupon.code
      appliedCoupon.value = coupon
      showCouponModal.value = false
      // Jika ingin tampilkan info sisa kuota:
      // Swal.fire('Info', `Sisa kuota: ${response.data.sisa_kuota ?? '-'}, Sisa user: ${response.data.sisa_user ?? '-'}`, 'info')
    })
  }

  function applyCoupon(coupon, price) {
    
      // Cari coupon berdasarkan kode
      const found = listCoupon.value.find(c => c.code === coupon)
      if (!found) {
        Swal.fire('Error', 'Coupon tidak ditemukan.', 'error')
        return
      }

      // Cek status aktif
      if (found.is_active==1) {
        Swal.fire('Error', 'Coupon tidak aktif.', 'error')
        return
      }
      // Cek tanggal berlaku
      const today = new Date().toISOString().slice(0, 10)
      if (found.start_date && today < found.start_date) {
        Swal.fire('Error', 'Coupon belum aktif.', 'error')
        return
      }
      if (found.end_date && today > found.end_date) {
        Swal.fire('Error', 'Coupon sudah kadaluarsa.', 'error')
        return
      }
      // Cek minimum transaksi
      const subtotal = Number(booking.value.price || 0) + Number(booking.value.ppn || 0) + Number(booking.value.fee || 0) - Number(booking.value.disc || 0)
      if (found.minimum_transaction && subtotal < found.minimum_transaction) {
        Swal.fire('Error', 'Transaksi belum memenuhi minimum penggunaan coupon.', 'error')
        return
      }

      if (appliedCoupon.value && appliedCoupon.value.code === found.code) {
        Swal.fire('Info', 'Coupon sudah diterapkan.', 'info')
        return
      }

      apiGetData('/public/cek_coupon', { code: found.code, total: price }).then((response) => {
        if (!response.data.valid) {
          Swal.fire('Error', response.data.message || 'Coupon tidak valid.', 'error')
          return
        }

        couponCode.value = found.code
        appliedCoupon.value = found
        showCouponModal.value = false
        // Jika ingin tampilkan info sisa kuota:
        // Swal.fire('Info', `Sisa kuota: ${response.data.sisa_kuota ?? '-'}, Sisa user: ${response.data.sisa_user ?? '-'}`, 'info')
      })
    }

  // Helper untuk timer sisa waktu coupon
  function getCouponTimer(endDate) {
    if (!endDate) return ''
    const now = new Date()
    const end = new Date(endDate + 'T23:59:59')
    const diff = end - now
    if (diff <= 0) return ''
    const days = Math.floor(diff / (1000 * 60 * 60 * 24))
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
    const seconds = Math.floor((diff % (1000 * 60)) / 1000)
    let result = ''
    if (days > 0) result += days + ' hari '
    if (hours > 0 || days > 0) result += hours.toString().padStart(2, '0') + ' jam '
    if (minutes > 0 || hours > 0 || days > 0) result += minutes.toString().padStart(2, '0') + ' menit '
    result += seconds.toString().padStart(2, '0') + ' detik'
    return result.trim()
  }

  function removeCoupon() {
    appliedCoupon.value = null
    couponCode.value = ''
  }

    const couponDiscount = computed(() => {
      if (!appliedCoupon.value) return 0
      if (appliedCoupon.value.jenis === 'cashback') return 0
      // Hitung diskon sesuai type
      const subtotal = Number(booking.value.price || 0) + Number(booking.value.ppn || 0) + Number(booking.value.fee || 0) - Number(booking.value.disc || 0)
      let discount = 0
      if (appliedCoupon.value.type === 'percentage') {
        discount = subtotal * (appliedCoupon.value.value || 0) / 100
        if (appliedCoupon.value.maximum_discount && discount > appliedCoupon.value.maximum_discount) {
          discount = appliedCoupon.value.maximum_discount
        }
      } else if (appliedCoupon.value.type === 'fixed') {
        discount = appliedCoupon.value.value || 0
      }
      return Math.floor(discount)
    })

    const couponCashback = computed(() => {
      if (!appliedCoupon.value) return 0
      return Math.floor(Number(appliedCoupon.value.value_cashback || 0))
    })

    const totalDiscount = computed(() => {
      const membershipDiscount = Number(booking.value.disc || 0)
      const coupon = Number(couponDiscount.value || 0)
      return Math.floor(membershipDiscount + coupon)
    })

    const totalPrice = computed(() => {
      // Total = harga kamar + pajak + fee - diskon membership - diskon coupon
      const base = Number(booking.value.price || 0)
      const pajak = Number(booking.value.ppn || 0)
      const fee = Number(booking.value.fee || 0)
      const disc = Number(booking.value.disc || 0)
      const coupon = Number(couponDiscount.value || 0)
      return Math.max(base + pajak + fee - disc - coupon, 0)
    })
    
  const booking = ref({
    propertyName: '',
    address: '',
    rating: 5,
    roomName: '',
    roomType: '',
    price: 0,
    priceLabel: '',
    ppn: 0,
    fee: 0,
    capacity: 2,
    breakfast: false,
    image: '',
    checkIn: '',
    checkOut: '',
    nights: 1,
    disc: 0,

  })
  const form = ref({
    name: '',
    phone: '',
    countryCode: '+62',
    email: '',
    forMe: false,
    guestList: [
      {
        guest_name: '',
        guest_gender: '',
        guest_phone: '',
        guest_email: '',
        guest_nik: ''
      }
    ]
  })
  const tax = computed(() => Math.round(booking.value.price * (currentInfo.value.ppn + currentInfo.value.fee) / 100))

  function formatDate(dateStr) {
    if (!dateStr) return '-'
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', { weekday: 'long', day: '2-digit', month: 'long', year: 'numeric' })
  }

  function formatNumber(num) {
    return Number(num || 0).toLocaleString('id-ID')
  }

  // Loading state for button
  const loading = ref(false)

  // Wrap proses with loading state
  const  proses = async () => {
    if (loading.value) return
    loading.value = true
    const payload = {
      ...form.value,
      booking: booking.value
    }

    const response = await apiPostData('/public/proses_booking/', payload)

    if (response) {
      loading.value = false
    } else {
      loading.value = false
    }

    loading.value = false
  }

  // Watch forMe checkbox, autofill if checked
  watch(() => form.value.forMe, (val) => {
    if (val) {
      // Autofill from user (auth store)
      form.value.name = user.value.name || ''
      form.value.phone = user.value.phone || ''
      form.value.email = user.value.email || ''
      form.value.guestList = [{
        guest_name: user.value.name || '',
        guest_gender: '',
        guest_phone: user.value.phone || '',
        guest_email: user.value.email || '',
        guest_nik: ''
      }]
    } else {
      // Clear guest info
      form.value.name = ''
      form.value.phone = ''
      form.value.email = ''
      form.value.guestList = [{
        guest_name: '',
        guest_gender: '',
        guest_phone: '',
        guest_email: '',
        guest_nik: ''
      }]
    }
  })

  // Add/remove tamu
  function addGuest() {
    form.value.guestList.push({
      guest_name: '',
      guest_gender: '',
      guest_phone: '',
      guest_email: '',
      guest_nik: ''
    })
  }
  function removeGuest(idx) {
    if (form.value.guestList.length > 1) {
      form.value.guestList.splice(idx, 1)
    }
  }

  let couponInterval = null
  onMounted(async () => {
    await membershipStore.fetch()
    const q = route.query
    booking.value = {
      propertyId: q.propertyID || '',
      image: q.image || '',
      checkIn: q.checkIn || '',
      checkOut: q.checkOut || '',
      nights: q.nights || 1
    }

    const payload = {
      property_id: route.query.propertyID,
      check_in: booking.value.checkIn,
      check_out: booking.value.checkOut
    }

    const response = await apiGetData('/public/properties_booking', payload)

    const data = response.data[0] || {}

    booking.value = {
      ...booking.value,
      propertyName: data.property.properties,
      address: data.address || '-',
      rating: data.rating || 5,
      roomName: data.sub_rooms[0]?.name_room || '-',
      roomType: data.sub_rooms[0]?.type_bed || '-',
      price: data.sub_rooms[0]?.price || 0,
      priceLabel: data.price_label || '',
      capacity: data.capacity || 2,
      ppn: (currentInfo.value.ppn || 0) * (data.sub_rooms[0]?.price || 0) / 100,
      
    }

    if (currentMembership.value[0].status === 'active' && currentMembership.value.length > 0) {
      if (currentMembership.value[0].membership.fee_admin == 0) {
        booking.value.fee = (currentInfo.value.fee || 0) * (data.sub_rooms[0]?.price || 0) / 100
      }else {
        booking.value.fee = 0
      }

      booking.value.disc = booking.value.price * (currentMembership.value[0].membership.discount_percent || 0) / 100
    }else{
      booking.value.fee = (currentInfo.value.fee || 0) * (data.sub_rooms[0]?.price || 0) / 100
    }

    const couponResponse = await apiGetData('/public/coupons_booking', {})
    listCoupon.value = couponResponse.data || []

    updateCouponTimers()

    // START interval for realtime coupon timer
    couponInterval = setInterval(updateCouponTimers, 1000)
  })

  onUnmounted(() => {
    if (couponInterval) clearInterval(couponInterval)
  })

  function updateCouponTimers() {
    listCoupon.value.forEach(coupon => {
      if (coupon.end_date) {
        couponTimers.value[coupon.end_date] = getCouponTimer(coupon.end_date)
      }
    })
  }
</script>

<style scoped>
  body {
    background: #f7fafc;
  }
  input, select {
    background: #f9fafb;
  }
  @media (max-width: 768px) {
    .animate-slideup-modal {
      animation: slideup-modal 0.25s cubic-bezier(0.4,0,0.2,1);
    }
    @keyframes slideup-modal {
      from { transform: translateY(100%); }
      to { transform: translateY(0); }
    }
  }

    
</style>
