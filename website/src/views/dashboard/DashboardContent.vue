<template>
    <main class="content">
        <Banner
            :currentMembership="currentMembership"
            :currentTransactions="currentTransactions"
            :currentBooking="currentBooking"
            :currentInfo="currentInfo"
        />
        <SearchProperty class="mt-3" />

        <div class="flex flex-col md:flex-row gap-3 mt-3">
            <div class="w-full md:w-1/3 md:pr-3">
                <WelcomeCard class="mt-3" :currentMembership="currentMembership" :user="user" />
            </div>
            <div class="w-full md:w-2/3">
                <MembershipStatusCard class="mt-3" :currentMembership="currentMembership" :user="user" @menu="$emit('menu', $event)"/>
            </div>
        </div>
        <br>
    </main>
</template>

<script setup>
import Banner from './Banner.vue';
import SearchProperty from '@/components/home/SearchProperty.vue';
import WelcomeCard from './WelcomeCard.vue';
import MembershipStatusCard from './MembershipStatusCard.vue';
import { computed, onMounted } from 'vue'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/store/auth'
import { useMembershipStore } from '@/store/membership'
import { useTransactionsStore } from '@/store/transactions'
import { useBookingStore } from '@/store/booking'
import { useInfoStore } from '@/store/info'

const authStore = useAuthStore()
const { user } = storeToRefs(authStore)

const membershipStore = useMembershipStore()
const { data: membership } = storeToRefs(membershipStore)
const currentMembership = computed(() => membership.value ?? [])

const transactionsStore = useTransactionsStore()
const { data: transactions } = storeToRefs(transactionsStore)
const currentTransactions = computed(() => transactions.value ?? [])

const bookingStore = useBookingStore()
const { data: booking } = storeToRefs(bookingStore)
const currentBooking = computed(() => booking.value ?? [])

const infoStore = useInfoStore()
const { data: info } = storeToRefs(infoStore)
const currentInfo = computed(() => info.value?.[0] ?? {})

onMounted(async () => {
    if (!membershipStore.loaded) {
        await membershipStore.fetch()
    }
    if (!transactionsStore.loaded) {
        await transactionsStore.fetch()
    }
    if (!info.value || !info.value.length) {
        await infoStore.fetch?.()
    }
    if (!bookingStore.loaded) {
        await bookingStore.fetch()
    }
})
</script>
