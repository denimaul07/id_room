<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Transactions" title="Wallet Ledger" />

                <div class="card ms-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                Filter :
                                <!-- Filter Type -->
                                <a-select v-model:value="filterType" placeholder="Type" style="width: 120px">
                                    <a-select-option value="">All Type</a-select-option>
                                    <a-select-option value="CREDIT">CREDIT</a-select-option>
                                    <a-select-option value="DEBIT">DEBIT</a-select-option>
                                </a-select>
                                <!-- Filter Source -->
                                <a-select v-model:value="filterSource" placeholder="Source" style="width: 140px">
                                    <a-select-option value="">All Source</a-select-option>
                                    <a-select-option value="TOPUP">TOPUP</a-select-option>
                                    <a-select-option value="BOOKING">BOOKING</a-select-option>
                                    <a-select-option value="MEMBERSHIP">MEMBERSHIP</a-select-option>
                                    <a-select-option value="REFUND">REFUND</a-select-option>
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
                                            <th class="text-center bg-dark text-nowrap">Source</th>
                                            <th class="text-center bg-dark text-nowrap">Amount</th>
                                            <th class="text-center bg-dark text-nowrap">Balance Before</th>
                                            <th class="text-center bg-dark text-nowrap">Saldo</th>
                                            <th class="text-center bg-dark text-nowrap">Description</th>
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
                                            <td class="text-center">
                                                <span v-if="data.type === 'CREDIT'" style="color: green;">
                                                    Credit
                                                </span>
                                                <span v-else-if="data.type === 'DEBIT'" style="color: red;">
                                                    Debit
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span v-if="data.source === 'TOPUP'">Top Up Saldo</span>
                                                <span v-else-if="data.source === 'BOOKING'">Pembayaran Booking</span>
                                                <span v-else-if="data.source === 'REFUND'">Refund Booking</span>
                                                <span v-else-if="data.source === 'ADJUSTMENT'">Penyesuaian Admin</span>
                                                <span v-else-if="data.source === 'MEMBERSHIP'">Pembayaran Membership</span>
                                                <span v-else-if="data.source === 'CANCEL'">Cancel Booking</span>
                                                <span v-else>{{ data.source }}</span>
                                            </td>
                                            <td class="text-center">{{ parseInt(data.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center">{{ parseInt(data.balance_before).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center">{{ parseInt(data.balance_after).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center">{{ data.description }}</td>
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

        <a-modal v-model:open="modalAdd" width="900px" style="top:20px" :closable="true" :maskClosable="true" title="Transaction Detail" :footer="false">
            <div class="row">
                <div class="col-6">
                    <h5 class="mb-3">Informasi Transaksi</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>No Invoice</th>
                                <td>{{ state.detail.topup_transactions?.invoice_code || '-' }}</td>
                            </tr>
                            <tr>
                                <th>User</th>
                                <td>{{ state.detail.user?.name || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ state.detail.user?.email || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Phone</th>
                                <td>{{ state.detail.user?.phone || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{ state.detail.type || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Source</th>
                                <td>{{ state.detail.source || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{ state.detail.description || '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <h5 class="mb-3">Detail Pembayaran</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Amount</th>
                                <td>
                                    {{ state.detail.amount ? parseInt(state.detail.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Balance Before</th>
                                <td>
                                    {{ state.detail.balance_before ? parseInt(state.detail.balance_before).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Balance After</th>
                                <td>
                                    {{ state.detail.balance_after ? parseInt(state.detail.balance_after).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Method</th>
                                <td>{{ state.detail.topup_transactions?.payment_method || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Payment Status</th>
                                <td>{{ state.detail.topup_transactions?.status || '-' }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ state.detail.created_at ? dayjs(state.detail.created_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
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
        EyeOutlined,
    } from '@ant-design/icons-vue';
    const store = useStore();
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const filterType = ref('');
    const filterSource = ref('');
    const filterDate = ref([]);

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
            type: filterType.value,
            source: filterSource.value,
            start_date: filterDate.value?.[0] ? dayjs(filterDate.value[0]).format('YYYY-MM-DD') : '',
            end_date: filterDate.value?.[1] ? dayjs(filterDate.value[1]).format('YYYY-MM-DD') : '',
        };
        const response = await apiGetData('/transactions/wallet', params);
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
            source: data.source,
        }
        const response = await apiGetData('/transactions/detail', params);
        state.detail = response.data;
        modalAdd.value = true;
    };



    onMounted(async() => {
        await getData();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));
    watch([filterType, filterSource, filterDate], async () => {
        await getData();
    });
</script>
