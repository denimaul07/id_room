<template>
  <div class="min-h-screen bg-[#f7fafc] py-8">
    <div class="max-w-5xl mx-auto flex flex-col md:flex-row gap-8">
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
            <div v-for="(guest, idx) in form.guestList" :key="idx" class="flex items-center gap-2 mb-2">
              <input type="text" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Nama Lengkap Tamu" v-model="form.guestList[idx]" required />
              <button type="button" @click="removeGuest(idx)" class="text-red-500 hover:text-red-700 px-2 py-1 rounded border border-red-200 bg-red-50"><i class="fas fa-trash"></i></button>
            </div>
            <button type="button" @click="addGuest" class="mt-2 px-3 py-1 rounded bg-blue-100 text-blue-700 text-sm font-semibold"><i class="fas fa-plus"></i> Tambah Tamu</button>
          </div>
        </div>
      </div>
      <!-- RIGHT: SUMMARY -->
      <div class="w-full md:w-[380px] flex-shrink-0">
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
          <div class="flex justify-between font-bold text-lg">
            <span>Total</span>
            <span>Rp {{ formatNumber(booking.price + tax - booking.disc) }}</span>
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
  import { ref, computed, onMounted, watch } from 'vue'
  import { useRoute } from 'vue-router'
  import { apiGetData, apiPostData } from '@/store/action'
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
    guestList: ['']
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
      form.value.guestList = [user.value.name || '']
    }else {
      // Clear guest info
      form.value.name = ''
      form.value.phone = ''
      form.value.email = ''
      form.value.guestList = ['']
    }
  })

  // Add/remove tamu
  function addGuest() {
    form.value.guestList.push('')
  }
  function removeGuest(idx) {
    if (form.value.guestList.length > 1) {
      form.value.guestList.splice(idx, 1)
    }
  }

  onMounted(async() => {
    await membershipStore.fetch()
    const q = route.query
    booking.value = {
      propertyId: q.propertyID || '',
      image: q.image || '',
      checkIn: q.checkIn || '',
      checkOut: q.checkOut || '',
      nights: q.nights || 1
    }

    const response = await apiGetData('/public/properties_booking/', { property_id: route.query.propertyID })

    const data = response.data[0] || {}
  
    booking.value = {
      ...booking.value,
      propertyName: data.property.properties,
      address: data.address || '-',
      rating: data.rating || 5,
      roomName: data.room_name || '-',
      roomType: data.room_type || '-',
      price: data.price || 0,
      priceLabel: data.price_label || '',
      capacity: data.capacity || 2,
      breakfast: data.include_breakfast,
      ppn: (currentInfo.value.ppn || 0) * (data.price || 0) / 100,
      
    }

    if (currentMembership.value[0].status === 'active' && currentMembership.value.length > 0) {
      if (currentMembership.value[0].membership.fee_admin == 0) {
        booking.value.fee = (currentInfo.value.fee || 0) * (data.price || 0) / 100
      }else {
        booking.value.fee = 0
      }

      booking.value.disc = booking.value.price * (currentMembership.value[0].membership.discount_percent || 0) / 100
    }else{
      booking.value.fee = (currentInfo.value.fee || 0) * (data.price || 0) / 100
    }



  })
</script>

<style scoped>
  body {
    background: #f7fafc;
  }
  input, select {
    background: #f9fafb;
  }
</style>
