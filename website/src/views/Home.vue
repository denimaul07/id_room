<template>
    <HeroSection />
    <HowItWorks />
    <ExplorePropertyType />
    <FeaturedProperties />
    <PopularCities />
    <FeaturedPropertiesRent />
    <StatsCounter />
    <Service />
    <Testimonials />
    <PricingPlans />
    <Faq />
</template>

<script setup>
import { useHead } from '@vueuse/head'
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
