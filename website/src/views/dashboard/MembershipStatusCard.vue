<template>
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 w-full" v-if="membershipTitle!=='No Membership'">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm text-gray-500">My Membership</h3>
                <p class="text-lg font-bold text-gray-900">
                    {{ membershipTitle }}
                </p>
            </div>

            <span
                class="px-3 py-1 text-xs font-semibold rounded-full"
                :class="statusBadgeClass"
            >
                {{ membershipStatusLabel }}
            </span>
        </div>

        <!-- Expiry -->
        <div class="mb-4">
            <p class="text-xs text-gray-500">Valid Until</p>
            <p class="text-sm font-semibold text-gray-800">
                {{ membershipEndDate ? formatDate(membershipEndDate) : '-' }}
            </p>
            <p class="text-xs text-gray-400">
                {{ membershipEndDate ? daysRemaining(membershipEndDate) : '-' }} days remaining
            </p>
        </div>

        <!-- Benefits -->
        <div class="bg-slate-50 rounded-xl p-4 mb-4">
            <p class="text-xs text-gray-500 mb-2">Your Benefits</p>

            <ul class="space-y-1 text-sm text-gray-700">
                <li
                    v-for="benefit in membershipBenefits"
                    :key="benefit?.id"
                >
                    ✔ {{ benefit?.benefit_details?.name ?? '-' }}
                </li>
            </ul>
        </div>

        <!-- CTA -->
        <button
            class="w-full bg-[#d5bd7d] hover:bg-[#c9ae65] text-white font-semibold py-2.5 rounded-xl transition"
            :disabled="!canUpgrade"
            :class="{ 'opacity-50 cursor-not-allowed': !canUpgrade }"
                @click="$emit('menu', 'membership')"
            >
                Upgrade Membership
            </button>

    </div>

    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 w-full text-center" v-else>
        <img src="@/assets/404/404registered.png" alt="No Membership" class="mx-auto mb-4 w-32">
        <p class="text-sm text-gray-500">You don't have an active membership yet.</p>
        <p class="text-lg font-bold text-gray-900">Explore our membership plans and enjoy exclusive benefits!</p>
        <button
            class="mt-4 px-6 py-2 bg-[#d5bd7d] hover:bg-[#c9ae65] text-white font-semibold rounded-xl transition"
            @click="$emit('menu', 'membership')"
        >
            View Membership Plans
        </button>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    currentMembership: {
        type: Array,
        default: () => []
    },
    user: Object
})

/*
|--------------------------------------------------------------------------
| SAFE COMPUTED LAYER
|--------------------------------------------------------------------------
*/

const activeMembership = computed(() => {
    return props.currentMembership?.[0] ?? null
})

const membershipTitle = computed(() => {
    return activeMembership.value?.membership?.title ?? 'No Membership'
})

const membershipStatus = computed(() => {
    return activeMembership.value?.status ?? null
})

const membershipStatusLabel = computed(() => {
    return membershipStatus.value
        ? membershipStatus.value.toUpperCase()
        : 'NO MEMBERSHIP'
})

const statusBadgeClass = computed(() => {
    if (membershipStatus.value === 'active') {
        return 'bg-emerald-100 text-emerald-600'
    }
    return 'bg-red-100 text-red-500'
})

const membershipEndDate = computed(() => {
    return activeMembership.value?.end_date ?? null
})

const membershipBenefits = computed(() => {
    return activeMembership.value?.membership?.benefits ?? []
})

const canUpgrade = computed(() => {
    return membershipStatus.value === 'active' ||
           membershipStatus.value === 'expired'
})

/*
|--------------------------------------------------------------------------
| HELPERS
|--------------------------------------------------------------------------
*/

function formatDate(date) {
    if (!date) return '-'
    return new Date(date).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    })
}

function daysRemaining(endDate) {
    if (!endDate) return '-'

    const today = new Date()
    const end = new Date(endDate)

    today.setHours(0,0,0,0)
    end.setHours(0,0,0,0)

    const diff = Math.ceil((end - today) / (1000 * 60 * 60 * 24))
    return diff >= 0 ? diff : 0
}
</script>