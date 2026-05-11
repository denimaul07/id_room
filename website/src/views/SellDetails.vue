<template>
    <section class="relative flex items-start md:items-center bg-center bg-cover min-h-[220px] md:h-[280px] lg:h-[260px] pt-8 md:pt-0"
        :style="{ backgroundImage: `url(${heroImage})` }">
        <div class="absolute inset-0 bg-gradient-to-r "></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 w-full">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="text-left">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full"
                            :style="{ backgroundColor: detailAccentColor, color: detailButtonTextColor }">
                            {{ propertyTypeLabel }}
                        </span>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full"
                            :style="{ backgroundColor: detailAccentColor, color: detailButtonTextColor }">
                            For Sale
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2" :style="{ color: detailTextColor }">
                        {{ propertyTitle || 'Detail Properti' }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-3 text-sm" :style="{ color: detailMutedColor }">
                        <div class="flex items-center gap-1 text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <span class="font-semibold ml-1" :style="{ color: detailMutedColor }">5.0</span>
                        </div>
                        <span class="opacity-60">•</span>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ propertyAddress }}</span>
                            <a v-if="mapUrl" :href="mapUrl" target="_blank" rel="noopener" class="ml-2 underline"
                                :style="{ color: detailTextColor }">
                                View Location
                            </a>
                        </div>
                        <span class="opacity-60">•</span>
                        <div class="flex items-center gap-1">
                            <span>Last Updated on :</span>
                            <span class="font-semibold">{{ createdAt }}</span>
                        </div>
                    </div>
                    <!-- Mobile price -->
                    <div class="flex items-baseline gap-2 mt-3 md:hidden">
                        <span class="text-lg font-bold" :style="{ color: detailAccentColor }">{{ formattedPrice }}</span>
                        <span class="text-xs opacity-80" :style="{ color: detailMutedColor }">/ {{ priceLabel }}</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-2">
                        <button class="w-10 h-10 rounded-lg text-white flex items-center justify-center"
                            :style="{ backgroundColor: detailAccentColor }" @click="shareProperty">
                            <i class="fas fa-share"></i>
                        </button>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="text-2xl font-bold" :style="{ color: detailAccentColor }">
                                {{ formattedPrice }}
                            </div>
                            <div class="text-xs" :style="{ color: detailMutedColor }">/ {{ priceLabel }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div v-if="isLoading" class="grid grid-cols-1 gap-8">
                <div class="space-y-4">
                    <div class="h-72 bg-gray-200 rounded-xl animate-pulse"></div>
                    <div class="h-6 bg-gray-200 rounded w-2/3 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="h-20 bg-gray-200 rounded animate-pulse"></div>
                        <div class="h-20 bg-gray-200 rounded animate-pulse"></div>
                    </div>
                </div>
            </div>

            <div v-else-if="!property" class="text-center text-gray-500 py-16">
                <p class="text-lg">Detail properti tidak ditemukan.</p>
            </div>

            <div v-else class="grid grid-cols-1 gap-8">
                <div>
                    <!-- PHOTO GALLERY - Mirip RentDetails -->
                    <section class="max-w-7xl mx-auto mt-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-2 h-[280px] md:h-[380px]">

                            <!-- LEFT BIG IMAGE -->
                            <div
                                class="md:col-span-3 relative rounded-xl overflow-hidden cursor-pointer group bg-gray-100"
                                @click="setActiveImage(0)"
                            >
                                <LazyImage
                                    :src="galleryImages[0] || fallbackImage"
                                    img-class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                />
                                <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition"></div>
                            </div>

                            <!-- RIGHT 3x2 GRID -->
                            <div class="hidden md:grid md:col-span-2 grid-cols-3 grid-rows-2 gap-2 h-full">
                                <div
                                    v-for="(img, i) in galleryImages.slice(1,7)"
                                    :key="i"
                                    class="relative rounded-xl overflow-hidden cursor-pointer group bg-gray-100"
                                    @click="setActiveImage(i + 1)"
                                >
                                    <LazyImage
                                        :src="img"
                                        img-class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                                    />

                                    <div
                                        v-if="i === 5 && galleryImages.length > 7"
                                        class="absolute inset-0 bg-black/60 flex items-center justify-center z-10"
                                    >
                                        <div class="text-white text-sm font-semibold bg-black/40 backdrop-blur-sm px-4 py-2 rounded-lg flex items-center gap-2">
                                            <i class="fas fa-th-large"></i>
                                            Lihat Semua Foto
                                        </div>
                                    </div>

                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="max-w-7xl mx-auto -mt-6 z-20 relative">

                        <!-- MAIN CONTAINER -->
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-200 p-6 md:p-8">

                            <!-- TOP HEADER -->
                            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">

                                <!-- LEFT -->
                                <div>
                                    <h1 class="text-1xl md:text-2xl font-bold text-gray-900">
                                        {{ propertyTitle }}
                                    </h1>

                                    <!-- TAGS -->
                                    <div class="flex items-center gap-3 mt-3 flex-wrap">
                                        <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700 font-semibold">
                                        {{ propertyTypeLabel }}
                                        </span>

                                        <div class="flex text-yellow-400 text-sm">
                                        <i class="fas fa-star" v-for="i in 5" :key="i"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- RIGHT PRICE -->
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Harga mulai dari</p>
                                    <p class="text-2xl font-bold text-orange-500">
                                        {{ formattedPrice }}
                                    </p>
                                </div>
                            </div>

                            <!-- ALERT -->
                            <div class="mt-6 bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center">
                                    <i class="fas fa-thumbs-up"></i>
                                </div>

                                <p class="text-sm text-gray-700">
                                    <span class="font-semibold text-blue-600">Jarang ada!</span>
                                    Properti ini sering habis terpesan.
                                </p>
                            </div>

                            <!-- GRID 3 -->
                            <div class="grid md:grid-cols-3 gap-6 mt-8">

                                <!-- REVIEW CARD -->
                                <div class="border rounded-2xl p-5">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold">Informasi</h3>
                                    </div>

                                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                                        <p class="text-gray-600 whitespace-pre-line prose prose-sm" v-html="propertyAbout"></p>
                                    </div>
                                </div>

                                <!-- AREA CARD -->
                                <div class="border rounded-2xl p-5">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold">Area Akomodasi</h3>
                                    </div>

                                    <div class="mt-4 space-y-3 text-sm text-gray-600">
                                        <div class="text-gray-600 prose prose-sm max-w-none">
                                            <div v-html="propertyDescription"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- FACILITIES -->
                                <div class="border rounded-2xl p-5">
                                    <div class="flex justify-between items-center">
                                        <h3 class="font-semibold">Fasilitas Utama</h3>
                                    </div>

                                    <ul class="mt-4 grid grid-cols-2 gap-x-6 gap-y-4 text-sm text-gray-700">
                                        <li
                                            class="flex items-center gap-3"
                                            v-for="facility in facilities"
                                            :key="facility.odata || facility.name"
                                        >
                                            <i class="fas" :class="facility.icon" :style="{ color: currentInfo.primaryColor }"></i>
                                            {{ facility.name }}
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <section class="bg-[#f5f7fa] pb-14 rounded-2xl mt-6" id="available-rooms">
                                <div class="max-w-7xl mx-auto px-4">

                                    <!-- TITLE -->
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4 pt-4">
                                        Tipe Kamar yang Siap di Jual di {{ propertyTitle }}
                                    </h2>


                                    <!-- ROOM CARD -->
                                    <div class="overflow-hidden">
                                        <div v-for="room in propertyRooms" :key="room.odata" class="border rounded-xl overflow-hidden mb-6 bg-white shadow-sm">
                                            <!-- ================= ROOM TITLE ================= -->
                                            <div class="px-6 pt-6 pb-3">
                                                <h3 class="text-xl font-bold text-gray-900">
                                                {{ room.room_name }}
                                                </h3>
                                            </div>

                                            <div class="grid md:grid-cols-[300px_1fr]">

                                                <!-- ================= LEFT IMAGE ================= -->
                                                <div class="p-6 pt-0">
                                                    <div class="relative">
                                                        <img
                                                        :src="getRoomImage(room)"
                                                        class="rounded-xl w-full h-[200px] object-cover"
                                                        />

                                                        <div
                                                        class="absolute bottom-0 left-0 right-0 text-xs px-4 py-2 rounded-b-xl font-semibold"
                                                        :style="{ backgroundColor: currentInfo.primaryColor, color: '#ffffff' }"
                                                        >
                                                            Pilihan populer di akomodasi ini
                                                        </div>
                                                    </div>

                                                    <!-- Room Info -->
                                                    <div v-if="room.luas" class="flex items-center gap-2 text-gray-700 text-sm mt-4">
                                                        <i class="fas fa-ruler"></i>
                                                        <span>{{ room.luas }} m²</span>
                                                    </div>

                                                    <div class="flex flex-wrap gap-3 mt-4 text-sm text-gray-600">
                                                        <div
                                                        v-for="facility in getRoomFacilities(room)"
                                                        :key="facility.name"
                                                        class="flex items-center gap-2"
                                                        >
                                                        <i class="fas" :class="facility.icon"></i>
                                                        {{ facility.name }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- ================= RIGHT TABLE ================= -->
                                                <div>

                                                    <!-- ================= RIGHT UNIT CARDS ================= -->
                                                    <div class="p-6 pt-0 space-y-4">

                                                        <div
                                                            v-for="subRoom in room.sub_rooms"
                                                            :key="subRoom.odata"
                                                            class="border rounded-2xl p-5 hover:shadow-md transition bg-white"
                                                        >
                                                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                                                <!-- LEFT INFO -->
                                                                <div class="flex-1">

                                                                    <div class="flex items-center gap-2 flex-wrap">
                                                                        <h4 class="font-semibold text-gray-900 text-lg">
                                                                            {{ subRoom.name_room }} - {{ subRoom.code_room }}
                                                                        </h4>

                                                                        <span
                                                                            class="text-xs px-2 py-1 rounded-full font-semibold"
                                                                            :class="parseInt(subRoom.total_room) > 0
                                                                                ? 'bg-green-100 text-green-700'
                                                                                : 'bg-gray-200 text-gray-500'"
                                                                        >
                                                                            {{ parseInt(subRoom.total_room) > 0 ? 'Ready Stock' : 'Sold Out' }}
                                                                        </span>
                                                                    </div>

                                                                    <div class="text-sm text-gray-500 mt-1">
                                                                        {{ subRoom.type_bed }}
                                                                    </div>

                                                                    <!-- Capacity -->
                                                                    <div class="flex items-center gap-2 text-gray-500 mt-3 text-sm">
                                                                        <i class="fas fa-user"></i>
                                                                        <span>{{ room.capacity }} Orang</span>
                                                                    </div>

                                                                    <!-- Stock Info -->
                                                                    <div
                                                                        v-if="parseInt(subRoom.total_room) <= 3 && parseInt(subRoom.total_room) > 0"
                                                                        class="text-sm text-red-500 font-semibold mt-2"
                                                                    >
                                                                        Sisa {{ subRoom.total_room }} unit lagi!
                                                                    </div>

                                                                </div>

                                                                <!-- RIGHT PRICE + CTA -->
                                                                <div class="flex flex-row md:flex-col md:text-right items-center justify-between md:items-end md:min-w-[180px] gap-3 pt-3 md:pt-0 border-t md:border-0">

                                                                    <div>
                                                                        <div class="text-xl md:text-2xl font-bold text-gray-900">
                                                                            {{ formatCurrency(subRoom.sale) }}
                                                                        </div>
                                                                        <div class="text-xs text-gray-400">
                                                                            Harga per unit
                                                                        </div>
                                                                    </div>

                                                                    <button
                                                                        class="flex-shrink-0 md:w-full px-4 py-2 rounded-lg font-semibold text-sm transition"
                                                                        :style="{
                                                                            backgroundColor: currentInfo.primaryColor,
                                                                            color: currentInfo.primaryTextColor
                                                                        }"
                                                                        @click="openContactDrawer"
                                                                    >
                                                                        Hubungi Sales
                                                                    </button>

                                                                </div>

                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </section>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div v-if="videoUrl" class="bg-white rounded-xl shadow overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Video</h3>
                            </div>
                            <div class="p-4">
                                <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-black/5">
                                    <iframe v-if="videoEmbedUrl" :src="videoEmbedUrl" class="absolute inset-0 w-full h-full"
                                        title="Property video" frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                                    <video v-else controls class="absolute inset-0 w-full h-full">
                                        <source :src="videoUrl" />
                                    </video>
                                </div>
                            </div>
                        </div>

                        <div v-if="mapEmbedUrl" class="bg-white rounded-xl shadow overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-900">Location</h3>
                            </div>
                            <div class="p-4">
                                <div class="relative w-full aspect-video rounded-lg overflow-hidden bg-black/5">
                                    <iframe :src="mapEmbedUrl" class="absolute inset-0 w-full h-full" title="Map"
                                        frameborder="0" allowfullscreen loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <template v-if="showContactDrawer">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 relative">
                <button @click="showContactDrawer = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl">&times;</button>
                <h3 class="text-xl font-bold mb-4">Contact Agent</h3>
                <form @submit.prevent="submitContactForm">
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1">Name</label>
                        <input v-model="contactForm.name" type="text" class="input" placeholder="Enter your name" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1">Email</label>
                        <input v-model="contactForm.email" type="email" class="input" placeholder="Enter your email" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1">Phone</label>
                        <div class="col-span-8 flex gap-2">
                            <div ref="countryMenuRef" class="relative w-36">
                                <button type="button" class="input w-full bg-white text-left pr-8 flex items-center gap-2"
                                    :disabled="countryCodes.length === 0" @click="toggleCountryMenu">
                                    <img v-if="selectedCountry && getCountryFlagUrl(selectedCountry)"
                                        :src="getCountryFlagUrl(selectedCountry)" alt="" class="w-5 h-4 rounded-sm" />
                                    <span>{{ selectedCountryLabel }}</span>
                                </button>
                                <i class="fas fa-chevron-down text-gray-400 absolute right-3 top-1/2 -translate-y-1/2"></i>
                                <div v-if="isCountryOpen"
                                    class="absolute z-10 mt-2 w-56 max-h-60 overflow-auto bg-white border border-gray-200 rounded-lg shadow-lg">
                                    <button v-for="(item, index) in countryCodes" :key="getCountryKey(item, index)"
                                        type="button"
                                        class="w-full px-3 py-2 text-left flex items-center gap-2 hover:bg-gray-50"
                                        @click="selectCountry(item)">
                                        <img v-if="getCountryFlagUrl(item)" :src="getCountryFlagUrl(item)" alt=""
                                            class="w-5 h-4 rounded-sm" />
                                        <span class="text-sm">{{ getCountryCodeLabel(item) }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="relative flex-1">
                                <i class="fas fa-phone text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                <input v-model="contactForm.phone" type="tel" class="input input-icon" placeholder="81234567890" @input="onPhoneInput" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-semibold mb-1">Message</label>
                        <textarea v-model="contactForm.message" class="input" rows="3" placeholder="Enter your message" required></textarea>
                    </div>

                    <button type="submit"
                        class="w-full font-semibold py-3 rounded-lg transition disabled:opacity-70 disabled:cursor-not-allowed"
                        :style="{
                            background: currentInfo.primaryColor,
                            color: currentInfo.primaryTextColor,
                            fontWeight: 'bold',
                            border: 'none',
                            boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
                        }"
                        :disabled="isSubmitting">
                        <span v-if="isSubmitting" class="inline-flex items-center gap-2">
                            <i class="fas fa-spinner fa-spin"></i>
                            Processing...
                        </span>
                        <span v-else>Kirim</span>
                    </button>
                </form>
            </div>
        </div>
    </template>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import LazyImage from '@/components/ui/LazyImage.vue'
import { storeToRefs } from 'pinia'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { apiGetData, apiPostDataWithReturn, Swal  } from '@/store/action'
import { formatCurrency } from '@/utils/helpers'
import { useInfoStore } from '@/store/info'

const route = useRoute()
const property = ref(null)
const isLoading = ref(false)
const activeImage = ref('')
const activeIndex = ref(0)
const transitionName = ref('slide-next')
const accordionOpen = ref(['description', 'features', 'about'])
const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const fallbackImage = 'https://images.unsplash.com/photo-1568605114967-8130f3a36994'
const infoStore = useInfoStore()
const { data: info, loaded } = storeToRefs(infoStore)
const currentInfo = computed(() => info.value?.[0] ?? {})

const odata = computed(() => route.query.odata)
const countryCodes = ref([])
const isCountryOpen = ref(false)
const isSubmitting = ref(false)
const propertyTitle = computed(() => property.value?.properties || 'Detail Properti')
const propertyAddress = computed(() => property.value?.address || '-')
const propertyTypeLabel = computed(() => property.value?.type || 'Property')
const mapUrl = computed(() => {
    const lat = Number(property.value?.latitude)
    const lng = Number(property.value?.longitude)
    if (!Number.isFinite(lat) || !Number.isFinite(lng)) return ''
    return `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`
})
const mapEmbedUrl = computed(() => {
    const lat = Number(property.value?.latitude)
    const lng = Number(property.value?.longitude)
    if (!Number.isFinite(lat) || !Number.isFinite(lng)) return ''
    return `https://maps.google.com/maps?q=${lat},${lng}&z=15&output=embed`
})
const normalizeInfoValue = (value) => {
    if (value === null || value === undefined) return ''
    const stringValue = String(value).trim()
    if (!stringValue || stringValue.toLowerCase() === 'null') return ''
    return stringValue
}

const getYouTubeId = (value) => {
    if (!value) return ''
    try {
        const parsed = new URL(value)
        if (parsed.hostname.includes('youtu.be')) {
            return parsed.pathname.replace('/', '')
        }
        const idFromQuery = parsed.searchParams.get('v')
        if (idFromQuery) return idFromQuery
        const match = parsed.pathname.match(/\/embed\/([^/?]+)/)
        return match ? match[1] : ''
    } catch (error) {
        return ''
    }
}

const detailBanner = computed(() => normalizeInfoValue(currentInfo.value?.bannerJualDetail))
const detailColor = computed(() => normalizeInfoValue(currentInfo.value?.colorJualDetail))

const heroImage = computed(() => {
    if (detailBanner.value) {
        return imageBaseUrl + detailBanner.value
    }
    if (property.value?.image) {
        return imageBaseUrl + property.value.image
    }
    return fallbackImage
})
const detailTextColor = computed(() => detailColor.value || '#ffffff')
const detailMutedColor = computed(() => detailColor.value || 'rgba(255, 255, 255, 0.9)')
const detailAccentColor = computed(() => '#10b981')
const detailButtonTextColor = computed(() => '#ffffff')

const galleryImages = computed(() => {
    const images = []
    if (property.value?.image) {
        images.push(imageBaseUrl + property.value.image)
    }
    if (Array.isArray(property.value?.gallery)) {
        property.value.gallery.forEach((item) => {
            if (item?.image) {
                images.push(imageBaseUrl + item.image)
            }
        })
    }
    return Array.from(new Set(images))
})

const setActiveImage = (index, direction = null) => {
    if (!galleryImages.value.length) return
    const safeIndex = Math.min(Math.max(index, 0), galleryImages.value.length - 1)
    if (direction) {
        transitionName.value = direction
    } else {
        transitionName.value = safeIndex >= activeIndex.value ? 'slide-next' : 'slide-prev'
    }
    activeIndex.value = safeIndex
    activeImage.value = galleryImages.value[safeIndex]
}

const nextImage = () => {
    if (!galleryImages.value.length) return
    const nextIndex = (activeIndex.value + 1) % galleryImages.value.length
    setActiveImage(nextIndex, 'slide-next')
}

const prevImage = () => {
    if (!galleryImages.value.length) return
    const prevIndex = (activeIndex.value - 1 + galleryImages.value.length) % galleryImages.value.length
    setActiveImage(prevIndex, 'slide-prev')
}

const formattedPrice = computed(() => {
    return formatCurrency(property.value?.sale_price || 0)
})

const priceLabel = computed(() => 'Sale')

const facilities = computed(() => {
    if (!Array.isArray(property.value?.facilities)) return []
    return property.value.facilities
        .map((entry) => entry?.facility)
        .filter(Boolean)
})

const propertyDescription = computed(() => property.value?.information || 'Belum ada deskripsi.')
const propertyAbout = computed(() => property.value?.description || 'Belum ada deskripsi.')
const propertyRooms = computed(() => {
    if (!Array.isArray(property.value?.rooms)) return []
    return property.value.rooms.filter((room) => room?.status == '0')
})

const formatRoomPrice = (value) => {
    if (value === null || value === undefined || value === '') return '-'
    return formatCurrency(value)
}

const getRoomPriceInfo = (room) => {
    const candidates = [
        { value: room?.price, label: 'Night' },
        { value: room?.price_month, label: 'Month' },
        { value: room?.price_year, label: 'Year' }
    ].filter((entry) => typeof entry.value === 'number' && entry.value > 0)

    if (!candidates.length) {
        return { value: 0, label: 'Rent' }
    }

    return candidates.reduce((min, current) => {
        return current.value < min.value ? current : min
    })
}

const getRoomBadgeTags = (room) => {
    const rentTags = []
    if (room?.price) {
        rentTags.push({ label: 'Harian', className: 'bg-emerald-600' })
    }
    if (room?.price_month) {
        rentTags.push({ label: 'Bulanan', className: 'bg-purple-600' })
    }
    if (room?.price_year) {
        rentTags.push({ label: 'Tahunan', className: 'bg-sky-600' })
    }
    if (!rentTags.length) {
        rentTags.push({ label: 'Di Sewakan', className: 'bg-gray-700' })
    }
    return rentTags
}

const getRoomImage = (room) => {
    if (room?.image) {
        return imageBaseUrl + room.image
    }
    return fallbackImage
}

const getRoomFacilities = (room) => {
    if (!Array.isArray(room?.facilities)) return []
    return room.facilities
        .map((entry) => entry?.facility)
        .filter(Boolean)
}

const videoUrl = computed(() => normalizeInfoValue(property.value?.url_video))
const videoEmbedUrl = computed(() => {
    const id = getYouTubeId(videoUrl.value)
    return id ? `https://www.youtube.com/embed/${id}` : ''
})

const featureList = computed(() => facilities.value
    .map((facility) => facility?.name)
    .filter(Boolean))

const createdAt = computed(() => {
    if (!property.value?.created_at) return '-'
    return new Date(property.value.created_at).toLocaleDateString('id-ID')
})

const fetchProperty = async () => {
    if (!odata.value) return
    isLoading.value = true
    try {
        const response = await apiGetData('public/property-detail-sell', { odata: odata.value })
        property.value = response?.data || null
        if (galleryImages.value.length) {
            setActiveImage(0)
        }
    } finally {
        isLoading.value = false
    }
}

const shareProperty = async () => {
    const shareUrl = window.location.href
    const shareTitle = propertyTitle.value
    try {
        if (navigator.share) {
            await navigator.share({ title: shareTitle, url: shareUrl })
            return
        }
        if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(shareUrl)
            alert('Link copied')
            return
        }
    } catch (error) {
        // Ignore and fallback to prompt.
    }
    window.prompt('Copy this link', shareUrl)
}

