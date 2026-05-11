<template>
  <div class="bg-white rounded-lg shadow p-5 sticky top-24">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-2xl font-bold">Filter</h3>
      <button class="text-red-500 text-base font-semibold" @click="resetFilters">Reset</button>
    </div>

    <!-- Search -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-search"></i></span>
        <span class="font-semibold">Search</span>
      </div>
      <input v-model="searchText" type="text" placeholder="Search here..."
        class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2" />
      <label class="block font-semibold mb-1">Select Location</label>
      <select v-model="selectedCity" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2">
        <option value="">All Cities</option>
        <option v-for="item in cityList" :key="item.odata" :value="item.odata">
          {{ item.city }}
        </option>
      </select>
      <label class="block font-semibold mb-1">Property Type</label>
      <select v-model="selectedType" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2">
        <option value="">All Types</option>
        <option v-for="type in propertyTypeList" :key="type" :value="type">
          {{ type }}
        </option>
      </select>
      <label class="block font-semibold mb-1">Jenis Sewa</label>
      <select v-model="selectedRentType" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2">
        <option value="">Semua</option>
        <option value="night">Harian</option>
        <option value="monthly">Bulanan</option>
        <option value="yearly">Tahunan</option>
      </select>
      <label class="block font-semibold mb-1">Tanggal Check-in</label>
      <input type="date" v-model="startDate"
        class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2" />
      <label class="block font-semibold mb-1">Tanggal Check-out</label>
      <input type="date" v-model="endDate" class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2" />

    </div>

    <!-- Room Type -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-bed"></i></span>
        <span class="font-semibold">Tipe Kamar</span>
      </div>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="rt in roomTypeOptions"
          :key="rt.value"
          @click="selectedRoomType = rt.value"
          class="px-3 py-1 rounded-full text-sm font-semibold border transition-all"
          :class="selectedRoomType === rt.value
            ? 'bg-red-500 text-white border-red-500'
            : 'bg-white text-gray-600 border-gray-300 hover:border-red-400'"
        >{{ rt.label }}</button>
      </div>
    </div>

    <!-- Furnishing -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-couch"></i></span>
        <span class="font-semibold">Furnishing</span>
      </div>
      <div class="flex flex-wrap gap-2">
        <button
          v-for="f in furnishingOptions"
          :key="f.value"
          @click="selectedFurnishing = f.value"
          class="px-3 py-1 rounded-full text-sm font-semibold border transition-all"
          :class="selectedFurnishing === f.value
            ? 'bg-red-500 text-white border-red-500'
            : 'bg-white text-gray-600 border-gray-300 hover:border-red-400'"
        >{{ f.label }}</button>
      </div>
    </div>

    <!-- Price -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-rupiah-sign"></i></span>
        <span class="font-semibold">Range Harga</span>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <input :value="minPriceDisplay" type="text" inputmode="numeric" placeholder="Min"
          class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2"
          @input="onPriceInput('min', $event)" />
        <input :value="maxPriceDisplay" type="text" inputmode="numeric" placeholder="Max"
          class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full mb-2"
          @input="onPriceInput('max', $event)" />
      </div>
    </div>


    <!-- Facilities -->
    <div class="mb-4 pb-4 border-t pt-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-list-check"></i></span>
        <span class="font-semibold">Facilities</span>
      </div>
      <div class="space-y-2 max-h-40 overflow-auto">
        <label v-for="facility in facilitiesList" :key="facility.odata" class="flex items-center gap-2 cursor-pointer">
          <input type="checkbox" :value="facility.odata" v-model="selectedFacilities" />
          <i v-if="facility.icon" class="fas" :class="facility.icon"></i>
          <span>{{ facility.name }}</span>
        </label>
      </div>
    </div>

    <!-- Apply Filter Button -->
    <button class="bg-gray-900 text-white w-full py-3 rounded-lg font-semibold mt-2" @click="applyFilters">
      Cari Akomodasi
    </button>
  </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useCityStore } from '@/store/city'
import { apiGetData } from '@/store/action'
import { useRoute } from 'vue-router'
const props = defineProps({
  keyword: { type: String, default: '' },
  city: { type: String, default: '' },
  minPrice: { type: [Number, String], default: null },
  maxPrice: { type: [Number, String], default: null },
  startDate: { type: String, default: '' },
  endDate: { type: String, default: '' },
})


