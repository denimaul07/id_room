<template>
    <div>
        <Button class="btn btn-primary mb-3" @click="add">Add Mitra</Button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Mitra</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="state.listData.length === 0">
                        <td colspan="6" class="text-center">No Mitra Added</td>
                    </tr>
                    <tr v-for="(item, index) in state.listData" :key="index" v-else>
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-center">
                            <a-tooltip title="View / Edit Mitra">
                                <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                                    <template #icon>
                                        <EyeOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>

                            <a-tooltip title="Delete Mitra">
                                <a-button type="primary" size="small" class="bg-danger ms-2" @click="deleteItem(item)">
                                    <template #icon>
                                        <DeleteOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>
                        </td>
                        <td class="text-center">
                            {{ item.nama }}
                        </td>
                        <td class="text-center">
                            <a-image :src="pathUrl + '/storage/' + item.image"  :width="100"
                                    fallback="data:image/png;base64,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"
                            />
                        </td>
                        <td class="text-center">
                            <span v-if="item.isActive === 0" class="badge bg-success">Active</span>
                            <span v-else class="badge bg-danger">Inactive</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <a-drawer v-model:visible="modalAdd" :width="400" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add Mitra' : 'Edit Mitra'">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Mitra Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" v-model="state.form.nama" placeholder="Masukan Mitra Name"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Image</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" @change="e => state.form.image = e.target.files[0]" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">isActive</label>
            <div class="col-sm-9">
                <select v-model="state.form.isActive" class="form-select" >
                    <option :value="0">Active</option>
                    <option :value="1">Inactive</option>
                </select>
            </div>
        </div>



        <template #footer>
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="save" v-if="action === 'add'">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Save
            </button>
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="update" v-if="action === 'edit'">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Update
            </button>
        </template>
        <br>
        <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
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
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
    const action = ref(null);
    const modalAdd = ref(false);

    const state = reactive({
        listData:{},
        form:{
            odata: '',
            nama: '',
            image: '',
            isActive:[]
        }
    });

    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/mitra', {});
        state.listData = response.data
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            odata_setting: '',
            nama: '',
            image: '',
            isActive: 0
        };

        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = {
            odata: item.odata,
            nama: item.nama,
            image: item.image,
            isActive: item.isActive
        };

        action.value = 'edit';
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('odata_setting', 'd73e5120-5b9c-44c1-9154-b718794e8fc3');
        formData.append('nama', state.form.nama);
        formData.append('isActive', state.form.isActive);
        if(state.form.image instanceof File){
            formData.append('image', state.form.image);
        }

        const response = await apiPostData('/setting/mitra_store', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response) {
            loadingSubmit.value = false;
            modalAdd.value = false;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Mitra has been saved successfully.',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });

            getData();
        }else{
            loadingSubmit.value = false;
        }
    };

    const update = async () => {
        loadingSubmit.value = true;

        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('odata_setting', 'd73e5120-5b9c-44c1-9154-b718794e8fc3');
        formData.append('nama', state.form.nama);
        formData.append('isActive', state.form.isActive);
        if(state.form.image instanceof File){
            formData.append('image', state.form.image);
        }

        const response = await apiPostData('/setting/mitra_update', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response) {
            loadingSubmit.value = false;
            modalAdd.value = false;
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Mitra has been updated successfully.',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });

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
                const response = await apiDeleteData('/setting/mitra', {
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

    onMounted(async () => {
        await getData();
    });

</script>