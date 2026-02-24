<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <Breadcrumbs main="Dashboard" title="Dashboard Overview" />

            </div>
        </div>

        <div class="container-fluid">
            <div class="row widget-grid">
                <div class="col-xxl-3 col-xl-3 col-sm-12 box-col-6">
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
                </div>

                <div class="col-xxl-9 col-xl-9 col-sm-12 box-col-12">
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
                                <!-- Booking Management Table -->
                                <div class="card mt-3">
                                                                
                                    <div class="card-header bg-dark text-white">
                                        <h5 class="mb-0">Booking Management</h5>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th class="text-nowrap">Action</th>
                                                        <th class="text-nowrap">Invoice</th>
                                                        <th class="text-nowrap">Guest Name</th>
                                                        <th class="text-nowrap">Room</th>
                                                        <th class="text-nowrap">Checkin - Checkout</th>
                                                        <th class="text-nowrap">Status Checkin</th>
                                                        <th class="text-nowrap">Check-in Time</th>
                                                        <th class="text-nowrap">Check-out Time</th>
                                                        <th class="text-nowrap">Status</th>
                                                        <th class="text-nowrap">Payment Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="loading">
                                                        <td colspan="10"><a-skeleton active /></td>
                                                    </tr>
                                                    <tr v-else-if="!state.booking_management || state.booking_management.total === 0">
                                                        <td colspan="10" class="text-center">No booking data</td>
                                                    </tr>
                                                    <tr v-for="(booking, idx) in state.booking_management.data" :key="idx" v-else>
                                                        <td class="text-nowrap">
                                                            <a-tooltip title="Check-in" v-if="booking.actions.can_checkin">
                                                                <a-button type="primary" size="small" class="bg-success me-2" @click="checkinBooking(booking)">
                                                                    <template #icon>
                                                                        <i class="fa fa-sign-in-alt"></i>
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                            <a-tooltip title="Check-out" v-if="booking.actions.can_checkout">
                                                                <a-button type="primary" size="small" class="bg-secondary" @click="checkoutBooking(booking)">
                                                                    <template #icon>
                                                                        <i class="fa fa-sign-out-alt"></i>
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                        </td>
                                                        <td class="text-nowrap">{{ booking.invoice }}</td>
                                                        <td class="text-nowrap">{{ booking.guest_name }}</td>
                                                        <td class="text-nowrap">{{ booking.room }}</td>
                                                        <td class="text-nowrap">{{ booking.checkin_checkout }}</td>
                                                        <td class="text-nowrap">
                                                            <span
                                                                class="badge"
                                                                :class="{
                                                                    'bg-success': booking.checkin === 'Y',
                                                                    'bg-danger': booking.checkin === 'N'
                                                                }"
                                                            >
                                                                {{ booking.checkin === 'Y' ? 'Yes' : 'No' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-nowrap">{{ booking.check_in }}</td>
                                                        <td class="text-nowrap">{{ booking.check_out }}</td>
                                                        
                                                        <td>
                                                            <span
                                                                class="badge"
                                                                :class="{
                                                                    'bg-warning text-dark': booking.status === 'PENDING',
                                                                    'bg-success': booking.status === 'PAID' || booking.status === 'COMPLETED',
                                                                    'bg-danger': booking.status === 'CANCELLED' || booking.status === 'EXPIRED'
                                                                }"
                                                            >
                                                                {{ booking.status }}
                                                            </span>
                                                        </td>
                                                        <td class="text-nowrap">
                                                            <span
                                                                class="badge"
                                                                :class="{
                                                                    'bg-warning text-dark': booking.payment_status === 'PENDING',
                                                                    'bg-success': booking.payment_status === 'PAID',
                                                                    'bg-danger': booking.payment_status === 'FAILED' || booking.payment_status === 'EXPIRED',
                                                                    'bg-secondary': booking.payment_status === 'REFUNDED'
                                                                }"
                                                            >
                                                                {{ booking.payment_status }}
                                                            </span>
                                                        </td>
                                                        
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row py-2">
                                            <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                Showing {{ state.booking_management.from }} to {{ state.booking_management.to }} of {{ state.booking_management.total }} entries
                                            </div>
                                            <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                <a-pagination :current="state.booking_management.current_page" :total="state.booking_management.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Room Availability Calendar -->
                                <div class="card mt-3">
                                    <div class="card-header bg-dark text-white">
                                        <h5 class="mb-0">Room Availability Calendar</h5>
                                    </div>
                                    <div class="card-body p-2">
                                        <FullCalendar
                                        id="calendar"
                                        :key="calendarKey"
                                        ref="calendarRef"
                                        :options="calendarOptions"
                                        />
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

        <a-drawer v-model:open="modalAdd" title="Block Room" width="400px" :footer="null" :closable="true">
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Check-in Date</label>
                <div class="col-sm-8">
                    <a-date-picker v-model:value="state.form.checkin_date"  style="width: 100%;" />
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Check-out Date</label>
                <div class="col-sm-8">
                    <a-date-picker v-model:value="state.form.checkout_date"  style="width: 100%;" />
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="blockRoom">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                    Booked
                </button>
                <br>
                <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        </a-drawer>

        <a-modal v-model:open="modalDetailBookingUsers" title="Detail Booking Availability" width="70%" style="top:20px" :footer="null">
            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>No Invoice</th>
                                <td>{{ state.bookingDetail.invoice_code }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <a-tag :color="state.bookingDetail.status == 'PAID' ? 'green' : state.bookingDetail.status == 'PENDING' ? 'orange' : 'red'">
                                        {{ state.bookingDetail.status }}
                                    </a-tag>
                                </td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>{{ state.bookingDetail.payment_method }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tagihan</th>
                                <td>{{ parseInt(state.bookingDetail.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td>{{ dayjs(state.bookingDetail.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>User</th>
                                <td>{{ state.bookingDetail.booking?.user?.name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ state.bookingDetail.booking?.user?.email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ state.bookingDetail.booking?.user?.phone }}</td>
                            </tr>
                            <tr>
                                <th>Membership</th>
                                <td>
                                    {{ state.bookingDetail.booking?.membership?.title || '-' }}
                                    <span v-if="state.bookingDetail.booking?.membership">({{ state.bookingDetail.booking.membership.discount_percent }}% diskon)</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Property</th>
                                <td>{{ state.bookingDetail.booking?.property?.properties }}</td>
                                <th>Room</th>
                                <td>{{ state.bookingDetail.booking?.room?.room_name }}</td>
                            </tr>
                            <tr>
                                <th>Check-in</th>
                                <td>{{ state.bookingDetail.booking?.checkin_date }}</td>
                                <th>Check-out</th>
                                <td>{{ state.bookingDetail.booking?.checkout_date }}</td>
                            </tr>
                            <tr>
                                <th>Harga Dasar</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.base_price || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Diskon</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.discount_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Pajak</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.tax_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Service Fee</th>
                                <td>{{ parseInt(state.bookingDetail.booking?.service_fee || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <td colspan="3">{{ parseInt(state.bookingDetail.booking?.grand_total || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12">
                    <h5>Daftar Tamu</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Gender</th>
                                <th>HP</th>
                                <th>Email</th>
                                <th>NIK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(p, i) in state.bookingDetail.booking?.passengers || []" :key="i">
                                <td>{{ i + 1 }}</td>
                                <td>{{ p.guest_name }}</td>
                                <td>{{ p.guest_gender == '0' ? 'Laki-laki' : 'Perempuan' }}</td>
                                <td>{{ p.guest_phone }}</td>
                                <td>{{ p.guest_email }}</td>
                                <td>{{ p.guest_nik }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </a-modal>
    </div>
</template>

<script setup>


    import { apiGetData, apiCetakPDF, apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan, apiPostData } from '@/store/action';
    import axios from 'axios';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";
    import iconSpritePath from '@/assets/svg/icon-sprite.svg';
    import {
        EyeOutlined,
        ReloadOutlined,
        FileExcelOutlined
    } from '@ant-design/icons-vue';
    import checkRole from '@/store/modules/role.js';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import FullCalendar from "@fullcalendar/vue3";
    import dayGridPlugin from '@fullcalendar/daygrid';
    import timeGridPlugin from '@fullcalendar/timegrid'
    import interactionPlugin from '@fullcalendar/interaction';
    import resourceTimelinePlugin from '@fullcalendar/resource-timeline';
    import resourceTimeGridPlugin from '@fullcalendar/resource-timegrid';
    import ProgressBar from 'primevue/progressbar';
    const calendarKey = ref(0)
    const calendarRef = ref(null)
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const modalAdd = ref(false);
    const modalDetailBookingUsers = ref(false);
       // Filter states
    const selectedFilter = ref('today');
    const customStart = ref('');
    const customEnd = ref('');
    const pagging = ref(20);

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

    const state = reactive({
        data: {},
        booking_management: [],
        room_availability_calendar: [],
        bookingDetail: [],
        form: {
            odata: '',
            property_odata: '',
            room_odata: '',
            checkin_date: '',
            checkout_date: '',
            total_nights: 0
        }
    });

    const calendarEvents = ref([]);
    const calendarResources = computed(() => {
        if (!state.room_availability_calendar?.rooms) return [];
        return state.room_availability_calendar.rooms.map(room => ({
            id: room.room_id,
            title: room.room_name
        }));
    });

    const getData = async () => {
        loading.value = true;
        const params = {page: 1, selectedFilter: selectedFilter.value, customStart: customStart.value, customEnd: customEnd.value};
        const response = await apiGetData("/dashboard/summary_properties", params);
        state.data = response.data;
        state.booking_management = response.booking_management;
        state.room_availability_calendar = response.room_availability_calendar;
        calendarEvents.value = [];
        if (state.room_availability_calendar?.rooms) {
            state.room_availability_calendar.rooms.forEach(room => {
                room.calendar.forEach(cell => {
                    let color = '#28a745';
                    let title = 'Available';
                    if (cell.status === 'booked') {
                        color = '#007bff';
                        title = 'Booked' + (cell.booking_user ? ` (${cell.booking_user})` : '');
                    } else if (cell.status === 'blocked') {
                        color = '#dc3545';
                        title = 'Blocked' + (cell.booking_user ? ` (${cell.booking_user})` : '');
                    }

                    const endDate = dayjs(cell.date).add(1, 'day').format('YYYY-MM-DD')
                    calendarEvents.value.push({
                        title,
                        start: cell.date,
                        end: endDate,
                        resourceId: String(room.room_id),
                        backgroundColor: color,
                        borderColor: color,
                        display: 'block',
                        extendedProps: {
                            room_id: room.room_id,
                            status: cell.status,
                            booking_user: cell.booking_user,
                            can_block: cell.can_block,
                            can_open: cell.can_open,
                            type_booking: cell.type, // default online jika tidak ada
                            can_cancel: cell.type === 'offline', // hanya offline bisa cancel
                            booking_odata: cell.booking_odata
                        }
                    })
                });
            });
        }
        loading.value = false;
    };

    const handlePageChange = async (page) => {
        loading.value = true;
        const params = { page, selectedFilter: selectedFilter.value, customStart: customStart.value, customEnd: customEnd.value };
        const response = await apiGetData("/dashboard/summary_properties", params);
        state.booking_management = response.booking_management;
        loading.value = false;
    };


    // Pindahkan handleEventClick ke atas sebelum calendarOptions
    const handleEventClick = async (info) => {
        const { room_id, status, can_block, can_open, booking_user, type_booking, can_cancel, booking_odata } = info.event.extendedProps;
        const date = info.event.startStr;
        if (status === 'available' && can_block) {
            state.form = {
                odata: '',
                room_odata: room_id,
                checkin_date: dayjs(date),
                checkout_date: dayjs(date).add(1, 'day'),
            }

            modalAdd.value = true;
        } else if (status === 'blocked' && can_open) {
            Swal.fire({
                title: 'Open Available Room',
                text: `Room ID: ${room_id}, Date: ${date}, User: ${booking_user || '-'}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Open Room',
                cancelButtonText: 'Close'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    const response = await apiPostData('dashboard/open_room', {
                        booking_odata: booking_odata
                    });

                    if (response) {
                        await getData();
                    }
                }
            });
        } else if (status === 'booked') {
            const params = {
                booking_odata
            };

            const response = await apiGetData('dashboard/booking-detail', params);
            state.bookingDetail = response.data;
            modalDetailBookingUsers.value = true;

        }
    };

    const calendarOptions = reactive({
        schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',

        plugins: [
            resourceTimelinePlugin,
            interactionPlugin
        ],

        initialView: "resourceTimelineDay",

        selectable: true,
        selectMirror: true,
        eventOverlap: false,

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "resourceTimelineDay,resourceTimelineWeek"
        },

        views: {
            resourceTimelineDay: {
            type: 'resourceTimeline',
            duration: { days: 1 },
            slotDuration: { days: 1 }
            },
            resourceTimelineWeek: {
            type: 'resourceTimeline',
            duration: { weeks: 1 },
            slotDuration: { days: 1 }
            }
        },

        resources: calendarResources,
        events: calendarEvents,

        eventClick: handleEventClick,

    })

    


    const checkinBooking = async (booking) => {
        const result = await Swal.fire({
            title: 'Check-in Booking',
            text: `Are you sure you want to check-in booking with invoice ${booking.invoice}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Check-in',
            cancelButtonText: 'No, Cancel'
        });

        if (result.isConfirmed) {
            pesan.value = 'Processing check-in...';
            processing.value = true;
            const payload = {
                booking_odata: booking.odata
            };
            const response = await apiPostData('dashboard/checkin_booking', payload);

            if (response) {
                await getData();
                processing.value = false;
            } else {
                processing.value = false;
            }
        }
    };

    const checkoutBooking = async (booking) => {
        const result = await Swal.fire({
            title: 'Check-out Booking',
            text: `Are you sure you want to check-out booking with invoice ${booking.invoice}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Check-out',
            cancelButtonText: 'No, Cancel'
        });

        if (result.isConfirmed) {
            pesan.value = 'Processing check-out...';
            processing.value = true;
            const payload = {
                booking_odata: booking.odata
            };
            const response = await apiPostData('dashboard/checkout_booking', payload);

            if (response) {
                await getData();
                processing.value = false;
            } else {
                processing.value = false;
            }
        }
    };

    // Room Availability Calendar actions
    const blockRoom = async () => {
        loadingSubmit.value = true;
        const payload = {
            odata: state.form.odata,
            room_odata: state.form.room_odata,
            checkin_date: dayjs(state.form.checkin_date).format('YYYY-MM-DD'),
            checkout_date: dayjs(state.form.checkout_date).format('YYYY-MM-DD')
        }
        const response = await apiPostData('dashboard/block_room', payload);

        if (response) {
            modalAdd.value = false;
            await getData();
            loadingSubmit.value = false;
        } else {
            loadingSubmit.value = false;
        }
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

    onMounted(async() => {
        await getData();
        timerSettings()
    })

    watch(calendarEvents, () => {
        const api = calendarRef.value?.getApi()
        if (api) {
            api.removeAllEvents()
            calendarEvents.value.forEach(e => api.addEvent(e))
        }
    })

</script>