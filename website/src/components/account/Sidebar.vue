<template>
    <div class="bg-white rounded-2xl shadow-sm p-6 h-fit">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 rounded-full bg-slate-200"></div>
            <div>
                <p class="font-semibold">Hi, Deni 👋</p>
                <p class="text-xs text-gray-500">Member IDROOM</p>
            </div>
        </div>

        <nav class="space-y-2 text-sm">
            <RouterLink to="/account/bookings" class="menu-item">
                🏠 My Booking
            </RouterLink>
            <RouterLink to="/account/membership" class="menu-item">
                💎 Membership
            </RouterLink>
            <RouterLink to="/account/invoices" class="menu-item">
                🧾 Invoices
            </RouterLink>
        </nav>
    </div>
</template>

<style scoped>
.menu-item {
    display: block;
    padding: 10px;
    border-radius: 12px;
    transition: 0.2s;
}

.menu-item:hover {
    background: #f1f5f9;
}

.router-link-active {
    background: #d5bd7d20;
    font-weight: 600;
}
</style>
