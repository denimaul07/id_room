<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Promotions" title="List Promotions" />

                <div class="card ms-2">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <Button label="Tambah Promotion" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
                                            <th class="text-center bg-dark text-nowrap">Banner</th>
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
                                                <a-tooltip title="Edit Promotion">
                                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                                        <template #icon>
                                                            <EditOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center">
                                                <a-image :src="imageBaseUrl + data.banner" :alt="data.banner" width="150px" />
                                            </td>
                                        
                                            <td class="text-center">
                                                <span v-if="data.isActive == 0" class="badge bg-success">Active</span>
                                                <span v-else class="badge bg-danger">Inactive</span>
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
                    </div>
                </div>
            </div>
        </div>

        <a-drawer
            title="Add Promotion"
            v-model:visible="modalAdd"
            placement="right"
            
            width="400"
        >

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Banner</label>
                <div class="col-sm-9">
                    <input type="file" class="form-control" @change="e => state.form.banner = e.target.files[0]" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <a-select v-model:value="state.form.isActive" style="width: 100%" placeholder="Select Status">
                        <a-select-option :value="0">Active</a-select-option>
                        <a-select-option :value="1">Inactive</a-select-option>
                    </a-select>
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
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch} from 'vue'
    import { useStore } from "vuex";
    import {
        EditOutlined,
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    const store = useStore();
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    const state = reactive({
        listData: {},
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
        const response = await apiGetData('/promotions/index', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = async () => {
        action.value = 'add';
        state.form = {
            odata: "",
            banner: null,
            isActive: []
        }
        modalAdd.value = true;
    };

    const view = async (data) => {
        action.value = 'edit';
        state.form = {
            odata: data.odata,
            banner: data.banner,
            isActive: data.isActive
        }
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        // Membuat FormData untuk upload file dan data lainnya
        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('isActive', state.form.isActive);
        if (state.form.banner) {
            formData.append('banner', state.form.banner);
        }

        let response;
        if (action.value == 'add') {
            response = await apiPostData('/promotions/store', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            response = await apiPutData('/promotions/update', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
            });
        }

        if(response){
            Swal.fire('Success', 'Data saved successfully', 'success');
            modalAdd.value = false;
            loadingSubmit.value = false;
            await getData();
        }else{
            loadingSubmit.value = false;
        }
    };



    onMounted(async() => {
        await getData();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));
</script>

<style scoped>
/* Ubah warna teks Properties menjadi hitam */
.properties-title {
  color: #222 !important;
}
</style>