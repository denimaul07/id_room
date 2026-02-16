<template>
    <HeroSection v-if="info" />
    <div class="reveal-section" :ref="setSectionRef">
        <HowItWorks />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <ExplorePropertyType />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <FeaturedPropertiesRent />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <PopularCities />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <FeaturedProperties />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <StatsCounter />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <Service />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <Testimonials />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <PricingPlans />
    </div>
    <div class="reveal-section" :ref="setSectionRef">
        <Faq />
    </div>
</template>

<script setup>
import { useHead } from '@vueuse/head'
import { onBeforeUnmount, onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
const route = useRoute()
import { storeToRefs } from 'pinia'
import { useInfoStore } from '@/store/info'

import HeroSection from '@/components/home/HeroSection.vue'
import HowItWorks from '@/components/HowItWorks.vue'
import ExplorePropertyType from '@/components/ExplorePropertyType.vue'
import FeaturedProperties from '@/components/FeaturedProperties.vue'
import FeaturedPropertiesRent from '@/components/FeaturedPropertiesRent.vue'
import StatsCounter from '@/components/StatsCounter.vue'
import PopularCities from '@/components/PopularCities.vue'
import Service from '@/components/Service.vue'
import Testimonials from '@/components/Testimonials.vue'
import PricingPlans from '@/components/PricingPlans.vue'
import Faq from '@/components/Faq.vue'
import Swal from 'sweetalert2'

if (route.query.session === 'expired') {
    Swal.fire({
        icon: 'warning',
        title: 'Session Expired',
        text: 'Sesi Anda telah berakhir. Silakan login kembali untuk melanjutkan.',
        confirmButtonColor: '#5cb85c',
        confirmButtonText: 'OK',
    })
}

const { data: info, loading } = storeToRefs(useInfoStore())
const sectionRefs = ref([])
let observer = null
const animationVariants = ['reveal-fade-up', 'reveal-fade-left', 'reveal-fade-right', 'reveal-zoom-in']

const setSectionRef = (el) => {
    if (el && !sectionRefs.value.includes(el)) {
        const anim = animationVariants[Math.floor(Math.random() * animationVariants.length)]
        el.dataset.revealAnim = anim
        el.classList.add(anim)
        sectionRefs.value.push(el)
    }
}

const setupObserver = () => {
    if (typeof window === 'undefined') return
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible')
                    observer.unobserve(entry.target)
                }
            })
        },
        {
            threshold: 0.2,
            rootMargin: '0px 0px -10% 0px'
        }
    )

    sectionRefs.value.forEach((el) => observer.observe(el))
}

onMounted(() => {
    setupObserver()
})

onBeforeUnmount(() => {
    if (observer) {
        observer.disconnect()
        observer = null
    }
})



useHead({
    title: 'IDROOM | Sewa Apartemen & Jual Properti di Indonesia',
    meta: [
        {
            name: 'description',
            content:
                'IDROOM adalah platform terpercaya untuk sewa apartemen dan penjualan properti di berbagai kota di Indonesia dengan pengelolaan profesional dan standar hunian terbaik.'
        },
        {
            name: 'keywords',
            content:
                'sewa apartemen, jual properti, properti Indonesia, apartemen Indonesia, manajemen properti, sewa hunian, IDROOM'
        },
        {
            property: 'og:title',
            content: 'IDROOM | Sewa Apartemen & Jual Properti'
        },
        {
            property: 'og:description',
            content:
                'Temukan hunian nyaman dan aman di berbagai kota di Indonesia bersama IDROOM. Solusi sewa dan pengelolaan properti profesional.'
        },
        {
            property: 'og:type',
            content: 'website'
        }
    ]
})

</script>

<style scoped>
.reveal-section {
    opacity: 0;
    transition: opacity 0.6s ease, transform 0.6s ease;
    will-change: opacity, transform;
}

.reveal-fade-up {
    transform: translateY(24px);
}

.reveal-fade-left {
    transform: translateX(-24px);
}

.reveal-fade-right {
    transform: translateX(24px);
}

.reveal-zoom-in {
    transform: scale(0.96);
}

.reveal-section.is-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (prefers-reduced-motion: reduce) {
    .reveal-section {
        opacity: 1;
        transform: none;
        transition: none;
    }
}
</style>
