<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Users" title="List Users" />

                <div class="card">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                                <div class="d-flex gap-2">
                                    <Button label="Tambah Users" icon="pi pi-plus" class="p-button-sm btn-dark me-2" @click="add"/>
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
                                            <th class="text-center bg-dark">User</th>
                                            <th class="text-center bg-dark">Email</th>
                                            <th class="text-center bg-dark">Department</th>
                                            <th class="text-center bg-dark">Role</th>
                                            <th class="text-center bg-dark">Status</th>
                                            <th class="text-center bg-dark">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="loading"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.users.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                        <tr v-for="(item, index) in state.users.data" :key="index" v-else>
                                            <td class="text-center">{{ state.users.from + index }}</td>
                                            <td>{{ item.name }}</td>
                                            <td>{{ item.email }}</td>
                                            <td>{{ item.deptname }}</td>
                                            <td>{{ item.roles.map(role => role.name).join(', ') }}</td>
                                            <td class="text-center">
                                                <span v-if="item.status_users == 0" class="badge bg-success">Active</span>
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
                                    Showing {{ state.users.from }} to {{ state.users.to }} of {{ state.users.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.users.current_page" :total="state.users.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <a-drawer v-model:open="modalAdd" :title="action + ' Users'" placement="right" width="400px">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> User Name</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.name" placeholder="User Name" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Email</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.email" placeholder="Email" />
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Department</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.kode" style="width: 100%" placeholder="Pilih Department">
                        <a-select-option v-for="(item, index) in state.stores" :key="index" :value="item.kode">{{ item.deptname }}</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Role</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.roles" style="width: 100%" placeholder="Pilih Role">
                        <a-select-option v-for="(item, index) in state.roles" :key="index" :value="item.name">{{ item.name }}</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Password</label>
                <div class="col-sm-8">
                    <a-input-password v-model:value="state.form.password" placeholder="Password" />
                </div>
            </div>

            

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label"> Status</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.status_users" style="width: 100%" placeholder="Pilih Status Reference">
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
        users :{},
        stores :{},
        roles :{},
    });

    const getData = async (page = state.users.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            search: search.value,
            per_page: pagging.value,
        };

        const response = await apiGetData('/setting/users', params);
        state.users = response.data;
        loading.value = false;
        
    };

    const getToko = async () => {
        const response = await apiGetData('/master/department', {});
        state.stores = response.data;
    };

    const getRoles = async () => {
        const response = await apiGetData('/master/roles', {});
        state.roles = response.data;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = () => {
        action.value = 'Tambah';
        state.form = {
            id : null,
            name : '',
            email : '',
            kode : [],
            password : '',
            status_users : 0,
            roles : [],
        };
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        const payload = {
            id : state.form.id,
            name : state.form.name,
            email : state.form.email,
            kode : state.form.kode,
            password : state.form.password,
            status_users : state.form.status_users,
            roles : state.form.roles,
        };
    
        let response
        if(action.value==='Tambah'){
            response = await apiPostData('/setting/users', payload);
        }else{
            response = await apiPutData('/setting/users', payload);
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
            name : item.name,
            email : item.email,
            kode : item.kode.toString(),
            password : '',
            status_users : item.status_users,
            roles : item.roles.map(role => role.name),
        };
        modalAdd.value = true;
    };

    onMounted(async () => {
        Promise.all([
            await getData(),
            await getToko(),
            await getRoles(),
        ]);
    });

    watch(search, async() => {
        useDebounceFn(() => {
            getData(1);
        }, 500)();
    });

</script>