<template>
    <section
        ref="heroRef"
        class="relative min-h-[70vh] md:min-h-[85vh] flex items-center bg-center bg-cover transition-opacity duration-700"
        :class="loaded ? 'opacity-100' : 'opacity-0'"
        :style="loaded ? { backgroundImage: `url(${heroImage})` } : {}"
    >
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 w-full">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col items-center text-center">
            <h1 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white mb-3">
                Temukan Penginapan & Properti Ideal Anda
            </h1>
            <p class="text-white/90 text-sm sm:text-base mb-6 max-w-2xl">
                Ribuan pilihan penginapan dan properti terbaik di Indonesia
            </p>

            <div class="flex flex-col sm:flex-row gap-3 mb-8 w-full sm:w-auto">
                <button class="bg-[#d5bd7d] px-6 py-3 text-white rounded-lg w-full sm:w-auto">
                Buy Property
                </button>
                <button class="bg-white px-6 py-3 text-gray-900 rounded-lg w-full sm:w-auto">
                Rent Property
                </button>
            </div>

            <SearchProperty />
            </div>
        </div>
        </div>
    </section>
</template>


<script setup>
    import { ref, onMounted, onBeforeUnmount } from 'vue'
    import SearchProperty from './SearchProperty.vue'
    import heroImage from '@/assets/banner/banner-bg.webp'

    const heroRef = ref(null)
    const loaded = ref(false)

    let observer

    onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
        if (entry.isIntersecting) {
            loaded.value = true
            observer.disconnect()
        }
        },
        { threshold: 0.2 }
    )

    if (heroRef.value) observer.observe(heroRef.value)
    })

    onBeforeUnmount(() => {
    if (observer) observer.disconnect()
    })
</script>

