<template>
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <!-- Title -->
            <div class="text-center mb-14">
                <h2 class="text-4xl font-bold text-primary">Berlangganan Membership</h2>
                <p class="text-secondary mt-4 max-w-xl mx-auto">
                    Kelola bisnis Anda dengan paket yang tepat untuk tim Anda. Pilih paket yang sesuai dengan kebutuhan dan nikmati berbagai manfaat eksklusif dari ID Room.
                </p>
            </div>
            <!-- Plans -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Standard -->
                <div
                    class="card bg-base-100 shadow-lg flex flex-col p-6 pricing-card cursor-pointer"
                    v-for="plan in currentInfo.membership"
                    :key="plan.odata"
                    :style="{
                        transition: 'background 0.3s, color 0.3s',
                        background: hoveredCard === plan.odata ? (currentInfo.primaryColor || '#2563eb') : '',
                        color: hoveredCard === plan.odata ? '#fff' : ''
                    }"
                    @mouseenter="hoveredCard = plan.odata"
                    @mouseleave="hoveredCard = null"
                >
                    <div class="card-body flex-1 flex flex-col text-center">
                        <h3 class="text-2xl font-bold mb-2">{{ plan.title }}</h3>
                        <p class="text-secondary mb-4">{{ plan.desc }}</p>
                        <div class="text-5xl font-bold text-primary my-4">{{ plan.price.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }} <span class="text-base font-normal text-secondary">/bulan</span></div>
                        <div class="text-left flex-1 pb-3">
                            <h4 class="font-semibold mb-2">Benefits</h4>
                            <ul class="space-y-2 text-sm text-secondary">
                                <li v-for="benefit in plan.benefits" :key="benefit.odata" class="flex items-center gap-2">
                                    <i v-if="benefit.value == 1" class="fa fa-check text-green-500 font-bold"></i>
                                    <i v-else class="fa fa-times text-red-500 font-bold"></i>
                                    <span>{{ benefit.benefit_details.name }}</span>
                                </li>
                            </ul>
                        </div>
                        <button class="w-full bg-black text-white pb-3 pt-3 text-sm hover:bg-gray-800">
                            Pilih Paket
                        </button>   
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>


<script setup>
import { computed, ref } from 'vue'
import { useInfoStore } from '@/store/info'
import { storeToRefs } from 'pinia'
const { data: info } = storeToRefs(useInfoStore())
const currentInfo = computed(() => {
    return info.value?.[0] ?? {}
})
const hoveredCard = ref(null)
</script>

