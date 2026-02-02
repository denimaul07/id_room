<template>
  <button
    v-show="showScrollTop"
    @click="scrollToTop"
    class="fixed bottom-8 left-8 z-50 w-12 h-12 rounded-full bg-[#d5bd7d] text-white flex items-center justify-center shadow-lg hover:bg-[#bfa94f] transition-all duration-200"
    aria-label="Scroll to top"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" /></svg>
  </button>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
const showScrollTop = ref(false)
function handleScroll() {
  showScrollTop.value = window.scrollY > 300
}
function scrollToTop() {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})
onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
</style>
