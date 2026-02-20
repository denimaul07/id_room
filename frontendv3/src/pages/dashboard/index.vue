<template>
    <div>

        <div class="container-fluid">
            <div class="row">
                <Breadcrumbs main="Dashboard" />
            </div>
        </div>
        <div class="container-fluid">
            <div class="row widget-grid">
                
                <div class="col-xxl-4 col-xl-4 col-sm-12 box-col-6">
                    <div class="card profile-box">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                <div class="greeting-user">
                                    <h4 class="f-w-600 mb-0">{{greeting}}</h4>
                                    <p>Welcome {{ user.name }} hope you have a nice day today</p>
                                    <div class="whatsnew-btn z-3">
                                    ID ROOM SYSTEM
                                    </div>
                                </div>
                                </div>
                                <div>
                                <div class="clockbox">
                                    <svg id="clock" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 600">
                                    <g id="face">
                                        <circle class="circle" cx="300" cy="300" r="253.9"></circle>
                                        <path class="hour-marks"
                                        d="M300.5 94V61M506 300.5h32M300.5 506v33M94 300.5H60M411.3 107.8l7.9-13.8M493 190.2l13-7.4M492.1 411.4l16.5 9.5M411 492.3l8.9 15.3M189 492.3l-9.2 15.9M107.7 411L93 419.5M107.5 189.3l-17.1-9.9M188.1 108.2l-9-15.6">
                                        </path>
                                        <circle class="mid-circle" cx="300" cy="300" r="16.2"></circle>
                                    </g>
                                    <g id="hour">
                                        <path class="hour-hand" d="M300.5 298V142"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="minute">
                                        <path class="minute-hand" d="M300.5 298V67"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    <g id="second">
                                        <path class="second-hand" d="M300.5 350V55"></path>
                                        <circle class="sizing-box" cx="300" cy="300" r="253.9"></circle>
                                    </g>
                                    </svg>
                                </div>
                                <div class="badge f-10 p-0" id="txt"></div>
                                </div>
                            </div>
                            <div class="cartoon">
                                <img class="img-fluid" src="@/assets/images/dashboard/cartoon.svg"
                                alt="vector women with leptop" width="80%"/>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" v-for="data in 2" v-if="loading">

                            <a href="#" class="card widget-1">
                                <div class="card-body">
                                    <div class="widget-content">
                                        <div class="widget-round" :class="data.class">
                                            <div class="bg-round">
                                                <a-skeleton-avatar :active="true" />
                                            </div>
                                        </div>
                                        <div>
                                            <h4><a-skeleton-input :active="true" /></h4>
                                            <span class="f-light">
                                                <a-skeleton-input :active="true" size="small"/>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12" v-for="data in 3" v-if="loading">

                            <div class="card widget-1">
                                <div class="card-body">
                                    <div class="widget-content">
                                        <div class="widget-round" :class="data.class">
                                            <div class="bg-round">
                                                <a-skeleton-avatar :active="true" />
                                            </div>
                                        </div>
                                        <div>
                                            <h4><a-skeleton-input :active="true" /></h4>
                                            <span class="f-light">
                                                <a-skeleton-input :active="true" size="small"/>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12" v-for="data in state.data" :key="data.title" v-else>
                            <router-link :to="data.url"  class="card widget-1">
                                <div class="card-body">
                                    <div class="widget-content" >
                                        <div class="widget-round" :class="data.class">
                                            <div class="bg-round">
                                                <svg class="svg-fill">
                                                    <use :xlink:href="iconSpritePath + `#${data.icon}`"></use>
                                                </svg>
                                                <svg class="half-circle svg-fill">
                                                    <use :xlink:href="iconSpritePath + `#halfcircle`"></use>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <h5>{{ data.number }}</h5>
                                            <span class="f-light">
                                                {{ data.title }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </router-link >
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                    <h5 class="mb-0">Revenue Composition</h5>
                                </div>
                                <div class="card-body">
                                    <apexchart type="donut" height="350" :options="pieOptions" :series="pieSeries"></apexchart>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                    <div class="row">
                        <h2 class="text-center text-white">Summary Dashboard Overview</h2>
                        <h6 class="text-center text-white mb-4">   {{ filterLabel }}</h6>
                        <!-- Filter Section -->

                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label class="form-label mb-0 fw-bold">
                                                <i class="fa fa-filter"></i> Filter :
                                            </label>
                                        </div>
                                        <div class="col-auto">
                                            <select v-model="selectedFilter" class="form-select form-select-sm" @change="filter">
                                                <option value="today">Hari ini</option>
                                                <option value="month">Bulan ini</option>
                                                <option value="year">Tahun ini</option>
                                                <option value="custom">Custom Filter</option>
                                            </select>
                                        </div>
                                        <div v-if="selectedFilter === 'custom'" class="col-auto">
                                            <input type="date" v-model="customStart" class="form-control form-control-sm" @change="filter" />
                                        </div>
                                        <div v-if="selectedFilter === 'custom'" class="col-auto">
                                            <input type="date" v-model="customEnd" class="form-control form-control-sm" @change="filter" />
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-primary btn-sm" @click="clear" :disabled="loading">
                                                <ReloadOutlined />
                                                {{ loading ? 'Loading...' : 'Reset' }}
                                            </button>
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-success btn-sm" @click="exportReport" :disabled="loading">
                                                <FileExcelOutlined />
                                                {{ loading ? 'Loading...' : 'Export Data' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="col-md-12" v-for="data in 2" v-if="loading">

                            <a href="#" class="card widget-1">
                                <div class="card-body">
                                    <div class="widget-content">
                                        <div class="widget-round" :class="data.class">
                                            <div class="bg-round">
                                                <a-skeleton-avatar :active="true" />
                                            </div>
                                        </div>
                                        <div>
                                            <h4><a-skeleton-input :active="true" /></h4>
                                            <span class="f-light">
                                                <a-skeleton-input :active="true" size="small"/>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-12 pb-2" v-else>
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 text-dark fw-bold">
                                        <i class="fa fa-exclamation-triangle text-warning me-2"></i>
                                        Operational Alerts
                                    </h5>
                                </div>
                                <div class="card-body py-4">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <router-link
                                                to="/admin/topup?status=pending"
                                                class="alert alert-warning d-flex flex-column justify-content-between h-100 p-4 border-0 rounded-3 shadow-sm text-decoration-none transition"
                                                style="min-height: 140px;"
                                            >
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-semibold text-warning-emphasis">
                                                        Pending Topup Verification
                                                    </span>
                                                    <span class="badge bg-warning text-dark fs-5 rounded-circle p-3 shadow-sm">
                                                        ⏳
                                                    </span>
                                                </div>
                                                <div class="mt-3 mb-1 fs-2 fw-bold text-white text-center">
                                                    {{ need_attention.pending_topup || 0 }}
                                                </div>
                                                <div class="small text-warning-emphasis text-center">
                                                    Waiting for admin confirmation
                                                </div>
                                                <div class="mt-2 text-center">
                                                    <span class="fw-semibold text-warning-emphasis text-decoration-underline">
                                                        View Transactions →
                                                    </span>
                                                </div>
                                            </router-link>
                                        </div>
                                        <div class="col-md-4">
                                            <router-link
                                                to="/admin/booking?status=expired"
                                                class="alert alert-info d-flex flex-column justify-content-between h-100 p-4 border-0 rounded-3 shadow-sm text-decoration-none transition"
                                                style="min-height: 140px;"
                                            >
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-semibold text-info-emphasis">
                                                        Booking Unpaid &gt; 1 Hour
                                                    </span>
                                                    <span class="badge bg-info text-dark fs-5 rounded-circle p-3 shadow-sm">
                                                        🕒
                                                    </span>
                                                </div>
                                                <div class="mt-3 mb-1 fs-2 fw-bold text-white o text-center">
                                                    {{ need_attention.unpaid_booking || 0 }}
                                                </div>
                                                <div class="small text-info-emphasis text-center">
                                                    Will release inventory soon
                                                </div>
                                                <div class="mt-2 text-center">
                                                    <span class="fw-semibold text-info-emphasis text-decoration-underline">
                                                        Review Booking →
                                                    </span>
                                                </div>
                                            </router-link>
                                        </div>
                                        <div class="col-md-4">
                                            <router-link
                                                to="/admin/transaction?status=failed"
                                                class="alert alert-danger d-flex flex-column justify-content-between h-100 p-4 border-0 rounded-3 shadow-sm text-decoration-none transition"
                                                style="min-height: 140px;"
                                            >
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="fw-semibold text-danger-emphasis">
                                                        Failed Payment
                                                    </span>
                                                    <span class="badge bg-danger text-white fs-5 rounded-circle p-3 shadow-sm">
                                                        ❌
                                                    </span>
                                                </div>
                                                <div class="mt-3 mb-1 fs-2 fw-bold text-white text-center">
                                                    {{ need_attention.failed_payment || 0 }}
                                                </div>
                                                <div class="small text-danger-emphasis text-center">
                                                    Payment needs investigation
                                                </div>
                                                <div class="mt-2 text-center">
                                                    <span class="fw-semibold text-danger-emphasis text-decoration-underline">
                                                        View Logs →
                                                    </span>
                                                </div>
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                    <h5 class="mb-0">Revenue Trend</h5>
                                </div>
                                <div class="card-body">
                                    <div v-if="loading">
                                        <a-skeleton active :paragraph="{ rows: 8 }" />
                                    </div>
                                    <div v-else>
                                        <Tabs value="0" class="p-tab-active">
                                            <TabList class="p-tab-active" style="color: black;">
                                                <Tab value="0">
                                                    <span style="color: #222 !important;"> To days Revenue</span>
                                                </Tab>
                                                <Tab value="1">
                                                    <span style="color: #222 !important;"> The Last 7 Days Revenue</span>
                                                </Tab>
                                                <Tab value="2">
                                                    <span style="color: #222 !important;"> This Month Revenue</span>
                                                </Tab>
                                                <Tab value="3">
                                                    <span style="color: #222 !important;"> This Year Revenue</span>
                                                </Tab>
                                            </TabList>
                                            <TabPanel value="0">
                                                <apexchart type="bar" height="350" :options="chartOptions" :series="series"></apexchart>
                                            </TabPanel>
                                            <TabPanel value="1">
                                                <apexchart type="bar" height="350" :options="weeklyOptions" :series="weeklySeries"></apexchart>
                                            </TabPanel>
                                            <TabPanel value="2">
                                                <apexchart type="bar" height="350" :options="monthlyOptions" :series="monthlySeries"></apexchart>
                                            </TabPanel>
                                            <TabPanel value="3">
                                                <apexchart type="bar" height="350" :options="yearsOptions" :series="yearsSeries"></apexchart>
                                            </TabPanel>
                                        </Tabs>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                    <h5 class="mb-0">Live Transaction Feed</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive pt-2  d-md-block d-none">
                                        <table class="table">
                                            <thead>
                                                <tr class="border-bottom-primary">
                                                    <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                    <th class="bg-primary text-nowrap text-center">User</th>
                                                    <th class="bg-primary text-nowrap text-center">Activity</th>
                                                    <th class="bg-primary text-nowrap text-center">Amount</th>
                                                    <th class="bg-primary text-nowrap text-center">Status</th>
                                                    <th class="bg-primary text-nowrap text-center">Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading"> 
                                                    <td class="text-center" colspan="7"><a-skeleton active /></td>
                                                </tr>
                                                <tr v-else-if="state.listTransaction.length==0">
                                                    <td class="text-center" colspan="7"><a-empty/></td>
                                                </tr>
                                                <tr v-for="(item, index) in state.listTransaction" :key="item.id" v-else>
                                                    <td class="text-center sticky-column">{{ index + 1 }}</td>
                                                    <td class="text-nowrap text-center">{{ item.user }}</td>
                                                    <td class="text-nowrap">{{ item.activity }}</td>
                                                    <td class="text-nowrap text-left">{{ item.amount.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td>
                                                    <td class="text-nowrap text-center">
                                                        <span
                                                            :class="[
                                                                'badge',
                                                                'text-white',
                                                                'fw-semibold',
                                                                item.status === 'PAID'
                                                                    ? 'bg-success'
                                                                    : item.status === 'PENDING'
                                                                    ? 'bg-warning'
                                                                    : item.status === 'FAILED'
                                                                    ? 'bg-danger'
                                                                    : item.status === 'REFUNDED'
                                                                    ? 'bg-info'
                                                                    : item.status === 'CANCELLED'
                                                                    ? 'bg-secondary'
                                                                    : 'bg-light text-dark'
                                                            ]"
                                                        >
                                                            {{ item.status }}
                                                        </span>
                                                    </td>
                                                    <td class="text-nowrap text-center">{{ dayjs(item.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>  
                                </div>
                            </div>

                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                    <h5 class="mb-0">Property Performance {{ filterLabel }}</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive pt-2  d-md-block d-none">
                                        <table class="table">
                                            <thead>
                                                <tr class="border-bottom-primary">
                                                    <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                    <th class="bg-primary text-nowrap text-center">Property Name</th>
                                                    <th class="bg-primary text-nowrap text-center">Total Bookings</th>
                                                    <th class="bg-primary text-nowrap text-center">Revenue</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading"> 
                                                    <td class="text-center" colspan="7"><a-skeleton active /></td>
                                                </tr>
                                                <tr v-else-if="state.listProperty.total==0">
                                                    <td class="text-center" colspan="7"><a-empty/></td>
                                                </tr>
                                                <tr v-for="(item, index) in state.listProperty.data" :key="item.id" v-else>
                                                    <td class="text-center sticky-column">{{ index + state.listProperty.from }}</td>
                                                    <td class="text-nowrap text-left">{{ item.property }}</td>
                                                    <td class="text-nowrap text-center">{{ item.booking_today }}</td>
                                                    <td class="text-nowrap text-left">{{ item.revenue.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>  

                                    <div class="row py-2">
                                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                            Showing {{ state.listProperty.from }} to {{ state.listProperty.to }} of {{ state.listProperty.total }} entries
                                        </div>
                                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                            <a-pagination :current="state.listProperty.current_page" :total="state.listProperty.total" v-model:pageSize="pagging"
                                                @change="handlePageChange" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
            <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
            </div>

            <div style="align-items:center;justify-content: center;display: flex;">
                {{ pesan }}
            </div>
        </a-modal>

        <a-modal v-model:open="modalPDF" :footer="null" style="top: 20px" :closable=true  title="Cetak Form Pengadaan Barang" width="2000px">
            <div class="col-12">
                <iframe :src="pdfUrl"  width="100%" height="700px"  fullscreen="true" />
            </div>
        </a-modal>
    </div>
</template>

<script setup>
    import { apiGetData, apiCetakPDF, apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import axios from 'axios';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";
    import iconSpritePath from '@/assets/svg/icon-sprite.svg';
    import {
        PrinterOutlined,
        ReloadOutlined,
        FileExcelOutlined
    } from '@ant-design/icons-vue';
    import checkRole from '@/store/modules/role.js';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    const isSuperAdmin = checkRole(['superAdmin','admin']);
    const isStaff = checkRole(['staff']);
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const chartOptions = ref({});
    const series = ref([]);
    const weeklyOptions = ref({});
    const weeklySeries = ref([]);
    const monthlyOptions = ref({});
    const monthlySeries = ref([]);
    const yearsOptions = ref({});
    const yearsSeries = ref([]);
    const pieSeries = ref([]);
    const pieOptions = ref({});
    const interval = ref(null);
    const pagging = ref(5);
    const modalPDF = ref(false);
    const pdfUrl = ref("");
    const need_attention = ref([]);

    // Filter states
    const selectedYear = ref(parseInt(dayjs().format('YYYY')));
    

    const timerSettings = () => {
        const HOURHAND = document.querySelector("#hour");
        const MINUTEHAND = document.querySelector("#minute");
        const SECONDHAND = document.querySelector("#second");
        const txtClock = document.getElementById("txt");

        // Pastikan elemen ditemukan
        if (!HOURHAND || !MINUTEHAND || !SECONDHAND || !txtClock) {
        console.error("Element jam tidak ditemukan di DOM!");
        return;
        }

        function runClock() {
            const date = new Date();
            let hr = date.getHours();
            let min = date.getMinutes();
            let sec = date.getSeconds();
            const ampm = hr >= 12 ? "PM" : "AM";

            // Konversi ke format 12 jam
            hr = hr % 12 || 12;

            // Format agar selalu dua digit
            const formattedHr = hr.toString().padStart(2, "0");
            const formattedMin = min.toString().padStart(2, "0");
            const formattedSec = sec.toString().padStart(2, "0");

            // Hitung posisi jarum jam
            const hrPosition = (hr * 360) / 12 + (min * (360 / 60)) / 12;
            const minPosition = (min * 360) / 60 + (sec * (360 / 60)) / 60;
            const secPosition = (sec * 360) / 60;

            // Update posisi jarum jam
            HOURHAND.style.transform = `rotate(${hrPosition}deg)`;
            MINUTEHAND.style.transform = `rotate(${minPosition}deg)`;
            SECONDHAND.style.transform = `rotate(${secPosition}deg)`;

            // Update teks jam
            txtClock.innerHTML = `${formattedHr}:${formattedMin}:${formattedSec} ${ampm}`;
        }

        // Jalankan clock langsung agar tidak menunggu 1 detik pertama
        runClock();

        // Interval untuk memperbarui setiap detik
        setInterval(runClock, 1000);
    };

    const currentTime = ref(dayjs().hour());

    const greeting = computed(() => {
        if (currentTime.value >= 5 && currentTime.value < 12) {
            return "Good Morning ☀️";
        } else if (currentTime.value >= 12 && currentTime.value < 18) {
            return "Good Afternoon 🌤️";
        } else if (currentTime.value >= 18 && currentTime.value < 22) {
            return "Good Evening 🌆";
        } else {
            return "Good Night 🌙";
        }
    });


    const state = reactive({
        data:{},
        listTransaction:{},
        listProperty:{}
    });


    const clear = async () => {
        selectedFilter.value = 'today';
        customStart.value = dayjs().startOf('day').format('YYYY-MM-DD');
        customEnd.value = dayjs().endOf('day').format('YYYY-MM-DD');
        await getData();
    };

    const getData = async (page = state.listProperty.current_page || 1) => {
        loading.value = true;
        const params = {page: page, selectedFilter: selectedFilter.value, customStart: customStart.value, customEnd: customEnd.value, pagging: pagging.value };
        
        const response = await apiGetData("/dashboard/summary", params);
        state.data = response.data;
        state.listTransaction = response.live_transaction_feed;
        need_attention.value = response.need_attention || [];
        state.listProperty = response.property_performance || [];

        // Daily
        const daily = response.transaction_chart?.daily || {};
        series.value = [
            { name: 'Topup', data: daily.topup || [] },
            { name: 'Booking', data: daily.booking || [] },
            { name: 'Membership', data: daily.membership || [] },
        ];
        chartOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: daily.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: '$ (thousands)',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString(),
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Weekly
        const weekly = response.transaction_chart?.weekly || {};
        weeklySeries.value = [
            { name: 'Topup', data: weekly.topup || [] },
            { name: 'Booking', data: weekly.booking || [] },
            { name: 'Membership', data: weekly.membership || [] },
        ];
        weeklyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: weekly.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: '$ (thousands)',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    }
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString(),
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => val
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
            }
        };

        // Monthly
        const monthly = response.transaction_chart?.monthly || {};
        monthlySeries.value = [
            { name: 'Topup', data: monthly.topup || [] },
            { name: 'Booking', data: monthly.booking || [] },
            { name: 'Membership', data: monthly.membership || [] },
        ];
        monthlyOptions.value = {
            chart: {
                height: 350,
                type: 'bar',
                stacked: false,
                toolbar: { show: true }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'bottom',
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4
                }
            },
            xaxis: {
                categories: monthly.dates || [],
                labels: {
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            yaxis: {
                min: 0,
                title: {
                    text: 'Rp (dalam ribuan)',
                    style: {
                        color: '#222',
                        fontSize: '14px'
                    },
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString(),
                    style: {
                        colors: '#222',
                        fontSize: '14px'
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            },
            fill: {
                opacity: 1
            },
            grid: {
                borderColor: '#e0e0e0'
                        }
        };

        // Yearly
        const yearly = response.transaction_chart?.yearly || {};
        yearsSeries.value = [
            { name: 'Topup', data: yearly.topup || [] },
            { name: 'Booking', data: yearly.booking || [] },
            { name: 'Membership', data: yearly.membership || [] },
        ];

        yearsOptions.value = {
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 5,
                    borderRadiusApplication: 'end',
                    dataLabels: {
                        position: 'top'
                    }
                },
            },
            dataLabels: {
                enabled: true,
                formatter: val => 'Rp ' + val.toLocaleString(),
                offsetY: -10,
                style: {
                    fontSize: '12px',
                    colors: ['#222']
                }
            },
                stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct','Nov','Dec'],
            },
            yaxis: {
                title: {
                    text: 'Rp (dalam ribuan)'
                },
                labels: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: val => 'Rp ' + val.toLocaleString()
                }
            }
        };

        // Pie chart
        const composition = response.transaction_composition || {};
        pieSeries.value = [composition.booking || 0, composition.topup || 0, composition.membership || 0];
        pieOptions.value = {
            chart: {
                type: 'donut',
                height: 350
            },
            labels: ['Booking', 'Topup', 'Membership'],
            colors: ['#4CAF50', '#2196F3', '#FF9800'],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                formatter: val => val + '%',
                style: {
                    fontSize: '14px',
                    colors: ['#ffffff']
                }
            },
            tooltip: {
                y: {
                    formatter: val => val + '%'
                }
            }
        };
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page)
    }


     // Filter states
    const selectedFilter = ref('today');
    const customStart = ref('');
    const customEnd = ref('');

    // Label filter
    const filterLabel = computed(() => {
        if (selectedFilter.value === 'today') return 'Hari ini';
        if (selectedFilter.value === 'month') return 'Bulan ini';
        if (selectedFilter.value === 'year') return 'Tahun ini';
        if (selectedFilter.value === 'custom') {
            if (customStart.value && customEnd.value) {
                return `${customStart.value} s/d ${customEnd.value}`;
            }
            return 'Custom Filter';
        }
        return '';
    });

    // Update handleFilterChange
    const filter = async () => {
        let params = {};
        if (selectedFilter.value === 'today') {
            params.filter = 'today';
        } else if (selectedFilter.value === 'month') {
            params.filter = 'month';
        } else if (selectedFilter.value === 'year') {
            params.filter = 'year';
        } else if (selectedFilter.value === 'custom') {
            params.filter = 'custom';
            params.start = customStart.value;
            params.end = customEnd.value;
        }
        // Panggil API dashboard dengan params filter
        await getData(params);
    };



    const exportReport = async () => {
        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/dashboard/exportexcelReport", {
            year: selectedYear.value
        }, 'Summary Data Sales Askara Aktiv')

        if(response){
            processing.value = false
        }else{
            processing.value = false
        }
    };

    

    onMounted(async() => {
        if (isStaff) {
            router.push({ name: "index_store" });
            return; // Stop execution immediately
        }
        
        timerSettings()
        await getData();
    })

    onUnmounted(() => {
        clearTimeout(interval.value);
    })

</script>

<style scoped>
    .properties-title {
        color: #222 !important;
    }

</style>