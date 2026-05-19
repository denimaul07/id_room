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
                            For Rent
                        </span>
                    </div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2" :style="{ color: detailTextColor }">
                        {{ propertyTitle || 'Detail Room' }}
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
                    <SearchProperty />
                
                    <!-- PHOTO GALLERY - Traveloka 3x2 -->
                    <section class="max-w-7xl mx-auto mt-6">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2 h-[280px] md:h-[380px]">

                        <!-- LEFT BIG IMAGE -->
                        <div
                        class="md:col-span-3 relative rounded-xl overflow-hidden cursor-pointer group bg-gray-100"
                        @click="openGallery(0)"
                        >
                        <LazyImage
                            :src="images[0]"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                        />
                        <div class="absolute inset-0 bg-black/10 group-hover:bg-black/20 transition"></div>
                        </div>

                        <!-- RIGHT 3x2 GRID -->
                        <div class="hidden md:grid md:col-span-2 grid-cols-3 grid-rows-2 gap-2 h-full">

                        <div
                            v-for="(img, i) in images.slice(1,7)"
                            :key="i"
                            class="relative rounded-xl overflow-hidden cursor-pointer group bg-gray-100"
                            @click="openGallery(i+1)"
                        >
                            <LazyImage
                            :src="img"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-105"
                            />

                            <!-- Overlay Lihat Semua Foto -->
                            <div
                            v-if="i === 5 && images.length > 7"
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
                                    <p class="text-sm text-gray-500">Harga/kamar/malam mulai dari</p>
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
                                        <p class="text-gray-600 whitespace-pre-line  prose prose-sm" v-html="propertyAbout"></p>
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
                                        Tipe Kamar yang Tersedia di {{ propertyTitle }}
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

                                                    <!-- TABLE HEADER -->
                                                    <div class="hidden md:grid grid-cols-12 bg-gray-50 border-y text-sm font-semibold text-gray-700">
                                                        <div class="col-span-4 px-6 py-3">Pilihan Kamar</div>
                                                        <div class="col-span-2 text-center py-3">Tamu</div>
                                                        <div class="col-span-3 text-right px-6 py-3">Harga/kamar/malam</div>
                                                        <div class="col-span-1 text-center py-3">Kamar</div>
                                                        <div class="col-span-2 text-center py-3"></div>
                                                    </div>

                                                    <!-- RATE LOOP -->
                                                    <div v-for="subRoom in room.sub_rooms"
                                                        :key="subRoom.odata"
                                                        class="border-b last:border-0">

                                                        <!-- MOBILE CARD -->
                                                        <div class="md:hidden p-4">
                                                            <div class="flex items-start justify-between gap-3">
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="font-semibold text-gray-900 text-sm">{{ subRoom.name_room }} - {{ subRoom.code_room }}</div>
                                                                    <div class="text-xs text-gray-500 mt-0.5">{{ subRoom.type_bed }} Bed</div>
                                                                    <div class="flex flex-wrap gap-1 mt-2 text-xs">
                                                                        <span class="inline-flex items-center bg-green-400 text-white px-2 py-1 rounded-full" v-if="subRoom.include_breakfast==='Y'">
                                                                            <i class="fas fa-utensils mr-1"></i>Termasuk Sarapan
                                                                        </span>
                                                                        <span class="inline-flex items-center bg-yellow-400 text-white px-2 py-1 rounded-full" v-if="subRoom.include_breakfast==='N'">
                                                                            <i class="fas fa-times mr-1"></i>Tidak Termasuk Sarapan
                                                                        </span>
                                                                        <span v-if="subRoom.smoking_area==='Y'" class="inline-flex items-center bg-red-100 text-red-600 px-2 py-1 rounded-full">
                                                                            <i class="fas fa-smoking mr-1"></i>Smoking
                                                                        </span>
                                                                        <span v-else class="inline-flex items-center bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                                                            <i class="fas fa-smoking-ban mr-1"></i>Non-Smoking
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right flex-shrink-0">
                                                                    <div class="text-base font-bold text-orange-500">{{ formatCurrency(subRoom.price) }}</div>
                                                                    <div class="text-xs text-gray-400">/malam</div>
                                                                    <div class="text-xs text-red-500 font-semibold mt-1" v-if="parseInt(subRoom.total_room) - parseInt(subRoom.booked_count) <= 5">
                                                                        {{ parseInt(subRoom.total_room) - parseInt(subRoom.booked_count) }} kamar tersisa
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button
                                                                class="mt-3 w-full py-2 rounded-lg font-semibold text-sm transition"
                                                                :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                                                                :disabled="parseInt(subRoom.total_room) - parseInt(subRoom.booked_count) <= 0"
                                                                @click="bookRoom(room)"
                                                            >
                                                                Pilih
                                                            </button>
                                                        </div>

                                                        <!-- DESKTOP TABLE ROW -->
                                                        <div class="hidden md:grid grid-cols-12 items-center border-t px-6 py-5">

                                                            <!-- DESCRIPTION -->
                                                            <div class="col-span-4">
                                                                <div class="font-semibold text-gray-900">
                                                                    {{ subRoom.name_room }} - {{ subRoom.code_room }}
                                                                </div>
                                                                <div class="text-sm text-gray-500 mt-1">
                                                                    {{ subRoom.type_bed }} Bed
                                                                </div>
                                                                <div class="mt-3 space-y-1 text-xs">
                                                                    <div class="inline-flex items-center bg-green-400 text-white px-2 py-1 rounded-full" v-if="subRoom.include_breakfast==='Y'">
                                                                        <i class="fas fa-utensils mr-1"></i>Termasuk Sarapan
                                                                    </div>
                                                                    <div class="inline-flex items-center bg-yellow-400 text-white px-2 py-1 rounded-full" v-if="subRoom.include_breakfast==='N'">
                                                                        <i class="fas fa-times mr-1"></i>Tidak Termasuk Sarapan
                                                                    </div>
                                                                    <div v-if="subRoom.smoking_area==='Y'" class="inline-flex items-center bg-red-100 text-red-600 px-2 py-1 rounded-full">
                                                                        <i class="fas fa-smoking mr-1"></i>
                                                                        Smoking Area
                                                                    </div>
                                                                    <div v-else class="inline-flex items-center bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                                                        <i class="fas fa-smoking-ban mr-1"></i>
                                                                        Non-Smoking
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- TAMU -->
                                                            <div class="col-span-2 text-center text-gray-400 text-lg">
                                                                <i class="fas fa-user" v-for="n in room.capacity" :key="n"></i>
                                                            </div>

                                                            <!-- PRICE -->
                                                            <div class="col-span-3 text-right">
                                                                <div class="text-2xl font-bold text-orange-500">
                                                                    {{ formatCurrency(subRoom.price) }}
                                                                </div>
                                                                <div class="text-xs text-gray-400">Di luar pajak & biaya</div>
                                                                <div class="text-xs text-red-500 font-semibold mt-1" v-if="parseInt(subRoom.total_room) - parseInt(subRoom.booked_count) <= 5">
                                                                    {{ parseInt(subRoom.total_room) - parseInt(subRoom.booked_count) }} kamar tersisa pada pilihan ini
                                                                </div>
                                                                <div class="text-xs text-green-500 font-semibold mt-1" v-else>
                                                                    Tersedia banyak kamar pada pilihan ini
                                                                </div>
                                                            </div>

                                                            <!-- JUMLAH -->
                                                            <div class="col-span-1 text-center text-sm text-gray-600">x1</div>

                                                            <!-- ACTION -->
                                                            <div class="col-span-2 flex flex-col items-center gap-2">
                                                                <button
                                                                    class="px-5 py-2 rounded-lg font-semibold text-sm transition"
                                                                    :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                                                                    :disabled="parseInt(subRoom.total_room) - parseInt(subRoom.booked_count) <= 0"
                                                                    @click="bookRoom(room)"
                                                                >
                                                                    Pilih
                                                                </button>
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

    <LoginModal :open="loginOpen" @close="loginOpen = false" />
    

    <!-- LIGHTBOX -->
    <div v-if="showGallery" class="fixed inset-0 bg-black/95 z-50 flex flex-col">

        <!-- HEADER -->
        <div class="flex justify-between items-center p-5 text-white relative">
            <span class="text-lg font-semibold">
            {{ currentIndex + 1 }} / {{ images.length }}
            </span>
            <button @click="showGallery = false" class="text-2xl cursor-pointer absolute right-5 top-5 z-50 bg-black/40 rounded-full px-3 py-1" style="pointer-events:auto;">
                ×
            </button>
        </div>

        <!-- IMAGE with pinch-to-zoom and drag -->
        <div class="flex-1 flex items-center justify-center select-none">
            <div ref="zoomWrap" class="relative" style="touch-action: none;">
                <img :src="images[currentIndex]"
                    class="max-h-[85vh] object-contain transition duration-300"
                    :style="zoomStyle"
                    @mousedown="startDrag"
                    @mousemove="onDrag"
                    @mouseup="endDrag"
                    @mouseleave="endDrag"
                    @touchstart="startPinch"
                    @touchmove="onPinch"
                    @touchend="endPinch"
                />
            </div>
        </div>

        <!-- NAVIGATION -->
        <div class="absolute inset-y-0 left-0 flex items-center">
            <button @click="prevImage"
                    class="text-white text-3xl px-6 hover:scale-125 transition">
            ‹
            </button>
        </div>

        <div class="absolute inset-y-0 right-0 flex items-center">
            <button @click="nextImage"
                    class="text-white text-3xl px-6 hover:scale-125 transition">
            ›
            </button>
        </div>

    </div>
