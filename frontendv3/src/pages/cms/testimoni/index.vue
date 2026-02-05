<template>
    <div>
        <Button class="btn btn-primary mb-3" @click="add">Add Testimoni</Button>
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Action</th>
                <th class="text-center">Nama</th>
                <th class="text-center">Type</th>
                <th class="text-center">Location</th>
                <th class="text-center">Image</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Desc</th>
                <th class="text-center">Status</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loading">
                <td colspan="9" class="text-center"><a-skeleton active /></td>
            </tr>
            <tr v-else-if="state.listData.length === 0">
                <td colspan="9" class="text-center"><a-empty description="No Testimoni Added" /></td>
            </tr>
            <tr v-for="(item, index) in state.listData" :key="index" v-else>
                <td class="text-center">{{ index + 1 }}</td>
                <td class="text-center text-nowrap">
                <a-tooltip title="View / Edit Testimoni">
                    <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                    <template #icon>
                        <EyeOutlined />
                    </template>
                    </a-button>
                </a-tooltip>
                <a-tooltip title="Delete Testimoni">
                    <a-button type="primary" size="small" class="bg-danger ms-2" @click="deleteItem(item)">
                    <template #icon>
                        <DeleteOutlined />
                    </template>
                    </a-button>
                </a-tooltip>
                </td>
                <td class="text-center">{{ item.nama }}</td>
                <td class="text-center">{{ item.type }}</td>
                <td class="text-center">{{ item.location }}</td>
                <td class="text-center">
                    <a-image :src="pathUrl + '/storage/' + item.image" :alt="item.nama" width="100px" />
                </td>
                <td class="text-center">{{ item.rate }}</td>
                <td class="text-center">{{ item.desc }}</td>
                <td class="text-center text-nowrap">
                <span v-if="item.isActive === 0" class="badge bg-success">Active</span>
                <span v-else class="badge bg-danger">Inactive</span>
                </td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>

    <a-drawer v-model:visible="modalAdd" :width="400" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add Testimoni' : 'Edit Testimoni'">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Nama</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" v-model="state.form.nama" placeholder="Masukan Nama" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Type</label>
            <div class="col-sm-9">
                <a-select
                    v-model:value="state.form.type"
                    style="width: 100%;"
                    mode="multiple"
                    show-search
                    placeholder="Pilih Type Service"
                    size="large"
                >
                    <a-select-option value="ID Room">ID Room</a-select-option>
                    <a-select-option value="Renovasi">Renovasi</a-select-option>
                </a-select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Location</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" v-model="state.form.location" placeholder="Masukan Lokasi" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Image</label>
            <div class="col-sm-9">
                <input type="file" class="form-control" @change="e => state.form.image = e.target.files[0]" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Rate</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" v-model="state.form.rate" min="1" max="5" placeholder="Rate (1-5)" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Desc</label>
            <div class="col-sm-9">
                <textarea class="form-control" v-model="state.form.desc" placeholder="Deskripsi"></textarea>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">isActive</label>
            <div class="col-sm-9">
                <select v-model="state.form.isActive" class="form-select">
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

        <br />
        <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
    </a-drawer>

    <a-modal v-model:open="processing" :footer="null" :closable="false" width="400px">
        <div style="align-items:center;justify-content: center;display: flex;" width="100px">
        <img class="img-fluid" :src="waitingicon" alt="vector women with leptop" />
        </div>
        <div style="align-items:center;justify-content: center;display: flex;">
        {{ pesan }}
        </div>
    </a-modal>
</template>

<script setup>
    import { apiGetData, apiPostData, apiPutData, apiDeleteData, processing, loadingButton, loadingSubmit, Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref } from 'vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EyeOutlined, DeleteOutlined } from '@ant-design/icons-vue';
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
    const action = ref(null);
    const modalAdd = ref(false);


    const state = reactive({
    listData: [],
    form: {
        odata: '',
        nama: '',
        location: '',
        image: '',
        type: '',
        rate: 5,
        desc: '',
        isActive: 0
    }
    });

    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/testimoni', {});
        state.listData = response.data || [];
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            nama: '',
            location: '',
            image: '',
            type: [],
            rate: 5,
            desc: '',
            isActive: 0
        };
        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = { ...item };
        action.value = 'edit';
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        const formData = new FormData();
        formData.append('odata_setting', 'd73e5120-5b9c-44c1-9154-b718794e8fc3');
        formData.append('nama', state.form.nama);
        formData.append('location', state.form.location);
        formData.append('rate', state.form.rate);
        if (Array.isArray(state.form.type)) {
            state.form.type.forEach(val => formData.append('type[]', val));
        } else {
            formData.append('type', state.form.type);
        }
        formData.append('desc', state.form.desc);
        formData.append('isActive', state.form.isActive);
        if(state.form.image instanceof File){
            formData.append('image', state.form.image);
        }

        const response = await apiPostData('/setting/testimoni_store', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Testimoni has been saved successfully.',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            loadingSubmit.value = false;
            modalAdd.value = false;

            getData();
        } else {
            loadingSubmit.value = false;
        }
    };

    const update = async () => {
        loadingSubmit.value = true;
        
        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('odata_setting', 'd73e5120-5b9c-44c1-9154-b718794e8fc3');
        formData.append('nama', state.form.nama);
        formData.append('location', state.form.location);
        formData.append('rate', state.form.rate);
        if (Array.isArray(state.form.type)) {
            state.form.type.forEach(val => formData.append('type[]', val));
        } else {
            formData.append('type', state.form.type);
        }
        formData.append('desc', state.form.desc);
        formData.append('isActive', state.form.isActive);
        if(state.form.image instanceof File){
            formData.append('image', state.form.image);
        }
        
        const response = await apiPostData('/setting/testimoni_update', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        
        if (response) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Testimoni has been updated successfully.',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            loadingSubmit.value = false;
            modalAdd.value = false;
        
            getData();
        } else {
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
            const response = await apiDeleteData('/setting/testimoni',{
                    odata: item.odata
            });
            if (response) {
                processing.value = false;
                getData();
            } else {
                processing.value = false;
            }
            }
        });
    };

    onMounted(async () => {
        await getData();
    });
</script>
