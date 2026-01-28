<template>
    <section class="bg-[#fafbfc] py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-black mb-1 tracking-tight">
                <span class="font-black text-teal-700 text-2xl align-middle mx-1">~</span>
                Layanan
                <span class="text-[#d5bd7d] font-extrabold"> Kami</span>
                <span class="font-black text-teal-700 text-2xl align-middle mx-1">~</span>
            </h2>
            <p class="text-gray-400 text-base md:text-lg mt-1">Berbagai solusi desain interior untuk kebutuhan Anda</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <div
                v-for="(service, idx) in services"
                :key="idx"
                class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-shadow duration-200 p-10 pt-12 text-center flex flex-col items-center"
            >
                <div class="text-[2.5rem] text-[#d6b96b] mb-5 flex items-center justify-center h-14">
                <component :is="service.icon" />
                </div>
                <h3 class="text-xl font-semibold mb-2 text-gray-900">{{ service.title }}</h3>
                <p class="text-gray-600 text-base">{{ service.desc }}</p>
            </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { defineComponent } from 'vue'
import { HomeIcon, BuildingIcon, ToolsIcon, PaintRollerIcon, SofaIcon, WrenchIcon } from './ServiceIcons.js'

const services = [
  {
    icon: HomeIcon,
    title: 'Interior Rumah Edit',
    desc: 'Desain interior lengkap untuk rumah tinggal, mulai dari konsep hingga eksekusi, termasuk pemilihan material dan furnitur.'
  },
  {
    icon: BuildingIcon,
    title: 'Interior Apartemen',
    desc: 'Solusi desain smart untuk ruang terbatas, memaksimalkan fungsi tanpa mengorbankan estetika dan kenyamanan.'
  },
  {
    icon: ToolsIcon,
    title: 'Konsultasi Desain',
    desc: 'Layanan konsultasi dengan desainer profesional untuk mendapatkan ide dan solusi desain sesuai kebutuhan Anda.'
  },
  {
    icon: PaintRollerIcon,
    title: 'Renovasi Interior',
    desc: 'Transformasi ruang yang ada menjadi lebih fungsional dan estetik tanpa perlu membongkar total.'
  },
  {
    icon: SofaIcon,
    title: 'Furnitur Custom',
    desc: 'Pembuatan furnitur khusus yang dirancang sesuai kebutuhan dan gaya interior ruang Anda.'
  },
  {
    icon: WrenchIcon,
    title: 'Renovasi Rumah',
    desc: 'Layanan perbaikan dan renovasi rumah mulai dari struktur, interior, hingga eksterior untuk menciptakan hunian yang nyaman dan estetis.'
  }
]
</script>


