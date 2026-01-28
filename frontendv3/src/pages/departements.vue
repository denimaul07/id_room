<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Departements" title="List Departements" />

                <div class="card">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                                <div class="d-flex gap-2">
                                    <Button label="Tambah Departements" icon="pi pi-plus" class="p-button-sm btn-dark me-2" @click="add"/>
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
                                            <th class="text-center bg-dark">Kode</th>
                                            <th class="text-center bg-dark">Departements</th>
                                            <th class="text-center bg-dark">Group</th>
                                            <th class="text-center bg-dark">Alamat</th>
                                            <th class="text-center bg-dark">Status</th>
                                            <th class="text-center bg-dark">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="loading"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.store.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                        <tr v-for="(item, index) in state.store.data" :key="index" v-else>
                                            <td class="text-center">{{ state.store.from + index }}</td>
                                            <td>{{ item.kode }}</td>
                                            <td>{{ item.deptname }}</td>
                                            <td>{{ item.group_dept }}</td>
                                            <td>{{ item.alamat }}</td>
                                            <td class="text-center">
                                                <span v-if="item.active == 0" class="badge bg-success">Active</span>
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
                                    Showing {{ state.store.from }} to {{ state.store.to }} of {{ state.store.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.store.current_page" :total="state.store.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <a-drawer v-model:open="modalAdd" :title="action + ' Store'" placement="right" width="400px">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Kode</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.kode" placeholder="Kode" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Deptname</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.deptname" placeholder="Deptname" />
                </div>
            </div>


            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Group Dept</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.group_dept" style="width: 100%" placeholder="Pilih Group Dept">
                        <a-select-option :value="'PUSAT'">Pusat</a-select-option>
                        <a-select-option :value="'TOKO'">Toko</a-select-option>
                    </a-select>
                </div>  
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Alamat</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.alamat" placeholder="Alamat" />
                </div>
            </div>
            

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Status</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.active" style="width: 100%" placeholder="Pilih Status Reference">
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

    const state = reactive({
        store :{},
        form : {
            id : null,
            kode:"",
            deptname:"",
            group_dept:"",
            alamat:"",
            active:0,
        },
    });

    const getData = async (page = state.store.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            search: search.value,
            per_page: pagging.value,
        };

        const response = await apiGetData('/master/get_department', params);
        state.store = response.data;
        loading.value = false;
        
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = () => {
        action.value = 'Tambah';
        state.form = {
            id : null,
            kode:"",
            deptname:"",
            group_dept:[],
            alamat:"",
            active:0,
        };
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        const payload = {
            id : state.form.id,
            kode:state.form.kode,
            deptname:state.form.deptname,
            group_dept:state.form.group_dept,
            alamat:state.form.alamat,
            active:state.form.active,
        };
    
        let response
        if(action.value==='Tambah'){
            response = await apiPostData('/setting/store', payload);
        }else{
            response = await apiPutData('/setting/store', payload);
        }

        if(response){
            await getData();
            modalAdd.value = false;
            loadingSubmit.value = false;
        }else{
            loadingSubmit.value = false;
        }
    
    
    };

    const edit = (item) => {
        console.log(item);
        action.value = 'Edit';
        state.form = {
            id : item.id,
            kode:item.kode,
            deptname:item.deptname,
            group_dept:item.group_dept,
            alamat:item.alamat,
            active:item.active,
        };
        modalAdd.value = true;
    };

    onMounted(async () => {
        Promise.all([
            await getData(),
        ]);
    });

    watch(search, async() => {
        useDebounceFn(() => {
            getData(1);
        }, 500)();
    });

</script>