<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Setting" title="List Setting" />

                <div class="card">
                    
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-xl-6">
                                <h5 class="card-title text-center">List References</h5>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="d-flex gap-2">
                                        <Button label="Tambah Reference" icon="pi pi-plus" class="p-button-sm btn-dark me-2" @click="add"/>
                                    </div>

                                    <div class="ms-auto">
                                        <a-input-search
                                            v-model:value="search"
                                            placeholder="Cari Data"
                                            style="width: 300px"
                                        />
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center bg-dark">No</th>
                                                <th class="text-center bg-dark">Reference</th>
                                                <th class="text-center bg-dark">Status</th>
                                                <th class="text-center bg-dark">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="loading"> 
                                                <td class="text-center" colspan="13"><a-skeleton active /></td>
                                            </tr>

                                            <tr v-else-if="state.reference.total==0">
                                                <td class="text-center" colspan="13"><a-empty/></td>
                                            </tr>
                                            <tr v-for="(item, index) in state.reference.data" :key="index" v-else>
                                                <td class="text-center">{{ state.reference.from + index }}</td>
                                                <td>{{ item.reference }}</td>
                                                <td class="text-center">
                                                    <span v-if="item.status_reference == 0" class="badge bg-success">Active</span>
                                                    <span v-else class="badge bg-danger">Inactive</span>
                                                </td>
                                                <td class="text-center">
                                                    <a-tooltip title="Edit Reference">
                                                        <a-button type="primary" size="small" class="bg-dark me-2" @click="edit(item)">
                                                            <template #icon>
                                                                <EditOutlined />
                                                            </template>
                                                        </a-button>
                                                    </a-tooltip>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row py-2">
                                    <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                        Showing {{ state.reference.from }} to {{ state.reference.to }} of {{ state.reference.total }} entries
                                    </div>
                                    <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                        <a-pagination :current="state.reference.current_page" :total="state.reference.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                    </div>
                                </div>

                            </div>

                            <div class="col-sm-12 col-md-6 col-xl-6">
                                <h5 class="card-title text-center">List Customer Type</h5>

                                <div class="d-flex align-items-center mb-3">
                                    <div class="d-flex gap-2">
                                        <Button label="Tambah Reference" icon="pi pi-plus" class="p-button-sm btn-dark me-2" @click="addType"/>
                                    </div>

                                    <div class="ms-auto">
                                        <a-input-search
                                            v-model:value="searchType"
                                            placeholder="Cari Data"
                                            style="width: 300px"
                                        />
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-center bg-dark">No</th>
                                                <th class="text-center bg-dark">Store</th>
                                                <th class="text-center bg-dark">Type Name</th>
                                                <th class="text-center bg-dark">Disc</th>
                                                <th class="text-center bg-dark">Tanggal Mulai</th>
                                                <th class="text-center bg-dark">Tanggal Akhir</th>
                                                <th class="text-center bg-dark">Status</th>
                                                <th class="text-center bg-dark">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="loadingType"> 
                                                <td class="text-center" colspan="13"><a-skeleton active /></td>
                                            </tr>

                                            <tr v-else-if="state.type.total==0">
                                                <td class="text-center" colspan="13"><a-empty/></td>
                                            </tr>
                                            <tr v-for="(item, index) in state.type.data" :key="index" v-else>
                                                <td class="text-center">{{ state.type.from + index }}</td>
                                                <td>{{ item.toko.reference }}</td>
                                                <td>{{ item.type }}</td>
                                                <td>{{ item.disc }}</td>
                                                <td>{{ item.tanggal_mulai }}</td>
                                                <td>{{ item.tanggal_akhir }}</td>
                                                <td class="text-center">
                                                    <span v-if="item.status_type == 0" class="badge bg-success">Active</span>
                                                    <span v-else class="badge bg-danger">Inactive</span>
                                                </td>
                                                <td class="text-center">
                                                    <a-tooltip title="Edit Reference">
                                                        <a-button type="primary" size="small" class="bg-dark me-2" @click="editType(item)">
                                                            <template #icon>
                                                                <EditOutlined />
                                                            </template>
                                                        </a-button>
                                                    </a-tooltip>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="row py-2">
                                    <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                        Showing {{ state.type.from }} to {{ state.type.to }} of {{ state.type.total }} entries
                                    </div>
                                    <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                        <a-pagination :current="state.type.current_page" :total="state.type.total" v-model:pageSize="paggingType" @change="handlePageChangeType" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a-drawer v-model:open="modalAdd" :title="action + ' Reference'" placement="right" width="400px">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Reference</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.formReference.reference" placeholder="Reference" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Status</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.formReference.status_reference" style="width: 100%" placeholder="Pilih Status Reference">
                        <a-select-option :value="0">Active</a-select-option>
                        <a-select-option :value="1">Inactive</a-select-option>
                    </a-select>
                </div>
            </div>

            <template #footer>
                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Simpan" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingSubmit" 
                        :disabled="loadingButton" 
                        @click="save()">
                    </Button>
                </div>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>

        </a-drawer>

        <a-drawer v-model:open="modalType" :title="actionType + ' Customer Type'" placement="right" width="400px">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Store</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.formType.kode" style="width: 100%" placeholder="Pilih Store">
                        <a-select-option v-for="(item, index) in state.toko" :key="index" :value="item.kode">{{ item.deptname }}</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Type Name</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.formType.type" placeholder="Type Name" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Disc (%)</label>
                <div class="col-sm-8">
                    <a-input-number v-model:value="state.formType.disc" placeholder="Disc" style="width: 100%" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Tanggal Mulai</label>
                <div class="col-sm-8">
                    <a-date-picker v-model:value="state.formType.tanggal_mulai" style="width: 100%" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Tanggal Akhir</label>
                <div class="col-sm-8">
                    <a-date-picker v-model:value="state.formType.tanggal_akhir" style="width: 100%" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Status</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.formType.status_type" style="width: 100%" placeholder="Pilih Status Type">
                        <a-select-option :value="0">Active</a-select-option>
                        <a-select-option :value="1">Inactive</a-select-option>
                    </a-select>
                </div>
            </div>

            

            <template #footer>
                <div class="d-flex justify-content-end gap-2 mb-2">
                    <Button 
                        label="Simpan" 
                        icon="pi pi-save" 
                        class="flex-auto btn btn-dark" 
                        severity="primary" 
                        :loading="loadingSubmit" 
                        :disabled="loadingButton" 
                        @click="saveType()">
                    </Button>
                </div>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        </a-drawer>
    </div>
