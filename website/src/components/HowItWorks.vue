<template>
    <section class="bg-slate-100 py-20">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Title -->
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Mengapa Memilih idROOM?</h2>
                <p class="text-gray-500 max-w-xl mx-auto">
                    Kami memberikan pengalaman terbaik untuk kebutuhan akomodasi dan properti Anda
                </p>
            </div>
            <!-- Fitur -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="flex flex-col items-center text-center" v-for="feature in currentInfo.services_home_all" :key="feature.title">
                    <div class="mb-2 text-6xl" :style="{color: currentInfo.primaryColor}">
                        <i :class="feature.icon" class="w-24 h-24 mx-auto" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ feature.title }}</h3>
                    <p class="text-gray-600 max-w-xs">
                        {{ feature.desc }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>


<script setup>
    import { computed } from 'vue'
    import { useInfoStore } from '@/store/info'
    import { storeToRefs } from 'pinia'
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })
</script>