<template>
    <section class="bg-slate-100  py-16">
        <div class="max-w-7xl mx-auto px-4 flex flex-col lg:flex-row gap-10 items-center">
        <!-- Left: Title & Info -->
        <div class="flex-1 min-w-[320px]">
            <div class="h-1 w-10 bg-gradient-to-r from-teal-400 to-purple-400 mb-4 rounded"></div>
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-3 leading-tight">
            Kisah sukses dalam <br /> kata-kata mereka sendiri
            </h2>
            <p class="text-gray-500 mb-6 max-w-md">Baca apa yang dikatakan klien kami yang puas tentang pengalaman mereka dengan platform kami.</p>
    
        </div>
        <!-- Right: Testimonials -->
        <div class="flex-[2] grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="(t, i) in testimonials" :key="i" class="bg-white rounded-2xl shadow-md p-7 flex flex-col gap-4 min-w-[320px]">
            <div class="flex items-center gap-2">
                <svg class="w-8 h-8 text-teal-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><text x="2" y="20" font-size="20" font-family="Arial" fill="currentColor">“</text></svg>
                <div class="flex gap-1 text-yellow-400">
                <svg v-for="n in 5" :key="n" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.967a1 1 0 00.95.69h4.178c.969 0 1.371 1.24.588 1.81l-3.385 2.46a1 1 0 00-.364 1.118l1.287 3.966c.3.922-.755 1.688-1.54 1.118l-3.385-2.46a1 1 0 00-1.175 0l-3.385 2.46c-.784.57-1.838-.196-1.54-1.118l1.287-3.966a1 1 0 00-.364-1.118L2.045 9.394c-.783-.57-.38-1.81.588-1.81h4.178a1 1 0 00.95-.69l1.286-3.967z"/></svg>
                </div>
            </div>
            <div class="text-gray-700 text-base leading-relaxed">{{ t.text }}</div>
            <div class="flex items-center gap-3 mt-2">
                <img :src="t.avatar" :alt="t.name" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow" />
                <div>
                <div class="font-bold text-gray-900">{{ t.name }}</div>
                <div class="text-gray-500 text-sm">{{ t.country }}</div>
                </div>
            </div>
            </div>
        </div>
        </div>
    </section>
</template>

<script setup>
const testimonials = [
  {
    name: 'Lily Brooks',
    country: 'South Africa',
    avatar: 'https://randomuser.me/api/portraits/women/65.jpg',
    text: 'Booking our dream home was incredibly easy with Dreams Estate. The interface was user-friendly.'
  },
  {
    name: 'Ethan Wells',
    country: 'United Kingdom',
    avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
    text: 'Booking our dream home was so simple with Dreams Estate. The site was easy to use!'
  },
  {
    name: 'Daniel Cooper',
    country: 'United States of America',
    avatar: 'https://randomuser.me/api/portraits/men/44.jpg',
    text: 'Dreams Estate made home booking a breeze. Super easy and stress-free!'
  },
  {
    name: 'Emma Davidson',
    country: 'Japan',
    avatar: 'https://randomuser.me/api/portraits/women/68.jpg',
    text: 'Dreams Estate made booking our dream home effortless. The interface was so easy to navigate!'
  }
]
</script>
