<template>
    <section class="py-20 bg-[#0d131e]">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-start gap-8">
            <!-- Kiri: Judul & Navigasi -->
            <div class="md:w-1/3 flex flex-col justify-center mb-8 md:mb-0">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">
                Jelajahi Berdasarkan<br />Tipe Properti
                </h2>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-block w-8 h-1 rounded bg-emerald-400 mr-1"></span>
                    <span class="inline-block w-4 h-1 rounded bg-purple-500"></span>
                </div>

                <p class="text-gray-300 mb-8">
                    Apakah Anda mencari apartemen yang nyaman, vila mewah, atau investasi komersial, kami siap membantu Anda.
                </p>
                <div class="flex gap-3">
                    <button @click="scrollLeft" class="btn btn-circle bg-white text-gray-800 shadow hover:bg-gray-200 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button @click="scrollRight" class="btn btn-circle bg-white text-gray-800 shadow hover:bg-gray-200 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <!-- Kanan: Card Scrollable -->
            <div class="md:w-2/3 w-full overflow-x-auto">
                <div ref="scrollRef" class="flex gap-6 snap-x snap-mandatory overflow-x-auto pb-2 hide-scrollbar">
                    <div
                        v-for="item in propertyTypes"
                        :key="item.name"
                        class="min-w-[210px] max-w-[220px] w-full bg-white rounded-xl flex flex-col items-center justify-center p-8 cursor-pointer shadow hover:scale-105 transition-all duration-300 snap-start"
                    >
                        
                        <div class="mb-4">
                            <component :is="item.icon" class="w-14 h-14 text-primary" />
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 mb-1">{{ item.name }}</h3>
                        <p class="text-sm text-blue-900 opacity-70">{{ item.count }} Properties</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
    import { ref } from 'vue'
    import {
    HomeIcon,
    BuildingOffice2Icon,
    BuildingStorefrontIcon,
    KeyIcon,
    BuildingLibraryIcon,
    BuildingOfficeIcon,
    } from '@heroicons/vue/24/outline'

    const propertyTypes = [
    { name: 'Villas', count: 28, icon: BuildingOffice2Icon },
    { name: 'Apartment', count: 35, icon: BuildingLibraryIcon },
    { name: 'Apartment', count: 30, icon: BuildingLibraryIcon },
    { name: 'Houses', count: 30, icon: HomeIcon },
    ]

    const scrollRef = ref(null)
    function scrollLeft() {
    if (scrollRef.value) scrollRef.value.scrollBy({ left: -240, behavior: 'smooth' })
    }
    function scrollRight() {
    if (scrollRef.value) scrollRef.value.scrollBy({ left: 240, behavior: 'smooth' })
    }
</script>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>