</template>

<script setup>
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import axios from 'axios';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";
    import iconSpritePath from '@/assets/svg/icon-sprite.svg';
    import {
        EditOutlined,
        EyeOutlined,
        CheckCircleOutlined,
        CloseCircleOutlined,
        PrinterOutlined
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const pagging = ref(10);
    const search = ref('');
    const modalAdd = ref(false);
    const action = ref('add');
    const modalType = ref(false);
    const actionType = ref('add');
    const loadingType = ref(false);
    const searchType = ref('');
    const paggingType = ref(10);

    const state = reactive({
        reference :{},
        type:{},
        toko : {},
        formReference : {
            id : '',
            reference : '',
            status_reference : [],
        },
        formType : {
            id : '',
            kode : '',
            type : '',
            tanggal_mulai : '',
            tanggal_akhir : '',
            disc : '',
            status_type : [],
        },
    });

    const getToko = async () => {
        const response = await apiGetData('/master/department', {});
        state.toko = response.data;
    };

    const add = () => {
        action.value = 'Tambah';
        state.formReference = {
            id : '',
            reference : '',
            status_reference : [],
        };
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;
        const payload = {
            id : state.formReference.id,
            reference : state.formReference.reference,
            status_reference : state.formReference.status_reference,
        };

        if(action.value==='Tambah'){
            await apiPostData('/setting/index', payload);
        } else {
            await apiPutData('/setting/index', payload);
        }
        modalAdd.value = false;
        await getData();
        loadingSubmit.value = false;
    };

    const edit = (item) => {
        action.value = 'Edit';
        state.formReference = {
            id : item.id,
            reference : item.reference,
            status_reference : item.status_reference,
        };
        modalAdd.value = true;
    };

    const getData = async (page = state.reference.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            search: search.value,
            per_page: pagging.value,
        };

        const response = await apiGetData('/setting/index', params);
        state.reference = response.data;
        loading.value = false;
        
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    //CUstomers Tyepe

    const customerType = async (page = state.type.current_page) => {
        loadingType.value = true;
        const params = {
            page: page,
            search: search.value,
            per_page: pagging.value,
        };

        const response = await apiGetData('/setting/type', params);
        state.type = response.data;
        loadingType.value = false;
        
    };

    const handlePageChangeType = (page) => {
        customerType(page);
    };

    const addType = () => {
        actionType.value = 'Tambah';
        state.formType = {
            id : '',
            kode : [],
            type : '',
            tanggal_mulai : dayjs(),
            tanggal_akhir : dayjs(),
            disc : 0,
            status_type : [],
        };
        modalType.value = true;
    };

    const saveType = async () => {
        loadingSubmit.value = true;
        const payload = {
            id : state.formType.id,
            kode : state.formType.kode,
            type : state.formType.type,
            tanggal_mulai : dayjs(state.formType.tanggal_mulai).format('YYYY-MM-DD'),
            tanggal_akhir : dayjs(state.formType.tanggal_akhir).format('YYYY-MM-DD'),
            disc : state.formType.disc,
            status_type : state.formType.status_type,
        };
        let response;
        if(actionType.value==='Tambah'){
            response = await apiPostData('/setting/type', payload);
        } else {
            response = await apiPutData('/setting/type', payload);
        }

        if(response){
            modalType.value = false;
            await customerType();
            loadingSubmit.value = false;
        }else{
            loadingSubmit.value = false;
        }
    
    };

    const editType = (item) => {
        actionType.value = 'Edit';
        state.formType = {
            id : item.id,
            kode : item.kode,
            type : item.type,
            tanggal_mulai : dayjs(item.tanggal_mulai),
            tanggal_akhir : dayjs(item.tanggal_akhir),
            disc : item.discount,
            status_type : item.status_type,
        };
        modalType.value = true;
    };


    onMounted(async () => {
        Promise.all([
            await getData(),
            await customerType(),
            await getToko(),
        ]);
    });

    watch(search, async() => {
        useDebounceFn(() => {
            getData(1);
        }, 500)();
    });

    watch(searchType, async() => {
        useDebounceFn(() => {
            customerType(1);
        }, 500)();
    });


</script>