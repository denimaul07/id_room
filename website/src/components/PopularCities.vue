
<template>
    <section class="py-16 bg-slate-100">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Cities With Listing</h2>
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="inline-block w-12 h-1 rounded bg-emerald-400 mr-1"></span>
                    <span class="inline-block w-6 h-1 rounded bg-purple-500"></span>
                </div>
                <p class="text-gray-500">Destinations we love the most</p>
            </div>
            <div class="relative">
                <div ref="scrollRef" class="flex gap-8 overflow-x-auto scroll-smooth pb-2 hide-scrollbar">
                    <div v-for="city in cities" :key="city.name" class="relative min-w-[320px] h-56 rounded-xl overflow-hidden shadow group cursor-pointer">
                        <img :src="city.image" :alt="city.name" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute left-0 bottom-0 p-5">
                            <h3 class="text-white text-xl font-bold mb-1">{{ city.name }}</h3>
                            <p class="text-white text-base opacity-90">{{ city.count }} Properties</p>
                        </div>
                        <!-- Tombol action di hover -->
                        <button class="absolute bottom-4 right-4 bg-emerald-400 text-white rounded-full w-12 h-12 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                        </button>
                    </div>
                </div>
                <!-- Tombol scroll -->
                <button @click="scrollLeft" class="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2 hidden md:block"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                <button @click="scrollRight" class="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2 hidden md:block"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
            </div>
            <!-- Carousel Indicator -->
            <div class="flex justify-center mt-8 gap-2">
                <span v-for="i in Math.ceil(cities.length / 3)" :key="i" :class="[i === currentPage ? 'bg-emerald-400' : 'bg-gray-300', 'w-4 h-2 rounded-full transition-all duration-300']"></span>
            </div>
        </div>
    </section>
</template>


<script setup>
import { ref, computed, onMounted } from 'vue'
const cities = [
    { name: 'New York', count: 300, image: 'https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=600&q=80' },
    { name: 'Singapore', count: 400, image: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80' },
    { name: 'Bangkok', count: 300, image: 'https://images.unsplash.com/photo-1500534314209-a25ddb2bd429?auto=format&fit=crop&w=600&q=80' },
    { name: 'Argentina', count: 740, image: 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=600&q=80' },
    { name: 'United Kingdom', count: 1450, image: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=600&q=80' },
    { name: 'Singapore', count: 400, image: 'https://images.unsplash.com/photo-1465101178521-c1a9136a3b99?auto=format&fit=crop&w=600&q=80' },
]
const scrollRef = ref(null)
const currentPage = ref(1)
const cardsPerPage = 3
const totalPages = computed(() => Math.ceil(cities.length / cardsPerPage))

function scrollLeft() {
    if (scrollRef.value) {
        scrollRef.value.scrollBy({ left: -360, behavior: 'smooth' })
        if (currentPage.value > 1) currentPage.value--
    }
}
function scrollRight() {
    if (scrollRef.value) {
        scrollRef.value.scrollBy({ left: 360, behavior: 'smooth' })
        if (currentPage.value < totalPages.value) currentPage.value++
    }
}
onMounted(() => {
    currentPage.value = 1
})
</script>

<style scoped>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
