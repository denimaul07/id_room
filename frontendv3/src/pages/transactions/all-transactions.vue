<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Transactions" title="All Transactions" />

                <div class="card ms-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                Filter :
                                <!-- Filter Source -->
                                <a-select v-model:value="filterSource" placeholder="Source" style="width: 140px">
                                    <a-select-option value="">All Source</a-select-option>
                                    <a-select-option value="TOPUP">TOPUP</a-select-option>
                                    <a-select-option value="BOOKING">BOOKING</a-select-option>
                                    <a-select-option value="MEMBERSHIP">MEMBERSHIP</a-select-option>
                                    <a-select-option value="REFUND">REFUND</a-select-option>
                                </a-select>
                                <!-- Filter Status -->
                                <a-select v-model:value="filterStatus" placeholder="Status" style="width: 140px">
                                    <a-select-option value="">All Status</a-select-option>
                                    <a-select-option value="PAID">PAID</a-select-option>
                                    <a-select-option value="PENDING">PENDING</a-select-option>
                                    <a-select-option value="FAILED">FAILED</a-select-option>
                                    <a-select-option value="REFUNDED">REFUNDED</a-select-option>
                                    <a-select-option value="CANCELLED">CANCELLED</a-select-option>
                                </a-select>
                                <!-- Filter Range Tanggal -->
                                <a-range-picker v-model:value="filterDate" style="width: 260px" format="YYYY-MM-DD" />
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
                                            <th class="text-center bg-dark text-nowrap">User</th>
                                            <th class="text-center bg-dark text-nowrap">Type</th>
                                            <th class="text-center bg-dark text-nowrap">Amount</th>
                                            <th class="text-center bg-dark text-nowrap">Payment Method</th>
                                            <th class="text-center bg-dark text-nowrap">Description</th>
                                            <th class="text-center bg-dark text-nowrap">Payment Status</th>
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
                                                <a-tooltip title="View Transaction">
                                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                                        <template #icon>
                                                            <EyeOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center">{{ data.user.name }}</td>
                                            <td class="text-center">{{ data.type }}</td>
                                            <td class="text-center">{{ parseInt(data.total_amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center">{{ data.payment_method }}</td>
                                            <td class="text-center">{{ data.description }}</td>
                                            <td class="text-center">
                                                <a-tag
                                                    :color="data.status === 'PAID' ? 'green'
                                                        : data.status === 'PENDING' ? 'orange'
                                                        : data.status === 'FAILED' ? 'red'
                                                        : data.status === 'REFUNDED' ? 'blue'
                                                        : data.status === 'CANCELLED' ? 'gray'
                                                        : 'default'"
                                                >
                                                    {{ data.status }}
                                                </a-tag>
                                            </td>
                                            <td class="text-center">{{ dayjs(data.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
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
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="modalAdd" width="1200px" title="Transaction Detail" :footer="null">
            <template v-if="state.detail && state.detail.type === 'TOPUP' && state.detail.topup">
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>No Invoice</th>
                                    <td>{{ state.detail.topup.invoice_code }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <a-tag :color="state.detail.topup.status == 'PAID' ? 'green' : state.detail.topup.status == 'PENDING' ? 'orange' : 'red'">
                                            {{ state.detail.topup.status }}
                                        </a-tag>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>{{ state.detail.topup.payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal Top Up</th>
                                    <td>{{ parseInt(state.detail.topup.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ state.detail.topup.description }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ dayjs(state.detail.topup.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibayar</th>
                                    <td>{{ state.detail.topup.paid_at ? dayjs(state.detail.topup.paid_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
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
                                    <th>Saldo Setelah Top Up</th>
                                    <td>{{ parseInt(state.detail.user?.balance || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
            
            <template v-if="state.detail && state.detail.type === 'BOOKING' && state.detail.booking_payments">
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>No Invoice</th>
                                    <td>{{ state.detail.booking_payments?.invoice_code }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <a-tag :color="state.detail.booking_payments?.status == 'PAID' ? 'green' : state.detail.booking_payments?.status == 'PENDING' ? 'orange' : 'red'">
                                            {{ state.detail.booking_payments?.status }}
                                        </a-tag>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>{{ state.detail.booking_payments?.payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Jumlah Tagihan</th>
                                    <td>{{ parseInt(state.detail.booking_payments?.amount || state.detail.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ dayjs(state.detail.booking_payments?.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibayar</th>
                                    <td>{{ state.detail.booking_payments?.paid_at ? dayjs(state.detail.booking_payments?.paid_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 col-lg-6 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>User</th>
                                    <td>{{ state.detail.booking_payments?.booking?.user?.name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ state.detail.booking_payments?.booking?.user?.email }}</td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td>{{ state.detail.booking_payments?.booking?.user?.phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>Property</th>
                                    <td>{{ state.detail.booking_payments?.booking?.property?.properties || '-' }}</td>
                                    <th>Room</th>
                                    <td>{{ state.detail.booking_payments?.booking?.room?.room_name || '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Check-in</th>
                                    <td>{{ state.detail.booking_payments?.booking?.checkin_date || '-' }}</td>
                                    <th>Check-out</th>
                                    <td>{{ state.detail.booking_payments?.booking?.checkout_date || '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Harga Dasar</th>
                                    <td>{{ parseInt(state.detail.booking_payments?.booking?.base_price || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                    <th>Diskon</th>
                                    <td>{{ parseInt(state.detail.booking_payments?.booking?.discount_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                </tr>
                                <tr>
                                    <th>Pajak</th>
                                    <td>{{ parseInt(state.detail.booking_payments?.booking?.tax_amount || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                    <th>Service Fee</th>
                                    <td>{{ parseInt(state.detail.booking_payments?.booking?.service_fee || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                </tr>
                                <tr>
                                    <th>Total Bayar</th>
                                    <td colspan="3">{{ parseInt(state.detail.booking_payments?.booking?.grand_total || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
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
                                <tr v-for="(p, i) in state.detail.booking_payments?.booking?.passengers || []" :key="i">
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
            </template>

            <template v-if="state.detail && state.detail.type === 'MEMBERSHIP' && state.detail.membership_transactions">
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>No Invoice</th>
                                    <td>{{ state.detail.membership_transactions.invoice_code }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <a-tag
                                            :color="state.detail.status === 'PAID' ? 'green'
                                                : state.detail.status === 'PENDING' ? 'orange'
                                                : state.detail.status === 'FAILED' ? 'red'
                                                : state.detail.status === 'REFUNDED' ? 'blue'
                                                : state.detail.status === 'CANCELLED' ? 'gray'
                                                : 'default'"
                                        >
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
                                <tr>
                                    <th>Tanggal Dibayar</th>
                                    <td>{{ state.detail.paid_at ? dayjs(state.detail.paid_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
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
                                        {{ state.detail.membership_transactions?.membership?.title || '-' }}
                                        <span v-if="state.detail.membership_transactions?.membership">({{ state.detail.membership_transactions.membership.discount_percent }}% diskon)</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Periode</th>
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
                </div>
            </template>

            <template v-if="state.detail && state.detail.type === 'REFUND' && state.detail.refund">
                <div class="row">
                    <div class="col-12 col-lg-6 mb-3">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th>No Invoice</th>
                                    <td>{{ state.detail.refund.invoice_code }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <a-tag :color="state.detail.refund.status == 'PAID' ? 'green' : state.detail.refund.status == 'PENDING' ? 'orange' : 'red'">
                                            {{ state.detail.refund.status }}
                                        </a-tag>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>{{ state.detail.refund.payment_method }}</td>
                                </tr>
                                <tr>
                                    <th>Nominal Refund</th>
                                    <td>{{ parseInt(state.detail.refund.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{{ state.detail.refund.description }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Dibuat</th>
                                    <td>{{ dayjs(state.detail.refund.created_at).format('DD MMM YYYY HH:mm:ss') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Diproses</th>
                                    <td>{{ state.detail.refund.processed_at ? dayjs(state.detail.refund.processed_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>
        </a-modal>
    </div>
</template> 


<script setup>
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch } from 'vue'
    import { useStore } from "vuex";
    import {
        EyeOutlined,
    } from '@ant-design/icons-vue';
    const store = useStore();
    import { useRoute } from 'vue-router';
    const route = useRoute();
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const filterType = ref('');
    const filterSource = ref(route.query.source ?? '');
    const filterUnpaid = ref(route.query.unpaid === 'true' ? 'Y' : '');

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
    const filterStatus = ref(route.query.type ?? '');
    const state = reactive({
        listData: {},
        detail: {}
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
            type: filterSource.value,
            status: filterStatus.value,
            unpaid: filterUnpaid.value,
            start_date: filterDate.value?.[0] ? dayjs(filterDate.value[0]).format('YYYY-MM-DD') : '',
            end_date: filterDate.value?.[1] ? dayjs(filterDate.value[1]).format('YYYY-MM-DD') : '',
        };
        const response = await apiGetData('/transactions/all_transactions', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };


    const view = async (data) => {
        action.value = 'edit';
        const params = {
            odata: data.reference_odata,
            type: data.type,
        }
        const response = await apiGetData('/transactions/detail_transaction', params);
        state.detail = response.data;
        modalAdd.value = true;
    };



    onMounted(async() => {
        await getData();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));
    watch([filterType, filterSource, filterDate, filterStatus], async () => {
        await getData();
    });
</script>