</template>

<script setup>
    import { computed, onMounted, ref, onBeforeUnmount, watch } from 'vue'
    import LazyImage from '@/components/ui/LazyImage.vue'
    import { storeToRefs } from 'pinia'
    import { useRoute } from 'vue-router'
    import { useHead } from '@vueuse/head'
    import { apiGetData } from '@/store/action'
    import { formatCurrency } from '@/utils/helpers'
    import { useInfoStore } from '@/store/info'
    import { useAuthStore } from '@/store/auth'
    import LoginModal from '@/components/auth/LoginModal.vue'
    import SearchProperty from '@/components/home/SearchProperty.vue'
    import dayjs from 'dayjs'

    const authStore = useAuthStore()
    const { token } = storeToRefs(authStore)
    const loginOpen = ref(false)


    const route = useRoute()
    const property = ref(null)
    const isLoading = ref(false)
    const accordionOpen = ref(['description', 'features', 'about'])
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
    const fallbackImage = 'https://images.unsplash.com/photo-1568605114967-8130f3a36994'
    const infoStore = useInfoStore()
    const { data: info, loaded } = storeToRefs(infoStore)
    const currentInfo = computed(() => info.value?.[0] ?? {})

    const odata = computed(() => route.query.odata)
    const startDate = computed(() => route.query.startDate || dayjs().format('YYYY-MM-DD'))
    const endDate = computed(() => route.query.endDate || dayjs().add(1, 'day').format('YYYY-MM-DD'))

    const propertyTitle = computed(() => property.value?.properties || 'Detail Room')
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

    const detailBanner = computed(() => normalizeInfoValue(currentInfo.value?.bannerSewaDetail))
    const detailColor = computed(() => normalizeInfoValue(currentInfo.value?.colorSewaDetail))

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
    const detailAccentColor = computed(() => detailColor.value || '#10b981')
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



    const showGallery = ref(false)
    const currentIndex = ref(0)


    const images = computed(() => {     
        return galleryImages.value.length ? galleryImages.value : [fallbackImage]
    })

    function openGallery(index){
        currentIndex.value = index
        showGallery.value = true
    }

    function nextImage(){
        currentIndex.value = (currentIndex.value + 1) % images.value.length
    }

    function prevImage(){
        currentIndex.value = (currentIndex.value - 1 + images.value.length) % images.value.length
    }

    
    // Pinch-to-zoom and drag state
    const zoom = ref(1)
    const offset = ref({ x: 0, y: 0 })
    const dragging = ref(false)
    const dragStart = ref({ x: 0, y: 0 })
    const offsetStart = ref({ x: 0, y: 0 })
    const pinchDist = ref(0)
    const zoomWrap = ref(null)

    const zoomStyle = computed(() => {
        return `transform: scale(${zoom.value}) translate(${offset.value.x / zoom.value}px, ${offset.value.y / zoom.value}px); transition: ${dragging.value ? 'none' : 'transform 0.3s'}; cursor: ${zoom.value > 1 ? 'grab' : 'auto'};`
    })

    function startDrag(e) {
        if (zoom.value === 1) return
        dragging.value = true
        dragStart.value = { x: e.clientX, y: e.clientY }
        offsetStart.value = { ...offset.value }
        window.addEventListener('mousemove', onDrag)
        window.addEventListener('mouseup', endDrag)
    }
    function onDrag(e) {
        if (!dragging.value) return
        offset.value = {
            x: offsetStart.value.x + (e.clientX - dragStart.value.x),
            y: offsetStart.value.y + (e.clientY - dragStart.value.y)
        }
    }
    function endDrag() {
        dragging.value = false
        window.removeEventListener('mousemove', onDrag)
        window.removeEventListener('mouseup', endDrag)
    }

    function startPinch(e) {
        if (e.touches && e.touches.length === 2) {
            pinchDist.value = getPinchDist(e)
            offsetStart.value = { ...offset.value }
            dragging.value = false
        } else if (e.touches && e.touches.length === 1 && zoom.value > 1) {
            dragging.value = true
            dragStart.value = { x: e.touches[0].clientX, y: e.touches[0].clientY }
            offsetStart.value = { ...offset.value }
        }
    }
    function onPinch(e) {
        if (e.touches && e.touches.length === 2) {
            const dist = getPinchDist(e)
            let scale = dist / pinchDist.value * zoom.value
            scale = Math.max(1, Math.min(4, scale))
            zoom.value = scale
        } else if (e.touches && e.touches.length === 1 && dragging.value) {
            offset.value = {
                x: offsetStart.value.x + (e.touches[0].clientX - dragStart.value.x),
                y: offsetStart.value.y + (e.touches[0].clientY - dragStart.value.y)
            }
        }
    }
    function endPinch(e) {
        dragging.value = false
    }
    function getPinchDist(e) {
        const [a, b] = e.touches
        return Math.sqrt(Math.pow(a.clientX - b.clientX, 2) + Math.pow(a.clientY - b.clientY, 2))
    }

    function onWheel(e) {
        if (!showGallery.value) return
        if (e.ctrlKey || e.metaKey) {
            e.preventDefault()
            let next = zoom.value + (e.deltaY < 0 ? 0.1 : -0.1)
            zoom.value = Math.max(1, Math.min(4, next))
        }
    }

    onMounted(() => {
        if (zoomWrap.value) {
            zoomWrap.value.addEventListener('wheel', onWheel, { passive: false })
        }
    })
    onBeforeUnmount(() => {
        if (zoomWrap.value) {
            zoomWrap.value.removeEventListener('wheel', onWheel)
        }
    })

    watch(showGallery, (val) => {
        if (!val) {
            zoom.value = 1
            offset.value = { x: 0, y: 0 }
        }
    })

    // --- LazyImage is imported from @/components/ui/LazyImage.vue ---

    const priceInfo = computed(() => {
        const candidates = [
            { value: Number(property.value?.price_per_night), label: 'Night' },
            { value: Number(property.value?.price_per_monthly), label: 'Month' },
            { value: Number(property.value?.price_per_year), label: 'Year' }
        ].filter((entry) => entry.value > 0)

        if (!candidates.length) {
            return { value: 0, label: 'Rent' }
        }

        return candidates.reduce((min, current) => {
            return current.value < min.value ? current : min
        })
    })

    const formattedPrice = computed(() => {
        return formatCurrency(priceInfo.value.value || 0)
    })

    const priceLabel = computed(() => priceInfo.value.label)


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
            { value: Number(room?.price), label: 'Night' },
            { value: Number(room?.price_month), label: 'Month' },
            { value: Number(room?.price_year), label: 'Year' }
        ].filter((entry) => entry.value > 0)

        if (!candidates.length) {
            return { value: 0, label: 'Rent' }
        }

        return candidates.reduce((min, current) => {
            return current.value < min.value ? current : min
        })
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


    const createdAt = computed(() => {
        if (!property.value?.created_at) return '-'
        return new Date(property.value.created_at).toLocaleDateString('id-ID')
    })

    const fetchProperty = async () => {
        if (!odata.value) return
        isLoading.value = true
        try {
            const payload = {
                odata: odata.value,
                startDate: startDate.value,
                endDate: endDate.value
            }
            const response = await apiGetData('public/property-detail', payload)
            property.value = response?.data || null
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

    const bookRoom = (room) => {
        if (!token.value) {
            loginOpen.value = true
            return
        }
        // Ambil data property dan room
        const propertyData = {
            propertyID: room?.odata,
            // capacity: room.capacity || 2,
            // image: getRoomImage(room),
            checkIn: new Date().toISOString().slice(0,10),
            checkOut: (() => {
                const d = new Date();
                d.setDate(d.getDate() + 1);
                return d.toISOString().slice(0,10);
            })(),
            // nights: 1
        }
        // Kirim data via query string (atau localStorage/sessionStorage jika data besar)
        const params = new URLSearchParams(propertyData).toString();
        // Navigasi ke halaman booking menggunakan router
        window.location.href = `/booking?${params}`;
    }

    onMounted(async () => {
        await infoStore.fetch()
        fetchProperty()
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

    window.addEventListener('keydown', (e)=>{
        if(!showGallery.value) return

        if(e.key === 'ArrowRight') nextImage()
        if(e.key === 'ArrowLeft') prevImage()
        if(e.key === 'Escape') showGallery.value = false
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

    .chip{
        background-color: #f3f4f6; /* bg-gray-100 */
        color: #374151;            /* text-gray-700 */
        padding-left: 0.75rem;     /* px-3 */
        padding-right: 0.75rem;
        padding-top: 0.5rem;       /* py-2 */
        padding-bottom: 0.5rem;
        border-radius: 9999px;     /* rounded-full */
        font-size: 0.875rem;       /* text-sm */
        font-weight: 500;          /* font-medium */
        cursor: pointer;           /* cursor-pointer */
    }
    .chip:hover{
        background-color: #eff6ff; /* bg-blue-50 */
        color: #2563eb;            /* text-blue-600 */
    }

/* Tambahkan styling agar tag HTML seperti <ul>, <li>, <b> tetap terbaca rapi */
.prose ul {
    list-style-type: disc;
    margin-left: 1.5em;
    margin-bottom: 0.5em;
}
.prose ol {
    list-style-type: decimal;
    margin-left: 1.5em;
    margin-bottom: 0.5em;
}
.prose li {
    margin-bottom: 0.25em;
}
.prose b, .prose strong {
    font-weight: bold;
    color: #222;
}
.prose p {
    margin-bottom: 0.5em;
}
.prose br {
    display: block;
    margin-bottom: 0.5em;
}
</style>

<style scoped>
    .gallery-left {
    display: flex;
    flex-direction: column;
    gap: 8px;
    width: 180px; /* kecilin lebar kiri */
    }
    .gallery-left-img {
    width: 100%;
    height: 120px; /* kecilin tinggi kiri */
    object-fit: contain;
    background: #f5f5f5;
    border-radius: 8px;
    }
    .gallery-right {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 8px;
    }
    .gallery-img {
    width: 100%;
    height: 120px;
    object-fit: contain;
    background: #f5f5f5;
    border-radius: 8px;
    }
</style>
