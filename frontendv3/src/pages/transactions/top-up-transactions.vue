<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Transactions" title="Top Up Transactions" />

                <div class="card ms-2">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                Filter :
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
                                            <th class="text-center bg-dark text-nowrap">Invoice No</th>
                                            <th class="text-center bg-dark text-nowrap">Users</th>
                                            <th class="text-center bg-dark text-nowrap">Amount</th>
                                            <th class="text-center bg-dark text-nowrap">Payment Method</th>
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
                                                <a-tooltip title="View Transaction">
                                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                                        <template #icon>
                                                            <EyeOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center text-nowrap">{{ data.invoice_code}}</td>
                                            <td class="text-center text-nowrap">{{ data.user.name }}</td>
                                            <td class="text-center text-nowrap">{{ parseInt(data.amount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center text-nowrap">{{ data.payment_method }}</td>
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
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="modalAdd" width="700px" title="Top-Up Transaction Detail" :footer="null">
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
                                    <a-tag :color="state.detail.status == 'paid' ? 'green' : state.detail.status == 'pending' ? 'orange' : 'red'">
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
            start_date: filterDate.value?.[0] ? dayjs(filterDate.value[0]).format('YYYY-MM-DD') : '',
            end_date: filterDate.value?.[1] ? dayjs(filterDate.value[1]).format('YYYY-MM-DD') : '',
        };
        const response = await apiGetData('/transactions/top_up_transactions', params);
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
