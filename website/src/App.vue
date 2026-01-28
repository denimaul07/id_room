<template>
  <Navbar />
  <router-view />
  <Footer />
  <ScrollToTop />
</template>

<script setup>
  import Navbar from '@/components/layout/Navbar.vue'
  import Footer from '@/components/layout/Footer.vue'
  import ScrollToTop from '@/components/ScrollToTop.vue'
</script>
