<template>
  <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden border">
    <!-- Image & Badges -->
    <div class="relative">
      <img :src="imageBaseUrl + item.image" class="h-52 w-full object-cover" loading="lazy" />
      <div class="absolute top-3 left-3 flex gap-2">
        <span v-for="tag in badgeTags" :key="tag.label" class="px-2 py-1 text-xs rounded font-semibold text-white"
          :class="tag.className">
          {{ tag.label }}
        </span>
      </div>
      <div class="absolute bottom-3 left-3 text-white font-bold text-xl">
        {{ formattedPrice }}
        <span class="text-sm font-normal">/ {{ priceLabel }}</span>
      </div>
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
        {{ item.address }}
      </div>
      <div
        class="bg-gray-50 rounded-lg flex flex-wrap items-center gap-3 px-4 py-2 mb-3 text-gray-700 text-sm font-medium">
        <div class="flex items-center gap-1" v-for="facility in facilities" :key="facility.odata || facility.name">
          <i class="fas" :class="facility.icon"></i> {{ facility.name }}
        </div>
      </div>
      <div class="flex items-center justify-between pt-2">
        <div class="flex items-center gap-2">

        </div>
        <router-link :to="detailLink" class="bg-gray-900 text-white px-4 py-2 rounded-lg font-semibold text-sm">
          Book Now
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
const props = defineProps({
  item: Object
})

const detailLink = computed(() => {
  const odata = props.item?.slug
  return {
    path: '/rent-details',
    query: { odata }
  }
})

const badgeTags = computed(() => {
  if (Array.isArray(props.item?.badge) && props.item.badge.length) {
    return props.item.badge.map((label) => ({
      label,
      className: label === 'New'
        ? 'bg-pink-500'
        : label === 'Featured'
          ? 'bg-yellow-500'
          : 'bg-indigo-500'
    }))
  }

  const rentTags = []
  if (props.item?.price_per_night) {
    rentTags.push({ label: 'Harian', className: 'bg-emerald-600' })
  }
  if (props.item?.price_per_monthly) {
    rentTags.push({ label: 'Bulanan', className: 'bg-purple-600' })
  }
  if (props.item?.price_per_year) {
    rentTags.push({ label: 'Tahunan', className: 'bg-sky-600' })
  }
  if (!rentTags.length) {
    rentTags.push({ label: 'Di Sewakan', className: 'bg-gray-700' })
  }
  return rentTags
})

const priceInfo = computed(() => {
  const candidates = [
    { value: props.item?.price_per_night, label: 'Night' },
    { value: props.item?.price_per_monthly, label: 'Month' },
    { value: props.item?.price_per_year, label: 'Year' }
  ].filter((entry) => typeof entry.value === 'number' && entry.value > 0)

  if (!candidates.length) {
    return { value: 0, label: 'Rent' }
  }

  return candidates.reduce((min, current) => {
    return current.value < min.value ? current : min
  })
})

const formattedPrice = computed(() => {
  return Number(priceInfo.value.value || 0)
    .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
    .slice(0, -3)
})

const priceLabel = computed(() => priceInfo.value.label)

const facilities = computed(() => {
  if (!Array.isArray(props.item?.facilities)) return []
  return props.item.facilities
    .map((entry) => entry?.facility)
    .filter(Boolean)
})
</script>
