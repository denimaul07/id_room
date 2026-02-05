<template>
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-8">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Layanan Kami</h2>
                <div class="flex items-center justify-center mb-2">
                    <span class="inline-block w-12 h-1 rounded" :style="{ backgroundColor: currentInfo.primaryColor }"></span>
                    <span class="inline-block w-12 h-1 rounded bg-black"></span>
                </div>
                <p class="text-gray-500">Berbagai solusi desain interior untuk kebutuhan Anda</p>
            </div>

            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div v-for="service in currentInfo.services_home" :key="service.title" class="bg-white rounded-xl shadow p-8 flex flex-col items-center text-center hover:shadow-lg transition">
                    <div class="mb-4 text-5xl text-yellow-400">
                        <i :class="service.icon" class="w-12 h-12 mx-auto" />
                    </div>
                    <h3 class="font-bold text-xl text-gray-900 mb-2">{{ service.title }}</h3>
                    <p class="text-gray-500">{{ service.desc }}</p>
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

<style scoped>
</style>
