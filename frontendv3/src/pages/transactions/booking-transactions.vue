<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Transactions" title="Booking Transactions" />

                <div class="card ms-2">
                    <div class="card-body">
                        <Tabs v-model:value="activeTab" class="p-tab-active">
                            <TabList class="p-tab-active">

                                <Tab value="0"> <span style="color: #222 !important;">Booking Payment</span></Tab>
                                <Tab value="1"> <span style="color: #222 !important;">Booking Room</span></Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            Filter :
                                            <!-- Filter Range Tanggal -->
                                            <a-range-picker v-model:value="filterDate" style="width: 260px" format="YYYY-MM-DD" />

                                            <!-- Filter Status Payment -->
                                            <a-select v-model:value="filterStatus" placeholder="Pilih Status" style="width: 150px">
                                                <a-select-option value="">Semua Status</a-select-option>
                                                <a-select-option value="PAID">PAID</a-select-option>
                                                <a-select-option value="PENDING">PENDING</a-select-option>
                                                <a-select-option value="CANCELLED">CANCELLED</a-select-option>
                                                <a-select-option value="EXPIRED">EXPIRED</a-select-option>
                                                <a-select-option value="COMPLETED">COMPLETED</a-select-option>
                                            </a-select>

                                        </div>
                                        <div class="ms-auto">
                                            <a-input-search
                                                v-model:value="search"
                                                placeholder="Cari Data"
                                                style="width: 300px"
                                            />
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center bg-dark">No</th>
                                                        <th class="text-center bg-dark text-nowrap">Action</th>
                                                        <th class="text-center bg-dark text-nowrap">Invoice No</th>
                                                        <th class="text-center bg-dark text-nowrap">Users</th>
                                                        <th class="text-center bg-dark text-nowrap">Room</th>
                                                        <th class="text-center bg-dark text-nowrap">Amount</th>
                                                        <th class="text-center bg-dark text-nowrap">Discount</th>
                                                        <th class="text-center bg-dark text-nowrap">Tax</th>
                                                        <th class="text-center bg-dark text-nowrap">Service Fee</th>
                                                        <th class="text-center bg-dark text-nowrap">Total</th>
                                                        <th class="text-center bg-dark text-nowrap">Status Payment</th>
                                                        <th class="text-center bg-dark text-nowrap">Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            
                                                    <tr v-if="loading"> 
                                                        <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                    </tr>

                                                    <tr v-else-if="state.listData.total==0">
                                                        <td class="text-center" colspan="13"><a-empty/></td>
                                                    </tr>
                                                
                                                    <tr v-for="(data, index) in state.listData.data" :key="index" v-else>
                                                        <td class="text-center">{{ index + state.listData.from }}</td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center gap-2">
                                                                <a-tooltip title="View Transaction">
                                                                    <a-button type="primary" size="small" class="bg-dark" @click="view(data)">
                                                                        <template #icon>
                                                                            <EyeOutlined />
                                                                        </template>
                                                                    </a-button>
                                                                </a-tooltip>
                                                                
                                                                <a-tooltip title="Batal Transaction" v-if="(data.status == 'PAID' || data.status == 'PENDING') && dayjs().isBefore(dayjs(data.booking.checkin_date))">
                                                                    <a-button type="primary" size="small" class="bg-danger" @click="cancel(data)">
                                                                        <template #icon>
                                                                            <CloseOutlined />
                                                                        </template>
                                                                    </a-button>
                                                                </a-tooltip>
                                                            </div>
                                                        </td>
                                                        <td class="text-center text-nowrap">{{ data.invoice_code}}</td>
                                                        <td class="text-center text-nowrap">{{ data.booking.user.name }}</td>
                                                        <td class="text-center text-nowrap">{{ data.booking.property.properties + '-' + data.booking.room.room_name }}</td>
                                                        <td class="text-center text-nowrap">{{ parseInt(data.booking.base_price).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                        <td class="text-center text-nowrap">{{ parseInt(data.booking.discount_amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                        <td class="text-center text-nowrap">{{ parseInt(data.booking.tax_amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                        <td class="text-center text-nowrap">{{ parseInt(data.booking.service_fee).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                        <td class="text-center text-nowrap">{{ parseInt(data.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                        <td class="text-center text-nowrap">
                                                            <a-tag :color="data.status == 'PAID' ? 'green' : data.status == 'PENDING' ? 'orange' : 'red'">{{ data.status }}</a-tag>
                                                            <a-tag v-if="data.status === 'EXPIRED'" color="default">EXPIRED</a-tag>
                                                            <a-tag v-else-if="data.status === 'FAILED'" color="red">FAILED</a-tag>
                                                        </td>   
                                                        <td class="text-center text-nowrap">{{ dayjs(data.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row py-2">
                                            <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                Showing {{ state.listData.from }} to {{ state.listData.to }} of {{ state.listData.total }} entries
                                            </div>
                                            <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                <a-pagination :current="state.listData.current_page" :total="state.listData.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                            </div>
                                        </div>
                                    </div>
                                </TabPanel>
                                <TabPanel value="1">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            Filter :
                                            <!-- Filter Range Tanggal -->
                                            <a-range-picker v-model:value="filterDateBooking" style="width: 260px" format="YYYY-MM-DD" />

                                            <!-- Filter Status Payment -->
                                            <a-select v-model:value="filterStatusRoom" placeholder="Pilih Status" style="width: 150px">
                                                <a-select-option value="">Semua Status</a-select-option>
                                                <a-select-option value="PAID">PAID</a-select-option>
                                                <a-select-option value="PENDING">PENDING</a-select-option>
                                                <a-select-option value="CANCELLED">CANCELLED</a-select-option>
                                                <a-select-option value="EXPIRED">EXPIRED</a-select-option>
                                                <a-select-option value="COMPLETED">COMPLETED</a-select-option>
                                            </a-select>
                                        </div>
                                        <div class="ms-auto">
                                            <a-input-search
                                                v-model:value="searchBooking"
                                                placeholder="Cari Data"
                                                style="width: 300px"
                                            />
                                        </div>
                                    </div>

                                    <div class="mb-3 row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center bg-dark">No</th>
                                                        <th class="text-center bg-dark text-nowrap">Action</th>
                                                        <th class="text-center bg-dark text-nowrap">Invoice No</th>
                                                        <th class="text-center bg-dark text-nowrap">Users</th>
                                                        <th class="text-center bg-dark text-nowrap">Room</th>
                                                        <th class="text-center bg-dark text-nowrap">Check-In</th>
                                                        <th class="text-center bg-dark text-nowrap">Check-Out</th>
                                                        <th class="text-center bg-dark text-nowrap">Total Hari</th>
                                                        <th class="text-center bg-dark text-nowrap">Status Check-In</th>
                                                        <th class="text-center bg-dark text-nowrap">Status Payment</th>
                                                        <th class="text-center bg-dark text-nowrap">Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            
                                                    <tr v-if="loading"> 
                                                        <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                    </tr>

                                                    <tr v-else-if="state.listDataBooking.total==0">
                                                        <td class="text-center" colspan="13"><a-empty/></td>
                                                    </tr>
                                                
                                                    <tr v-for="(data, index) in state.listDataBooking.data" :key="index" v-else>
                                                        <td class="text-center">{{ index + state.listDataBooking.from }}</td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center gap-2">
                                                                <a-tooltip title="View Transaction">
                                                                    <a-button type="primary" size="small" class="bg-dark" @click="viewBooking(data)">
                                                                        <template #icon>
                                                                            <EyeOutlined />
                                                                        </template>
                                                                    </a-button>
                                                                </a-tooltip>
                                                            </div>
                                                        </td>
                                                        <td class="text-center text-nowrap">{{ data.payments[0].invoice_code }}</td>
                                                        <td class="text-center text-nowrap">{{ data.user.name}}</td>
                                                        <td class="text-center text-nowrap">{{ data.property.properties + '-' + data.room.room_name }}</td>
                                                        <td class="text-center text-nowrap">{{ data.checkin_date }}</td>
                                                        <td class="text-center text-nowrap">{{ data.checkout_date }}</td>
                                                        <td class="text-center text-nowrap">{{ dayjs(data.checkout_date).diff(dayjs(data.checkin_date), 'day') }} Hari</td>
                                                        <td class="text-center text-nowrap">
                                                            <a-tag :color="data.checkin == 'CONFIRMED' ? 'green' : data.checkin == 'CHECKED_IN' ? 'blue' : data.checkin == 'CHECKED_OUT' ? 'gray' : data.checkin == 'Y' ? 'green' : data.checkin == 'N' ? 'red' : 'red'">
                                                                {{ data.checkin == 'Y' ? 'Checked In' : data.checkin == 'N' ? 'Not Checked In' : data.checkin }}
                                                            </a-tag>
                                                        </td>
                                                        <td class="text-center text-nowrap">
                                                            <a-tag
                                                                :color="data.status === 'PAID' ? 'green' 
                                                                    : data.status === 'PENDING' ? 'orange' 
                                                                    : data.status === 'CANCELLED' ? 'red'
                                                                    : data.status === 'EXPIRED' ? 'default'
                                                                    : data.status === 'COMPLETED' ? 'blue'
                                                                    : 'red'"
                                                            >
                                                                {{ data.status }}
                                                            </a-tag>
                                                        </td>   
                                                        <td class="text-center text-nowrap">{{ dayjs(data.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row py-2">
                                            <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                Showing {{ state.listDataBooking.from }} to {{ state.listDataBooking.to }} of {{ state.listDataBooking.total }} entries
                                            </div>
                                            <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                <a-pagination :current="state.listDataBooking.current_page" :total="state.listDataBooking.total" v-model:pageSize="pagging" @change="handlePageChangeBooking" />
                                            </div>
                                        </div>
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="modalAdd" width="1200px" style="top:20px" title="Booking Transaction Detail" :footer="null">
            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>No Invoice</th>
                                <td>{{ state.detail.invoice_code }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <a-tag :color="state.detail.status == 'PAID' ? 'green' : state.detail.status == 'PENDING' ? 'orange' : 'red'">
                                        {{ state.detail.status }}
                                    </a-tag>
                                </td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>{{ state.detail.payment_method }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tagihan</th>
                                <td>{{ parseInt(state.detail.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td>{{ dayjs(state.detail.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>User</th>
                                <td>{{ state.detail.booking?.user?.name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ state.detail.booking?.user?.email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ state.detail.booking?.user?.phone }}</td>
                            </tr>
                            <tr>
                                <th>Membership</th>
                                <td>
                                    {{ state.detail.booking?.membership?.title || '-' }}
                                    <span v-if="state.detail.booking?.membership">({{ state.detail.booking.membership.discount_percent }}% diskon)</span>
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
                                <td>{{ state.detail.booking?.property?.properties }}</td>
                                <th>Room</th>
                                <td>{{ state.detail.booking?.room?.room_name }}</td>
                            </tr>
                            <tr>
                                <th>Check-in</th>
                                <td>{{ state.detail.booking?.checkin_date }}</td>
                                <th>Check-out</th>
                                <td>{{ state.detail.booking?.checkout_date }}</td>
                            </tr>
                            <tr>
                                <th>Harga Dasar</th>
                                <td>{{ parseInt(state.detail.booking?.base_price || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Diskon</th>
                                <td>{{ parseInt(state.detail.booking?.discount_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Pajak</th>
                                <td>{{ parseInt(state.detail.booking?.tax_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Service Fee</th>
                                <td>{{ parseInt(state.detail.booking?.service_fee || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <td colspan="3">{{ parseInt(state.detail.booking?.grand_total || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
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
                            <tr v-for="(p, i) in state.detail.booking?.passengers || []" :key="i">
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

        <a-modal v-model:open="modalAddBooking" width="1200px" title="Booking Detail" style="top:20px" :footer="null">
            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <a-tag :color="state.detail.status == 'PAID' ? 'green' : state.detail.status == 'PENDING' ? 'orange' : state.detail.status == 'CANCELLED' ? 'red' : 'default'">
                                        {{ state.detail.status }}
                                    </a-tag>
                                </td>
                            </tr>
                            <tr>
                                <th>Check-in</th>
                                <td>{{ state.detail.checkin_date }}</td>
                            </tr>
                            <tr>
                                <th>Check-out</th>
                                <td>{{ state.detail.checkout_date }}</td>
                            </tr>
                            <tr>
                                <th>Total Malam</th>
                                <td>{{ state.detail.total_nights }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibuat</th>
                                <td>{{ dayjs(state.detail.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>User</th>
                                <td>{{ state.detail.user?.name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ state.detail.user?.email }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ state.detail.user?.phone }}</td>
                            </tr>
                            <tr>
                                <th>Membership</th>
                                <td>
                                    {{ state.detail.membership?.title || '-' }}
                                    <span v-if="state.detail.membership">({{ state.detail.membership.discount_percent }}% diskon)</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Periode Membership</th>
                                <td>
                                    <span v-if="state.detail.user?.user_memberships && state.detail.user.user_memberships.length">
                                        {{ state.detail.user.user_memberships[0].start_date }} s/d {{ state.detail.user.user_memberships[0].end_date }}
                                    </span>
                                    <span v-else>-</span>
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
                                <td>{{ state.detail.property?.properties }}</td>
                                <th>Room</th>
                                <td>{{ state.detail.room?.room_name }}</td>
                            </tr>
                            <tr>
                                <th>Harga Dasar</th>
                                <td>{{ parseInt(state.detail.base_price || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Diskon</th>
                                <td>{{ parseInt(state.detail.discount_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Pajak</th>
                                <td>{{ parseInt(state.detail.tax_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <th>Service Fee</th>
                                <td>{{ parseInt(state.detail.service_fee || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                            <tr>
                                <th>Total Bayar</th>
                                <td colspan="3">{{ parseInt(state.detail.grand_total || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 mb-3">
                    <h5>Pembayaran</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No Invoice</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Tanggal Dibayar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(pay, i) in state.detail.payments || []" :key="i">
                                <td>{{ pay.invoice_code }}</td>
                                <td>{{ pay.payment_method }}</td>
                                <td>{{ parseInt(pay.amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                <td>
                                    <a-tag :color="pay.status == 'PAID' ? 'green' : pay.status == 'PENDING' ? 'orange' : pay.status == 'REFUNDED' ? 'blue' : pay.status == 'CANCELLED' ? 'red' : 'default'">
                                        {{ pay.status }}
                                    </a-tag>
                                </td>
                                <td>{{ pay.paid_at ? dayjs(pay.paid_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
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
                            <tr v-for="(p, i) in state.detail.passengers || []" :key="i">
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
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch } from 'vue'
    import { useStore } from "vuex";
    import {
        EyeOutlined,CloseOutlined
    } from '@ant-design/icons-vue';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    const store = useStore();
    import { useRoute, useRouter } from 'vue-router';
    const route = useRoute();
    const router = useRouter();
    const pagging = ref(10);
    const search = ref('');
    const searchBooking = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const modalAddBooking = ref(false);
    const filterType = ref('');
    const filterSource = ref('');
    const filterDate = ref([]);
    if(route.query.date == 'today') {
        filterDate.value = [dayjs().startOf('day'), dayjs().endOf('day')];
    }else if(route.query.date == 'month') {
        filterDate.value = [dayjs().startOf('month'), dayjs().endOf('month')];
    }else if(route.query.date == 'year') {
        filterDate.value = [dayjs().startOf('year'), dayjs().endOf('year')];
    }else if(route.query.date == 'custom') {
        filterDate.value = [
            dayjs(route.query.customStart),
            dayjs(route.query.customEnd)
        ];
    }else {
        filterDate.value = [];
    }
    const filterDateBooking = ref([]);
    const activeTab = ref(route.query.tab || '0');

    const filterStatus = ref(route.query.status || '');
    const filterStatusRoom = ref(route.query.status || '');

    const state = reactive({
        listData: {},
        listDataBooking: {},
        detail: {}
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
            status: filterStatus.value,
            start_date: filterDate.value?.[0] ? dayjs(filterDate.value[0]).format('YYYY-MM-DD') : '',
            end_date: filterDate.value?.[1] ? dayjs(filterDate.value[1]).format('YYYY-MM-DD') : '',
        };
        const response = await apiGetData('/transactions/booking_transactions', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };



    const view = async (data) => {
        state.detail = data;
        modalAdd.value = true;
    };

    const cancel = async (data) => {
        Swal.fire({
            title: 'Apakah Anda yakin ingin membatalkan transaksi ini?',
            text: "Transaksi yang dibatalkan tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Batalkan!',
            cancelButtonText: 'Tidak, Batal!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;
                const response = await apiPutData(`/transactions/cancel_booking_transactions`, { odata: data.odata });
                if (response) {
                    getData();
                }
            }
        });
    };

    //Booking Room

    const getDataBooking = async (page = state.listDataBooking.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: searchBooking.value,
            start_date: filterDateBooking.value?.[0] ? dayjs(filterDateBooking.value[0]).format('YYYY-MM-DD') : '',
            end_date: filterDateBooking.value?.[1] ? dayjs(filterDateBooking.value[1]).format('YYYY-MM-DD') : '',
            status: filterStatusRoom.value,
        };
        const response = await apiGetData('/transactions/get_booking', params);
        state.listDataBooking = response.data;
        loading.value = false;
    };

    const handlePageChangeBooking = (page) => {
        getDataBooking(page);
    };

    const viewBooking = async (data) => {
        state.detail = data;
        modalAddBooking.value = true;
    };


    onMounted(async() => {
        await getData();
        await getDataBooking();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));

    watch([filterType, filterSource, filterDate, filterStatus], async () => {
        await getData();
    });

    watch(searchBooking, useDebounceFn(async () => {
        await getDataBooking();
    }, 500));

    watch([filterDateBooking, filterStatusRoom], async () => {
        await getDataBooking();
    });

    // Sync tab from query string
    watch(() => route.query.tab, (val) => {
        if (val !== undefined && val !== activeTab.value) {
            activeTab.value = val;
        }
    });
    // Sync query string from tab
    watch(activeTab, (val) => {
        if (val !== route.query.tab) {
            router.replace({ query: { ...route.query, tab: val } });
        }
    });
</script>
