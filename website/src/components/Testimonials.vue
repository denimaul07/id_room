<template>
  <section class="py-16 bg-[#0d1a2b] overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">

      <!-- Header -->
      <div class="text-center mb-10">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">
          Testimonials
        </h2>
        <div class="flex justify-center gap-2 mb-2">
          <span class="w-12 h-1 bg-emerald-400 rounded"></span>
          <span class="w-6 h-1 bg-purple-500 rounded"></span>
        </div>
        <p class="text-white/80">What our happy client says</p>
      </div>

      <!-- Slider -->
      <div class="relative overflow-hidden">
        <div
          class="flex gap-6 transition-transform duration-500 ease-out"
          :style="sliderStyle"
        >
          <div
            v-for="(item, i) in realData"
            :key="i"
            class="testimonial-card bg-white rounded-2xl shadow p-8 text-center flex-shrink-0 w-[340px]"
          >
            <img
              :src="item.image ? imageBaseUrl + item.image : '/default-avatar.png'"
              class="w-16 h-16 rounded-full mx-auto mb-4 object-cover"
            />

            <p class="text-gray-700 mb-4 leading-relaxed">
              {{ item.desc || '-' }}
            </p>

            <div class="font-bold text-gray-900 mb-2">
              {{ item.nama || '-' }}
            </div>

            <div class="text-gray-500 mb-4">
              {{ item.location || '-' }}
            </div>

            <div class="flex justify-center gap-1">
              <svg
                v-for="n in (item.rate || 5)"
                :key="n"
                class="w-5 h-5 text-yellow-400"
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path
                  d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"
                />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <div class="flex flex-col items-center mt-8 gap-4">
        <!-- Dots -->
        <div class="flex gap-2">
          <span
            v-for="i in totalPages"
            :key="i"
            @click="goTo(i - 1)"
            :class="[
              'w-6 h-2 rounded-full cursor-pointer transition-all',
              page === i - 1 ? 'bg-emerald-400' : 'bg-gray-400'
            ]"
          ></span>
        </div>

        <!-- Arrows -->
        <div class="flex gap-3">
          <button class="nav-btn" @click="prev">‹</button>
          <button class="nav-btn" @click="next">›</button>
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
  import { useInfoStore } from '@/store/info'
  import { storeToRefs } from 'pinia'

  const { data: info } = storeToRefs(useInfoStore())

  const cardsPerPage = 3
  const cardWidth = 340
  const gap = 24

  const page = ref(0)
  let autoplayTimer = null

  const currentInfo = computed(() => info.value?.[0] ?? {})
  const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

  const realData = computed(() =>
    Array.isArray(currentInfo.value.testimoni_home)
      ? currentInfo.value.testimoni_home
      : []
  )

  const totalPages = computed(() => {
    if (realData.value.length <= cardsPerPage) return 1
    return realData.value.length - cardsPerPage + 1
  })


  const sliderStyle = computed(() => ({
    transform: `translateX(-${page.value * (cardWidth + gap) * cardsPerPage}px)`
  }))

  const next = () => {
    page.value = (page.value + 1) % totalPages.value
  }

  const prev = () => {
    page.value =
      page.value === 0 ? totalPages.value - 1 : page.value - 1
  }

  const goTo = (p) => {
    page.value = p
  }

  const startAutoplay = () => {
    autoplayTimer = setInterval(next, 3500)
  }

  onMounted(startAutoplay)
  onBeforeUnmount(() => clearInterval(autoplayTimer))
</script>

<style scoped>
  .testimonial-card {
    transition: transform 0.35s ease, box-shadow 0.35s ease;
  }

  .testimonial-card:hover {
    transform: translateY(-6px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
  }

  .nav-btn {
    width: 40px;
    height: 40px;
    border-radius: 9999px;
    background: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    font-size: 20px;
    font-weight: bold;
    transition: all 0.25s ease;
  }

  .nav-btn:hover {
    background: #10b981;
    color: white;
    transform: scale(1.1);
  }
</style>
