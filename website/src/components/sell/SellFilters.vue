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
    </div>

    <!-- Price -->
    <div class="mb-4 border-b pb-4">
      <div class="flex items-center gap-2 mb-2">
        <span class="text-purple-500"><i class="fas fa-rupiah-sign"></i></span>
        <span class="font-semibold">Range Harga</span>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <input :value="minPriceDisplay" type="text" inputmode="numeric" placeholder="Min"
          class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full" @input="onPriceInput('min', $event)" />
        <input :value="maxPriceDisplay" type="text" inputmode="numeric" placeholder="Max"
          class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-2 w-full" @input="onPriceInput('max', $event)" />
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
      Apply Filter
    </button>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useCityStore } from '@/store/city'
import { apiGetData } from '@/store/action'

const cityStore = useCityStore()
const { data: cities, type: propertyTypes } = storeToRefs(cityStore)

const emit = defineEmits(['apply', 'reset'])

const searchText = ref('')
const selectedCity = ref('')
const selectedType = ref('')
const minPrice = ref(null)
const maxPrice = ref(null)
const facilities = ref([])
const selectedFacilities = ref([])

const cityList = computed(() => cities.value || [])
const propertyTypeList = computed(() =>
  Array.isArray(propertyTypes.value) ? propertyTypes.value : []
)
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

const fetchFacilities = async () => {
  const response = await apiGetData('public/properties-facilities')
  facilities.value = Array.isArray(response?.data) ? response.data : []
}

onMounted(() => {
  if (!cityStore.loaded) {
    cityStore.fetch()
  }
  fetchFacilities()
})

const applyFilters = () => {
  emit('apply', {
    search: searchText.value,
    city: selectedCity.value,
    type: selectedType.value,
    minPrice: minPrice.value,
    maxPrice: maxPrice.value,
    facilities: selectedFacilities.value
  })
}

const resetFilters = () => {
  searchText.value = ''
  selectedCity.value = ''
  selectedType.value = ''
  minPrice.value = null
  maxPrice.value = null
  selectedFacilities.value = []
  emit('reset')
}
</script>
