<template>
    <section class="py-20 bg-base-200">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Title & Arrow -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 text-center md:text-left">
                        Properti Unggulan untuk DiSewakan
                    </h2>
                    <div class="flex items-center mt-2 justify-center md:justify-start">
                        <span class="inline-block w-8 h-1 rounded"
                            :style="{ backgroundColor: currentInfo.primaryColor }"></span>
                        <span class="inline-block w-8 h-1 rounded bg-black"></span>
                    </div>
                    <p class="text-gray-500 mt-2 text-center md:text-left">
                        Pilihan tempat berkualitas yang dipilih secara khusus
                    </p>
                </div>
                <div class="flex gap-2">
                    <button @click="prevPage"
                        class="btn btn-circle bg-gray-800 text-white shadow hover:bg-gray-200 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <button @click="nextPage"
                        class="btn btn-circle bg-gray-800 text-white shadow hover:bg-gray-200 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div v-for="property in pagedProperties" :key="property.id"
                    class="bg-white rounded-xl shadow hover:-translate-y-2 transition duration-300 overflow-hidden">
                    <!-- Image & Price -->
                    <div class="relative">
                        <img :src="property.image" alt="property" class="h-56 w-full object-cover" />
                        <div
                            class="absolute bottom-0 left-0 bg-black bg-opacity-70 text-white px-4 py-2 text-lg font-bold rounded-tr-xl">
                            {{ (property.price || 0).toLocaleString('id-ID', {
                                style: 'currency', currency: 'IDR'
                            }).slice(0, -3) }}
                        </div>
                        <div class="absolute top-4 left-4 flex flex-wrap gap-1">
                            <span v-for="tag in property.rentTags" :key="tag.label"
                                class="text-white text-xs font-semibold px-2 py-1 rounded" :class="tag.className">
                                {{ tag.label }}
                            </span>
                        </div>
                        <span
                            class="absolute top-4 right-4 bg-yellow-400 text-white text-xs font-semibold px-3 py-1 rounded">Di
                            Sewa</span>

                    </div>
                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-yellow-500 text-lg">★</span>
                                <span class="font-semibold text-gray-800">{{ property.rating }}</span>
                                <span class="text-xs text-gray-500">({{ property.reviews }} Reviews)</span>
                            </div>
                            <span class="text-xs px-2 py-1 rounded"
                                :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }">{{
                                property.category }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ property.title }}</h3>
                        <p class="text-sm text-gray-500 mb-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 1011.314-11.314l-4.243-4.243a4 4 0 00-5.657 5.657l4.243 4.243z" />
                            </svg>
                            {{ property.location }}
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-2">
                            <span v-for="facility in property.facilities" :key="facility.odata || facility.name"
                                class="flex items-center gap-1">
                                <i class="fa" :class="facility.icon" :style="{ color: currentInfo.primaryColor }"></i>
                                {{ facility.name }}
                            </span>
                        </div>
                        <div class="flex justify-center">
                            <router-link :to="{ path: '/rent-details', query: { odata: property.id } }"
                                class="btn btn-primary btn-sm bg-black text-white text-center w-40 rounded-full mt-4">
                                View Details
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Explore All Button -->
            <div class="flex justify-center mt-8">
                <router-link to="/sewa-properti"
                    class="btn btn-circle bg-gray-800 text-white px-6 font-semibold flex items-center rounded-full p-2">
                    Explore All
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </router-link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { apiGetData } from '@/store/action'
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'
import { ref, watch, onMounted, computed } from 'vue'

const { data: info } = storeToRefs(useInfoStore())
const currentInfo = computed(() => info.value?.[0] ?? {})

const rawProperties = ref([])
const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const fallbackImage = 'https://images.unsplash.com/photo-1568605114967-8130f3a36994'

const normalizeListingTypes = (value) => {
    if (Array.isArray(value)) return value
    if (typeof value === 'string') {
        return value
            .replace(/_/g, ',')
            .split(',')
            .map((entry) => entry.trim())
            .filter(Boolean)
    }
    return []
}

const filteredProperties = computed(() => {
    return rawProperties.value.filter((item) => {
        const types = normalizeListingTypes(item.listing_type).map((entry) => entry.toLowerCase())
        return types.length === 0 ? true : types.includes('rent')
    })
})

const properties = computed(() => {
    return filteredProperties.value.map((item) => {
        const price = Math.min(
            ...[
                item.sale_price,
                item.price_per_year,
                item.price_per_monthly,
                item.price_per_night
            ].filter(p => p && p > 0)
        ) || 0
        const rentTags = []
        if (item.price_per_night) {
            rentTags.push({ label: 'Harian', className: 'bg-emerald-600' })
        }
        if (item.price_per_monthly) {
            rentTags.push({ label: 'Bulanan', className: 'bg-purple-600' })
        }
        if (item.price_per_year) {
            rentTags.push({ label: 'Tahunan', className: 'bg-sky-600' })
        }
        if (!rentTags.length) {
            rentTags.push({ label: 'Di Sewakan', className: 'bg-gray-700' })
        }
        const facilities = Array.isArray(item.facilities)
            ? item.facilities.map((entry) => entry.facility).filter(Boolean)
            : []
        return {
            id: item.odata || item.id,
            title: item.properties,
            location: item.address,
            price,
            rentTags,
            facilities,
            image: item.image ? imageBaseUrl + item.image : fallbackImage,
            listed: item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-',
            category: item.type || '-',
            featured: false,
            rating: 4.7,
            reviews: 0,
            agent: null
        }
    })
})

const page = ref(1)
const perPage = 3
const totalPages = computed(() => Math.ceil(properties.value.length / perPage))
const pagedProperties = computed(() => {
    const start = (page.value - 1) * perPage
    return properties.value.slice(start, start + perPage)
})

const fetchProperties = async () => {
    const res = await apiGetData('public/properties', { limit: 9, listing_type: 'Rent' })
    if (Array.isArray(res)) {
        rawProperties.value = res
    } else {
        rawProperties.value = res?.data || []
    }
}

onMounted(() => {
    fetchProperties()
})

function nextPage() {
    if (page.value < totalPages.value) page.value++
}
function prevPage() {
    if (page.value > 1) page.value--
}
</script>