const toggleAccordion = (id) => {
    if (accordionOpen.value.includes(id)) {
        accordionOpen.value = accordionOpen.value.filter((entry) => entry !== id)
        return
    }
    accordionOpen.value = [...accordionOpen.value, id]
}

const getCountryCodeValue = (item) => {
    return item?.dial_code || item?.code || item?.kode || item?.kode_negara || item?.kodeNegara || item?.phone_code || item?.phoneCode || item?.calling_code || item?.callingCode || ''
}

const getCountryLabelName = (item) => {
    return item?.nama || item?.nama_negara || item?.negara || item?.country || item?.name || ''
}

const getCountryFlagCode = (item) => {
    const raw = item?.flag || item?.flag_code || item?.flagCode || item?.country_code || item?.countryCode || ''
    return String(raw).trim().toUpperCase()
}

const getCountryCodeLabel = (item) => {
    const name = getCountryLabelName(item)
    const code = getCountryCodeValue(item)
    if (name && code) return `${name} (${code})`
    return name || code || '-'
}

const getCountryFlagUrl = (item) => {
    const code = getCountryFlagCode(item).toLowerCase()
    if (!code || code.length !== 2) return ''
    return `https://flagcdn.com/16x12/${code}.png`
}

const selectedCountry = computed(() => {
    return countryCodes.value.find((item) => getCountryCodeValue(item) === contactForm.value.countryCode) || null
})

