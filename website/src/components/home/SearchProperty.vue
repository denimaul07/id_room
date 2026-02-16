<template>
    <form class="bg-white/95 backdrop-blur rounded-2xl shadow-xl p-4 md:p-6 w-full max-w-7xl mx-auto"
        @submit.prevent="onSearch">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-3 md:gap-4 items-end w-full">

            <!-- DATE -->
            <div class="col-span-12 md:col-span-2">
                <label class="form-label">Tanggal Check-in</label>
                <input type="date" class="form-input" v-model="startDate" />
            </div>

            <div class="col-span-12 md:col-span-2">
                <label class="form-label">Tanggal Check-out</label>
                <input type="date" class="form-input" v-model="endDate" />
            </div>

            <!-- CITY -->
            <div class="col-span-12 md:col-span-3">
                <label class="form-label">Kota</label>
                <select class="form-input" v-model="city">
                    <option value="">Semua Kota</option>
                    <option v-for="item in cityList" :key="item.odata" :value="item.odata">
                        {{ item.city }}
                    </option>
                </select>
            </div>

            <!-- PRICE -->
            <div class="col-span-12 md:col-span-2">
                <label class="form-label">Harga Minimum</label>
                <input type="text" :value="minPriceDisplay" @input="onPriceInput('min', $event)" class="form-input" />
            </div>

            <div class="col-span-12 md:col-span-2">
                <label class="form-label">Harga Maksimum</label>
                <input type="text" :value="maxPriceDisplay" @input="onPriceInput('max', $event)" class="form-input" />
            </div>

            <div class="col-span-12 md:col-span-1">
                <button type="submit" class="w-full font-semibold py-3 px-4 rounded-xl transition"
                    :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }">
                    Cari
                </button>
            </div>

        </div>
    </form>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useCityStore } from '@/store/city'
import { useInfoStore } from '@/store/info'



const router = useRouter()
const route = useRoute()

const cityStore = useCityStore()
const { data: cities } = storeToRefs(cityStore)

const { data: info } = storeToRefs(useInfoStore())
const currentInfo = computed(() => {
    return info.value?.[0] ?? {}
})

const cityList = computed(() => cities.value || [])


const city = ref('')
const today = new Date().toISOString().split('T')[0]
const startDate = ref(today)
const endDate = ref(today)

/* RAW NUMBER STATE */
const minPrice = ref(0)
const maxPrice = ref(0)

/* DISPLAY STATE */
const minPriceDisplay = ref(0)
const maxPriceDisplay = ref(0)

function parseRupiah(val) {
    const numeric = String(val || '').replace(/[^0-9]/g, '')
    return numeric ? Number(numeric) : null
}

function formatRupiah(val) {
    if (!val) return ''
    return 'Rp' + Number(val).toLocaleString('id-ID')
}

function onPriceInput(type, e) {
    const parsed = parseRupiah(e.target.value)

    if (type === 'min') {
        minPrice.value = parsed
        minPriceDisplay.value = formatRupiah(parsed)
    } else {
        maxPrice.value = parsed
        maxPriceDisplay.value = formatRupiah(parsed)
    }
}

/* HYDRATE FROM URL */
onMounted(() => {
    if (!cityStore.loaded) cityStore.fetch()

    const q = route.query

    city.value = q.city || ''
    startDate.value = q.startDate || today
    endDate.value = q.endDate || today

    minPrice.value = parseRupiah(q.minPrice) ?? 0
    maxPrice.value = parseRupiah(q.maxPrice) ?? 0

    minPriceDisplay.value = formatRupiah(minPrice.value)
    maxPriceDisplay.value = formatRupiah(maxPrice.value)
})

function onSearch() {
    router.push({
        path: '/sewa-properti',
        query: {
            city: city.value || undefined,
            startDate: startDate.value || undefined,
            endDate: endDate.value || undefined,
            minPrice: minPrice.value || undefined,
            maxPrice: maxPrice.value || undefined,
        }
    })
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