<template>
    <div>
        <div class="d-flex align-items-center mb-3">
            <div class="d-flex gap-2">
                <Button label="Tambah Membership Package" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
                            <th class="text-center bg-dark">Desc</th>
                            <th class="text-center bg-dark">Benefit Type</th>
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
                                <a-tooltip title="Edit Membership Package">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>

                                <a-tooltip title="Delete Benefits">
                                    <a-button type="primary" size="small" class="bg-danger" @click="hapus(data)">
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center">{{ data.name }}</td>
                            <td class="text-center">{{ data.description }}</td>
                            <td class="text-center">{{ data.benefit_type }}</td>
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

    <a-drawer
        :title="action == 'add' ? 'Tambah Membership Benefit' : 'Edit Membership Benefit'"
        :visible="modalAdd"
        :width="500"
        :closable="true"
        :maskClosable="false"
        @close="modalAdd = false"\
    >
        <div class="mb-3 row">
            <label class="col-sm-4 col-form-label">Name</label>
            <div class="col-sm-8">
                <a-input v-model:value="state.form.name" placeholder="Name" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-4 col-form-label">Desc</label>
            <div class="col-sm-8">
                <a-input v-model:value="state.form.desc" placeholder="Desc" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-4 col-form-label">Benefit Type</label>
            <div class="col-sm-8">
                <a-select style="width: 100%" v-model:value="state.form.benefit_type" placeholder="Benefit Type">
                    <a-select-option value="discount">Discount</a-select-option>
                    <a-select-option value="service">Service</a-select-option>
                    <a-select-option value="privilege">Privilege</a-select-option>
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

</template>

<script setup>
    import { apiGetData, apiPutData, apiPostData,apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import axios from 'axios';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";
    import iconSpritePath from '@/assets/svg/icon-sprite.svg';
    import {
        EditOutlined,
        DeleteOutlined
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);

    const state = reactive({
        listData: {},
        form: {
            odata:"",
            name: "",
            desc: "",
            benefit_type: []
        }
    });

    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/membership_benefit/index', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = async () => {
        action.value = 'add';
        state.form = {
            title: "",
            desc: "",
            price: 0,
            duration: "",
            discount_percent: 0,
            isActive: []
        }
        modalAdd.value = true;
    };

    const view = async (data) => {
        action.value = 'edit';
        state.form = {
            odata: data.odata,
            name: data.name,
            desc: data.description,
            benefit_type: data.benefit_type
        }
        modalAdd.value = true;
    };
    const save = async () => {
        loadingSubmit.value = true;
        const payload = {
            odata: state.form.odata,
            name: state.form.name,
            description: state.form.desc,
            benefit_type: state.form.benefit_type
        };

        let response;
        if(action.value == 'add'){
            response = await apiPostData('/membership_benefit/index', payload);
        }else{
            response = await apiPutData('/membership_benefit/index', payload);
        }


        if(response){
            modalAdd.value = false;
            loadingSubmit.value = false;
            await getData();
        }else{
            loadingSubmit.value = false;
        }
    };

    const hapus = async (data) => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const payload = {
                    odata: data.odata
                };
                const response = await apiDeleteData('/membership_benefit/index', payload);
                if(response){
                    await getData();
                }
            }
        })
    };

    onMounted(async() => {
        await getData();
    });

    watch(search, async() => {
        useDebounceFn(async() => {
            await getData();
        }, 500)();
    });
</script>