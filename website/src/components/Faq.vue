<template>
    <section class="bg-[#f7f8fa] py-16">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">Frequently Asked Questions</h2>
                <div class="h-1 w-10 mx-auto bg-gradient-to-r from-teal-400 to-purple-400 mb-3 rounded"></div>
                <p class="text-gray-500 text-base md:text-lg">Temukan jawaban untuk pertanyaan yang sering diajukan</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden flex items-center justify-center">
                    <img src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=800&q=80" alt="FAQ House" class="w-full h-[520px] object-cover" />
                </div>
                <div class="bg-white rounded-2xl shadow-lg p-8">
                    <div>
                        <h3 class="font-bold text-xl text-gray-900 mb-4">General FAQ’s</h3>
                        <div
                            v-for="(item, index) in items"
                            :key="index"
                            class="border rounded-lg mb-3 overflow-hidden"
                            >
                            <!-- Header -->
                            <button
                                @click="toggle(index)"
                                class="w-full flex justify-between items-center px-5 py-4 text-left font-semibold bg-white hover:bg-gray-50"
                            >
                                <span>{{ item.title }}</span>
                                <svg
                                class="w-5 h-5 transition-transform duration-300"
                                :class="active === index ? 'rotate-180' : ''"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Content -->
                            <div
                                class="transition-all duration-300 ease-in-out overflow-hidden"
                                :class="active === index ? 'max-h-40 opacity-100' : 'max-h-0 opacity-0'"
                            >
                                <div class="px-5 pb-4 text-gray-600">
                                {{ item.content }}
                                </div>
                            </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { ref } from 'vue'

const active = ref(0)

const items = [
    {
        title: 'Kenapa memilih IDROOM?',
        content: 'Kami menyediakan properti terverifikasi dengan proses yang transparan dan mudah.',
    },
    {
        title: 'Apakah harga transparan?',
        content: 'Ya, semua harga ditampilkan secara jelas tanpa biaya tersembunyi.',
    },
    {
        title: 'Bagaimana proses kerja sama?',
        content: 'Mulai dari konsultasi, survey, hingga serah terima properti.',
    },
]

const toggle = (index) => {
    active.value = active.value === index ? null : index
}
</script>

