<template>
    <section class="bg-[#fafbfc] py-12 md:py-16">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-3xl md:text-4xl font-extrabold text-black mb-1 tracking-tight">
                    <span class="font-black text-teal-700 text-2xl align-middle mx-1">~</span>
                    Proses Kerja
                    <span class="text-[#d5bd7d] font-extrabold"> Kami</span>
                    <span class="font-black text-teal-700 text-2xl align-middle mx-1">~</span>
                </h2>
                <p class="text-gray-400 text-base md:text-lg mt-1">Langkah-langkah untuk mewujudkan ruang impian Anda</p>
            </div>


            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 justify-center items-stretch mt-8">
                <div
                    v-for="(item, index) in currentInfo.processwork"
                    :key="index"
                    :class="`rounded-xl p-6 flex flex-col shadow-sm ${randomBg(index)}`"
                >
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-9 h-9 flex items-center justify-center rounded-full" :class="randomBg(index)">
                            <fa :class="[item.icon, `w-5 h-5 ${randomText(index)}`]" />
                        </div>
                        <h3 class="font-bold text-lg text-gray-900">{{ item.title }}</h3>
                    </div>
                    <p class="text-gray-700 text-base">{{ item.desc }}</p>
                </div>
            </div>
        </div>
    </section>

</template>

<script setup>
    import { ref, computed } from 'vue'
    import { useInfoStore } from '@/store/info'
    import { storeToRefs } from 'pinia'
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })
    const bgColors = [
        { bg: 'bg-orange-100', text: 'text-orange-500' },
        { bg: 'bg-violet-100', text: 'text-violet-500' },
        { bg: 'bg-pink-100', text: 'text-pink-500' },
        { bg: 'bg-teal-100', text: 'text-teal-700' },
        { bg: 'bg-blue-100', text: 'text-blue-500' },
        { bg: 'bg-green-100', text: 'text-green-500' },
        { bg: 'bg-yellow-100', text: 'text-yellow-500' },
        { bg: 'bg-red-100', text: 'text-red-500' }
    ]
    function randomBg(idx) {
        return bgColors[idx % bgColors.length].bg;
    }
    function randomText(idx) {
        return bgColors[idx % bgColors.length].text;
    }
</script>
