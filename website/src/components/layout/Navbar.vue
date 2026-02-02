<script setup>
    import { storeToRefs } from 'pinia'
    import { useInfoStore } from '@/store/info'
    import { ref, watch, onMounted, computed } from 'vue'
    import NavItem from '@/components/ui/NavItem.vue'

    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => info.value?.[0] ?? {})
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
    const drawerOpen = ref(false)


    // lock scroll when drawer open
    watch(drawerOpen, (val) => {
        document.body.style.overflow = val ? 'hidden' : ''
    })
</script>


<template>
    <nav :style="{ backgroundColor: currentInfo?.navBarColor, color: currentInfo?.navBarTextColor }" class="sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

        <!-- Logo -->
        <router-link to="/" class="flex items-center">
            <img :src="imageBaseUrl + currentInfo?.logo" alt="ID Room" class="h-12" />
        </router-link>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex gap-6 font-medium" :style="{ color: currentInfo?.navBarTextColor }">
            <NavItem label="Beranda" to="/" />
            <NavItem label="Sewa Properti" to="/sewa-properti" />
            <NavItem label="Jual Properti" to="/jual-properti" />
            <NavItem label="Interior & Renovasi Properti" to="/interior-renovation" />
            <NavItem label="Tentang Kami" to="/tentang-kami" />
            <NavItem label="Hubungi Kami" to="/hubungi-kami" />
        </ul>

        <!-- Actions Desktop -->
        <div class="hidden md:flex gap-3">
            <button  :style="{
                    '--btn-bg': currentInfo.primaryColor,
                    '--btn-bg-hover': currentInfo.primaryColorHover,
                    '--btn-text-color': currentInfo.primaryTextColor
                }"   class="px-4 py-2 text-sm bg-[var(--btn-bg)] text-[var(--btn-text-color)] hover:bg-[var(--btn-bg-hover)] transition-colors duration-300">
                Sign In
            </button>
            <button
                :style="{
                    '--btn-bg': currentInfo.secondColor,
                    '--btn-bg-hover': currentInfo.secondColorHover,
                    '--btn-text-color': currentInfo.secondTextColor
                }" class="px-4 py-2 text-sm bg-[var(--btn-bg)] text-[var(--btn-text-color)] hover:bg-[var(--btn-bg-hover)] transition-colors duration-300"
            >  
            Register 
            </button>
        </div>

        <!-- Hamburger -->
        <button
            @click="drawerOpen = true"
            class="md:hidden p-2 rounded hover:bg-gray-800"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        </div>
    </nav>

    <!-- BACKDROP -->
    <transition name="fade">
        <div
        v-if="drawerOpen"
        @click="drawerOpen = false"
        class="fixed inset-0 bg-black/60 z-40"
        />
    </transition>

    <!-- DRAWER -->
    <transition name="drawer">
        <aside
        v-if="drawerOpen"
        class="fixed inset-0 bg-gray-900 text-white z-50 flex flex-col"
        >

        <!-- Header -->
        <div class="flex items-center justify-between px-6 h-16 border-b border-gray-800">
            <img src="@/assets/navbar/logo.png" alt="ID Room" class="h-10" />
            <button @click="drawerOpen = false" class="p-2 hover:bg-gray-800 rounded">
            ✕
            </button>
        </div>

        <!-- Menu -->
        <ul class="flex-1 px-6 py-8 space-y-6 text-lg font-medium">
            <NavItem label="Beranda" to="/" @click="drawerOpen = false" />
            <NavItem label="Sewa Properti" to="/sewa-properti" @click="drawerOpen = false" />
            <NavItem label="Jual Properti" to="/jual-properti" @click="drawerOpen = false" />
            <NavItem label="Interior & Renovasi Properti" to="/interior-renovation" @click="drawerOpen = false" />
            <NavItem label="Tentang Kami" to="/tentang-kami" @click="drawerOpen = false" />
            <NavItem label="Hubungi Kami" to="/hubungi-kami" @click="drawerOpen = false" />
        </ul>

        <!-- Actions -->
        <div class="px-6 pb-8 space-y-3">
            <button class="w-full bg-[#d5bd7d] py-3 text-sm hover:bg-[#d5b356]">
            Sign In
            </button>
            <button class="w-full bg-white text-gray-900 py-3 text-sm hover:bg-gray-100">
            Register
            </button>
        </div>

        </aside>
    </transition>
</template>


<style scoped>
    .fade-enter-active,
    .fade-leave-active {
    transition: opacity 0.2s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
    opacity: 0;
    }

    .drawer-enter-active,
    .drawer-leave-active {
    transition: transform 0.3s ease;
    }
    .drawer-enter-from,
    .drawer-leave-to {
    transform: translateX(100%);
    }
</style>
