<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Transactions" title="Membership Transactions" />

                <div class="card ms-2">
                    <Tabs v-model:value="activeTab" class="p-tab-active">
                        <TabList class="p-tab-active">

                            <Tab value="0"> <span style="color: #222 !important;">Membership Payment</span></Tab>
                            <Tab value="1"> <span style="color: #222 !important;">Membership Users</span></Tab>
                        </TabList>
                        <TabPanels>
                            <TabPanel value="0">
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
                                                        <th class="text-center bg-dark text-nowrap">Membership</th>
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
                                                        <td class="text-center text-nowrap">{{ data.membership.title }}</td>
                                                        <td class="text-center text-nowrap">{{ formatCurrency(data.amount) }}</td>
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
                            </TabPanel>
                            <TabPanel value="1">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            Filter :
                                            <!-- Filter Range Tanggal -->
                                            <a-range-picker v-model:value="filterDateUsers" style="width: 260px" format="YYYY-MM-DD" />
                                            <!-- Filter Status -->
                                            <a-select v-model:value="filterStatusUsers" placeholder="Pilih Status" style="width: 200px">
                                                <a-select-option value="active">ACTIVE</a-select-option>
                                                <a-select-option value="pending">PENDING</a-select-option>
                                                <a-select-option value="expired">EXPIRED</a-select-option>
                                                <a-select-option value="cancelled">CANCELLED</a-select-option>
                                            </a-select>
                                        </div>
                                        <div class="ms-auto">
                                            <a-input-search
                                                v-model:value="searchUsers"
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
                                                        <th class="text-center bg-dark text-nowrap">Users</th>
                                                        <th class="text-center bg-dark text-nowrap">Email</th>
                                                        <th class="text-center bg-dark text-nowrap">Phone</th>
                                                        <th class="text-center bg-dark text-nowrap">Membership</th>
                                                        <th class="text-center bg-dark text-nowrap">Periode</th>
                                                        <th class="text-center bg-dark text-nowrap">Status Membership</th>
                                                        <th class="text-center bg-dark text-nowrap">Created At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                            
                                                    <tr v-if="loading"> 
                                                        <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                    </tr>

                                                    <tr v-else-if="state.listDataUsers.total==0">
                                                        <td class="text-center" colspan="13"><a-empty/></td>
                                                    </tr>
                                                
                                                    <tr v-for="(data, index) in state.listDataUsers.data" :key="index" v-else>
                                                        <td class="text-center">{{ index + state.listDataUsers.from }}</td>
                                                        <td class="text-center">
                                                            <a-tooltip title="View Transaction">
                                                                <a-button type="primary" size="small" class="bg-dark me-2" @click="viewUsers(data)">
                                                                    <template #icon>
                                                                        <EyeOutlined />
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                        </td>
                                                        <td class="text-center text-nowrap">{{ data.user.name}}</td>
                                                        <td class="text-center text-nowrap">{{ data.user.email }}</td>
                                                        <td class="text-center text-nowrap">{{ data.user.phone }}</td>
                                                        <td class="text-center text-nowrap">{{ data.membership.title }}</td>
                                                        <td class="text-center text-nowrap">
                                                                {{ data.start_date }} s/d {{ data.end_date }}
                                                        </td>
                                                        <td class="text-center text-nowrap">
                                                            <a-tag :color="data.status === 'active' ? 'green' : data.status === 'pending' ? 'orange' : data.status === 'expired' ? 'default' : data.status === 'cancelled' ? 'red' : 'blue'">
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
                                                Showing {{ state.listDataUsers.from }} to {{ state.listDataUsers.to }} of {{ state.listDataUsers.total }} entries
                                            </div>
                                            <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                <a-pagination :current="state.listDataUsers.current_page" :total="state.listDataUsers.total" v-model:pageSize="paggingUsers" @change="handlePageChangeUsers" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </TabPanel>
                        </TabPanels>
                    </Tabs>
                </div>
            </div>
        </div>

        <a-modal v-model:open="modalAdd" width="700px" title="Membership Transaction Detail" :footer="null">
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
                                <td>{{ formatCurrency(state.detail.amount) }}</td>
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
                                    {{ state.detail.membership?.title || '-' }}
                                    <span v-if="state.detail.membership">({{ state.detail.membership.discount_percent }}% diskon)</span>
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
        </a-modal>

        <a-modal v-model:open="modalAddUsers" width="1000px" title="Membership User Detail" :footer="null">
            <div class="row">
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <a-tag :color="state.detail.transactions?.status == 'paid' ? 'green' : state.detail.transactions?.status == 'pending' ? 'orange' : 'red'">
                                        {{ state.detail.transactions?.status }}
                                    </a-tag>
                                </td>
                            </tr>
                            <tr>
                                <th>Membership</th>
                                <td>
                                    {{ state.detail.membership?.title || '-' }}<br>
                                    <span v-if="state.detail.membership">Harga: {{ formatCurrency(state.detail.membership.price) }}<br>Diskon: {{ state.detail.membership.discount_percent }}%<br>Durasi: {{ state.detail.membership.duration }} bulan</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <td>{{ state.detail.transactions?.payment_method }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah Tagihan</th>
                                <td>{{ formatCurrency(state.detail.transactions?.amount) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Dibayar</th>
                                <td>{{ state.detail.transactions?.paid_at ? dayjs(state.detail.transactions.paid_at).format('DD MMM YYYY HH:mm:ss') : '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>User</th>
                                <td>
                                    {{ state.detail.user?.name }} <br>
                                    <a-image
                                        :src="imageUrl(state.detail.user?.foto)"
                                        width="80px"
                                        height="80px"
                                    />
                                </td>
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
        </a-modal>
    </div>
</template> 


<script setup>
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { formatCurrency } from '@/utils/helpers';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch } from 'vue'
    import { useStore } from "vuex";
    import {
        EyeOutlined,
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
    const activeTab = ref(route.query.tab || '0');
    const pagging = ref(10);
    const search = ref('');
    const modalAdd = ref(false);
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

    const paggingUsers = ref(10);
    const searchUsers = ref('');
    const modalAddUsers = ref(false);
    const filterTypeUsers = ref('');
    const filterSourceUsers = ref('');
    const filterDateUsers = ref([]);
    const filterStatusUsers = ref([route.query.status] ?? []);

    
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;

    const imageUrl = (value) => {
        if (!value) {
            return '';
        }
        return `${pathUrl}/storage/${value}`;
    };

    const state = reactive({
        listData: {},
        listDataUsers: {},
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
        const response = await apiGetData('/transactions/membership_transactions', params);
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


    const getDataUsers = async (page = state.listDataUsers.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: paggingUsers.value,
            search: searchUsers.value,
            status: filterStatusUsers.value,
            start_date: filterDateUsers.value?.[0] ? dayjs(filterDateUsers.value[0]).format('YYYY-MM-DD') : '',
            end_date: filterDateUsers.value?.[1] ? dayjs(filterDateUsers.value[1]).format('YYYY-MM-DD') : '',
        };
        const response = await apiGetData('/transactions/membership_list', params);
        state.listDataUsers = response.data;
        loading.value = false;
    };

    const handlePageChangeUsers = (page) => {
        getDataUsers(page);
    };

    const viewUsers = async (data) => {
        state.detail = data;
        modalAddUsers.value = true;
    };


    onMounted(async() => {
        await getData();
        await getDataUsers();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));

    watch([filterType, filterSource, filterDate], async () => {
        await getData();
    });

    watch(searchUsers, useDebounceFn(async () => {
        await getDataUsers();
    }, 500));

    watch([filterTypeUsers, filterSourceUsers, filterDateUsers, filterStatusUsers], async () => {
        await getDataUsers();
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
