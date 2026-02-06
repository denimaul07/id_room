<template>
    <form class="bg-white/95 backdrop-blur rounded-2xl shadow-xl p-4 md:p-6 w-full max-w-7xl mx-auto"
        @submit.prevent="onSearch">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3 md:gap-4">


            <div class="md:col-span-1">
                <label class="form-label">Keyword</label>
                <input
                    type="text"
                    placeholder="Apartemen, rumah, lokasi..."
                    class="form-input"
                    v-model="keyword"
                />
            </div>


            <div class="relative md:col-span-1">
                <label class="form-label">Tipe Properti</label>

                <input
                    type="text"
                    placeholder="Pilih tipe"
                    class="form-input"
                    v-model="propertyTypeSearch"
                    @focus="showType = true"
                />

                <ul
                    v-if="showType"
                    class="absolute z-20 mt-1 w-full bg-white border rounded-xl shadow max-h-40 overflow-auto"
                >
                    <li
                    v-for="type in filteredPropertyTypes"
                    :key="type"
                    @click="selectType(type)"
                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                    >
                    {{ type }}
                    </li>
                </ul>
            </div>


            <div class="relative md:col-span-1">
                <label class="form-label">Kota</label>

                <input
                    type="text"
                    placeholder="Cari kota"
                    class="form-input"
                    v-model="citySearch"
                    @focus="showCity = true"
                />

                <ul
                    v-if="showCity"
                    class="absolute z-20 mt-1 w-full bg-white border rounded-xl shadow max-h-40 overflow-auto"
                >
                    <li
                    v-for="item in filteredCities"
                    :key="item.odata"
                    @click="selectCity(item)"
                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                    >
                    {{ item.city }}
                    </li>
                </ul>
            </div>


            <div>
                <label class="form-label">Harga Minimum</label>
                <input
                    type="text"
                    placeholder="Rp 0"
                    class="form-input"
                    v-model="minPrice"
                    @input="formatRupiah('minPrice')"
                />
                </div>

                <div class="flex items-end gap-2">
                <div class="flex-1">
                    <label class="form-label">Harga Maksimum</label>
                    <input
                    type="text"
                    placeholder="Rp 0"
                    class="form-input"
                    v-model="maxPrice"
                    @input="formatRupiah('maxPrice')"
                    />
                </div>

                <button
                    class="h-[44px] px-5 rounded-xl bg-[#d5bd7d] text-white font-semibold hover:bg-[#cbb36e] transition"
                >
                    🔍
                </button>
            </div>

        </div>
    </form>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useCityStore } from '@/store/city'
import { ref, watch, onMounted, computed } from 'vue'

const cityStore = useCityStore()
const { data: cities, loading } = storeToRefs(cityStore)

const cityList = computed(() => cities.value || [])

const minPrice = ref('')
const maxPrice = ref('')
const keyword = ref('')
const propertyTypes = ['Apartment', 'House']
const propertyTypeSearch = ref('')
const propertyType = ref('')
const showType = ref(false)

const filteredPropertyTypes = computed(() =>
  propertyTypes.filter(t =>
    t.toLowerCase().includes(propertyTypeSearch.value.toLowerCase())
  )
)

const selectType = (type) => {
  propertyType.value = type
  propertyTypeSearch.value = type
  showType.value = false
}

const citySearch = ref('')
const city = ref('')
const showCity = ref(false)

const filteredCities = computed(() =>
  cityList.value.filter(c =>
    c.city.toLowerCase().includes(citySearch.value.toLowerCase())
  )
)

const selectCity = (item) => {
  city.value = item.odata
  citySearch.value = item.city
  showCity.value = false
}


onMounted(() => {
    if (!cityStore.loaded) {
        cityStore.fetch()
    }
})

watch(cities, (val) => {
    console.log('Kota loaded:', val)
})

function formatRupiah(field) {
    let val = field === 'minPrice' ? minPrice.value : maxPrice.value
    val = val.replace(/[^\d]/g, '')
    val = val ? 'Rp' + Number(val).toLocaleString('id-ID') : ''
    field === 'minPrice'
        ? (minPrice.value = val)
        : (maxPrice.value = val)
}
</script>

<style scoped>
    .form-label {
    @apply block text-xs font-semibold mb-1 text-gray-600;
    }
    .form-input {
    @apply w-full h-[44px] border border-gray-200 rounded-xl px-3 focus:ring-2 focus:ring-[#d5bd7d] outline-none;
    }

</style>