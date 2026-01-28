<template>
    <section class="min-h-screen flex flex-col items-center justify-center text-center px-4">
        <img src="@/assets/404/404-image.webp" alt="404 Not Found" class="w-90 mb-6" />
        <h1 class="text-4xl font-bold mb-4">404 - Halaman Tidak Ditemukan</h1>
        <p class="text-gray-600 mb-8">Maaf, halaman yang Anda cari tidak ada di website kami.</p>
    </section>
</template>

<script setup>
    import { useHead } from '@unhead/vue'
    useHead({
        title: '404 Not Found | ID Room',
        meta: [
            {
            name: 'description',
            content: 'Halaman tidak ditemukan di website ID Room.'
            },
            {
            name: 'robots',
            content: 'noindex, nofollow'
            }
        ]
    })
</script>
