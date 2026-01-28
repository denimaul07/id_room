
<template>
    <section class="bg-[#0d131e] py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div v-for="stat in stats" :key="stat.label" class="bg-white rounded-xl flex items-center gap-4 p-6 shadow">
                    <div class="flex-shrink-0">
                        <span v-html="stat.icon" class="w-12 h-12 block"></span>
                    </div>
                    <div>
                        <div class="text-2xl md:text-3xl font-bold text-gray-900">{{ stat.value }}</div>
                        <div class="text-gray-500 text-base">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    name: 'StatsCounter',
    data() {
        return {
            stats: [
                {
                    value: '50K',
                    label: 'Listings Added',
                    icon: `<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" rx="12" fill="#fff"/><g stroke="#00E0C6" stroke-width="2"><rect x="12" y="12" width="24" height="24" rx="4"/><rect x="16" y="16" width="16" height="4" rx="2"/><rect x="16" y="22" width="10" height="2" rx="1"/><rect x="16" y="26" width="6" height="2" rx="1"/><rect x="24" y="26" width="8" height="2" rx="1"/></g><g><rect x="32" y="12" width="4" height="8" rx="2" fill="#A78BFA"/></g></svg>`
                },
                {
                    value: '3000+',
                    label: 'Agents Listed',
                    icon: `<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" rx="12" fill="#fff"/><g stroke="#00E0C6" stroke-width="2"><circle cx="24" cy="20" r="4"/><path d="M16 32c0-4 8-4 8-4s8 0 8 4v2H16v-2Z"/></g><g><circle cx="36" cy="24" r="2" fill="#A78BFA"/><circle cx="12" cy="24" r="2" fill="#A78BFA"/></g></svg>`
                },
                {
                    value: '2000+',
                    label: 'Sales Completed',
                    icon: `<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" rx="12" fill="#fff"/><g stroke="#00E0C6" stroke-width="2"><path d="M16 32V20l8-8 8 8v12"/><rect x="20" y="28" width="8" height="4" rx="2"/></g><g><circle cx="36" cy="32" r="2" fill="#A78BFA"/><text x="22" y="38" font-size="8" fill="#A78BFA">$</text></g></svg>`
                },
                {
                    value: '5000+',
                    label: 'Users',
                    icon: `<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="48" height="48" rx="12" fill="#fff"/><g stroke="#00E0C6" stroke-width="2"><circle cx="24" cy="20" r="4"/><path d="M16 32c0-4 8-4 8-4s8 0 8 4v2H16v-2Z"/></g><g><circle cx="18" cy="28" r="2" fill="#A78BFA"/><circle cx="30" cy="28" r="2" fill="#A78BFA"/><polygon points="24,36 26,32 22,32" fill="#A78BFA"/></g></svg>`
                },
            ]
        }
    }
}
</script>

<style scoped>
</style>