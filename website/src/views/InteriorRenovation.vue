<template>
    <section class="relative h-[420px] md:h-[480px] flex items-center justify-center bg-cover bg-center"  :style="loaded ? { backgroundImage: `url(${heroImage})` } : {}">
        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 text-center px-4 max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-4">
            Interior & Renovasi Properti Profesional
            </h1>
            <p class="text-white/80 mb-6">
            Wujudkan hunian nyaman, fungsional, dan bernilai tinggi bersama tim profesional
            </p>
            <div class="flex justify-center gap-4">
            <button class="bg-[#d5bd7d] px-6 py-3 font-semibold text-white rounded hover:bg-[#c9ae63]">
                Konsultasi Gratis
            </button>
            <button class="border border-white text-white px-6 py-3 rounded hover:bg-white hover:text-black">
                Lihat Portfolio
            </button>
            </div>
        </div>
    </section>

    <WhyMe />
    <Service />
    <Portofolio />
    <Process />
    <Client />

</template>

<script setup>
    import { ref } from 'vue'
    import { useHead } from '@vueuse/head'
    import WhyMe from '@/components/renovation/WhyMe.vue'
    import heroImage from '@/assets/banner/renov.png'
    import Service from '@/components/renovation/Service.vue'
    import Portofolio from '@/components/renovation/Portofolio.vue'
    import Process from '@/components/renovation/Process.vue'
    import Client from '@/components/renovation/Client.vue'
    const loaded = ref(true)
</script>