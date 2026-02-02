<template>
    <div>
        <Button class="btn btn-primary mb-3" @click="add">Add Social Media</Button>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">Social Media</th>
                        <th class="text-center">URL</th>
                        <th class="text-center">Icon</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="state.listData.length === 0">
                        <td colspan="6" class="text-center">No Social Media Added</td>
                    </tr>
                    <tr v-for="(item, index) in state.listData" :key="index" v-else>
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-center">
                            <a-tooltip title="View / Edit Social Media">
                                <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                                    <template #icon>
                                        <EyeOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>

                            <a-tooltip title="Delete Social Media">
                                <a-button type="primary" size="small" class="bg-danger ms-2" @click="deleteItem(item)">
                                    <template #icon>
                                        <DeleteOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>
                        </td>
                        <td class="text-center">
                            {{ item.social }}
                        </td>
                        <td class="text-center">
                            {{ item.url }}
                        </td>
                        <td class="text-center">
                            {{ item.icon }}
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

    <a-drawer v-model:visible="modalAdd" :width="400" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add Social Media' : 'Edit Social Media'">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Social Name</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" v-model="state.form.label" placeholder="Masukan Social Name"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Url</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" v-model="state.form.url" placeholder="Masukan URL" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Icon</label>
            <div class="col-sm-9">
                <select class="form-select" v-model="state.form.icon">
                    <option value="">-- Select Icon --</option>
                    <option value="fab fa-facebook-f">Facebook</option>
                    <option value="fab fa-twitter">Twitter</option>
                    <option value="fab fa-instagram">Instagram</option>
                    <option value="fab fa-linkedin-in">LinkedIn</option>
                    <option value="fab fa-youtube">YouTube</option>
                    <option value="fab fa-x-twitter">X</option>
                    <option value="fab fa-tiktok">TikTok</option>
                    <option value="fab fa-whatsapp">WhatsApp</option>
                </select>
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
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="save">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Save
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

    const action = ref(null);
    const modalAdd = ref(false);

    const state = reactive({
        listData:{},
        form:{
            odata: '',
            label: '',
            url: '',
            icon: '',
            isActive:[]
        }
    });

    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/social_media', {});
        state.listData = response.data
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            label: '',
            url: '',
            icon: '',
            isActive: []
        };

        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = {
            odata: item.odata,
            label: item.social,
            url: item.url,
            icon: item.icon,
            isActive: item.isActive
        };

        action.value = 'edit';
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;
        const payload = {
            odata_setting: 'd73e5120-5b9c-44c1-9154-b718794e8fc3',
            odata : state.form.odata,
            social: state.form.label,
            url: state.form.url,
            icon: state.form.icon,
            isActive: state.form.isActive,
        };

        let response;
        if (action.value === 'add') {
            response = await apiPostData('/setting/social_media', payload);
        }else {
            response = await apiPutData('/setting/social_media', payload);
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
                const response = await apiDeleteData('/setting/social_media', {
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