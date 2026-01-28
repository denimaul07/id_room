<template>
  <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden border">
    <!-- Image & Badges -->
    <div class="relative">
      <img :src="item.image" class="h-52 w-full object-cover" loading="lazy" />
      <div class="absolute top-3 left-3 flex gap-2">
        <span v-for="b in item.badge" :key="b" class="px-2 py-1 text-xs rounded font-semibold text-white"
          :class="b === 'New' ? 'bg-pink-500' : b === 'Featured' ? 'bg-yellow-500' : 'bg-indigo-500'">
          {{ b }}
        </span>
      </div>
      <div class="absolute bottom-3 left-3 text-white font-bold text-xl">
        ${{ item.price }} <span class="text-sm font-normal">/ Night</span>
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
      <h3 class="font-bold text-lg mb-1">{{ item.title }}</h3>
      <div class="flex items-center text-gray-500 text-sm mb-2">
        <i class="fas fa-map-marker-alt mr-1"></i>
        17, Grove Towers, New York, USA
      </div>
      <div class="bg-gray-50 rounded-lg flex items-center justify-between px-4 py-2 mb-3 text-gray-700 text-sm font-medium">
        <div class="flex items-center gap-1"><i class="fas fa-bed"></i> 4 Bedroom</div>
        <div class="flex items-center gap-1"><i class="fas fa-bath"></i> 4 Bath</div>
        <div class="flex items-center gap-1"><i class="fas fa-ruler-combined"></i> 350 Sq Ft</div>
      </div>
      <div class="flex flex-wrap justify-between text-sm text-gray-700 mb-2">
        <div>Listed on : <span class="font-semibold">{{ item.listedOn }}</span></div>
        <div>Category : <span class="font-semibold">{{ item.type }}</span></div>
        
      </div>
      <button class="bg-gray-900 text-white px-4 py-2 rounded-lg font-semibold text-sm">Buy Now</button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  item: Object
})
</script>
