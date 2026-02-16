<template>
    <section class="min-h-screen flex flex-col items-center justify-center text-center px-4">
        <img src="@/assets/verified/verified-image.webp" alt="Akun Kamu Terverifikasi" class="w-90 mb-6" />
        <h1 class="text-4xl font-bold mb-4">Akun Kamu Telah Terverifikasi!</h1>
        <p class="text-gray-600 mb-8">Terima kasih telah memverifikasi akun kamu. Kamu sekarang dapat masuk dan mulai
            menggunakan layanan kami.</p>
    </section>
</template>

<script setup>
import { useHead } from '@unhead/vue'
useHead({
    title: 'Akun Terverifikasi | ID Room',
    meta: [
        {
            name: 'description',
            content: 'Akun kamu telah berhasil diverifikasi di website ID Room.'
        },
        {
            name: 'robots',
            content: 'noindex, nofollow'
        }
    ]
})
</script>
