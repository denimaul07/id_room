<template>
  <section class="py-16 bg-[#0d1a2b] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4">

        <!-- Header -->
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">Testimonials</h2>
            <div class="flex justify-center gap-2 mb-2">
            <span class="w-12 h-1 bg-emerald-400 rounded"></span>
            <span class="w-6 h-1 bg-purple-500 rounded"></span>
            </div>
            <p class="text-white/80">What our happy client says</p>
        </div>

        <!-- Slider -->
        <div class="relative flex justify-center">
            <div
            ref="slider"
            class="flex gap-6 overflow-x-hidden scroll-smooth w-full max-w-6xl"
            >
            <div
                v-for="(item, i) in loopedTestimonials"
                :key="i"
                class="testimonial-card bg-white rounded-2xl shadow p-8 text-center flex-shrink-0 w-[340px]"
            >
                <img :src="item.avatar" class="w-16 h-16 rounded-full mx-auto mb-4" />
                <p class="text-gray-700 mb-4">{{ item.message }}</p>
                <div class="font-bold text-gray-900 mb-2">{{ item.name }}</div>
                <div class="flex justify-center gap-1">
                <svg v-for="n in 5" :key="n" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118l-3.385-2.46c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/>
                </svg>
                </div>
            </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="flex flex-col items-center mt-8 gap-4">
            <div class="flex gap-2">
            <span
                v-for="i in totalPages"
                :key="i"
                @click="goTo(i)"
                :class="[
                'w-6 h-2 rounded-full cursor-pointer transition-all',
                currentPage === i ? 'bg-emerald-400' : 'bg-gray-400'
                ]"
            ></span>
            </div>

            <div class="flex gap-3">
            <button @click="prev" class="w-10 h-10 rounded-full bg-white shadow">‹</button>
            <button @click="next" class="w-10 h-10 rounded-full bg-white shadow">›</button>
            </div>
        </div>

        </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'


const testimonials = [
  { name: 'Karen Maria', avatar: 'https://randomuser.me/api/portraits/women/65.jpg', message: 'From browsing to booking, everything felt effortless.' },
  { name: 'John Carter', avatar: 'https://randomuser.me/api/portraits/men/32.jpg', message: 'Finding the perfect home was a breeze.' },
  { name: 'Daniel Cooper', avatar: 'https://randomuser.me/api/portraits/men/44.jpg', message: 'Super easy and stress-free experience.' },
  { name: 'Lily Brooks', avatar: 'https://randomuser.me/api/portraits/women/68.jpg', message: 'User-friendly and smooth booking process.' }
]

const slider = ref(null)
const currentPage = ref(1)
const cardsPerPage = 3
const gap = 24
let autoplayTimer = null

/* clone for infinite loop */
const loopedTestimonials = computed(() => [
  ...testimonials.slice(-cardsPerPage),
  ...testimonials,
  ...testimonials.slice(0, cardsPerPage)
])

const totalPages = Math.ceil(testimonials.length / cardsPerPage)

const getPageWidth = () => {
  const card = slider.value.querySelector('.testimonial-card')
  return (card.offsetWidth + gap) * cardsPerPage
}

const scrollToPage = (page, smooth = true) => {
  slider.value.scrollTo({
    left: getPageWidth() * page,
    behavior: smooth ? 'smooth' : 'auto'
  })
  currentPage.value = page
}

const next = () => {
  if (currentPage.value < totalPages) {
    scrollToPage(currentPage.value + 1)
  } else {
    scrollToPage(totalPages + 1)
    setTimeout(() => scrollToPage(1, false), 400)
  }
}

const prev = () => {
  if (currentPage.value > 1) {
    scrollToPage(currentPage.value - 1)
  } else {
    scrollToPage(0)
    setTimeout(() => scrollToPage(totalPages, false), 400)
  }
}

const goTo = (page) => scrollToPage(page)

const startAutoplay = () => {
  autoplayTimer = setInterval(next, 3500)
}

onMounted(async () => {
  await nextTick()
  scrollToPage(1, false)
  startAutoplay()
})

onBeforeUnmount(() => {
  clearInterval(autoplayTimer)
})

onBeforeUnmount(() => clearInterval(autoplayTimer))
</script>
