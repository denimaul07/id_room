<template>
    <div>

        <div class="d-flex align-items-center mb-3">
            <div class="d-flex gap-2">
                <Button label="Tambah Room" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
            </div>

            <div class="ms-auto">
                <a-input-search v-model:value="search" placeholder="Cari Data" style="width: 300px" />
            </div>
        </div>

        <div class="mb-3 row">
            <div class="table-responsive">
                <table class="table table-sticky">
                    <thead>
                        <tr>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-left-1 col-no">No</th>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-left-2 col-action">Action</th>
                            <th class="text-center bg-dark text-nowrap">Room Name</th>
                            <th class="text-center bg-dark text-nowrap">Room Type</th>
                            <th class="text-center bg-dark text-nowrap">Capacity</th>
                            <th class="text-center bg-dark text-nowrap">Image</th>
                            <th class="text-center bg-dark text-nowrap">Total Rooms</th>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-right-1 col-status">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-if="loading">
                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                        </tr>

                        <tr v-else-if="state.listData.total == 0">
                            <td class="text-center" colspan="13"><a-empty /></td>
                        </tr>

                        <tr v-for="(data, index) in state.listData.data" :key="index" v-else>
                            <td class="text-center sticky-col sticky-left-1 col-no">{{ index + state.listData.from }}
                            </td>
                            <td class="text-center text-nowrap sticky-col sticky-left-2 col-action">
                                <a-tooltip title="Edit Properties" placement="top">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center text-nowrap">{{ data.room_name }}</td>
                            <td class="text-center text-nowrap">{{ data.room_type }}</td>
                            <td class="text-center text-nowrap">
                                {{ data.capacity }}
                            </td>
                            <td class="text-center text-nowrap">
                                <a-image :src="pathUrl + data.image" height="40px" width="40px"
                                    fallback="data:image/png;base64,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" />
                            </td>
                            <td class="text-center text-nowrap">
                                <a-tooltip title="Tambah Sub Room" placement="top">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="getSubRoom(data)">
                                        {{ data.sub_rooms.length }}
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center text-nowrap sticky-col sticky-right-1 col-status">
                                <span v-if="data.status == 0" class="badge bg-success">Active</span>
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
                    <a-pagination :current="state.listData.current_page" :total="state.listData.total"
                        v-model:pageSize="pagging" @change="handlePageChange" />
                </div>
            </div>
        </div>

        <a-drawer v-model:visible="modalAdd" :closable="false" :mask-closable="true" width="50%"
            :title="action === 'add' ? 'Tambah Room' : 'Edit Room'">

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Room Name</label>
                        <div class="col-sm-9">
                            <a-input v-model:value="state.form.room_name" placeholder="Masukan Room Name" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Room Type</label>
                        <div class="col-sm-9">
                            <a-select v-model:value="state.form.room_type" placeholder="Pilih Room Type"
                                style="width: 100%">
                                <a-select-option :value="'Single'">Single</a-select-option>
                                <a-select-option :value="'Double'">Double</a-select-option>
                                <a-select-option :value="'Suite'">Suite</a-select-option>
                                <a-select-option :value="'Deluxe'">Deluxe</a-select-option>
                                <a-select-option :value="'Family'">Family</a-select-option>
                                <a-select-option :value="'Presidential'">Presidential</a-select-option>
                                <a-select-option :value="'Standard'">Standard</a-select-option>
                                <a-select-option :value="'Studio'">Studio</a-select-option>
                            </a-select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Capacity</label>
                        <div class="col-sm-9">
                            <a-input-number v-model:value="state.form.capacity" style="width: 100%"
                                placeholder="Masukan Capacity" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Luas</label>
                        <div class="col-sm-9">
                            <a-input-number v-model:value="state.form.luas" style="width: 100%"
                                placeholder="Masukan Luas" />
                        </div>
                    </div>


                </div>

                <div class="col-sm-12 col-md-6">

    

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Image</label>
                        <div class="col-sm-9">
                            <input type="file" @change="e => state.form.image = e.target.files[0]" accept="image/webp" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <a-select v-model:value="state.form.status" placeholder="Status" style="width: 100%">
                                <a-select-option :value="0">Active</a-select-option>
                                <a-select-option :value="1">Inactive</a-select-option>
                            </a-select>
                        </div>
                    </div>

                </div>
            </div>



            <hr class="my-3" v-if="action === 'edit'" />

            <RoomFacilities :room_odata="state.form.odata" v-if="action === 'edit'" />

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

        <a-modal v-model:visible="modalSubRoom" title="Sub Room" :closable="false" :mask-closable="true"  width="1200px" :footer="false">
            <Button label="Tambah Sub Room" icon="pi pi-plus" class="btn btn-dark mb-3" size="small" @click="addSubRoom" />
            <div class="table-responsive">
                <table class="table table-sticky">
                    <thead>
                        <tr>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-left-1 col-no">No</th>
                            <th class="text-center bg-dark text-nowrap sticky-col sticky-left-2 col-action">Action</th>
                            <th class="text-center bg-dark text-nowrap">Name Room</th>
                            <th class="text-center bg-dark text-nowrap">Code Room</th>
                            <th class="text-center bg-dark text-nowrap">Type Bed</th>
                            <th class="text-center bg-dark text-nowrap">Price</th>
                            <th class="text-center bg-dark text-nowrap">Price / Month</th>
                            <th class="text-center bg-dark text-nowrap">Price / Year</th>
                            <th class="text-center bg-dark text-nowrap">Price Sale</th>
                            <th class="text-center bg-dark text-nowrap">Include Breakfast</th>
                            <th class="text-center bg-dark text-nowrap">Smoking Area</th>
                            <th class="text-center bg-dark text-nowrap">Total Room</th>
                            <th class="text-center bg-dark text-nowrap">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-if="state.listDataSub.length == 0">
                            <td class="text-center" colspan="13"><a-empty /></td>
                        </tr>

                        <tr v-for="(data, index) in state.listDataSub" :key="index" v-else>
                            <td class="text-center sticky-col sticky-left-1 col-no">{{ index + 1 }}</td>
                            <td class="text-center sticky-col sticky-left-2 col-action">
                                <a-tooltip title="Edit Sub Room" placement="top">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="viewSubRoom(data)">
                                        <template #icon>
                                            <EditOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center text-nowrap">{{ data.name_room }}</td>
                            <td class="text-center text-nowrap">{{ data.code_room }}</td>
                            <td class="text-center text-nowrap">{{ data.type_bed }}</td>
                            <td class="text-center text-nowrap">{{ formatCurrency(data.price) }}</td>
                            <td class="text-center text-nowrap">{{ formatCurrency(data.price_month) }}</td>
                            <td class="text-center text-nowrap">{{ formatCurrency(data.price_year) }}</td>
                            <td class="text-center text-nowrap">{{ formatCurrency(data.sale) }}</td>
                            <td class="text-center text-nowrap">{{ data.include_breakfast == 'Y' ? 'Yes' : 'No' }}</td>
                            <td class="text-center text-nowrap">{{ data.smoking_area == 'Y' ? 'Yes' : 'No' }}</td>
                            <td class="text-center text-nowrap">{{ data.total_room }}</td>      
                            <td class="text-center text-nowrap">
                                <span v-if="data.status == 0" class="badge bg-success">Active</span>
                                <span v-else class="badge bg-danger">Inactive</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <a-drawer v-model:visible="modalAddSubRoom" :closable="false" :mask-closable="true" width="400px"
                :title="actionSubRoom === 'addSubRoom' ? 'Tambah Sub Room' : 'Edit Sub Room'" >

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Name Room</label>
                    <div class="col-sm-8">
                        <a-input v-model:value="state.formSub.name_room" placeholder="Masukan Name Room" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Code Room</label>
                    <div class="col-sm-8">
                        <a-input v-model:value="state.formSub.code_room" placeholder="Masukan Code Room" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Type Bed</label>
                    <div class="col-sm-8">
                        <a-select v-model:value="state.formSub.type_bad" placeholder="Pilih Type Bed" style="width: 100%">
                            <a-select-option :value="'Single'">Single</a-select-option>
                            <a-select-option :value="'Double'">Double</a-select-option>
                            <a-select-option :value="'Queen'">Queen</a-select-option>
                            <a-select-option :value="'King'">King</a-select-option>
                            <a-select-option :value="'Twin'">Twin</a-select-option>
                        </a-select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Include Breakfast</label>
                    <div class="col-sm-8">
                        <a-select v-model:value="state.formSub.include_breakfast" placeholder="Include Breakfast"
                            style="width: 100%">
                            <a-select-option :value="'Y'">Yes</a-select-option>
                            <a-select-option :value="'N'">No</a-select-option>
                        </a-select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Smoking Area</label>
                    <div class="col-sm-8">
                        <a-select v-model:value="state.formSub.smoking_area" placeholder="Smoking Area"
                            style="width: 100%">
                            <a-select-option :value="'Y'">Yes</a-select-option>
                            <a-select-option :value="'N'">No</a-select-option>
                        </a-select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Price Sale</label>
                    <div class="col-sm-8">
                        <a-input-number v-model:value="state.formSub.sale" style="width: 100%"
                            placeholder="Masukan Price Sale"
                            :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Price</label>
                    <div class="col-sm-8">
                        <a-input-number v-model:value="state.formSub.price" style="width: 100%"
                            placeholder="Masukan Price"
                            :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" />
                    </div>
                </div>

          

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Price / Month</label>
                    <div class="col-sm-8">
                        <a-input-number v-model:value="state.formSub.price_month" style="width: 100%"
                            placeholder="Masukan Price / Month"
                            :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Price / Year</label>
                    <div class="col-sm-8">
                        <a-input-number v-model:value="state.formSub.price_year" style="width: 100%"
                            placeholder="Masukan Price / Year"
                            :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                            :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Total Room</label>
                    <div class="col-sm-8">
                        <a-input-number v-model:value="state.formSub.total_room" style="width: 100%"
                            placeholder="Masukan Total Room" />
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Status</label>
                    <div class="col-sm-8">
                        <a-select v-model:value="state.formSub.status" placeholder="Status" style="width: 100%">
                            <a-select-option :value="0">Active</a-select-option>
                            <a-select-option :value="1">Inactive</a-select-option>
                        </a-select>
                    </div>
                </div>

                <template #footer>
                    <button type="button" :disabled="loadingSubmit" class="btn btn-primary ms-2" @click="saveSubRoom">
                        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                        Save
                    </button>

                    <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
                </template>

            </a-drawer>
        </a-modal>
    </div>
