<template>
    <!-- Page Header -->
    <section
        class="relative flex items-center bg-center bg-cover transition-opacity duration-700
            h-[200px] md:h-[250px] lg:h-[230px]"
        :class="loaded ? 'opacity-100' : 'opacity-0'"
        :style="{ backgroundImage: `url(${imageBaseUrl}${currentInfo.bannerAboutMe})` }"
        >
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" :style="{ color: currentInfo.colorAboutMe}">
            Tentang Kami
            </h1>

            <p class="text-sm" :style="{ color: currentInfo.colorAboutMe}">
            <router-link to="/" class="hover:underline">Home</router-link>
            <span class="mx-2">›</span>
            Tentang Kami
            </p>
        </div>
    </section>


    <section class="max-w-7xl mx-auto py-12">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Tentang Kami – ID ROOM</h2>
        <p class="text-gray-600 text-lg mb-8" v-html="currentInfo.aboutme"></p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow p-0 overflow-hidden flex items-center justify-center">
                <img :src="imageBaseUrl + currentInfo.image1aboutme" alt="Living Room" class="w-full h-[400px] object-cover" />
            </div>
            <div class="bg-white rounded-2xl shadow p-0 overflow-hidden flex items-center justify-center">
                <img :src="imageBaseUrl + currentInfo.image2aboutme" alt="Kitchen" class="w-full h-[400px] object-cover" />
            </div>
            <div class="bg-white rounded-2xl shadow p-0 overflow-hidden flex items-center justify-center">
                <img :src="imageBaseUrl + currentInfo.image3aboutme" alt="Bedroom" class="w-full h-[400px] object-cover" />
            </div>
        </div>
    </section>

    <section class="bg-gray-50 py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Visi &amp; Misi</h2>
                <div class="h-1 w-10 mx-auto bg-gradient-to-r from-teal-400 to-purple-400 mb-3 rounded"></div>
                <p class="text-gray-500 text-base md:text-lg">Landasan kami dalam memberikan layanan terbaik</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-start">
                    <h3 class="text-2xl font-bold text-black mb-4">Visi</h3>
                    <p class="text-gray-700 text-lg prose" v-html="currentInfo.visi"> </p>
                </div>
                <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-start">
                    <h3 class="text-2xl font-bold text-black mb-4">Misi</h3>
                    <p class="text-gray-700 text-lg prose" v-html="currentInfo.misi"> </p>
                </div>
            </div>
        </div>
    </section>

    <section class="relative w-full min-h-[480px] flex items-center justify-center bg-black/70" :style="`background-image:url('${imageBaseUrl + currentInfo.bgSectionAboutme}'); background-size:cover; background-position:center;`">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-center w-full max-w-7xl px-4">
            <div class="flex-1 flex flex-col items-start justify-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-6">{{ currentInfo.sectionTitleAboutme }}</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-[340px] h-[220px] mb-6">
                    <img :src="imageBaseUrl + currentInfo.imageSectionAboutme" alt="Living Room" class="w-full h-full object-cover" />
                </div>
            </div>
            <div class="flex-1 text-white">
                <p class="text-lg md:text-xl leading-relaxed text-white prose" v-html="currentInfo.descTitleAboutme"></p>
            </div>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Mitra Kami</h2>
                <div class="h-1 w-10 mx-auto bg-gradient-to-r from-teal-400 to-purple-400 mb-3 rounded"></div>
                <p class="text-gray-500 text-base md:text-lg">Berkolaborasi dengan yang terbaik di industri</p>
            </div>
            <div class="flex flex-wrap justify-center gap-6 mt-8">
                <div v-for="(partner, idx) in currentInfo.mitras" :key="idx" class="bg-gray-50 rounded-xl shadow-sm flex items-center justify-center w-85 h-20">
                    <img :src="imageBaseUrl + partner.image" :alt="partner.nama" class="h-20 object-contain" />
                </div>
            </div>
        </div>
    </section>

</template>

<script setup>
    import { useHead } from '@vueuse/head'
    import { ref } from 'vue'
    import { useInfoStore } from '@/store/info'
    import { computed } from 'vue'
    import { storeToRefs } from 'pinia'
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })

    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
    import heroImage from '@/assets/banner/rent.png'
    import About1 from '@/assets/about/about-us-01.webp'
    import About2 from '@/assets/about/about-us-02.webp'
    import About3 from '@/assets/about/about-us-03.jpg'
    import About  from '@/assets/about/about-us-04.jpg'
    import BgAbout from '@/assets/about/about-us-bg-img.jpg'
    import Partner from '@/assets/partner/1.webp'
    import Partner2 from '@/assets/partner/2.webp'
    import Partner3 from '@/assets/partner/3.webp'

    const loaded = ref(true)
    const partners = [
        { name: 'LiveChat', logo: Partner },
        { name: 'Headspace', logo: Partner2 },
        { name: 'Luno', logo: Partner3 },
    ]

    useHead({
        title: 'Tentang Kami - ID ROOM',
        meta: [
            {
                name: 'description',
                content: 'Pelajari lebih lanjut tentang ID ROOM, perusahaan jasa pengelolaan properti terpercaya di Indonesia yang menyediakan solusi lengkap untuk penjualan, sewa, dan manajemen aset properti Anda.'
            },
            {
                name: 'keywords',
                content: 'Tentang Kami, ID ROOM, Jasa Pengelolaan Properti, Penjualan Properti, Sewa Properti, Manajemen Aset, Interior Design, Renovasi Properti, Perawatan Unit, Solusi Properti'
            },
            {
                property: 'og:title',
                content: 'Tentang Kami - ID ROOM'
            },
            {
                property: 'og:description',
                content: 'Pelajari lebih lanjut tentang ID ROOM, perusahaan jasa pengelolaan properti terpercaya di Indonesia yang menyediakan solusi lengkap untuk penjualan, sewa, dan manajemen aset properti Anda.'
            },
            {
                property: 'og:image',
                content: heroImage
            }

        ]
    })
</script>