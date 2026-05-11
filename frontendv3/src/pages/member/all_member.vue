<template>
    <div>
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
                            <th class="text-center bg-dark text-nowrap">Kota</th>
                            <th class="text-center bg-dark text-nowrap">Membership</th>
                            <th class="text-center bg-dark text-nowrap">First Stay</th>
                            <th class="text-center bg-dark text-nowrap">Last Stay</th>
                            <th class="text-center bg-dark text-nowrap">Total Stay</th>
                            <th class="text-center bg-dark text-nowrap">Total Malam</th>
                            <th class="text-center bg-dark text-nowrap">Total Spending</th>
                            <th class="text-center bg-dark text-nowrap">Total Point</th>
                            <th class="text-center bg-dark text-nowrap">Reedem Point</th>
                            <th class="text-center bg-dark text-nowrap">Point Balance</th>
                            <th class="text-center bg-dark text-nowrap">Status</th>
                            <th class="text-center bg-dark text-nowrap">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <tr v-if="loading"> 
                            <td class="text-center" colspan="14"><a-skeleton active /></td>
                        </tr>

                        <tr v-else-if="state.listData.total==0">
                            <td class="text-center" colspan="14"><a-empty/></td>
                        </tr>
                    
                        <tr v-for="(data, index) in state.listData.data" :key="index" v-else>
                            <td class="text-center">{{ index + state.listData.from }}</td>
                            <td class="text-center">
                                <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                    <a-tooltip title="Detail User Member">
                                        <a-button type="primary" size="small" class="bg-dark" @click="view(data)">
                                            <template #icon>
                                                <EyeOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                    <a-tooltip title="Edit User Member">
                                        <a-button type="primary" size="small" class="bg-warning" @click="edit(data)">
                                            <template #icon>
                                                <EditOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                </div>
                            </td>
                            <td class="text-center text-nowrap">{{ data.name }}</td>
                            <td class="text-center text-nowrap">{{ data.email }}</td>
                            <td class="text-center text-nowrap">{{ data.phone }}</td>
                            <td class="text-center text-nowrap">{{ data.gender }}</td>
                            <td class="text-center text-nowrap">{{ data.birth_date }}</td>
                            <td class="text-center text-nowrap">{{ data.kota }}</td>
                            <td class="text-center text-nowrap">{{ data.user_memberships.length > 0 ? data.user_memberships[0].membership.title : 'Non Member' }}</td>
                            <td class="text-center">{{ dayjs(data.first_booking_date).format('DD/MM/YYYY') }}</td>
                            <td class="text-center">{{ dayjs(data.last_booking_date).format('DD/MM/YYYY') }}</td>
                            <td class="text-center">{{ data.total_stay ? data.total_stay : 0 }}</td>
                            <td class="text-center">{{ data.total_malam ? data.total_malam : 0 }}</td>
                            <td class="text-center">
                                {{
                                    parseInt(data.total_transaction_amount)
                                        ? formatCurrency(data.total_transaction_amount)
                                        : 0
                                }}
                            </td>
                            <td class="text-center">{{ data.total_credit_amount ? data.total_credit_amount : 0 }}</td>
                            <td class="text-center">{{ data.total_point_redeem }}</td>
                            <td class="text-center">{{ data.wallet_point ? data.wallet_point.coin_balance : 0 }}</td>
                            <td class="text-center">
                                <span v-if="data.status_users == 0" class="badge bg-success">Aktif</span>
                                <span v-else-if="data.status_users == 1" class="badge bg-danger">Tidak</span>
                                <span v-else-if="data.status_users == 2" class="badge bg-warning text-dark">Harus Verifikasi Email</span>
                            </td>
                            <td class="text-center">{{ data.catatan }}</td>
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

        <a-drawer 
            v-model:open="modalEdit" 
            :width="'500px'" 
            :closable="true" 
            :maskClosable="true" 
            title="Edit Members"
        >
        

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Tanggal Lahir</label>
                <div class="col-sm-9">
                    <a-date-picker v-model:value="state.form.tgl_lahir" placeholder="Tanggal Lahir" style="width:100%" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
                <div class="col-sm-9">
                    <a-select
                        v-model:value="state.form.jenis_kelamin"
                        placeholder="Jenis Kelamin"
                        style="width: 100%"
                    >
                        <a-select-option value="Laki-laki">Laki-laki</a-select-option>
                        <a-select-option value="Perempuan">Perempuan</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Kota</label>
                <div class="col-sm-9">
                    <a-select v-model:value="state.form.kota" show-search placeholder="Kota" style="width: 100%">
                        <a-select-option v-for="(kota, index) in state.listCity" :key="index" :value="kota.city">{{ kota.city }}</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Catatan</label>
                <div class="col-sm-9">
                    <a-input v-model:value="state.form.catatan" placeholder="Catatan" style="width: 100%" />
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="save">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                    Save
                </button>
                <br>
                <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>

        </a-drawer>
    </div>
</template> 


<script setup>
    import { formatCurrency } from '@/utils/helpers';
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch} from 'vue'
    import { useStore } from "vuex";
    import {
        EyeOutlined, EditOutlined
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    
    const store = useStore();
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const modalEdit = ref(false);
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    const state = reactive({
        listData: {},
        detail : {},
        listCity: [],
        form: {
            odata: "",
            tgl_lahir: "",
            jenis_kelamin: "",
            kota: "",
            catatan: "",
        }
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/member/customers', params);
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

    const edit = async (data) => {
        state.form.odata = data.odata
        state.form.tgl_lahir = dayjs(data.birth_date)
        state.form.jenis_kelamin = data.gender
        state.form.kota = data.kota
        state.form.catatan = data.catatan
        modalEdit.value = true;
    };

    const getKota = async () => {
        const response = await apiGetData('/member/kota');
        state.listCity = response.data;
    };

    const save = async () => {
        loadingSubmit.value = true;
        loadingButton.value = true;
        const payload = {
            odata: state.form.odata,
            birth_date: state.form.tgl_lahir ? dayjs(state.form.tgl_lahir).format('YYYY-MM-DD') : null,
            gender: state.form.jenis_kelamin,
            city: state.form.kota,
            catatan: state.form.catatan,
        };

        const response = await apiPutData('/member/customers', payload);
        if (response) {
            modalEdit.value = false;
            await getData();    
            loadingSubmit.value = false;
            loadingButton.value = false;
        }else{
            loadingSubmit.value = false;
            loadingButton.value = false;
        }
    }

    onMounted(async() => {
        await getData();
        await getKota();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));
</script>

