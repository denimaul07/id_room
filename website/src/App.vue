<template>
  <Navbar />
  <div class="pt-16">
  <router-view />
  <Footer />
  <ScrollToTop />
  <FloatingHelp v-if="!isDashboard" />
  <AppToast />
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useInfoStore } from '@/store/info'
import Navbar from '@/components/layout/Navbar.vue'
import Footer from '@/components/layout/Footer.vue'
import ScrollToTop from '@/components/ScrollToTop.vue'
import FloatingHelp from '@/components/FloatingHelp.vue'
import AppToast from '@/components/AppToast.vue'

const infoStore = useInfoStore()
const route = useRoute()

const isDashboard = computed(() => route.path.startsWith('/dashboard'))

onMounted(() => {
  infoStore.fetch()
})
</script>
