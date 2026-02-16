<template>
    <section class="max-w-6xl mx-auto px-4 py-6">

        <!-- INFO BAR -->
        <div class="flex items-center gap-3 bg-gray-100 border border-gray-200 rounded-xl px-5 py-4 mb-6">
            <div class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border">
                <!-- icon -->
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.8"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-6h13M9 7h13M5 7h.01M5 17h.01M5 12h.01" />
                </svg>
            </div>

            <p class="text-gray-700 text-[15px]">
                Temukan Booking Anda Di
                <span class="text-blue-600 font-medium cursor-pointer hover:underline" @click="$emit('menu', 'booking')">
                Pesanan Saya
                </span>
            </p>
        </div>

        <!-- FILTER BUTTON -->
        <div class="flex flex-wrap gap-4 mb-6 items-center">
            <!-- Transaction Type Filter -->
            <select
                v-model="filterType"
                @change="getData(1)"
                class="rounded-full px-6 py-2 text-sm font-semibold transition custom-select"
                :style="{
                    background: '#fff',
                    color: '#222',
                    border: '1px solid #e5e7eb',
                    boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)',
                    minWidth: '140px',
                    height: '40px',
                    marginRight: '8px',
                }"
            >
                <option value="" class="custom-option">Semua Tipe</option>
                <option value="MEMBERSHIP" class="custom-option">Membership</option>
                <option value="BOOKING" class="custom-option">Booking</option>
                <option value="TOPUP" class="custom-option">Top Up</option>
                <option value="REFUND" class="custom-option">Refund</option>
            </select>
            <button
            class="rounded-full px-6 py-2"
            :style="activeFilter === '90' ? {
                background: currentInfo.primaryColor,
                color: currentInfo.primaryTextColor,
                fontWeight: 'bold',
                border: 'none',
                boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
            } : {
                background: '#fff',
                color: '#222',
                border: '1px solid #e5e7eb'
            }"
            @click="selectFilter('90')"
            >90 Hari Terakhir</button>
            <button
            class="rounded-full px-6 py-2"
            :style="activeFilter === '30' ? {
                background: currentInfo.primaryColor,
                color: currentInfo.primaryTextColor,
                fontWeight: 'bold',
                border: 'none',
                boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
            } : {
                background: '#fff',
                color: '#222',
                border: '1px solid #e5e7eb'
            }"
            @click="selectFilter('30')"
            >{{ bulanBerjalan }}</button>
            <button
            class="rounded-full px-6 py-2"
            :style="activeFilter === '60' ? {
                background: currentInfo.primaryColor,
                color: currentInfo.primaryTextColor,
                fontWeight: 'bold',
                border: 'none',
                boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
            } : {
                background: '#fff',
                color: '#222',
                border: '1px solid #e5e7eb'
            }"
            @click="selectFilter('60')"
            >{{ bulanLalu }}</button>
            <button
            class="rounded-full px-6 py-2"
            :style="activeFilter === 'date' ? {
                background: currentInfo.primaryColor,
                color: currentInfo.primaryTextColor,
                fontWeight: 'bold',
                border: 'none',
                boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
            } : {
                background: '#fff',
                color: '#222',
                border: '1px solid #e5e7eb'
            }"
            @click="selectFilter('date')"
            >Atur tanggal</button>
            <button
            class="rounded-full px-6 py-2"
            :style="activeFilter === 'all' ? {
                background: currentInfo.primaryColor,
                color: currentInfo.primaryTextColor,
                fontWeight: 'bold',
                border: 'none',
                boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
            } : {
                background: '#000000',
                color: '#ffffff',
                border: '1px solid #e5e7eb'
            }"
            @click="filter"
            >Filter</button>
            <input
            v-model="search"
            type="text"
            placeholder="Cari transaksi..."
            class="ml-auto rounded-full border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            style="min-width: 220px;"
            />
        </div>

        <!-- DATE PANEL -->
        <div v-if="showDatePanel" class="bg-white border border-gray-200 rounded-xl mb-3 flex divide-x max-w-xs mx-auto">
            <div class="flex-1 flex items-center gap-2 p-3">
                <div class="w-3 h-3 flex items-center justify-center rounded-lg bg-gray-100">
                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor"/>
                        <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-semibold mb-1">Dari</div>
                    <input type="date" v-model="dateFrom" class="text-sm font-bold text-gray-800 border rounded px-1 py-0.5 w-[110px]" />
                    <div class="text-xs text-gray-500 mt-1">{{ formatDate(dateFrom) }}</div>
                </div>
            </div>
            <div class="flex-1 flex items-center gap-2 p-3">
                <div class="w-3 h-3 flex items-center justify-center rounded-lg bg-gray-100">
                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor"/>
                        <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs text-gray-500 font-semibold mb-1">Sampai</div>
                    <input type="date" v-model="dateTo" class="text-sm font-bold text-gray-800 border rounded px-1 py-0.5 w-[110px]" />
                    <div class="text-xs text-gray-500 mt-1">{{ formatDate(dateTo) }}</div>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-8 flex flex-col md:flex-row items-center gap-4 md:gap-8" v-if="loading">
            <!-- IMAGE -->
            <div class="w-[160px] shrink-0 animate-pulse">
                <div class="w-full h-20 bg-gray-200 rounded mb-4"></div>
                <div class="w-full h-20 bg-gray-200 rounded"></div>
            </div>

            <!-- TEXT -->
            <div class="text-center md:text-left max-w-xl w-full">
                <div class="h-5 bg-gray-200 rounded w-3/4 mb-2 animate-pulse"></div>
                <div class="h-4 bg-gray-200 rounded w-full mb-1 animate-pulse"></div>
                <div class="h-4 bg-gray-200 rounded w-full mb-1 animate-pulse"></div>
                <div class="h-4 bg-gray-200 rounded w-5/6 mb-3 animate-pulse"></div>
                <div class="h-5 bg-gray-200 rounded w-1/3 animate-pulse"></div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl p-4 md:p-8 flex flex-col md:flex-row items-center gap-4 md:gap-8" v-else-if="listData.total === 0">

            <!-- IMAGE -->
            <div class="w-[160px] shrink-0">
                <img
                src="@/assets/404/404member.png"
                class="w-full object-contain"
                />
            </div>

            <!-- TEXT -->
            <div class="text-center md:text-left max-w-xl">
                <h3 class="text-gray-800 font-semibold text-xl mb-2">
                Belum Ada Pembelian
                </h3>

                <p class="text-gray-600 leading-relaxed text-[15px] mb-3">
                Tidak ada pembelian dalam 30 hari terakhir. Jika Anda pernah melakukan
                pembelian sebelumnya, silakan gunakan Filter untuk melihatnya.
                </p>

                <router-link to="/" class="text-blue-600 font-semibold hover:underline">
                Buat Pembelian Baru
                </router-link>
            </div>
        </div>

        <div v-else class="space-y-4">
            <div
                v-for="item in listData.data"
                :key="item.odata"
                class="bg-white border border-gray-200 rounded-2xl p-5 hover:shadow-md transition-all duration-200"
            >
                <div class="flex flex-col lg:flex-row lg:items-center gap-5">

                    <!-- LEFT : Membership Info -->
                    <div class="flex-1">
                        <p class="text-xs text-gray-500 mb-1">
                        Invoice
                        </p>
                        <p class="font-semibold text-gray-800 tracking-wide">
                        {{ item.invoice_number }}
                        </p>

                        <h3 class="text-lg font-bold text-gray-900 mt-2">
                        {{ item.title }}
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                        Dibeli pada {{ formatDate(item.paid_at) }}
                        </p>
                    </div>

                    <!-- MIDDLE : Period -->
                    <div class="flex-1 bg-gray-50 rounded-xl p-4">
                        <p class="text-xs text-gray-500 mb-2">Type</p>

                        <div class="flex items-center gap-3 text-sm font-medium text-gray-700">
                        <span>{{ item.type }}</span>

                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 5l7 7-7 7" />
                        </svg>

                        <span>{{ item.description }}</span>
                        </div>
                    </div>

                    <!-- RIGHT : Price + Status -->
                    <div class="lg:text-right">
                        <p class="text-xs text-gray-500 mb-1">Nominal</p>

                        <p class="text-lg font-bold text-blue-600">
                        {{ item.total_amount
                            ? 'Rp ' + item.total_amount.toLocaleString('id-ID')
                            : '-' }}
                        </p>

                        <span
                        class="inline-block mt-3 px-3 py-1 text-xs font-semibold rounded-full"
                        :class="item.status === 'aktif'
                            ? 'bg-green-100 text-green-700'
                            : 'bg-red-100 text-red-700'"
                        >
                        {{ item.status }}
                        </span>

                        
                    </div>

                    

                </div>
                <!-- Print Invoice Button -->
                <div class="flex justify-end">
                    <button
                        @click="printInvoice(item.odata)"
                        class="mt-4 px-4 py-2 rounded-full text-xs font-semibold transition"
                        :style="{
                            background: currentInfo.primaryColor,
                            color: currentInfo.primaryTextColor,
                            border: 'none',
                            boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
                        }"
                    >
                        Print Invoice
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-6" v-if="listData.total > paginate">
            <pagination
                :current-page="listData.currentPage"
                :total-pages="listData.lastPage"
                @page-changed="getData"
            />
        </div>


    </section>

    <template v-if="showPdfModal && pdfUrl">
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-[95vw] h-[95vh] p-4 relative flex flex-col">
            <button @click="showPdfModal = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800 text-xl">&times;</button>
            <iframe :src="pdfUrl" class="flex-1 w-full rounded" frameborder="0"></iframe>
            </div>
        </div>
    </template>
