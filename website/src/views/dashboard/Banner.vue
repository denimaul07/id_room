<template>
    <div class="bg-gray-100">

        <!-- 🔥 COVER HEADER -->

        <div class="relative h-[320px] w-full overflow-hidden shadow">
            <!-- Carousel Slider Promo -->
            <div class="h-full w-full relative">
                <!-- Banner Image (promo or fallback) -->
                <transition name="fade-banner" mode="out-in">
                    <img
                        v-if="currentInfo && currentInfo.promo && currentInfo.promo.length"
                        :key="currentSlide"
                        :src="imageBaseUrl + currentInfo.promo[currentSlide].banner"
                        class="absolute inset-0 w-full h-full object-cover transition-all duration-700"
                    />
                    <img v-else src="https://images.unsplash.com/photo-1501785888041-af3ef285b470"
                        class="absolute inset-0 w-full h-full object-cover" />
                </transition>
                <div class="absolute inset-0 bg-black/40"></div>
                <!-- Content always stays on top of the banner -->
                <div class="relative z-10 h-full flex items-end justify-between px-10 pb-8 text-white">
                    <div class="flex items-end gap-6"></div>
                    <div class="flex gap-10 text-right">
                        <div>
                            <h2 class="text-xl font-bold">{{ currentBooking.length }}</h2>
                            <p class="text-white/70 text-sm font-medium">Bookings</p>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">{{ currentMembership.length }}</h2>
                            <p class="text-white/70 text-sm font-medium"> Memberships</p>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">{{ currentTransactions.length }}</h2>
                            <p class="text-white/70 text-sm font-medium">Transactions</p>
                        </div>
                    </div>
                </div>
                <!-- Carousel Controls -->
                <div v-if="currentInfo && currentInfo.promo && currentInfo.promo.length" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                    <button v-for="(item, idx) in (currentInfo.promo ? currentInfo.promo : [])" :key="'dot'+item.odata"
                        class="w-3 h-3 rounded-full border border-white"
                        :class="{ 'bg-white': idx === currentSlide, 'bg-white/40': idx !== currentSlide }"
                        @click="goToSlide(idx)"></button>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>

import { ref, onMounted, onUnmounted, computed, defineProps } from 'vue'

const props = defineProps({
    currentMembership: {
        type: Array,
        default: () => []
    },
    currentTransactions: {
        type: Array,
        default: () => []
    },
    currentBooking: {
        type: Array,
        default: () => []
    },
    currentInfo: {
        type: Object,
        default: () => ({})
    }
})

const currentMembership = computed(() => props.currentMembership)
const currentTransactions = computed(() => props.currentTransactions)
const currentBooking = computed(() => props.currentBooking)
const currentInfo = computed(() => props.currentInfo)


const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

const currentSlide = ref(0)
let interval = null

function startSlider() {
    if (interval) clearInterval(interval)
    if (currentInfo.value?.promo?.length > 1) {
        interval = setInterval(nextSlide, 3000)
    }
}

function nextSlide() {
    if (currentInfo.value?.promo?.length) {
        currentSlide.value =
            (currentSlide.value + 1) % currentInfo.value.promo.length
    }
}

function goToSlide(idx) {
    currentSlide.value = idx
    startSlider()
}

onMounted(() => {
    startSlider()
})

onUnmounted(() => {
    if (interval) clearInterval(interval)
})
</script>

<style scoped>
.fade-banner-enter-active, .fade-banner-leave-active {
    transition: opacity 0.7s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-banner-enter-from, .fade-banner-leave-to {
    opacity: 0;
}
.fade-banner-enter-to, .fade-banner-leave-from {
    opacity: 1;
}
</style>