const selectedCountryLabel = computed(() => {
    const code = selectedCountry.value ? getCountryCodeValue(selectedCountry.value) : ''
    return code || 'Pilih kode'
})

const getCountryKey = (item, index) => {
    return item?.id || getCountryCodeValue(item) || getCountryLabelName(item) || index
}

const pickDefaultCountryCode = (list) => {
    if (!Array.isArray(list) || list.length === 0) return ''
    const byPlus62 = list.find((item) => getCountryCodeValue(item) === '+62')
    if (byPlus62) return getCountryCodeValue(byPlus62)
    const byIndonesia = list.find((item) => /indo/i.test(getCountryLabelName(item)))
    if (byIndonesia) return getCountryCodeValue(byIndonesia)
    const by62 = list.find((item) => /62/.test(getCountryCodeValue(item)))
    if (by62) return getCountryCodeValue(by62)
    return getCountryCodeValue(list[0])
}

const onPhoneInput = (event) => {
    let val = event.target.value.replace(/\D/g, '')
    if (val.startsWith('0')) {
        val = val.replace(/^0+/, '')
    }
    contactForm.value.phone = val
    event.target.value = val
}

const toggleCountryMenu = () => {
    if (countryCodes.value.length === 0) return
    isCountryOpen.value = !isCountryOpen.value
}