const route = useRoute()
const cityStore = useCityStore()
const { data: cities, type: propertyTypes } = storeToRefs(cityStore)

const emit = defineEmits(['apply', 'reset'])

const searchText = ref(props.keyword)
const selectedCity = ref(props.city)
const minPrice = ref(props.minPrice)
const maxPrice = ref(props.maxPrice)
const startDate = ref(props.startDate)
const endDate = ref(props.endDate)


const cityList = computed(() => cities.value || [])
const propertyTypeList = computed(() =>
  Array.isArray(propertyTypes.value) ? propertyTypes.value : []
)
const selectedType = ref('')
const selectedRentType = ref('')
const selectedRoomType = ref('')
const selectedFurnishing = ref('')
const selectedFacilities = ref(Array.isArray(props.facilities) ? props.facilities : [])

const roomTypeOptions = [
  { label: 'All', value: '' },
  { label: 'Studio', value: 'studio' },
  { label: '1BR', value: '1br' },
  { label: '2BR', value: '2br' },
  { label: '3BR', value: '3br' },
]

const furnishingOptions = [
  { label: 'All', value: '' },
  { label: 'Full Furnished', value: 'full_furnished' },
  { label: 'Unfurnished', value: 'unfurnished' },
]

const facilities = ref([])

const facilitiesList = computed(() => facilities.value || [])

const formatRupiah = (value) => {
  const numberValue = Number(value || 0)
  if (!numberValue) return ''
  return 'Rp' + numberValue.toLocaleString('id-ID')
}

const parseRupiah = (value) => {
  const numeric = String(value || '').replace(/[^0-9]/g, '')
  return numeric ? Number(numeric) : null
}

const minPriceDisplay = computed(() => formatRupiah(minPrice.value))
const maxPriceDisplay = computed(() => formatRupiah(maxPrice.value))

const onPriceInput = (target, event) => {
  const rawValue = event?.target?.value
  const parsed = parseRupiah(rawValue)
  if (target === 'min') {
    minPrice.value = parsed
  } else {
    maxPrice.value = parsed
  }
}

function normalizeDate(value) {
  if (!value) return ''

  // kalau sudah format yyyy-mm-dd → langsung pakai
  if (/^\d{4}-\d{2}-\d{2}$/.test(value)) return value

  const date = new Date(value)
  if (isNaN(date)) return ''

  const y = date.getFullYear()
  const m = String(date.getMonth() + 1).padStart(2, '0')
  const d = String(date.getDate()).padStart(2, '0')

  return `${y}-${m}-${d}`
}



// Sync local refs from props on mount and when props change
onMounted(() => {
  if (!cityStore.loaded) {
    cityStore.fetch()
  }
  fetchFacilities()
})

watch(
  () => [props.keyword, props.city, props.minPrice, props.maxPrice, props.startDate, props.endDate],
  () => syncFromProps(),
  { immediate: true } // <- ini penting
)


function syncFromProps() {
  const q = route.query

  searchText.value = props.keyword || q.keyword || ''
  selectedCity.value = props.city || q.city || ''

  minPrice.value = parseRupiah(props.minPrice ?? q.minPrice)
  maxPrice.value = parseRupiah(props.maxPrice ?? q.maxPrice)

  // 🔥 fallback ke query kalau props kosong
  const start = props.startDate || q.startDate
  const end = props.endDate || q.endDate

  startDate.value = normalizeDate(start)
  endDate.value = normalizeDate(end)
}



const fetchFacilities = async () => {
  const response = await apiGetData('public/properties-facilities')
  facilities.value = Array.isArray(response?.data) ? response.data : []
}

const applyFilters = () => {
  emit('apply', {
    keyword: searchText.value,
    city: selectedCity.value,
    minPrice: minPrice.value,
    maxPrice: maxPrice.value,
    startDate: startDate.value,
    endDate: endDate.value,
    type: selectedType.value,
    rentType: selectedRentType.value,
    roomType: selectedRoomType.value,
    furnishing: selectedFurnishing.value,
    facilities: selectedFacilities.value,

  })
}

const resetFilters = () => {
  searchText.value = ''
  selectedCity.value = ''
  selectedType.value = ''
  selectedRentType.value = ''
  selectedRoomType.value = ''
  selectedFurnishing.value = ''
  minPrice.value = null
  maxPrice.value = null
  selectedFacilities.value = []
  emit('reset')
}

watch(
  () => route.query,
  () => syncFromProps()
)

</script>
