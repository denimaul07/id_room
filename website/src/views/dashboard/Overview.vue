<template>
    <div class="space-y-8">

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">

            <div class="card">
                <p class="text-gray-500 text-sm">Total Booking</p>
                <h2 class="text-2xl font-bold mt-1">12</h2>
            </div>

            <div class="card">
                <p class="text-gray-500 text-sm">Active Membership</p>
                <h2 class="text-2xl font-bold mt-1 text-emerald-600">IDROOM PLUS</h2>
            </div>

            <div class="card">
                <p class="text-gray-500 text-sm">Total Spending</p>
                <h2 class="text-2xl font-bold mt-1">Rp 8.500.000</h2>
            </div>

        </div>

        <!-- Recent Booking -->
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-semibold mb-4">Recent Booking</h3>

            <div class="space-y-4">

                <div class="flex gap-4 items-center border rounded-xl p-4 hover:shadow-md transition">
                    <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2"
                        class="w-24 h-24 rounded-xl object-cover" />

                    <div class="flex-1">
                        <h4 class="font-semibold">Apartemen Sudirman</h4>
                        <p class="text-sm text-gray-500">12 Jan - 15 Jan</p>
                        <p class="text-sm mt-1 font-medium">Rp 2.400.000</p>
                    </div>

                    <span class="status-paid">Paid</span>
                </div>

            </div>

        </div>

    </div>
</template>

<style scoped>
.card {
    background: white;
    padding: 16px;
    border-radius: 20px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}
@media (min-width: 768px) {
    .card {
        padding: 24px;
    }
}

.status-paid {
    background: #dcfce7;
    color: #15803d;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}
</style>
