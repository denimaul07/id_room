<template>
  <nav v-if="totalPages > 1" class="flex items-center gap-2">
    <button
      class="px-3 py-1 rounded-full border text-sm"
      :disabled="currentPage === 1"
      @click="changePage(currentPage - 1)"
    >
      &laquo;
    </button>
    <button
      v-for="page in pages"
      :key="page"
      class="px-3 py-1 rounded-full border text-sm"
      :class="{ 'bg-blue-600 text-white font-bold': page === currentPage }"
      @click="changePage(page)"
    >
      {{ page }}
    </button>
    <button
      class="px-3 py-1 rounded-full border text-sm"
      :disabled="currentPage === totalPages"
      @click="changePage(currentPage + 1)"
    >
      &raquo;
    </button>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  totalPages: {
    type: Number,
    required: true
  }
})
const emit = defineEmits(['page-changed'])

const pages = computed(() => {
  const arr = []
  const max = Math.min(props.totalPages, 5)
  let start = Math.max(1, props.currentPage - 2)
  let end = Math.min(props.totalPages, start + max - 1)
  if (end - start < max - 1) {
    start = Math.max(1, end - max + 1)
  }
  for (let i = start; i <= end; i++) {
    arr.push(i)
  }
  return arr
})

function changePage(page) {
  if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
    emit('page-changed', page)
  }
}
</script>

<style scoped>
nav {
  user-select: none;
}
button[disabled] {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
