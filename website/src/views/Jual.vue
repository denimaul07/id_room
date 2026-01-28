<template>
    <!-- Page Header -->
    <section
        class="relative flex items-center bg-center bg-cover transition-opacity duration-700
            h-[200px] md:h-[250px] lg:h-[230px]"
        :class="loaded ? 'opacity-100' : 'opacity-0'"
        :style="loaded ? { backgroundImage: `url(${heroImage})` } : {}"
        >
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-black mb-2">
            Jual Properti
            </h1>

            <p class="text-black/80 text-sm">
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
        <div class="bg-white rounded-xl shadow p-6 mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="text-gray-600 text-base">
                Showing result <span class="font-bold text-primary text-lg">06</span> of <span class="font-bold text-lg">125</span>
            </div>
            <div class="flex flex-row items-center gap-6">
                <label class="text-gray-900 font-semibold mr-2">Sort By</label>
                <select class="bg-white border border-gray-200 rounded-lg px-5 py-2 text-base font-semibold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                    <option>Default</option>
                    <option>Newest</option>
                </select>
                <label class="text-gray-900 font-semibold ml-2 mr-2">Price Range</label>
                <select class="bg-white border border-gray-200 rounded-lg px-5 py-2 text-base font-semibold focus:outline-none focus:ring-2 focus:ring-primary shadow-sm">
                    <option>Low to High</option>
                    <option>High to Low</option>
                </select>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
            <SellSidebar />
            </aside>

            <!-- Listing -->
            <div class="lg:col-span-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                <SellCard
                v-for="item in properties"
                :key="item.id"
                :item="item"
                />
            </div>
            </div>

        </div>
        </div>
    </section>
</template>

<script setup>
    import { ref } from 'vue'
    import { useHead } from '@vueuse/head'
    import SellSidebar from '@/components/sell/SellFilters.vue'
    import SellCard from '@/components/sell/SellCard.vue'
    import heroImage from '@/assets/banner/sell.png'
    const loaded = ref(true)

    const properties = [
        {
            id: 1,
            title: 'Serenity Condo Suite',
            price: 21000,
            image: 'https://images.unsplash.com/photo-1505691938895-1758d7feb511',
            badge: ['New', 'Featured'],
            type: 'Lodge'
        },
        {
            id: 2,
            title: 'Getaway Apartment',
            price: 1130,
            image: 'https://images.unsplash.com/photo-1502673530728-f79b4cab31b1',
            badge: [],
            type: 'Apartment'
        }
    ]

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
