<template>
    <section class="relative h-[420px] md:h-[480px] flex items-center justify-center bg-cover bg-center"  :style="`background-image: url('${imageBaseUrl + currentInfo.bannerRenov}');`">
        <div class="absolute inset-0 bg-black/60"></div>

        <div class="relative z-10 text-center px-4 max-w-3xl">
            <h1 class="text-3xl md:text-5xl font-bold mb-4" :style="{ color: currentInfo.colorRenov }">
            {{ currentInfo.titleRenov }}
            </h1>
            <p class="mb-6" :style="{ color: currentInfo.colorRenov }">
            {{ currentInfo.subTitleRenov }}
            </p>
            <div class="flex justify-center gap-4">
                <button class="bg-[#d5bd7d] px-6 py-3 font-semibold text-white rounded hover:bg-[#c9ae63]" @click="modalWA = true">
                    Konsultasi Gratis
                </button>
                <button class="border border-white text-white px-6 py-3 rounded hover:bg-white hover:text-black" @click="scrollToPortofolio">
                    Lihat Portfolio
                </button>
            </div>
        </div>
    </section>

    <WhyMe />
    <Service />
    <div ref="portofolioSection">
        <Portofolio />
    </div>
    <Process />
    <Client />

    <!-- Modal WA -->
    <WhatsappModal :open="modalWA" nowa="6285772004271" @close="modalWA = false" />

</template>

<script setup>
    import { ref, computed, nextTick } from 'vue'
    import WhatsappModal from '@/components/WhatsappModal.vue'
    import { useHead } from '@vueuse/head'
    import WhyMe from '@/components/renovation/WhyMe.vue'
    import heroImage from '@/assets/banner/renov.png'
    import Service from '@/components/renovation/Service.vue'
    import Portofolio from '@/components/renovation/Portofolio.vue'
    import Process from '@/components/renovation/Process.vue'
    import Client from '@/components/renovation/Client.vue'
    import { useInfoStore } from '@/store/info'
    import { storeToRefs } from 'pinia'

        const { data: info } = storeToRefs(useInfoStore())
        const currentInfo = computed(() => {
                return info.value?.[0] ?? {}
        })

        const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
        const loaded = ref(true)

        // Modal WA
        const modalWA = ref(false)

        // Scroll to Portofolio
        const portofolioSection = ref(null)
        const scrollToPortofolio = () => {
            nextTick(() => {
                if (portofolioSection.value) {
                    portofolioSection.value.scrollIntoView({ behavior: 'smooth' })
                }
            })
        }

    useHead({
        title: 'Interior Renovation - ID ROOM',
        meta: [
            {
                name: 'description',
                content: 'Wujudkan hunian impian Anda dengan layanan interior dan renovasi profesional dari ID ROOM. Tim ahli kami siap membantu menciptakan ruang yang nyaman, fungsional, dan estetis sesuai kebutuhan Anda.',
            },
            {
                name: 'keywords',
                content: 'interior, renovation, desain interior, renovasi rumah, jasa renovasi, ID ROOM',
            },
            {
                name: 'author',
                content: 'ID ROOM',
            },
            {
                name: 'viewport',
                content: 'width=device-width, initial-scale=1.0',
            },
            {
                rel: 'canonical',
                href: 'https://idroom.id/interior-renovation',
            },

        ],
    })
</script>