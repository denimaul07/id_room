<template>
    <div class="bg-white rounded-2xl shadow-md border border-slate-100 p-5 w-full">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-sm text-gray-500">My Membership</h3>
            <p class="text-lg font-bold text-gray-900">
            {{ currentMembership.length > 0 ? currentMembership[0].membership.title : 'No Membership' }}
            </p>
        </div>

        <span
            class="px-3 py-1 text-xs font-semibold rounded-full"
            :class="currentMembership.length > 0 && currentMembership[0].status === 'active'
            ? 'bg-emerald-100 text-emerald-600'
            : 'bg-red-100 text-red-500'"
        >
            {{ currentMembership.length > 0 ? currentMembership[0].status.toUpperCase() : 'NO MEMBERSHIP' }}
        </span>
        </div>

        <!-- Expiry -->
        <div class="mb-4">
        <p class="text-xs text-gray-500">Valid Until</p>
        <p class="text-sm font-semibold text-gray-800">
            {{ currentMembership.length > 0 ? formatDate(currentMembership[0].end_date) : '-' }}
        </p>
        <p class="text-xs text-gray-400">
        {{ currentMembership.length > 0 ? daysRemaining(currentMembership[0].end_date) : '-' }} days remaining
        </p>
        </div>

        <!-- Benefits -->
        <div class="bg-slate-50 rounded-xl p-4 mb-4">
            <p class="text-xs text-gray-500 mb-2">Your Benefits</p>

            <ul class="space-y-1 text-sm text-gray-700">
                <li
                    v-for="benefit in currentMembership.length > 0 ? currentMembership[0].membership.benefits : []"
                    :key="benefit.odata"
                >
                    ✔ {{ benefit.benefit_details?.name || '-' }}
                </li>
            </ul>
        </div>


        <!-- CTA -->
        <button
        class="w-full bg-[#d5bd7d] hover:bg-[#c9ae65] text-white font-semibold py-2.5 rounded-xl transition"
        :disabled="!(currentMembership.length > 0 && (currentMembership[0].status === 'active' || currentMembership[0].status === 'expired'))"
        :class="{
            'opacity-50 cursor-not-allowed': !(currentMembership.length > 0 && (currentMembership[0].status === 'active' || currentMembership[0].status === 'expired'))
        }"
        >
        Upgrade Membership
        </button>

    </div>
</template>

<script setup>
    defineProps({
        currentMembership: Array,
        user: Object
    })

    function formatRupiah(val) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(val)
    }

    function formatDate(date) {
        return new Date(date).toLocaleDateString('id-ID', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        })
    }

    function daysRemaining(endDate) {
        if (!endDate) return '-';
        const today = new Date();
        const end = new Date(endDate);
        // Set time to 00:00:00 for accurate diff
        today.setHours(0,0,0,0);
        end.setHours(0,0,0,0);
        const diff = Math.ceil((end - today) / (1000 * 60 * 60 * 24));
        return diff >= 0 ? diff : 0;
    }
</script>
