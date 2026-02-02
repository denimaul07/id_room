<template>
  <Navbar />
  <router-view />
  <Footer />
  <ScrollToTop />
  <FloatingHelp />

</template>

<script setup>
  import { onMounted } from 'vue'
  import { useInfoStore } from '@/store/info'
  import Navbar from '@/components/layout/Navbar.vue'
  import Footer from '@/components/layout/Footer.vue'
  import ScrollToTop from '@/components/ScrollToTop.vue'
  import FloatingHelp from '@/components/FloatingHelp.vue'
  
  const infoStore = useInfoStore()

  onMounted(() => {
    infoStore.fetch()
  })
</script>
