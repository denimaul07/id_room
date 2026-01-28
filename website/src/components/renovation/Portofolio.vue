<template>
    <section class="portfolio-section">
        <div class="container">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-1 tracking-tight">
                    <span class="font-black text-teal-700 text-2xl align-middle mx-1">~</span>
                    Portofolio 
                    <span class="text-[#d5bd7d] font-extrabold"> Kami</span>
                    <span class="font-black text-teal-700 text-2xl align-middle mx-1">~</span>
                </h2>
                <p class="text-gray-400 text-base md:text-lg mt-1">Portofolio proyek properti terbaik yang telah kami realisasikan</p>
            </div>
            <div class="flex flex-wrap justify-center gap-8 min-h-[420px] relative">
                <div
                    v-for="(img, idx) in images"
                    :key="idx"
                    :class="[
                        'bg-white rounded-2xl shadow-xl overflow-hidden flex items-center justify-center relative transition-transform duration-300',
                        'w-80 h-56',
                        idx % 2 === 0 ? 'rotate-[-6deg] scale-105 z-20' : 'rotate-3 scale-100 z-10',
                        idx % 3 === 0 ? 'mt-8' : '',
                        idx % 4 === 0 ? 'mb-8' : ''
                    ]"
                >
                    <img
                        :src="img.src"
                        :alt="img.alt"
                        class="w-full h-full object-cover rounded-xl transition-transform duration-300 cursor-pointer hover:scale-110"
                        @click="openModal(img.src, img.alt)"
                    />
                </div>
                <!-- Modal -->
                <div v-if="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
                    <div class="relative max-w-3xl w-full mx-4">
                        <img :src="modalImg" :alt="modalAlt" class="rounded-2xl w-full max-h-[80vh] object-contain shadow-2xl animate-fadein" />
                        <button @click="closeModal" class="absolute top-2 right-2 bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 shadow-lg transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script setup>
import { ref } from 'vue';
import renov from '@/assets/banner/renov.png';
import banner from '@/assets/banner/banner-bg.webp';

const images = [
        { src: renov, alt: 'Renovasi' },
        { src: banner, alt: 'Banner' },
        { src: renov, alt: 'Renovasi' },
        { src: banner, alt: 'Banner' },
        { src: renov, alt: 'Renovasi' },
        { src: banner, alt: 'Banner' },
        { src: renov, alt: 'Renovasi' },
        { src: banner, alt: 'Banner' },
];

const modalOpen = ref(false);
const modalImg = ref('');
const modalAlt = ref('');

function openModal(src, alt) {
    modalImg.value = src;
    modalAlt.value = alt;
    modalOpen.value = true;
    document.body.style.overflow = 'hidden';
}
function closeModal() {
    modalOpen.value = false;
    document.body.style.overflow = '';
}
</script>

<style scoped>
.portfolio-section {
    background: #101820;
    padding: 56px 0 64px 0;
}
.container {
    margin: 0 auto;
    padding: 0 16px;
}
@keyframes fadein {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fadein {
    animation: fadein 0.3s;
}
</style>
