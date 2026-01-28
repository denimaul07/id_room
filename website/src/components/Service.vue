<template>
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Layanan Kami</h2>
                <div class="flex items-center justify-center gap-2 mb-2">
                    <span class="inline-block w-12 h-1 rounded bg-emerald-400 mr-1"></span>
                    <span class="inline-block w-6 h-1 rounded bg-purple-500"></span>
                </div>
                <p class="text-gray-500">Berbagai solusi desain interior untuk kebutuhan Anda</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="service in services" :key="service.title" class="bg-white rounded-xl shadow p-8 flex flex-col items-center text-center hover:shadow-lg transition">
                    <div class="mb-4 text-5xl text-yellow-400">
                        <component :is="service.icon" v-if="service.icon" class="w-12 h-12 mx-auto" />
                        <span v-else>{{ service.emoji }}</span>
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-2">{{ service.title }}</h3>
                    <p class="text-gray-500">{{ service.desc }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { HomeIcon, BuildingOffice2Icon, WrenchScrewdriverIcon, PaintBrushIcon, Cog6ToothIcon, WrenchIcon } from '@heroicons/vue/24/solid'
const services = [
    {
        title: 'Interior Rumah Edit',
        desc: 'Desain interior lengkap untuk rumah tinggal, mulai dari konsep hingga eksekusi, termasuk pemilihan material dan furnitur.',
        icon: HomeIcon,
    },
    {
        title: 'Interior Apartemen',
        desc: 'Solusi desain smart untuk ruang terbatas, memaksimalkan fungsi tanpa mengorbankan estetika dan kenyamanan.',
        icon: BuildingOffice2Icon,
    },
    {
        title: 'Konsultasi Desain',
        desc: 'Layanan konsultasi dengan desainer profesional untuk mendapatkan ide dan solusi desain sesuai kebutuhan Anda.',
        icon: WrenchScrewdriverIcon,
    },
    {
        title: 'Renovasi Interior',
        desc: 'Transformasi ruang yang ada menjadi lebih fungsional dan estetik tanpa perlu membongkar total.',
        icon: PaintBrushIcon,
    },
    {
        title: 'Furnitur Custom',
        desc: 'Pembuatan furnitur khusus yang dirancang sesuai kebutuhan dan gaya interior ruang Anda.',
        icon: Cog6ToothIcon,
    },
    {
        title: 'Renovasi Rumah',
        desc: 'Layanan perbaikan dan renovasi rumah mulai dari struktur, interior, hingga eksterior untuk menciptakan hunian yang nyaman dan estetis.',
        icon: WrenchIcon,
    },
]
</script>

<style scoped>
</style>
