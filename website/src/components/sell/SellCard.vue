<template>
  <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden border">
    <!-- Image & Badges -->
    <div class="relative">
      <img :src="imageUrl" class="h-52 w-full object-cover" loading="lazy" />
      <div class="absolute top-3 left-3 flex gap-2">
        <span v-for="b in item.badge" :key="b" class="px-2 py-1 text-xs rounded font-semibold text-white"
          :class="b === 'New' ? 'bg-pink-500' : b === 'Featured' ? 'bg-yellow-500' : 'bg-indigo-500'">
          {{ b }}
        </span>
      </div>
      <div class="absolute bottom-3 left-3 text-white font-bold text-xl">
        {{ formattedPrice }}
      </div>
      <button class="absolute top-3 right-3 bg-white/80 rounded-full p-2 shadow hover:bg-white">
        <i class="fas fa-heart text-gray-600"></i>
      </button>
    </div>

    <!-- Card Content -->
    <div class="p-4 pb-2">
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-1 text-yellow-400 text-sm">
          ★★★★★ <span class="text-gray-500 ml-1">Excellent</span>
        </div>
        <span class="inline-block bg-purple-600 text-white text-xs px-3 py-1 rounded font-semibold">
          {{ item.type }}
        </span>
      </div>
      <h3 class="font-bold text-lg mb-1">{{ item.properties || item.title }}</h3>
      <div class="flex items-center text-gray-500 text-sm mb-2">
        <i class="fas fa-map-marker-alt mr-1"></i>
        {{ item.address || '-' }}
      </div>
      <div
        class="bg-gray-50 rounded-lg flex flex-wrap items-center gap-3 px-4 py-2 mb-3 text-gray-700 text-sm font-medium">
        <div class="flex items-center gap-1" v-for="facility in facilities" :key="facility.odata || facility.name">
          <i class="fas" :class="facility.icon"></i> {{ facility.name }}
        </div>
      </div>
      <router-link :to="detailLink" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-semibold text-sm">
        Buy Now
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const fallbackImage = 'https://images.unsplash.com/photo-1568605114967-8130f3a36994'

const props = defineProps({
  item: Object
})

const detailLink = computed(() => {
  const odata = props.item?.odata || props.item?.id
  return {
    path: '/sell-details',
    query: { odata }
  }
})

const imageUrl = computed(() => {
  if (!props.item?.image) return fallbackImage
  return props.item.image.startsWith('http') ? props.item.image : imageBaseUrl + props.item.image
})

const formattedPrice = computed(() => {
  const price = Number(props.item?.sale_price || props.item?.price || 0)
  return price
    ? price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)
    : 'Rp0'
})

const listedOn = computed(() => {
  const createdAt = props.item?.created_at
  return createdAt ? new Date(createdAt).toLocaleDateString('id-ID') : '-'
})

const facilities = computed(() => {
  if (!Array.isArray(props.item?.facilities)) return []
  return props.item.facilities
    .map((entry) => entry?.facility)
    .filter(Boolean)
})
</script>
