<template>

    <section class="bg-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1">
                <span class="inline-block text-white text-base font-semibold rounded-lg px-4 py-1 mb-4" :style="{ backgroundColor: currentInfo.primaryColor }"> Mengapa Memilih Kami</span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-4 leading-tight">
                    {{ currentInfo.titleSectionRenov }}
                </h1>
                <p class="text-gray-500 text-lg mb-8 leading-relaxed" v-html="currentInfo.descSectionRenov"></p>
            </div>
            <div class="flex-1 min-w-[320px] flex justify-end w-full">
                <iframe
                    :src="currentInfo.urlRenov"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen
                    class="w-full h-72 md:h-96 rounded-lg shadow-lg"
                ></iframe>
    
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

</script>