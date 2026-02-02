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
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
            Kebijakan Privasi
            </h1>

            <p class="text-white/80 text-sm">
            <router-link to="/" class="hover:underline">Home</router-link>
            <span class="mx-2">›</span>
            Kebijakan Privasi
            </p>
        </div>
    </section>
    
    <section class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div
                v-html="currentInfo.privacypolicy || '<p>Kebijakan privasi belum tersedia.</p>'"
                class="prose max-w-none"
            ></div>

        </div>
    </section>
</template>

<script setup>
    import { ref } from 'vue'
    import heroImage from '@/assets/banner/breadcrumb-bg.jpg'
    const loaded = ref(true)
    import { useInfoStore } from '@/store/info'
    import { computed } from 'vue'
    import { storeToRefs } from 'pinia'
    const { data: info } = storeToRefs(useInfoStore())
    
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })
    
</script>