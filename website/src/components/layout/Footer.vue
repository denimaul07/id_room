<template>
    <footer :style="{ backgroundColor: currentInfo.footerColor, color: currentInfo.footerTextColor }" class="pt-12">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 px-6 mb-3">
        
            <!-- About -->
            <div>
                <h3 class="text-xl font-bold mb-2">{{ currentInfo.siteName }}</h3>
                <p class="text-sm">
                {{ currentInfo.footerDesk }}
                </p>

                <h3 class="text-xl font-bold mb-2 pt-2">Hubungi Kami</h3>
                <div class="text-center">
                    <div class="flex justify-center gap-4 pb-6">
                        <a
                            v-for="item in currentInfo.socials"
                            :key="item.social"
                            :href="item.url"
                            target="_blank"
                            rel="noopener"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition"
                            :aria-label="item.social"
                        >
                        <i :class="item.icon + ' text-xl text-gray-900'"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold mb-2">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                <li><router-link to="/">Beranda</router-link></li>
                <li><router-link to="/sewa-properti">Sewa Properti</router-link></li>
                <li><router-link to="/tentang-kami">Tentang Kami</router-link></li>
                <li><router-link to="/interior-renovation">Interior & Renovasi Properti</router-link></li>
                <li><router-link to="/tentang-kami">Tentang Kami</router-link></li>
                <li><router-link to="/kontak-kami">Kontak Kami</router-link></li>
                </ul>
            </div>

            <!-- Dukungan -->
            <div>
                <h4 class="text-white font-semibold mb-2">Dukungan</h4>
                <ul class="space-y-2 text-sm">
                <li><router-link to="/syarat-dan-ketentuan">Syarat & Ketentuan</router-link></li>
                <li><router-link to="/kebijakan-privasi">Kebijakan Privasi</router-link></li>
                </ul>   
            </div>
            <!-- Contact -->
            <div>
                <h4 class="text-white font-semibold mb-2">Kunjungi Kami</h4>
                <p class="text-sm" v-html="currentInfo.alamat"></p>
            </div>


        </div>

        <div class="text-center text-sm text-gray-500 py-4 border-t border-gray-800">
        © {{ new Date().getFullYear() }} IDROOM. All rights reserved.
        </div>
    </footer>
</template>

<script setup>
    import { ref, computed } from 'vue'
    import { useRouter } from 'vue-router'
    import { storeToRefs } from 'pinia'
    import { useInfoStore } from '@/store/info'

    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })

</script>
