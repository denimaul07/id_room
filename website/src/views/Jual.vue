<template>
    <!-- Page Header -->
    <section class="relative flex items-center bg-center bg-cover transition-opacity duration-700
            h-[200px] md:h-[250px] lg:h-[230px]" :class="bannerReady ? 'opacity-100' : 'opacity-0'"
        :style="{ backgroundImage: `url(${heroImageUrl})` }">
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" :style="{ color: textColor }">
                Jual Properti
            </h1>

            <p class="text-sm opacity-80" :style="{ color: textColor }">
                <router-link to="/" class="hover:underline">Home</router-link>
                <span class="mx-2">›</span>
                Jual Properti
            </p>
        </div>
    </section>


    <!-- Content -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">

            <!-- Top Bar -->
            <div
                class="bg-white rounded-xl shadow p-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="text-gray-600 text-base">
                    Showing result <span class="font-bold text-primary text-lg">{{ properties.to }}</span> of <span
                        class="font-bold text-lg">{{ properties.total }}</span>
                </div>
                <div class="flex flex-row items-center gap-6">
                    <label class="text-gray-900 font-semibold mr-2">Sort By</label>
                    <select v-model="sortBy" @change="fetchProperties(1)"
                        class="bg-white border border-gray-200 rounded-lg px-5 py-2 text-base font-semibold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                        <option value="">Default</option>
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                    <label class="text-gray-900 font-semibold ml-2 mr-2">Price Range</label>
                    <select v-model="priceSort" @change="fetchProperties(1)"
                        class="bg-white border border-gray-200 rounded-lg px-5 py-2 text-base font-semibold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                        <option value="">Default</option>
                        <option value="price_asc">Low to High</option>
                        <option value="price_desc">High to Low</option>
                    </select>
                </div>
            </div>

            <!-- Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <SellSidebar @apply="onApplyFilters" @reset="onResetFilters" />
                </aside>

                <!-- Listing -->
                <div class="lg:col-span-3">
                    <div v-if="isLoading" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        <div v-for="item in skeletonItems" :key="item"
                            class="bg-white rounded-xl shadow overflow-hidden border">
                            <div class="h-52 w-full bg-gray-200 animate-pulse"></div>
                            <div class="p-4 space-y-3">
                                <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                                <div class="h-5 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                                <div class="h-4 bg-gray-200 rounded w-2/3 animate-pulse"></div>
                                <div class="h-10 bg-gray-200 rounded w-full animate-pulse"></div>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="hasProperties" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        <SellCard v-for="item in properties.data" :key="item.id" :item="item" />
                    </div>
                    <div v-else class="flex flex-col items-center justify-center py-12 text-gray-500">
                        <img :src="emptyImage" alt="No properties" class="w-100 max-w-full mb-4" />
                        <p class="text-lg">Tidak ada properti untuk ditampilkan.</p>
                    </div>
                    <div v-if="hasPagination" class="flex items-center justify-center gap-3 mt-8">
                        <button
                            class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-100"
                            :disabled="!canPrev" @click="goPrev">
                            Prev
                        </button>
                        <div class="text-sm text-gray-600">
                            Page {{ currentPage }} of {{ lastPage }}
                        </div>
                        <button
                            class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-100"
                            :disabled="!canNext" @click="goNext">
                            Next
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useHead } from '@vueuse/head'
import { apiGetData } from '@/store/action'
import { useInfoStore } from '@/store/info'
import SellSidebar from '@/components/sell/SellFilters.vue'
import SellCard from '@/components/sell/SellCard.vue'
import heroImage from '@/assets/banner/sell.png'
import emptyImage from '@/assets/404/404-property.png'
const bannerReady = ref(true)
const properties = ref([])
const isLoading = ref(false)
const skeletonItems = [1, 2, 3, 4, 5, 6]

const search = ref('')
const city = ref('')
const type = ref('')
const minPrice = ref(null)
const maxPrice = ref(null)
const facilities = ref([])
const sortBy = ref('')
const priceSort = ref('')
const pagging = ref(12)
const { data: info } = storeToRefs(useInfoStore())
const currentInfo = computed(() => info.value?.[0] ?? {})
const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const heroImageUrl = computed(() => {
    return currentInfo.value?.bannerJual ? imageBaseUrl + currentInfo.value.bannerJual : heroImage
})
const textColor = computed(() => currentInfo.value?.colorJual || '#ffffff')

const fetchProperties = async (page = properties.value.current_page) => {
    isLoading.value = true
    const payload = {
        page: page,
        pagging: pagging.value,
        search: search.value,
        city: city.value,
        type: type.value,
        min_price: minPrice.value,
        max_price: maxPrice.value,
        facilities: facilities.value,
        sort: sortBy.value,
        price_sort: priceSort.value,
    }
    try {
        const response = await apiGetData('public/properties-jual', payload)
        properties.value = response.data || []
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    fetchProperties()
})

const hasProperties = computed(() => Array.isArray(properties.value?.data) && properties.value.data.length > 0)
const currentPage = computed(() => Number(properties.value?.current_page || 1))
const lastPage = computed(() => Number(properties.value?.last_page || 1))
const canPrev = computed(() => currentPage.value > 1)
const canNext = computed(() => currentPage.value < lastPage.value)
const hasPagination = computed(() => hasProperties.value && lastPage.value > 1)

const onApplyFilters = (filters) => {
    search.value = filters?.search || ''
    city.value = filters?.city || ''
    type.value = filters?.type || ''
    minPrice.value = filters?.minPrice ?? null
    maxPrice.value = filters?.maxPrice ?? null
    facilities.value = Array.isArray(filters?.facilities) ? filters.facilities : []
    sortBy.value = ''
    priceSort.value = ''
    fetchProperties(1)
}

const onResetFilters = () => {
    search.value = ''
    city.value = ''
    type.value = ''
    minPrice.value = null
    maxPrice.value = null
    facilities.value = []
    sortBy.value = ''
    priceSort.value = ''
    fetchProperties(1)
}

const goPrev = () => {
    if (canPrev.value) {
        fetchProperties(currentPage.value - 1)
    }
}

const goNext = () => {
    if (canNext.value) {
        fetchProperties(currentPage.value + 1)
    }
}

useHead({
    title: 'Jual Properti - ID Room',
    meta: [
        {
            name: 'description',
            content: 'Temukan properti jual terbaik di ID Room. Jelajahi berbagai pilihan apartemen, rumah, dan vila untuk dijual sesuai kebutuhan Anda.'
        },
        {
            name: 'keywords',
            content: 'jual properti, jual apartemen, jual rumah, properti dijual, ID Room'
        },
        {
            property: 'og:title',
            content: 'Jual Properti - ID Room'
        },
        {
            property: 'og:description',
            content: 'Cari properti jual terbaik di ID Room. Pilih dari berbagai apartemen, rumah, dan vila yang sesuai dengan kebutuhan Anda.'
        },
        {
            property: 'og:type',
            content: 'website'
        }
    ]
})
</script>
