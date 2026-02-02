<template>
    <!-- Skeleton -->
    <HeroSkeleton v-if="!currentInfo" />

    <!-- Hero -->
    <section
        v-else
        ref="heroRef"
        class="relative min-h-[70vh] md:min-h-[85vh] flex items-center overflow-hidden"
    >
        <div
            class="absolute inset-0 bg-center bg-cover transition-transform duration-[2000ms] ease-out"
            :class="loaded ? 'scale-100' : 'scale-110'"
            :style="{ backgroundImage: `url(${imageBaseUrl}${currentInfo.imageBanner})` }"
        ></div>
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 w-full">
            <div class="max-w-6xl mx-auto px-4 sm:px-6">
                <div class="flex flex-col items-center text-center mb-3">
                    <h1
                        :style="{ color: currentInfo.colorTitleBanner }"
                        class="
                            text-2xl sm:text-3xl md:text-5xl font-bold mb-3
                            transition-all duration-700 ease-out
                        "
                        :class="loaded
                            ? 'opacity-100 translate-y-0'
                            : 'opacity-0 translate-y-8'
                        "
                        >
                    {{ currentInfo.titleBanner }}
                    </h1>

                    <p :style="{ color: colorWithOpacity(currentInfo.colorTitleBanner, 0.9) }"
                        class="
                            text-sm sm:text-base mb-6 max-w-2xl
                            transition-all duration-700 delay-150 ease-out
                        "
                        :class="loaded
                            ? 'opacity-100 translate-y-0'
                            : 'opacity-0 translate-y-8'
                        "
                        >
                        {{ currentInfo.subTitleBanner }}
                    </p>

                    <!-- Buttons -->
                    <div
                        class="flex flex-col sm:flex-row gap-3 justify-center transition-all duration-700 delay-300"
                        :class="loaded ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                    >
                        <button
                        :style="{
                            '--btn-bg': currentInfo.primaryColor,
                            '--btn-bg-hover': currentInfo.primaryColorHover,
                            '--btn-text-color': currentInfo.primaryTextColor
                        }"
                        class="px-6 py-3 rounded-lg bg-[var(--btn-bg)] text-[var(--btn-text-color)] hover:bg-[var(--btn-bg-hover)] transition-colors duration-300"
                        @click="goToBuy"
                        >
                        Buy Property
                        </button>

                            <button 
                            :style="{
                                '--btn-bg': currentInfo.secondColor,
                                '--btn-bg-hover': currentInfo.secondColorHover,
                                '--btn-text-color': currentInfo.secondTextColor
                            }"
                            class="px-6 py-3 rounded-lg bg-[var(--btn-bg)] text-[var(--btn-text-color)] hover:bg-[var(--btn-bg-hover)] transition-colors duration-300"
                            @click="goToRent"
                        >
                        Rent Property 
                        </button>
                    </div>

                    
                </div>

                <!-- Search Property Wrapper -->
                <div class="
                        w-full
                        transition-all duration-700 delay-500 ease-out
                    "
                    :class="loaded
                        ? 'opacity-100 translate-y-0'
                        : 'opacity-0 translate-y-6'
                    "
                    >
                    <SearchProperty />
                </div>

            </div>
            
        </div>
    </section>
</template>

<script setup>
    import { ref, onMounted, onBeforeUnmount, computed, watch } from 'vue'
    import { useRouter } from 'vue-router'
    import { storeToRefs } from 'pinia'
    import { useInfoStore } from '@/store/info'
    import SearchProperty from './SearchProperty.vue'
    import HeroSkeleton from './HeroSkeleton.vue'
    import { colorWithOpacity } from '@/composables/useColor'

    const router = useRouter()
    const heroRef = ref(null)
    const loaded = ref(false)
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    const goToBuy = () => router.push('/jual-properti')
    const goToRent = () => router.push('/sewa-properti')

    const preloadImage = (url) => {
    if (!url) return
    if (document.querySelector(`link[href="${url}"]`)) return

    const link = document.createElement('link')
    link.rel = 'preload'
    link.as = 'image'
    link.href = url
    document.head.appendChild(link)
    }

    watch(currentInfo, (val) => {
    if (val?.imageBanner) {
        preloadImage(imageBaseUrl + val.imageBanner)
    }
    })

    let observer = null

    onMounted(async () => {

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
