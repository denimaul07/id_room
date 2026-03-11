<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h3 class="m-0">Crm Settings</h3>
                    </div>
                </div>
            </div>
        </div>
        <Button class="btn btn-primary mb-3" size="small" @click="add">Add Sumber Booking</Button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Sumber Booking</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="5" class="text-center"><a-skeleton active /></td>
                    </tr>
                    <tr v-else-if="state.listData.total === 0">
                        <td colspan="5" class="text-center"><a-empty description="No Sumber Booking Added" /></td>
                    </tr>
                    <tr v-for="(item, index) in state.listData.data" :key="index" v-else>
                        <td class="text-center">{{ index + state.listData.from }}</td>
                        <td class="text-center text-nowrap">
                            <a-tooltip title="View / Edit Sumber Booking">
                                <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                                    <template #icon>
                                        <EyeOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>

                            <a-tooltip title="Delete Sumber Booking">
                                <a-button type="primary" size="small" class="bg-danger ms-2" @click="deleteItem(item)">
                                    <template #icon>
                                        <DeleteOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>
                        </td>
                        <td class="text-center">
                            {{ item.source }}
                        </td>
                        <td class="text-center">
                            <span v-if="item.status == 0" class="badge bg-success">Active</span>
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

        <div class="card-header">
            <h3 class="card-title">Parameter</h3>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Rate Komisi Add-On</th>
                        <th class="text-center">Bonus Repeat Booking</th>
                        <th class="text-center">Point per Rp</th>
                        <th class="text-center">Total Unit Aktif</th>
                        <th class="text-center">Target Occupancy</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="7" class="text-center"><a-skeleton active /></td>
                    </tr>
                    <tr v-else-if="state.listParameter.length === 0">
                        <td colspan="7" class="text-center"><a-empty description="No Sumber Booking Added" /></td>
                    </tr>
                    <tr v-for="(item, index) in state.listParameter" :key="index" v-else>
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-center text-nowrap">
                            <a-tooltip title="View / Edit Sumber Booking">
                                <a-button type="primary" size="small" @click="viewParameter(item)" class="bg-dark">
                                    <template #icon>
                                        <EyeOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>
                        </td>
                        <td class="text-center">{{ item.rate_komisi }} %</td>
                        <td class="text-center">{{ item.bonus_repeat }}</td>
                        <td class="text-center">{{ item.point }}</td>
                        <td class="text-center">{{ item.unit_aktif }}</td>
                        <td class="text-center">{{ item.target_occupancy }} %</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <a-drawer v-model:visible="modalAdd" :width="400" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add Sumber Booking' : 'Edit Sumber Booking'">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Sumber Booking</label>
            <div class="col-sm-9">
                <a-input class="form-control" v-model:value="state.form.source" placeholder="Masukan Sumber Booking"></a-input>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">isActive</label>
            <div class="col-sm-9">
                <select v-model="state.form.status" class="form-select" placeholder="Status">
                    <option :value="0">Active</option>
                    <option :value="1">Inactive</option>
                </select>
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
            <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
        </template>
        
    </a-drawer>

    <a-drawer v-model:visible="modalParameter" :width="400" :closable="false" :mask-closable="true" title="Parameter">
        <div class="mb-3 row">
            <label class="col-sm-6 col-form-label">Rate Komisi Add-On (%)</label>
            <div class="col-sm-6">
                <a-input class="form-control" v-model:value="state.formParameter.rate_komisi" placeholder="Masukan Rate Komisi Add-On"></a-input>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-6 col-form-label">Bonus Repeat Booking</label>
            <div class="col-sm-6">
                <a-input class="form-control" v-model:value="state.formParameter.bonus_repeat" placeholder="Masukan Bonus Repeat Booking"></a-input>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-6 col-form-label">Point per Rp</label>
            <div class="col-sm-6">
                <a-input class="form-control" v-model:value="state.formParameter.point" placeholder="Masukan Point per Rp"></a-input>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-6 col-form-label">Total Unit Aktif</label>
            <div class="col-sm-6">
                <a-input class="form-control" v-model:value="state.formParameter.unit_aktif" placeholder="Masukan Total Unit Aktif"></a-input>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-6 col-form-label">Target Occupancy (%)</label>
            <div class="col-sm-6">
                <a-input class="form-control" v-model:value="state.formParameter.target_occupancy" placeholder="Masukan Target Occupancy"></a-input>
            </div>
        </div>

        <template #footer>
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="saveParameter">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Save
            </button>
            <br>
            <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
        </template>
    </a-drawer>

    <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
        <div style="align-items:center;justify-content: center;display: flex;" width="100px">
            <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
        </div>

        <div style="align-items:center;justify-content: center;display: flex;">
            {{ pesan }}
        </div>
    </a-modal>
</template>

<script setup>
    import { apiGetData, apiPostData,apiPutData,apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref } from 'vue'; 
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EyeOutlined, DeleteOutlined } from '@ant-design/icons-vue';

    const action = ref(null);
    const modalAdd = ref(false);
    const modalParameter = ref(false);

    const state = reactive({
        listData:{},
        listParameter: [],
        form:{
            odata: '',
            source: '',
            status: 0
        },
        formParameter: {
            rate_komisi: 0,
            bonus_repeat: 0,
            point: 0,
            unit_aktif: 0,
            target_occupancy: 0
        }
    });

    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const payload = {
            page: page,
            pagging: 5
        };
        const response = await apiGetData('/setting/source', payload);
        state.listData = response.data
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            source: '',
            status: 0
        };

        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = {
            odata: item.odata,
            source: item.source,
            status: item.status
        };

        action.value = 'edit';
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;
        const payload = {
            odata : state.form.odata,
            source: state.form.source,
            status: state.form.status,
        };

        let response;
        if (action.value === 'add') {
            response = await apiPostData('/setting/source', payload);
        }else {
            response = await apiPutData('/setting/source', payload);
        }

        if (response) {
            loadingSubmit.value = false;
            modalAdd.value = false;
            getData();
        }else{
            loadingSubmit.value = false;
        }
    };

    const deleteItem = async (item) => {
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
                processing.value = true;
                pesan.value = 'Mohon tunggu sedang proses...';
                const response = await apiDeleteData('/setting/source', {
                    odata: item.odata
                });

                if (response) {
                    processing.value = false;
                    getData();
                }else{
                    processing.value = false;
                }
            }
        });
    };

    //Paramerers

    const getParameter = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/parameter');
        state.listParameter = response.data
        loading.value = false;
    };

    const viewParameter = (item) => {
        state.formParameter = {
            rate_komisi: item.rate_komisi,
            bonus_repeat: item.bonus_repeat,
            point: item.point,
            unit_aktif: item.unit_aktif,
            target_occupancy: item.target_occupancy
        };
        modalParameter.value = true;
    };

    const saveParameter = async () => {
        loadingSubmit.value = true;
        const payload = {
            rate_komisi: state.formParameter.rate_komisi,
            bonus_repeat: state.formParameter.bonus_repeat,
            point: state.formParameter.point,
            unit_aktif: state.formParameter.unit_aktif,
            target_occupancy: state.formParameter.target_occupancy
        };

        const response = await apiPutData('/setting/parameter', payload);

        if (response) {
            loadingSubmit.value = false;
            modalParameter.value = false;
            getParameter();
        }else{
            loadingSubmit.value = false;
        }
    };

    onMounted(async () => {
        await getData();
        await getParameter();
    });

</script>