</template>

<script setup>
    import { apiGetData, apiPostData, apiPutData, apiDeleteData, processing, loadingButton, loadingSubmit, dayjs, Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref, watch, computed } from 'vue';
    import { useDebounceFn } from '@vueuse/core';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';
    import RoomFacilities from './roomFacilities.vue';
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/';
    const props = defineProps({
        property_odata: {
            type: String,
            required: true
        }
    });

    const action = ref(null);
    const modalAdd = ref(false);
    const pagging = ref(10);
    const search = ref('');
    const modalSubRoom = ref(false);
    const canLoad = computed(() => !!props.property_odata && props.property_odata.length > 0);
    const actionSubRoom = ref(null);
    const modalAddSubRoom = ref(false);
    const state = reactive({
        listData: {},
        listDataSub: {},
        form: {
            odata: '',
            property_odata: '',
            room_name: '',
            room_type: [],
            capacity: 0,
            luas: 0,
            image: null,
            status: []
        },
        formSub: {
            odata_room: '',
            odata: '',
            name_room: '',
            code_room: '',
            type_bad: '',
            sale: 0,
            price: 0,
            price_month: 0,
            price_year: 0,
            include_breakfast: 'N',
            smoking_area: 'N',
            status: [],
            total_room: 0
        }
    });

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
        const response = await apiGetData('/rooms/index', payload);
        state.listData = response.data
        loading.value = false;
    };

    const add = () => {
        state.form = {
            odata: '',
            property_odata: props.property_odata,
            room_name: '',
            room_type: [],
            capacity: 0,
            luas: 0,
            image: null,
            status: []
        };

        action.value = 'add';
        modalAdd.value = true;
    };

    const view = (item) => {
        state.form = {
            odata: item.odata,
            property_odata: item.property_odata,
            room_name: item.room_name,
            room_type: item.room_type,
            capacity: item.capacity,
            luas: item.luas,
            image: null,
            status: item.status
        };

        action.value = 'edit';
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('property_odata', state.form.property_odata);
        formData.append('room_name', state.form.room_name);
        formData.append('room_type', state.form.room_type);
        formData.append('capacity', state.form.capacity);
        formData.append('luas', state.form.luas);
        formData.append('status', state.form.status);
        if (state.form.image) {
            formData.append('image', state.form.image);
        }

        let response;
        if (action.value === 'add') {
            response = await apiPostData('/rooms/store', formData);
        } else {
            response = await apiPostData('/rooms/update', formData);
        }

        if (response) {
            Swal.fire(
                'Success',
                action.value === 'add' ? 'Room berhasil ditambahkan' : 'Room berhasil diupdate',
                'success'
            );
            loadingSubmit.value = false;
            modalAdd.value = false;
            getData();
        } else {
            loadingSubmit.value = false;
        }
    };

    const getSubRoom = async (data) => {
        state.formSub.odata_room = data.odata;
        const response = await apiGetData('/rooms/getSubRoom', { odata_room: data.odata });
        state.listDataSub = response.data;
        modalSubRoom.value = true;
    };

    const addSubRoom = () => {
        state.formSub = {
            odata_room: state.formSub.odata_room,
            odata: '',
            name_room: '',
            code_room: '',
            type_bad: [],
            sale: 0,
            price: 0,
            price_month: 0,
            price_year: 0,
            include_breakfast: 'N',
            smoking_area: 'N',
            status: [],
            total_room: 0
        };
        actionSubRoom.value = 'addSubRoom';
        modalAddSubRoom.value = true;
    };

    const saveSubRoom = async () => {
        loadingSubmit.value = true;

        const payload = {
            odata_room: state.formSub.odata_room,
            odata: state.formSub.odata,
            name_room: state.formSub.name_room,
            code_room: state.formSub.code_room,
            type_bad: state.formSub.type_bad,
            price: state.formSub.price,
            price_month: state.formSub.price_month,
            price_year: state.formSub.price_year,
            sale: state.formSub.sale,
            include_breakfast: state.formSub.include_breakfast,
            smoking_area: state.formSub.smoking_area,
            status: state.formSub.status,
            total_room: state.formSub.total_room
        };

        let response;
        if (actionSubRoom.value === 'addSubRoom') {
            response = await apiPostData('/rooms/storeSubRoom', payload);
        } else {
            response = await apiPutData('/rooms/updateSubRoom', payload);
        }

        if (response) {
            loadingSubmit.value = false;
            modalAddSubRoom.value = false;
            getSubRoom({ odata: state.formSub.odata_room });
            getData();
        } else {
            loadingSubmit.value = false;
        }
    };

    const viewSubRoom = (data) => {
        state.formSub = {
            odata_room: data.odata_room,
            odata: data.odata,
            name_room: data.name_room,
            code_room: data.code_room,
            type_bad: data.type_bed,
            sale: data.sale,
            price: data.price,
            price_month: data.price_month,
            price_year: data.price_year,
            include_breakfast: data.include_breakfast,
            smoking_area: data.smoking_area,
            status: data.status,
            total_room: data.total_room
        };
        actionSubRoom.value = 'editSubRoom';
        modalAddSubRoom.value = true;
    };

    onMounted(async () => {
        await getData();
    });

    watch(
        () => props.property_odata,
        (newVal) => {
            if (newVal) {
                getData(newVal);
            }
        },
        { immediate: true }
    );


    const handlePageChange = async (page) => {
        await getData(page);
    };

    watch(search, useDebounceFn(async () => {
        await getData(1);
    }, 500));

    const formatCurrency = (value) => {
        if (value === null || value === undefined || value === '') {
            return '-';
        }
        return (value * 1).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3);
    };

</script>