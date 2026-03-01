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
            placeholder="Cari membership/invoice..."
            class="ml-auto rounded-full border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            style="min-width:220px"
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
                class="bg-white border border-gray-200 rounded-2xl p-4 md:p-5 hover:shadow-md transition-all duration-200"
            >
                <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-5">

                <!-- LEFT : Membership Info -->
                <div class="flex-1">
                    <p class="text-xs text-gray-500 mb-1">
                    Invoice
                    </p>
                    <p class="font-semibold text-gray-800 tracking-wide">
                    {{ item.transactions.invoice_code }}
                    </p>

                    <h3 class="text-lg font-bold text-gray-900 mt-2">
                    {{ item.membership.title }}
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                    Dibeli pada {{ formatDate(item.transactions.paid_at) }}
                    </p>
                </div>

                <!-- MIDDLE : Period -->
                <div class="flex-1 bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-2">Periode Membership</p>

                    <div class="flex items-center gap-3 text-sm font-medium text-gray-700">
                    <span>{{ formatDate(item.start_date) }}</span>

                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 5l7 7-7 7" />
                    </svg>

                    <span>{{ formatDate(item.end_date) }}</span>
                    </div>
                </div>

                <!-- RIGHT : Price + Status -->
                <div class="lg:text-right">
                    <p class="text-xs text-gray-500 mb-1">Nominal</p>

                    <p class="text-lg font-bold text-blue-600">
                    {{ item.transactions.amount
                        ? 'Rp ' + item.transactions.amount.toLocaleString('id-ID')
                        : '-' }}
                    </p>

                    <span
                        class="inline-block mt-3 px-3 py-1 text-xs font-semibold rounded-full"
                        :class="{
                            'bg-yellow-100 text-yellow-700': item.status === 'pending',
                            'bg-green-100 text-green-700': item.status === 'active',
                            'bg-gray-100 text-gray-700': item.status === 'expired',
                            'bg-red-100 text-red-700': item.status === 'cancelled'
                        }"
                    >
                        {{ item.status }}
                    </span>
                </div>

                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8">
            <div class="text-sm text-gray-600">
                Page: {{ listData.current_page || 0 }} First: {{ (listData.current_page - 1) * paginate || 0 }} Rows: {{ paginate }}
            </div>

            <div class="flex items-center gap-2">
                <button 
                    @click="changePage(1)"
                    :disabled="listData.current_page === 1"
                    class="p-2 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    «
                </button>

                <button 
                    @click="changePage(listData.current_page - 1)"
                    :disabled="listData.current_page === 1"
                    class="p-2 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    ‹
                </button>

                <div class="flex gap-1">
                    <button 
                        v-for="page in visiblePages"
                        :key="page"
                        @click="changePage(page)"
                        :class="listData.current_page === page 
                            ? 'w-8 h-8 rounded-full  font-semibold flex items-center justify-center'
                            : 'w-8 h-8 rounded-full flex items-center justify-center'"
                        :style="listData.current_page === page 
                            ? {
                                background: currentInfo.primaryColor,
                                color: currentInfo.primaryTextColor,
                                border: 'none',
                                boxShadow: '0 2px 8px 0 rgb(0 0 0 / 0.04)'
                            } 
                            : {
                                background: '#fff',
                                color: '#222',
                                border: '1px solid #e5e7eb'
                            }"
                        >
                        {{ page }}
                    </button>
                </div>

                <button 
                    @click="changePage(listData.current_page + 1)"
                    :disabled="listData.current_page === listData.last_page"
                    class="p-2 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    ›
                </button>

                <button 
                    @click="changePage(listData.last_page)"
                    :disabled="listData.current_page === listData.last_page"
                    class="p-2 rounded hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    »
                </button>

                <select 
                    v-model="paginate"
                    @change="getData(1)"
                    class="ml-4 rounded border border-gray-300 px-3 py-1 text-sm"
                >
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>


    </section>
</template>

<script setup>
    import { ref, computed, onMounted, watch } from 'vue'
    import { apiGetData, dayjs } from '@/store/action'
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

    const listData = ref({
        data: [],
        total: 0,
        current_page: 1,
        last_page: 1
    })
    const search = ref('')
    const paginate = ref(10)
    const loading = ref(false)

    const visiblePages = computed(() => {
        const lastPage = Number(listData.value?.last_page || 1)
        const safeLastPage = Number.isFinite(lastPage) && lastPage > 0 ? lastPage : 1
        const maxPage = Math.min(5, safeLastPage)
        return Array.from({ length: maxPage }, (_, index) => index + 1)
    })

    const getData = async (page = 1) => {
        loading.value = true
        const params = {
            page: page,
            filter: activeFilter.value,
            dateFrom: dateFrom.value,
            dateTo: dateTo.value,
            search: search.value,
            paginate: Number(paginate.value),
            keyActive: keyActive.value
        }

        const response = await apiGetData('/membership/list-membership', params)
        listData.value = {
            data: response?.data?.data || [],
            total: Number(response?.data?.total || 0),
            current_page: Number(response?.data?.current_page || 1),
            last_page: Number(response?.data?.last_page || 1)
        }
        loading.value = false
    }

    const changePage = (page) => {
        const targetPage = Number(page)
        if (!Number.isFinite(targetPage)) return

        const lastPage = Number(listData.value?.last_page || 1)
        if (targetPage < 1 || targetPage > lastPage) return

        getData(targetPage)
    }

    const filter = () => {
        getData(1) // reset ke halaman 1 saat filter
        keyActive.value = 'date' // set key filter ke 'all' saat klik tombol Filter
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