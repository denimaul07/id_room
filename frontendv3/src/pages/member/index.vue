<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Members" title="List Members" />

                <div class="card ms-2">
                    <div class="card-body">
                        <Tabs value="0" class="p-tab-active">
                            <TabList  class="p-tab-active">
                                <Tab value="0"> <span style="color: #222 !important;">Customers Membership</span></Tab>
                                <Tab value="1"> <span style="color: #222 !important;">All Customers</span></Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            
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
                                                        <th class="text-center bg-dark text-nowrap">Name</th>
                                                        <th class="text-center bg-dark text-nowrap">Email</th>
                                                        <th class="text-center bg-dark text-nowrap">No Telp</th>
                                                        <th class="text-center bg-dark text-nowrap">Jenis Kelamin</th>
                                                        <th class="text-center bg-dark text-nowrap">Birth Date</th>
                                                        <th class="text-center bg-dark text-nowrap">Membership</th>
                                                        <th class="text-center bg-dark text-nowrap">Total Point</th>
                                                        <th class="text-center bg-dark text-nowrap">Point Balance</th>
                                                        <th class="text-center bg-dark text-nowrap">Total Stay</th>
                                                        <th class="text-center bg-dark text-nowrap">Total Spending</th>
                                                        <th class="text-center bg-dark text-nowrap">Status</th>
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
                                                            <a-tooltip title="Detail User Member">
                                                                <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                                                    <template #icon>
                                                                        <EyeOutlined />
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                        </td>
                                                        <td class="text-center text-nowrap">{{ data.name }}</td>
                                                        <td class="text-center text-nowrap">{{ data.email }}</td>
                                                        <td class="text-center text-nowrap">{{ data.phone }}</td>
                                                        <td class="text-center text-nowrap">{{ data.gender }}</td>
                                                        <td class="text-center text-nowrap">{{ data.birth_date }}</td>
                                                        <td class="text-center text-nowrap">{{ data.user_memberships.length > 0 ? data.user_memberships[0].membership.title : 'Non Member' }}</td>
                                                        <td class="text-center">{{ data.total_credit_amount ? data.total_credit_amount : 0 }}</td>
                                                        <td class="text-center">{{ data.wallet_point ? data.wallet_point.coin_balance : 0 }}</td>
                                                        <td class="text-center">{{ data.total_stay ? data.total_stay : 0 }}</td>
                                                        <td class="text-center">
                                                            {{
                                                                parseInt(data.total_transaction_amount)
                                                                    ? formatCurrency(data.total_transaction_amount)
                                                                    : 0
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span v-if="data.status_users == 0" class="badge bg-success">Aktif</span>
                                                            <span v-else-if="data.status_users == 1" class="badge bg-danger">Tidak</span>
                                                            <span v-else-if="data.status_users == 2" class="badge bg-warning text-dark">Harus Verifikasi Email</span>
                                                        </td>
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
                                    <all_member />
                                </TabPanel>
                            </TabPanels>    
                        </Tabs>
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:visible="modalAdd" title="Membership User Detail" width="900px" :footer="null">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Nama</td>
                                <td>{{ state.detail.name || '-' }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ state.detail.email || '-' }}</td>
                            </tr>
                            <tr>
                                <td>No HP</td>
                                <td>{{ state.detail.phone || '-' }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Lahir</td>
                                <td>{{ state.detail.birth_date || '-' }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>
                                    <span v-if="state.detail.status_users == 0" class="badge bg-success">Aktif</span>
                                    <span v-else-if="state.detail.status_users == 1" class="badge bg-danger">Tidak Aktif</span>
                                    <span v-else-if="state.detail.status_users == 2" class="badge bg-warning text-dark">Harus Verifikasi Email</span>
                                    <span v-else>-</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Membership</td>
                                <td>
                                    <span v-if="state.detail.user_memberships && state.detail.user_memberships.length > 0">
                                        {{ state.detail.user_memberships[0].membership.title }}
                                        <span v-if="state.detail.user_memberships[0].membership.discount_percent"> ({{ state.detail.user_memberships[0].membership.discount_percent }}% diskon)</span>
                                    </span>
                                    <span v-else>Non Member</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Periode</td>
                                <td>
                                    <span v-if="state.detail.user_memberships && state.detail.user_memberships.length > 0">
                                        {{ state.detail.user_memberships[0].start_date }} s/d {{ state.detail.user_memberships[0].end_date }}
                                    </span>
                                    <span v-else>-</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Status Membership</td>
                                <td>
                                    <span v-if="state.detail.user_memberships && state.detail.user_memberships.length > 0">
                                        <span v-if="state.detail.user_memberships[0].status == 'active'" class="badge bg-success">Active</span>
                                        <span v-else-if="state.detail.user_memberships[0].status == 'inactive'" class="badge bg-danger">Inactive</span>
                                        <span v-else>{{ state.detail.user_memberships[0].status }}</span>
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
    import { formatCurrency } from '@/utils/helpers';
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch} from 'vue'
    import { useStore } from "vuex";
    import {
        EyeOutlined,
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import all_member from './all_member.vue';
    const store = useStore();
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    const state = reactive({
        listData: {},
        detail : {},
        form: {
            odata: "",
            banner: null,
            isActive: []
        }
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/member/index', params);
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
</script>

