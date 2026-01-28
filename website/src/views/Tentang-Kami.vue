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
            Tentang Kami
            </h1>

            <p class="text-black/80 text-sm">
            <router-link to="/" class="hover:underline">Home</router-link>
            <span class="mx-2">›</span>
        ]    Tentang Kami
            </p>
        </div>
    </section>


    <section class="max-w-7xl mx-auto py-12">
        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">Tentang Kami – ID ROOM</h2>
        <p class="text-gray-600 text-lg mb-8">
            ID ROOM adalah perusahaan jasa pengelolaan properti yang melayani penjualan, sewa, dan manajemen aset mulai dari apartemen, villa, rumah, ruko, hingga gudang. Kami hadir untuk memberikan solusi lengkap bagi pemilik maupun penyewa dengan layanan yang cepat, aman, dan terpercaya.



            Selain bergerak di bidang properti, ID ROOM juga memiliki workshop dan tim profesional yang berpengalaman dalam interior design, renovasi, hingga perawatan unit. Dengan tenaga ahli yang terlatih, kami memastikan setiap properti tidak hanya terkelola dengan baik, tetapi juga memiliki nilai tambah yang lebih tinggi.



            Sebagai mitra yang dapat diandalkan, ID ROOM berkomitmen menghadirkan layanan one-stop solution untuk semua kebutuhan properti Anda – mulai dari pengelolaan, pemasaran, penyewaan, hingga renovasi.



            Bersama ID ROOM, properti Anda bukan hanya sekadar aset, tetapi juga investasi yang berkembang.


        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-2xl shadow p-0 overflow-hidden flex items-center justify-center">
                <img :src="About1" alt="Living Room" class="w-full h-[400px] object-cover" />
            </div>
            <div class="bg-white rounded-2xl shadow p-0 overflow-hidden flex items-center justify-center">
                <img :src="About2" alt="Kitchen" class="w-full h-[400px] object-cover" />
            </div>
            <div class="bg-white rounded-2xl shadow p-0 overflow-hidden flex items-center justify-center">
                <img :src="About3" alt="Bedroom" class="w-full h-[400px] object-cover" />
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
                    <h3 class="text-2xl font-bold text-teal-600 mb-4">Visi</h3>
                    <p class="text-gray-700 text-lg">
                        Menjadi perusahaan properti terpercaya di Indonesia yang menghadirkan solusi lengkap dalam pengelolaan, penjualan, penyewaan, serta pengembangan properti, dengan standar layanan profesional dan inovatif.
                    </p>
                </div>
                <div class="bg-white rounded-2xl shadow p-8 flex flex-col items-start">
                    <h3 class="text-2xl font-bold text-purple-600 mb-4">Misi</h3>
                    <ul class="list-disc pl-5 space-y-2 text-gray-700 text-lg">
                        <li>Memberikan layanan pengelolaan properti profesional untuk meningkatkan nilai dan kenyamanan aset klien.</li>
                        <li>Menyediakan solusi penjualan dan sewa properti (apartemen, villa, rumah, ruko, gudang) yang aman, cepat, dan transparan.</li>
                        <li>Menghadirkan workshop & tim interior yang kreatif dalam mewujudkan desain, renovasi, dan perawatan properti dengan kualitas terbaik.</li>
                        <li>Membangun hubungan jangka panjang dengan klien melalui layanan yang berintegritas, terpercaya, dan customer-oriented.</li>
                        <li>Terus berinovasi dengan memanfaatkan teknologi digital dan strategi pemasaran modern untuk menjangkau pasar yang lebih luas.</li>   
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="relative w-full min-h-[480px] flex items-center justify-center bg-black/70" :style="`background-image:url('${BgAbout}'); background-size:cover; background-position:center;`">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-center w-full max-w-7xl px-4">
            <div class="flex-1 flex flex-col items-start justify-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-6">Siap Booking Tempat?</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-[340px] h-[220px] mb-6">
                    <img :src="About" alt="Living Room" class="w-full h-full object-cover" />
                </div>
            </div>
            <div class="flex-1 text-white">
                <h3 class="text-xl md:text-1xl font-bold mb-3">Temukan properti impian Anda dan amankan ruang ideal Anda dengan proses booking yang mudah, cepat, dan tanpa ribet hari ini.</h3>
                <p class="text-base md:text-lg mb-3">Jelajahi berbagai listing properti terverifikasi yang disesuaikan dengan gaya hidup dan anggaran Anda. Baik Anda mencari apartemen mewah di pusat kota atau rumah keluarga yang nyaman di pinggiran, platform kami menyediakan pengalaman booking yang mulus dan terpercaya. Nikmati transaksi yang aman, konfirmasi booking instan. Dengan fitur yang ramah pengguna dan listing terpercaya, ID Room memudahkan Anda menemukan dan mengamankan ruang yang sempurna.</p>
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
                <div v-for="(partner, idx) in partners" :key="idx" class="bg-gray-50 rounded-xl shadow-sm flex items-center justify-center w-85 h-20">
                    <img :src="partner.logo" :alt="partner.name" class="h-20 object-contain" />
                </div>
            </div>
        </div>
    </section>

</template>

<script setup>
    import { useHead } from '@vueuse/head'
    import { ref } from 'vue'
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
</script>