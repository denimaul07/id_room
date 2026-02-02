<template>
    <div class="fixed bottom-3 right-3 sm:bottom-6 sm:right-6 z-50 flex flex-col items-end gap-2 sm:gap-3">

        <!-- Action Buttons (muncul saat open) -->
        <transition name="fade-slide">
        <div v-if="open" class="flex flex-col gap-2 items-end">
            <div class="flex flex-col gap-1 items-end">
                <a
                    v-if="currentInfo.phone !== 'null'"
                    :href="`tel:${currentInfo.phone}`"
                    class="flex items-center gap-2 px-4 py-2 rounded-full shadow-lg transition hover:scale-105 hover:shadow-xl"
                    :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                >
                    <span class="text-sm font-semibold">Hubungi Admin</span>
                    <span class="bg-white rounded-full p-1" :style="{ color: currentInfo.primaryTextColor }">
                        📞
                    </span>
                </a>
                <a
                    v-if="currentInfo.phone1 !== 'null'"
                    :href="`tel:${currentInfo.phone1}`"
                    class="flex items-center gap-2 px-4 py-2 rounded-full shadow-lg transition hover:scale-105 hover:shadow-xl"
                    :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                >
                    <span class="text-sm font-semibold">Hubungi Admin 2</span>
                    <span class="bg-white rounded-full p-1" :style="{ color: currentInfo.primaryTextColor }">
                        📞
                    </span>
                </a>
                <a
                    v-if="currentInfo.wa !== 'null'"
                    target="_blank"
                    rel="noopener"
                    class="cursor-pointer flex items-center gap-2 px-4 py-2 rounded-full shadow-lg transition hover:scale-105 hover:shadow-xl"
                    :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                    @click="kirimWA(currentInfo.wa)"
                >
                    <span class="text-sm font-semibold">WhatsApp Admin</span>
                    <span class="bg-white rounded-full p-1" :style="{ color: currentInfo.primaryTextColor }">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="18" height="18" fill="currentColor">
                        <g>
                            <circle cx="16" cy="16" r="16" fill="#25D366"/>
                            <path fill="#FFF" d="M23.5 19.7c-.3-.2-1.7-.8-2-1s-.5-.2-.7.2c-.2.3-.7 1-1 1.2-.2.2-.4.2-.7.1-.3-.2-1.1-.4-2.1-1.3-.8-.7-1.3-1.5-1.5-1.8-.2-.3 0-.5.1-.7.1-.1.2-.3.3-.4.1-.1.1-.2.2-.4.1-.2 0-.4 0-.6s-.7-1.7-1-2.3c-.3-.6-.6-.5-.8-.5h-.7c-.2 0-.5.1-.7.3-.2.2-.9.9-.9 2.1 0 1.2.9 2.3 1 2.5.1.2 1.7 2.7 4.2 3.7.6.2 1.1.4 1.5.5.6.2 1.1.2 1.5.1.5-.1 1.7-.7 1.9-1.4.2-.7.2-1.3.1-1.4z"/>
                        </g>
                    </svg>
                    </span>
                </a>
                <a
                    v-if="currentInfo.wa1 !== 'null'"
                    @click="kirimWA(currentInfo.wa1)"
                    target="_blank"
                    rel="noopener"
                    class="flex items-center gap-2 px-4 py-2 rounded-full shadow-lg transition hover:scale-105 hover:shadow-xl"
                    :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                >
                    <span class="text-sm font-semibold">WhatsApp Admin 2</span>
                    <span class="bg-white rounded-full p-1" :style="{ color: currentInfo.primaryTextColor }">
                        💬
                    </span>
                </a>
            </div>

            <button
                class="flex items-center gap-2 px-4 py-2 rounded-full shadow-lg transition hover:scale-105 hover:shadow-xl"
                :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
                @click="showDrawer = true"
            >
                <span class="text-sm font-semibold">FAQ</span>
                <span class="bg-white rounded-full p-1" :style="{ color: currentInfo.primaryTextColor }">
                    ❓
                </span>
            </button>
        </div>
        </transition>

        <!-- Main Card -->
        <div @click="toggle"
            class="rounded-xl shadow-xl p-2 w-[180px] sm:rounded-2xl sm:p-4 sm:w-[280px] cursor-pointer hover:scale-[1.02] transition"
            :style="{ backgroundColor: currentInfo.primaryColor, color: currentInfo.primaryTextColor }"
        >
            <div class="flex items-center gap-2 sm:gap-3">
                <img
                src="https://i.pravatar.cc/100?img=32"
                alt="Sales"
                class="w-10 h-10 sm:w-14 sm:h-14 rounded-full border-2 sm:border-4 border-white"
                />
                <div class="flex-1">
                <p class="text-xs sm:text-sm font-bold">Masih Bingung?</p>
                <p class="text-[10px] sm:text-xs opacity-90">
                    Mau sewa, beli atau renovasi properti? Yuk ngobrol dulu sama tim kami.
                </p>
                <button
                    class="mt-1 sm:mt-2 bg-white text-black text-[10px] sm:text-xs font-bold px-2 sm:px-3 py-1 rounded-full"
                >
                    Chat Admin
                </button>
                </div>
            </div>
        </div>

    </div>

    <WhatsappModal :open="showWA" @close="showWA = false" :nowa="nowa" />

    <!-- Drawer FAQ -->
    <transition name="drawer-slide">
        <div v-if="showDrawer" class="fixed inset-0 z-50 flex">
            <div class="bg-white w-full  h-full shadow-xl p-6 overflow-y-auto" style="border-top-right-radius: 1rem; border-bottom-right-radius: 1rem;">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">FAQ</h2>
                    <button @click="showDrawer = false" class="text-xl">&times;</button>
                </div>
            
                <FAQ />
            </div>
            <div class="flex-1 bg-black bg-opacity-40" @click="showDrawer = false"></div>
        </div>
    </transition>
</template>

<script setup>
    import { storeToRefs } from 'pinia'
    import { useInfoStore } from '@/store/info'
    import { ref, watch, onMounted, computed } from 'vue'
    import WhatsappModal from '@/components/WhatsappModal.vue'
    import FAQ from '@/components/Faq.vue'

    const showWA = ref(false)
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => info.value?.[0] ?? {})
    const nowa = ref('')
    const kirimWA = (number) => {
        showWA.value = true
        nowa.value = number
    }

    const open = ref(false)
    const showDrawer = ref(false)

    const toggle = () => {
        open.value = !open.value
    }
</script>

<style scoped>
            .drawer-slide-enter-active,
            .drawer-slide-leave-active {
                transition: all 0.3s cubic-bezier(.25,.8,.25,1);
            }
            .drawer-slide-enter-from {
                transform: translateX(-100%);
                opacity: 0;
            }
            .drawer-slide-leave-to {
                transform: translateX(-100%);
                opacity: 0;
            }
    .fade-slide-enter-active,
    .fade-slide-leave-active {
    transition: all 0.3s ease;
    }
    .fade-slide-enter-from {
    opacity: 0;
    transform: translateY(10px);
    }
    .fade-slide-leave-to {
    opacity: 0;
    transform: translateY(10px);
    }
</style>
