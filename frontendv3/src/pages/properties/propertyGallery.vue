<template>
    <div>

        <div class="d-flex align-items-center mb-3">
            <div class="d-flex gap-2">
                <Button label="Tambah Image" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
                <table class="table table-sticky">
                    <thead>
                        <tr>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-left-1 col-no">No</th>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-left-2 col-action">Action</th>
                            <th class="text-center bg-dark text-nowrap">Image</th>
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
                            <td class="text-center sticky-col sticky-left-1 col-no">{{ index + state.listData.from }}</td>
                            <td class="text-center text-nowrap sticky-col sticky-left-2 col-action">
                                <a-tooltip title="Edit Image" placement="top">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                                <a-tooltip title="Delete Image" placement="top">
                                    <a-button type="primary" size="small" class="bg-danger" @click="deleteItem(data)">
                                        <template #icon>
                                            <DeleteOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center text-nowrap">
                                <a-image
                                    :src="imageUrl(data.image)"
                                    width="50px"
                                    height="50px"
                                />
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

        <a-drawer
            v-model:visible="modalAdd"
            :closable="false"
            :mask-closable="true"
            width="450"
            :title="action === 'add' ? 'Tambah Image' : 'Edit Image'"
        >
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Image</label>
                <div class="col-sm-9">
                    <input type="file" accept="image/*" @change="onFileChange" />
                    <div v-if="previewUrl" class="mt-2">
                        <a-image :src="previewUrl" width="96" height="96" style="object-fit: cover; border-radius: 6px;" />
                    </div>
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
            
                <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        </a-drawer>
    </div>
</template>

<script setup>
    import { apiGetData, apiPostData, apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref, computed, watch } from 'vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';

    const props = defineProps({
        property_odata: {
            type: String,
            required: true
        }
    });

    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
    const action = ref(null);
    const modalAdd = ref(false);
    const pagging = ref(10);
    const search = ref('');
    const previewUrl = ref('');

    const state = reactive({
        listData:{},
        form:{
            odata: '',
            property_odata: '',
            image: null
        }
    });

    const canLoad = computed(() => !!props.property_odata && props.property_odata.length > 0);

    const imageUrl = (value) => {
        if (!value) {
            return '';
        }
        return `${pathUrl}/storage/${value}`;
    };

    const getData = async (page = state.listData.current_page) => {
        if (!canLoad.value) {
            return;
        }
        loading.value = true;
        const payload = {
            page: page,
            search: search.value,
            pagging: pagging.value,
            property_odata: props.property_odata
        };
        const response = await apiGetData('/property_gallery/index', payload);
        state.listData = response.data;
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            property_odata: props.property_odata,
            image: null
        };
        previewUrl.value = '';

        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = {
            odata: item.odata,
            property_odata: item.property_odata,
            image: null
        };
        previewUrl.value = imageUrl(item.image);

        action.value = 'edit';
        modalAdd.value = true;
    };

    const onFileChange = (event) => {
        const file = event.target.files[0];
        state.form.image = file || null;
        previewUrl.value = file ? URL.createObjectURL(file) : '';
    };

    const save = async () => {
        loadingSubmit.value = true;
        const formData = new FormData();
        formData.append('property_odata', state.form.property_odata);
        if (state.form.odata) {
            formData.append('odata', state.form.odata);
        }
        if (state.form.image) {
            formData.append('image', state.form.image);
        }

        let response;
        if (action.value === 'add') {
            response = await apiPostData('/property_gallery/store', formData);
        } else {
            response = await apiPostData('/property_gallery/update', formData);
        }

        if (response) {
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
                const response = await apiDeleteData('/property_gallery/index', {
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

    const handlePageChange = async (page) => {
        await getData(page);
    };

    watch(
        () => props.property_odata,
        async (value) => {
            if (!value) {
                return;
            }
            await getData(1);
        }
    );

    onMounted(async () => {
        await getData();
    });

</script>
