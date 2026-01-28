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
                                    ASKARA AKTIV SYSTEM
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

                        <div class="col-md-12" v-for="data in state.profit" :key="data.title" v-else>
                            <div class="card widget-1" v-if="isSuperAdmin">
                                <div class="card-body">
                                    <div class="widget-content">
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
                                            <h4>{{ data.number }}</h4>
                                            <span class="f-light">
                                                {{ data.title }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div >
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center p-3">
                                    <h5 class="mb-0">Sales Store By Invoice {{ getMonthName(selectedMonth) }} {{ selectedYear }}</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive pt-2  d-md-block d-none">
                                        <table class="table">
                                            <thead>
                                                
                                                <tr class="border-bottom-primary">
                                                    <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                    <th class="bg-primary text-nowrap text-center sticky-column1">Store Name</th>
                                                    <th class="bg-primary text-nowrap text-center">Total Sales</th>
                                                    <th class="bg-primary text-nowrap text-center">Action</th>
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <tr v-if="loading"> 
                                                    <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                </tr>

                                                <tr v-else-if="state.topProducts.length==0">
                                                    <td class="text-center" colspan="13"><a-empty/></td>
                                                </tr>
                                                <tr v-for="(item, index) in state.store" :key="item.odata" v-else>
                                                    <td class="text-center sticky-column">{{ 1 + index }}</td>
                                                    <td class=" sticky-column1">
                                                        {{ item.nama_toko }}
                                                    </td>
                                                    <td class="text-nowrap">{{ item.total }}</td>
                                                    <td class="text-nowrap text-center">
                                                        <a-tooltip title="Print Invoice Store">
                                                            <a-button type="primary" size="small" @click="printInvoice(item)" class="bg-biru me-3">
                                                                <template #icon>
                                                                    <PrinterOutlined />
                                                                </template>
                                                            </a-button>
                                                        </a-tooltip>

                                                        <a-tooltip title="Export Invoice Store">
                                                            <a-button type="primary" size="small" @click="exportInvoice(item)" class="bg-success">
                                                                <template #icon>
                                                                    <FileExcelOutlined />
                                                                </template>
                                                            </a-button>
                                                        </a-tooltip>
                                                    </td>
                                                
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-2 d-md-none d-block">
                                        <div v-if="loading" v-for="index in 3">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <a-skeleton-input :active="true" />
                                                    <a-skeleton-input :active="true" size="small" class="align-self-center" />
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <a-skeleton-input size="small" :active="true" />
                                                    <a-skeleton-input size="small" :active="true" />
                                                </div>
                                                <a-skeleton-input class="mt-2" :active="true" />
                                            </div>
                                            <div
                                                class="py-2 card-footer d-flex justify-content-between text-primary text-opacity-75 small">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                            <div class="py-2 card-footer">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                            <div class="py-1 card-footer">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                        </div>

                                        <div v-else-if="state.topProducts.length==0">
                                            <div class="card-body">
                                                <a-empty/>
                                            </div>
                                        </div>

                                        <div v-else v-for="(item, index) in state.store" :key="item.id" class="pb-2 card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <b>Store Name</b>
                                                    <small class="align-self-center">
                                                        <span class="badge badge-sm badge-primary text-white fw-semibold">{{ item.nama_toko }}</span>
                                                    
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="card-body">

                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <small class="text-muted align-self-center">
                                                        {{ item.total }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center  p-3">
                                    <h5 class="mb-0">Top Reference Sales {{ getMonthName(selectedMonth) }} {{ selectedYear }}</h5>
                                </div>
                                <div class="card-body  p-3">
                                    <div class="table-responsive pt-2  d-md-block d-none">
                                        <table class="table">
                                            <thead>
                                                
                                                <tr class="border-bottom-primary">
                                                    <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                    <th class="bg-primary text-nowrap text-center sticky-column1">Reference</th>
                                                    <th class="bg-primary text-nowrap text-center">Total Sales</th>
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <tr v-if="loading"> 
                                                    <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                </tr>

                                                <tr v-else-if="state.topReference.total==0">
                                                    <td class="text-center" colspan="13"><a-empty/></td>
                                                </tr>
                                                <tr v-for="(item, index) in state.topReference.data" :key="item.odata" v-else>
                                                    <td class="text-center sticky-column">{{ state.topReference.from + index }}</td>
                                                    <td class=" sticky-column1">
                                                        {{ item.reference }}
                                                    </td>
                                                    <td class="text-nowrap text-center">{{ item.total }}</td>
                                                
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-2 d-md-none d-block">
                                        <div v-if="loading" v-for="index in 3">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <a-skeleton-input :active="true" />
                                                    <a-skeleton-input :active="true" size="small" class="align-self-center" />
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <a-skeleton-input size="small" :active="true" />
                                                    <a-skeleton-input size="small" :active="true" />
                                                </div>
                                                <a-skeleton-input class="mt-2" :active="true" />
                                            </div>
                                            <div
                                                class="py-2 card-footer d-flex justify-content-between text-primary text-opacity-75 small">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                            <div class="py-2 card-footer">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                            <div class="py-1 card-footer">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                        </div>

                                        <div v-else-if="state.topProducts.total==0">
                                            <div class="card-body">
                                                <a-empty/>
                                            </div>
                                        </div>

                                        <div v-else v-for="(item, index) in state.topReference.data" :key="item.id" class="pb-2 card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <b>Reference</b>
                                                    <small class="align-self-center">
                                                        <span class="badge badge-sm badge-primary text-white fw-semibold">{{ item.reference }}</span>
                                                    
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <small class="text-muted align-self-center">
                                                        {{ item.total }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row py-2">
                                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                            Showing {{ state.topReference.from }} to {{ state.topReference.to }} of {{ state.topReference.total }} entries
                                        </div>
                                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                            <a-pagination :current="state.topReference.current_page" :total="state.topReference.total" v-model:pageSize="pagging" @change="handlePageChangeReference" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-8 col-xl-8 col-sm-12 box-col-12">
                    <div class="row">
                        <h2 class="text-center text-white">Summary Data Sales Askara Aktiv </h2>
                        
                        <!-- Filter Section -->
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <label class="form-label mb-0 fw-bold">
                                                <i class="fa fa-filter"></i>Filter :
                                            </label>
                                        </div>
                                        <div class="col-auto">
                                            <select 
                                                v-model="selectedMonth" 
                                                class="form-select form-select-sm"
                                                @change="handleFilterChange"
                                            >
                                                <option value="all">All Months</option>
                                                <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                                    {{ month }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <select 
                                                v-model="selectedYear" 
                                                class="form-select form-select-sm"
                                                @change="handleFilterChange"
                                            >
                                                <option v-for="year in years" :key="year" :value="year">
                                                    {{ year }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button 
                                                class="btn btn-primary btn-sm"
                                                @click="clear"
                                                :disabled="loading"
                                            >
                                                <ReloadOutlined  />
                                                {{ loading ? 'Loading...' : 'Reset' }}
                                            </button>
                                        </div>

                                        <div class="col-auto">
                                            <button 
                                                class="btn btn-success btn-sm"
                                                @click="exportReport"
                                                :disabled="loading"
                                            >
                                                <FileExcelOutlined  />
                                                {{ loading ? 'Loading...' : 'Export Data' }}
                                            </button>
                                        </div>

                                        <div class="col-auto">
                                            <button 
                                                class="btn btn-info btn-sm"
                                                @click="printReport"
                                                :disabled="loading"
                                            >
                                                <PrinterOutlined  />
                                                {{ loading ? 'Loading...' : 'Print Report' }}
                                            </button>
                                        </div>

                                        <div class="col-auto ms-auto">
                                            <span class="badge bg-info text-white p-2">
                                                <i class="fa fa-calendar-alt me-1"></i>
                                                {{ getMonthName(selectedMonth) }} {{ selectedYear }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4" v-for="data in 3" v-if="loading">

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

                        <div :class="isSuperAdmin ? 'col-4' : 'col-6'" v-for="data in state.data" :key="data.title" v-else>
                            <div class="card widget-1">
                                <div class="card-body">
                                    <div class="widget-content">
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
                            </div >
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-body">
                                    <apexchart type="line" height="350" :options="chartOptions" :series="series"></apexchart>
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-12 mt-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center  p-3">
                                    <h5 class="mb-0">Top Product Varian Sales {{ getMonthName(selectedMonth) }} {{ selectedYear }}</h5>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive pt-2  d-md-block d-none">
                                        <table class="table">
                                            <thead>
                                                
                                                <tr class="border-bottom-primary">
                                                    <th class="bg-primary text-nowrap text-center sticky-column">No</th>
                                                    <th class="bg-primary text-nowrap text-center sticky-column1">Product Code</th>
                                                    <th class="bg-primary text-nowrap text-center">Product Name</th>
                                                    <th class="bg-primary text-nowrap text-center">Product SKU</th>
                                                    <th class="bg-primary text-nowrap text-center">Total Sales</th>
                                                    
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <tr v-if="loading"> 
                                                    <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                </tr>

                                                <tr v-else-if="state.topProducts.total==0">
                                                    <td class="text-center" colspan="13"><a-empty/></td>
                                                </tr>
                                                <tr v-for="(item, index) in state.topProducts.data" :key="item.odata" v-else>
                                                    <td class="text-center sticky-column">{{ state.topProducts.from + index }}</td>
                                                    <td class=" sticky-column1">
                                                        {{ item.product_code }}
                                                    </td>
                                                    <td class="text-nowrap">{{ item.product_name }}</td>
                                                    <td class="text-nowrap">{{ item.product_sku }}</td>
                                                    <td class="text-nowrap text-center">{{ item.total }}</td>
                                                
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="p-2 d-md-none d-block">
                                        <div v-if="loading" v-for="index in 3">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <a-skeleton-input :active="true" />
                                                    <a-skeleton-input :active="true" size="small" class="align-self-center" />
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <a-skeleton-input size="small" :active="true" />
                                                    <a-skeleton-input size="small" :active="true" />
                                                </div>
                                                <a-skeleton-input class="mt-2" :active="true" />
                                            </div>
                                            <div
                                                class="py-2 card-footer d-flex justify-content-between text-primary text-opacity-75 small">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                            <div class="py-2 card-footer">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                            <div class="py-1 card-footer">
                                                <a-skeleton-button :active="true" size="small" block="block" />
                                            </div>
                                        </div>

                                        <div v-else-if="state.topProducts.total==0">
                                            <div class="card-body">
                                                <a-empty/>
                                            </div>
                                        </div>

                                        <div v-else v-for="(item, index) in state.topProducts.data" :key="item.id" class="pb-2 card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between">
                                                    <b>Product Code</b>
                                                    <small class="align-self-center">
                                                        <span class="badge badge-sm badge-primary text-white fw-semibold">{{ item.product_code }}</span>
                                                    
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    Product Name
                                                    <small class="text-muted align-self-center">
                                                        {{ item.product_name }}
                                                    </small>
                                                </div>

                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <small class="text-muted align-self-center">
                                                        {{ item.total }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="row py-2">
                                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                            Showing {{ state.topProducts.from }} to {{ state.topProducts.to }} of {{ state.topProducts.total }} entries
                                        </div>
                                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                            <a-pagination :current="state.topProducts.current_page" :total="state.topProducts.total" v-model:pageSize="pagging" @change="handlePageChange" />
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
    const isSuperAdmin = checkRole(['superAdmin']);
    const isAdmin = checkRole(['admin']);
    const isStaff = checkRole(['staff']);
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const series = ref(null)
    const chartOptions = ref("")
    const interval = ref(null);
    const pagging = ref(5);
    const modalPDF = ref(false);
    const pdfUrl = ref("");

    // Filter states
    const selectedMonth = ref(parseInt(dayjs().format('MM')));
    const selectedYear = ref(parseInt(dayjs().format('YYYY')));
    
    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December',
    ];
    
    const years = computed(() => {
        const currentYear = parseInt(dayjs().format('YYYY'));
        const yearsList = [];
        for (let i = currentYear - 5; i <= currentYear + 1; i++) {
            yearsList.push(i);
        }
        return yearsList.reverse();
    });

    const getMonthName = (monthNumber) => {
        if (monthNumber === 'all') return 'All Months';
        return months[monthNumber - 1];
    };

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
        profit:{},
        topProducts:{},
        store:{},
        topReference:{}
    });

    const handleFilterChange = async () => {
        await getData();
    };

    const clear = async () => {
        selectedMonth.value = 'all';
        selectedYear.value = parseInt(dayjs().format('YYYY'));
        await getData();
    };

    const getData = async (page = state.topProducts.current_page || 1) => {
        loading.value = true;
        const params = {page: page, year: selectedYear.value, per_page: pagging.value };
        
        if (selectedMonth.value !== 'all') {
            params.month = String(selectedMonth.value).padStart(2, '0');
        }
        
        const response = await apiGetData("/dashboard/summary", params);
        state.data = response.data;
        state.profit = response.profit;
        state.topProducts = response.topProducts;
        state.store = response.store;
        state.topReference = response.topReference;

        // Map data dari response.chart
        const chartData = response.chart || [];
        const salesData = chartData.map(item => item.total);
        const monthLabels = chartData.map(item => {
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return monthNames[parseInt(item.bulan_angka) - 1];
        });

        series.value = [{
            name: "Total Sales",
            data: salesData
        }];
        chartOptions.value = {
            chart: {
            height: 350,
            type: 'line',
            zoom: { enabled: false }
            },
            dataLabels: {
            enabled: true,
            formatter: (val) => val,
            offsetY: -10
            },
            markers: {
            size: 4,
            hover: { size: 6 }
            },
            stroke: {
            curve: 'straight'
            },
            title: {
            text: 'Sales Chart by Month',
            align: 'left'
            },
            grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'],
                opacity: 0.5
            }
            },
            xaxis: {
            categories: monthLabels
            }
        };
        loading.value = false;
    };

    const handlePageChange = async (page) => {
        const params = {page: page, year: selectedYear.value, per_page: pagging.value };
        
        if (selectedMonth.value !== 'all') {
            params.month = String(selectedMonth.value).padStart(2, '0');
        }
        const response = await apiGetData("/dashboard/summary", params);
        state.topProducts = response.topProducts;
    };

    const handlePageChangeReference = async (page) => {
        const params = {page: page, year: selectedYear.value, per_page: pagging.value };
        
        if (selectedMonth.value !== 'all') {
            params.month = String(selectedMonth.value).padStart(2, '0');
        }
        const response = await apiGetData("/dashboard/summary", params);
        state.topReference = response.topReference;
    };

    const printInvoice = async (item) => {
        processing.value = true
        pesan.value="Sabar Dikit ya, Lagi Proses Generate  Data"

        const response = await apiCetakPDF(`/dashboard/printInvoice`, {
            toko: item.toko,
            month : selectedMonth.value,
            year: selectedYear.value
        })

        if (response) {
            processing.value = false;
            modalPDF.value = true;
            pdfUrl.value = URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
            await getData()
        } else {
            processing.value = false;
        }
    };

    const exportInvoice = async (item) => {
        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/dashboard/exportexcelInvoice", {
            toko: item.toko,
            month : selectedMonth.value,
            year: selectedYear.value
        }, 'Data Invoice Sales')

        if(response){
            processing.value = false
        }else{
            processing.value = false
        }
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

    const printReport = async () => {
        processing.value = true
        pesan.value="Sabar Dikit ya, Lagi Proses Generate  Data"

        const response = await apiCetakPDF(`/dashboard/printPDFReport`, {
            year: selectedYear.value
        })

        if (response) {
            processing.value = false;
            modalPDF.value = true;
            pdfUrl.value = URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
            await getData()
        } else {
            processing.value = false;
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