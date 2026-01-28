<template>
    <div class="flex items-center justify-center gap-2 py-4">
        <button
        @click="$emit('change', currentPage - 1)"
        :disabled="currentPage <= 1"
        class="btn btn-sm"
        >Prev</button>

        <span class="text-sm">Halaman {{ currentPage }} dari {{ totalPages }}</span>

        <button
        @click="$emit('change', currentPage + 1)"
        :disabled="currentPage >= totalPages"
        class="btn btn-sm"
        >Next</button>
    </div>
</template>

<script setup>
    import { defineProps } from 'vue'
    const props = defineProps({
    currentPage: Number,
    totalPages: Number
    })
</script>