const selectCountry = (item) => {
    contactForm.value.countryCode = getCountryCodeValue(item)
    isCountryOpen.value = false
}

const showContactDrawer = ref(false)
const contactForm = ref({
    property_id: '',
    property_odata: '',
    name: '',
    email: '',
    phone: '',
    message: ''
})

const openContactDrawer = () => {
    contactForm.value.property_id = property.value?.id || ''
    contactForm.value.property_odata = property.value?.odata || ''
    contactForm.value.name = ''
    contactForm.value.email = ''
    contactForm.value.phone = ''
    contactForm.value.message = ''
    showContactDrawer.value = true
}

const submitContactForm = async () => {
    if (isSubmitting.value) return
    isSubmitting.value = true
    const payload = {
        property_id: contactForm.value.property_id,
        property_odata: contactForm.value.property_odata,
        name: contactForm.value.name,
        email: contactForm.value.email,
        phone: contactForm.value.phone ? `${contactForm.value.countryCode}${contactForm.value.phone}` : '',
        message: contactForm.value.message
    }
    const result = await apiPostDataWithReturn('auth/contactAgent', payload, {}, false)
    isSubmitting.value = false
    if (!result?.success) return

    const token = result.data?.access_token
    if (token) {
        if (form.remember) {
            localStorage.setItem('token_id_room', token)
            sessionStorage.removeItem('token_id_room')
        } else {
            sessionStorage.setItem('token_id_room', token)
            localStorage.removeItem('token_id_room')
        }
        Api.defaults.headers.common['Authorization'] = `Bearer ${token}`
    }

    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        html: 'Pesan berhasil dikirim. Tunggu kontak dari agen kami.',
        confirmButtonColor: currentInfo.value?.primaryColor || '#10b981',
    })
    resetForm()
    showContactDrawer.value = false
    
}