</template>

<script setup>
    import { ref, computed, onMounted, watch } from 'vue'
    import { apiGetData, dayjs, loadingSubmit, apiCetakPDF } from '@/store/action'
    import { storeToRefs } from 'pinia'
    import { useInfoStore } from '@/store/info'
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => info.value?.[0] ?? {})
    const activeFilter = ref('90') // default to Bulan Berjalan
    const showDatePanel = ref(false)
    const dateFrom = ref(dayjs().startOf('month').format('YYYY-MM-DD')) // default ke awal bulan
    const dateTo = ref(dayjs().endOf('month').format('YYYY-MM-DD')) // default ke akhir bulan
    const bulanBerjalan = ref(dayjs().format('MMMM YYYY')) // bulan berjalan
    const bulanLalu = ref(dayjs().subtract(1, 'month').format('MMMM YYYY')) // bulan lalu
    const keyActive = ref('month') // key untuk filter aktif

    const formatDate = (date) => {
        return dayjs(date).format('D MMMM YYYY')
    }

    const listData = ref([])
    const search = ref('')
    const filterType = ref('')
    const paginate = ref(10)
    const loading = ref(false)
    const pdfUrl = ref("")
    const showPdfModal = ref(false)

    const getData = async (page = listData.value.currentPage) => {
        loading.value = true
        const params = {
            page: page,
            filter: activeFilter.value,
            dateFrom: dateFrom.value,
            dateTo: dateTo.value,
            search: search.value,
            paginate: paginate.value,
            keyActive: keyActive.value,
            type: filterType.value
        }

        const response = await apiGetData('/membership/list-transactions', params)
        listData.value = response.data
        loading.value = false
    }

    const filter = () => {
        getData(1) // reset ke halaman 1 saat filter
        keyActive.value = 'date' // set key filter ke 'all' saat klik tombol Filter
    }

    const printInvoice = async(invoice_number) => {
        loadingSubmit.value = true
        const response = await apiCetakPDF('/membership/printInvoice', { id: invoice_number })

        if (response) {
            const blob = new Blob([response], { type: 'application/pdf' });
            const url = URL.createObjectURL(blob);
            // Check if mobile
            if (window.innerWidth <= 768) {
                // Download PDF directly
                const link = document.createElement('a');
                link.href = url;
                link.download = `invoice_${invoice_number}.pdf`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                URL.revokeObjectURL(url);
            } else {
                pdfUrl.value = url;
                showPdfModal.value = true;
            }
        } else {
            loadingSubmit.value = false;
        }
        loadingSubmit.value = false
    }


    

    onMounted(() => {
        getData()
    })

    const selectFilter = (filter) => {
        activeFilter.value = filter
        showDatePanel.value = filter === 'date'
        if(filter !== 'date'){
            keyActive.value = 'month' // reset ke filter bulan saat ganti filter
        }else{
            keyActive.value = 'date' // set key filter ke 'date' saat pilih filter tanggal
        }
        getData(1) // reset ke halaman 1 saat filter berubah
    }



    watch(search, () => {
        getData(1) // reset ke halaman 1 saat pencarian
    })

</script>

<style scoped>
    .custom-select {
        background: #fff;
        color: #222;
        font-weight: 500;
        border-radius: 16px;
        padding: 8px 24px;
        font-size: 15px;
    }
    /* Option styling is limited by browser support */
    .custom-select option {
        background: #fff;
        color: #222;
        font-weight: 500;
        font-size: 15px;
        padding: 8px 24px;
    }
    .custom-select option:checked {
        background: #000;
        color: #fff;
    }
</style>