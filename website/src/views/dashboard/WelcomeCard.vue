<template>
    <div class="relative overflow-hidden rounded-2xl p-6 md:p-8 bg-gradient-to-r from-slate-900 to-slate-700 text-white shadow-xl">
        <!-- Decorative Blur -->
        <div class="absolute -top-16 -right-16 w-56 h-56 bg-indigo-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-16 -left-16 w-56 h-56 bg-purple-500/20 rounded-full blur-3xl"></div>

        <div class="relative z-10 flex flex-col h-full justify-between">
            <!-- Greeting & Clock Row -->
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-1">
                        {{ greeting }} <span v-if="greetingIcon">{{ greetingIcon }}</span>
                    </h1>
                    <div class="text-base font-medium">Welcome {{ user.name }} hope you<br>have a nice day today</div>
                </div>
                <div class="flex flex-col items-end">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 mb-1" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="white" stroke-width="2" fill="none" />
                        <!-- Hour hand -->
                        <line :x1="12" :y1="12" :x2="hourX" :y2="hourY" stroke="white" stroke-width="2.5" stroke-linecap="round" />
                        <!-- Minute hand -->
                        <line :x1="12" :y1="12" :x2="minuteX" :y2="minuteY" stroke="white" stroke-width="1.5" stroke-linecap="round" />
                        <!-- Second hand -->
                        <line :x1="12" :y1="12" :x2="secondX" :y2="secondY" stroke="#facc15" stroke-width="1" stroke-linecap="round" />
                        <circle cx="12" cy="12" r="0.7" fill="#facc15" />
                    </svg>
                    <span class="text-xs font-bold">{{ time }}</span>
                </div>
            </div>

            <!-- Savings Highlight -->
            <div
                class="mt-5 inline-flex items-center gap-3 bg-white/10
                    backdrop-blur px-4 py-3 rounded-xl border border-white/10"
            >
                <span class="text-2xl">🎉</span>
                <p class="text-sm md:text-base">
                Kamu Hemat
                <span class="font-bold text-white">Rp 1.250.000</span>
                Tahun Ini dengan Membership ID Room!
                </p>
            </div>

            <div class="mt-4 text-xs font-semibold tracking-wide">ID ROOM Sewa Jual Properti Di Indonesia</div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import dayjs from 'dayjs'

defineProps({
    currentMembership: Array,
    user: Object
})

const time = ref('')
const greeting = ref('')
const greetingIcon = ref('')
const now = ref(new Date())
let interval = null

function updateTimeAndGreeting() {
    now.value = new Date()
    // Format time as HH:mm:ss
    time.value = now.value.toLocaleTimeString('en-US', { hour12: false })
    const hour = now.value.getHours()
    if (hour >= 5 && hour < 12) {
        greeting.value = 'Good Morning'
        greetingIcon.value = '🌞'
    } else if (hour >= 12 && hour < 17) {
        greeting.value = 'Good Afternoon'
        greetingIcon.value = '🌤️'
    } else if (hour >= 17 && hour < 20) {
        greeting.value = 'Good Evening'
        greetingIcon.value = '🌇'
    } else {
        greeting.value = 'Good Night'
        greetingIcon.value = '🌙'
    }
}

// SVG clock hands
const hourX = computed(() => {
    const h = now.value.getHours() % 12
    const m = now.value.getMinutes()
    const angle = ((h + m / 60) * 30 - 90) * Math.PI / 180
    return 12 + 5 * Math.cos(angle)
})
const hourY = computed(() => {
    const h = now.value.getHours() % 12
    const m = now.value.getMinutes()
    const angle = ((h + m / 60) * 30 - 90) * Math.PI / 180
    return 12 + 5 * Math.sin(angle)
})
const minuteX = computed(() => {
    const m = now.value.getMinutes()
    const s = now.value.getSeconds()
    const angle = ((m + s / 60) * 6 - 90) * Math.PI / 180
    return 12 + 7 * Math.cos(angle)
})
const minuteY = computed(() => {
    const m = now.value.getMinutes()
    const s = now.value.getSeconds()
    const angle = ((m + s / 60) * 6 - 90) * Math.PI / 180
    return 12 + 7 * Math.sin(angle)
})
const secondX = computed(() => {
    const s = now.value.getSeconds()
    const angle = (s * 6 - 90) * Math.PI / 180
    return 12 + 8 * Math.cos(angle)
})
const secondY = computed(() => {
    const s = now.value.getSeconds()
    const angle = (s * 6 - 90) * Math.PI / 180
    return 12 + 8 * Math.sin(angle)
})

onMounted(() => {
    updateTimeAndGreeting()
    interval = setInterval(updateTimeAndGreeting, 1000)
})
onUnmounted(() => {
    if (interval) clearInterval(interval)
})
</script>