const resetForm = () => {
    contactForm.value.name = ''
    contactForm.value.email = ''
    contactForm.value.phone = ''
    contactForm.value.message = ''
    contactForm.value.countryCode = pickDefaultCountryCode(countryCodes.value)
}

onMounted(async () => {
    await infoStore.fetch()
    fetchProperty()
    const response = await apiGetData('public/kode-negara')
    countryCodes.value = response?.data || []
    if (!contactForm.value.countryCode) {
        contactForm.value.countryCode = pickDefaultCountryCode(countryCodes.value)
    }
})

useHead({
    title: `${propertyTitle.value} - ID Room`,
    meta: [
        {
            name: 'description',
            content: propertyAddress.value
        }
    ]
})
</script>

<style scoped>
    .slide-next-enter-active,
    .slide-next-leave-active,
    .slide-prev-enter-active,
    .slide-prev-leave-active {
        transition: opacity 0.8s ease, transform 0.8s cubic-bezier(0.22, 0.61, 0.36, 1);
    }

    .slide-next-enter-from {
        opacity: 0;
        transform: translateX(48px);
    }

    .slide-next-leave-to {
        opacity: 0;
        transform: translateX(-48px);
    }

    .slide-prev-enter-from {
        opacity: 0;
        transform: translateX(-48px);
    }

    .slide-prev-leave-to {
        opacity: 0;
        transform: translateX(48px);
    }

    .accordion-enter-active,
    .accordion-leave-active {
        transition: max-height 0.4s ease, opacity 0.4s ease;
    }

    .accordion-enter-from,
    .accordion-leave-to {
        max-height: 0;
        opacity: 0;
    }

    .accordion-enter-to,
    .accordion-leave-from {
        max-height: 420px;
        opacity: 1;
    }

    .input {
        width: 100%;
        border: 1px solid #e5e7eb; /* border-gray-200 */
        border-radius: 0.5rem; /* rounded-lg */
        padding-left: 0.75rem; /* px-3 */
        padding-right: 0.75rem; /* px-3 */
        padding-top: 0.5rem; /* py-2 */
        padding-bottom: 0.5rem; /* py-2 */
        font-size: 0.875rem; /* text-sm */
        background-color: #f9fafb; /* bg-gray-50 */
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px var(--primary-color);
        outline: none;
    }

    .input-icon {
        padding-left: 2.5rem;
    }

    .input-icon-right {
        padding-right: 2.5rem;
    }

    .input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px var(--primary-color);
    }
</style>
