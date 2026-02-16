<template>
    <div>

        <div class="d-flex align-items-center mb-3">
            <div class="d-flex gap-2">
                <Button label="Tambah Facility" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
                            <th class="text-center bg-dark text-nowrap">Facility</th>
                            <th class="text-center bg-dark text-nowrap">Type</th>
                            <th class="text-center bg-dark text-nowrap">Icon</th>
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
                                <a-tooltip title="Edit Facility" placement="top">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center text-nowrap">{{ data.facility?.name || '-' }}</td>
                            <td class="text-center text-nowrap">{{ formatType(data.facility?.type) }}</td>
                            <td class="text-center text-nowrap">
                                <span class="d-inline-flex align-items-center gap-2">
                                    <i class="fa" :class="data.facility?.icon"></i>
                                    {{ data.facility?.icon || '-' }}
                                </span>
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
            :title="action === 'add' ? 'Tambah Facility' : 'Edit Facility'"
        >
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Facility</label>
                <div class="col-sm-9">
                    <a-select
                        v-model:value="state.form.facility_odata"
                        placeholder="Pilih Facility"
                        style="width: 100%"
                        show-search
                        allow-clear
                        :filter-option="filterFacility"
                    >
                        <a-select-option v-for="item in filteredFacilities" :key="item.odata" :value="item.odata">
                            <span class="d-inline-flex align-items-center gap-2">
                                <i class="fa" :class="item.icon"></i>
                                {{ item.name }}
                            </span>
                        </a-select-option>
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
            
                <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        </a-drawer>
    </div>
</template>

<script setup>
    import { apiGetData, apiPostData, apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref, computed , watch} from 'vue'; 
    import { useDebounceFn } from '@vueuse/core';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EditOutlined } from '@ant-design/icons-vue';

    const props = defineProps({
        room_odata: {
            type: String,
            required: true
        }
    });

    const action = ref(null);
    const modalAdd = ref(false);
    const pagging = ref(10);
    const search = ref('');

    const state = reactive({
        listData:{},
        facilities: [],
        form:{
            odata: '',
            room_odata: '',
            facility_odata: ''
        }
    });

    const canLoad = computed(() => !!props.room_odata && props.room_odata.length > 0);

    const getFacilities = async () => {
        const response = await apiGetData('/facilities/index', { page: 1, pagging: 1000, search: '' });
        state.facilities = response.data?.data || [];
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
            room_odata: props.room_odata
        };
        const response = await apiGetData('/room_facilities/index', payload);
        state.listData = response.data;
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            room_odata: props.room_odata,
            facility_odata: ''
        };

        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = {
            odata: item.odata,
            room_odata: item.room_odata,
            facility_odata: item.facility_odata
        };

        action.value = 'edit';
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;
        const payload = {
            odata : state.form.odata,
            room_odata : state.form.room_odata,
            facility_odata: state.form.facility_odata
        };

        let response;
        if (action.value === 'add') {
            response = await apiPostData('/room_facilities/store', payload);
        }else {
            response = await apiPostData('/room_facilities/update', payload);
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
                const response = await apiDeleteData('/room_facilities/index', {
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

    const handlePageChange = async (page) => {
        await getData(page);
    };

    const filterFacility = (input, option) => {
        const label = option?.children?.toString?.() || '';
        return label.toLowerCase().includes(input.toLowerCase());
    };

    const normalizeType = (value) => {
        if (Array.isArray(value)) {
            return value;
        }
        if (!value) {
            return [];
        }
        try {
            const parsed = JSON.parse(value);
            return Array.isArray(parsed) ? parsed : [value];
        } catch (e) {
            return [value];
        }
    };

    const formatType = (value) => {
        const list = normalizeType(value);
        return list.length ? list.join(', ') : '-';
    };

    const filteredFacilities = computed(() => {
        return state.facilities.filter((item) => {
            const list = normalizeType(item.type);
            return list.includes('Room');
        });
    });

    onMounted(async () => {
        await getFacilities();
        await getData();
    });

    watch(search, useDebounceFn(async () => {
        await getData(1);
    }, 500));

    
</script>
