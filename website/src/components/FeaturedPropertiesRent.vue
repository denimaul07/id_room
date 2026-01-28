<template>
    <section class="py-20 bg-base-200">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Title & Arrow -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 text-center md:text-left">
                        Properti Unggulan untuk DiSewakan
                    </h2>
                    <div class="flex items-center gap-2 mt-2 justify-center md:justify-start">
                        <span class="inline-block w-8 h-1 rounded bg-emerald-400 mr-1"></span>
                        <span class="inline-block w-4 h-1 rounded bg-purple-500"></span>
                    </div>
                    <p class="text-gray-500 mt-2 text-center md:text-left">
                        Pilihan tempat berkualitas yang dipilih secara khusus
                    </p>
                </div>
                <div class="flex gap-2">
                    <button @click="prevPage" class="btn btn-circle bg-gray-800 text-white shadow hover:bg-gray-200 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button @click="nextPage" class="btn btn-circle bg-gray-800 text-white shadow hover:bg-gray-200 rounded-full p-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
            <!-- Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="property in pagedProperties"
                    :key="property.id"
                    class="bg-white rounded-xl shadow hover:-translate-y-2 transition duration-300 overflow-hidden"
                >
                    <!-- Image & Price -->
                    <div class="relative">
                        <img :src="property.image" alt="property" class="h-56 w-full object-cover" />
                        <div class="absolute bottom-0 left-0 bg-black bg-opacity-70 text-white px-4 py-2 text-lg font-bold rounded-tr-xl">
                            ${{ property.price.toLocaleString() }}
                        </div>
                        <span class="absolute top-4 left-4 bg-purple-600 text-white text-xs font-semibold px-3 py-1 rounded">For Sale</span>
                        <span v-if="property.featured" class="absolute top-4 right-4 bg-yellow-400 text-white text-xs font-semibold px-3 py-1 rounded">Featured</span>
                        <img v-if="property.agent" :src="property.agent" alt="agent" class="absolute bottom-4 right-4 w-8 h-8 rounded-full border-2 border-white shadow" />
                        <button class="absolute top-4 right-12 bg-white rounded-full p-1 shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 19.364l-1.415-1.415A8 8 0 1112 20a7.963 7.963 0 01-4.243-1.272" /></svg>
                        </button>
                    </div>
                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-yellow-500 text-lg">★</span>
                            <span class="font-semibold text-gray-800">{{ property.rating }}</span>
                            <span class="text-xs text-gray-500">({{ property.reviews }} Reviews)</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ property.title }}</h3>
                        <p class="text-sm text-gray-500 mb-2 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414a4 4 0 10-5.657 5.657l4.243 4.243a8 8 0 1011.314-11.314l-4.243-4.243a4 4 0 00-5.657 5.657l4.243 4.243z" /></svg>
                            {{ property.location }}
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600 mb-2">
                            <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg> {{ property.bed }} Bedroom</span>
                            <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10h10M7 14h10M7 18h10" /></svg> {{ property.bath }} Bath</span>
                            <span class="flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16" /></svg> {{ property.size }} Sq Ft</span>
                        </div>
                        <div class="flex justify-between text-xs text-gray-400 mt-auto">
                            <span>Listed on : {{ property.listed }}</span>
                            <span>Category : {{ property.category }}</span>
                        </div>
                        <button class="btn btn-primary btn-sm w-full mt-4">View Details</button>
                    </div>
                </div>
            </div>
            <!-- Explore All Button -->
            <div class="flex justify-center mt-8">
                <button class="btn btn-circle bg-gray-800  text-white px-6 font-semibold flex items-center rounded-full p-2">
                    Explore All
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </button>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref, computed } from 'vue'
const properties = [
    {
        id: 1,
        title: 'Loyal Apartment',
        location: '25, Willow Crest Apartment, USA',
        price: 1940,
        bed: 2,
        bath: 2,
        size: 350,
        image: 'https://images.unsplash.com/photo-1568605114967-8130f3a36994',
        listed: '02 May 2025',
        category: 'Apartment',
        featured: false,
        rating: 4.6,
        reviews: 36,
        agent: 'https://randomuser.me/api/portraits/men/32.jpg'
    },
    {
        id: 2,
        title: 'Grand Villa House',
        location: '10, Oak Ridge Villa, USA',
        price: 1370,
        bed: 4,
        bath: 3,
        size: 520,
        image: 'https://images.unsplash.com/photo-1572120360610-d971b9b78825',
        listed: '28 Apr 2025',
        category: 'Villa',
        featured: true,
        rating: 4.9,
        reviews: 25,
        agent: 'https://randomuser.me/api/portraits/women/44.jpg'
    },
    {
        id: 3,
        title: 'Elite Suite Room',
        location: '42, Maple Grove Residences, USA',
        price: 2470,
        bed: 2,
        bath: 1,
        size: 480,
        image: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
        listed: '14 Apr 2025',
        category: 'Suite',
        featured: false,
        rating: 4.4,
        reviews: 79,
        agent: 'https://randomuser.me/api/portraits/men/45.jpg'
    },
    {
        id: 4,
        title: 'Blue Horizon Villa',
        location: '76, Golden Oaks, Dallas, USA',
        price: 2000,
        bed: 2,
        bath: 1,
        size: 400,
        image: 'https://images.unsplash.com/photo-1506744038136-46273834b3fb',
        listed: '08 Mar 2025',
        category: 'Residency',
        featured: false,
        rating: 4.9,
        reviews: 19,
        agent: 'https://randomuser.me/api/portraits/women/47.jpg'
    },
    {
        id: 5,
        title: 'Wanderlust Lodge',
        location: '91, Birch Residences, Boston, USA',
        price: 1950,
        bed: 3,
        bath: 2,
        size: 550,
        image: 'https://images.unsplash.com/photo-1460518451285-97b6aa326961',
        listed: '25 Feb 2025',
        category: 'Lodge',
        featured: false,
        rating: 4.7,
        reviews: 45,
        agent: 'https://randomuser.me/api/portraits/men/48.jpg'
    },
    {
        id: 6,
        title: 'Celestial Residency',
        location: '28, Hilltop Gardens, San Francisco, USA',
        price: 1900,
        bed: 2,
        bath: 2,
        size: 354,
        image: 'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd',
        listed: '06 Apr 2025',
        category: 'Villa',
        featured: false,
        rating: 4.6,
        reviews: 47,
        agent: 'https://randomuser.me/api/portraits/women/49.jpg'
    }
]

const page = ref(1)
const perPage = 3
const totalPages = computed(() => Math.ceil(properties.length / perPage))
const pagedProperties = computed(() => {
    const start = (page.value - 1) * perPage
    return properties.slice(start, start + perPage)
})
function nextPage() {
    if (page.value < totalPages.value) page.value++
}
function prevPage() {
    if (page.value > 1) page.value--
}
</script>
