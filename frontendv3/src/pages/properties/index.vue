<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Properties" title="List Properties" />

                <div class="card ms-2">
                    
                    <div class="card-body">
                        <Tabs value="0" class="p-tab-active">
                            <TabList class="p-tab-active" style="color: black;">
                                <Tab value="0">
                                    <i class="fa fa-building" style="color: #222 !important;" />
                                    <span style="color: #222 !important;">Properties</span>
                                </Tab>
                                <Tab value="1">
                                    <i class="fa fa-university" style="color: #222 !important;" />
                                    <span style="color: #222 !important;">Room</span>
                                </Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            <Button label="Tambah Property" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
                                                        <th class="text-center bg-dark text-nowrap">Properties</th>
                                                        <th class="text-center bg-dark">Type</th>
                                                        <th class="text-center bg-dark">Address</th>
                                                        <th class="text-center bg-dark text-nowrap">Total Room</th>
                                                        <th class="text-center bg-dark text-nowrap">Harga PerMalam</th>
                                                        <th class="text-center bg-dark text-nowrap">Status</th>
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
                                                        </td>
                                                        <td class="text-center">{{ data.properties }}</td>
                                                        <td class="text-center">{{ data.type }}</td>
                                                        <td class="text-center">{{ data.address }}</td>
                                                        <td class="text-center">{{ data.total_rooms }}</td>
                                                        <td class="text-center">{{ (data.price_per_night * 1).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                    
                                                        <td class="text-center">
                                                            <span v-if="data.isActive == 0" class="badge bg-success">Active</span>
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
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                </div>
            </div>
        </div>

        <a-drawer
            title="Add Membership Package"
            v-model:visible="modalAdd"
            placement="right"
            width="50%"
        >
            <div class="row">
                <div class="col-sm-12 col-md-6 col-xl-6">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Properties Name</label>
                        <div class="col-sm-8">
                            <a-input v-model:value="state.form.properties" placeholder="Properties Name" />
                        </div>
                    </div> 

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Type</label>
                        <div class="col-sm-8">
                            <a-select v-model:value="state.form.type" placeholder="Select Type" style="width: 100%;">
                                <a-select-option value="Hotel">Hotel</a-select-option>
                                <a-select-option value="Villa">Villa</a-select-option>
                                <a-select-option value="Apartment">Apartment</a-select-option>
                                <a-select-option value="Guest House">Guest House</a-select-option>
                            </a-select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Address</label>
                        <div class="col-sm-8">
                            <a-textarea v-model:value="state.form.address" placeholder="Address" rows="5" />
                        </div>
                    </div>  

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Province</label>
                        <div class="col-sm-8">
                            <a-select v-model:value="state.form.province" placeholder="Select Province" style="width: 100%;">
                                <a-select-option v-for="(province, index) in state.listProvince" :key="index" :value="province.province">
                                    {{ province.province }}
                                </a-select-option>
                            </a-select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">City</label>
                        <div class="col-sm-8">
                            <a-select v-model:value="state.form.city" placeholder="Select City" style="width: 100%;">
                                <a-select-option v-for="(city, index) in state.listCity" :key="index" :value="city.city">
                                    {{ city.city }}
                                </a-select-option>
                            </a-select>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-xl-6">
                
                </div>
            </div>
        

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
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);

    const state = reactive({
        listData: {},
        listProvince: [],
        listCity: [],
        form: {
            odata: "",
            properties: "",
            type: [],
            address: "",
            total_rooms: 0,
            city: "",
            province: "",
            maps: "",
            notelp: "",
            description: "",
            information: "",
            price_per_night: 0,
            total_rooms: 0,
            images: "",
            isActive: []
        }
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/properties/index', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = async () => {
        action.value = 'add';
        state.form = {
            odata: "",
            properties: "",
            type: [],
            address: "",
            city: [],
            province: [],
            maps: "",
            notelp: "",
            description: "",
            information: "",
            price_per_night: 0,
            total_rooms: 0,
            images: "",
            isActive: []
        }
        modalAdd.value = true;
    };

    const view = async (data) => {
        action.value = 'edit';
        state.form = {
            odata: data.odata,
            properties: data.properties,
            type: data.type,
            address: data.address,
            city: data.city,
            province: data.province,
            maps: data.maps,
            notelp: data.notelp,
            description: data.description,
            information: data.information,
            price_per_night: data.price_per_night,
            total_rooms: data.total_rooms,
            images: data.images,
            isActive: data.isActive
        }
        modalAdd.value = true;
    };
    const save = async () => {
        loadingSubmit.value = true;
        // Kirim array of object benefitRows
        const benefitDetails = state.form.benefitRows.map(row => ({
            odata_benefit: row.odata_benefit,
            value: row.value
        }));
        const payload = {
            odata: state.form.odata,
            benefits: benefitDetails, // array of {odata_benefit, value}
            properties: state.form.properties,
            type: state.form.type,
            address: state.form.address,
            city: state.form.city,
            province: state.form.province,
            maps: state.form.maps,
            notelp: state.form.notelp,
            description: state.form.description,
            information: state.form.information,
            price_per_night: state.form.price_per_night,
            total_rooms: state.form.total_rooms,
            images: state.form.images,
            isActive: state.form.isActive
        };

        let response;
        if(action.value == 'add'){
            response = await apiPostData('/properties/index', payload);
        }else{
            response = await apiPutData('/properties/index', payload);
        }

        if(response){
            modalAdd.value = false;
            loadingSubmit.value = false;
            await getData();
        }else{
            loadingSubmit.value = false;
        }
    };


    const getProvinceAndCity = async () => {
        loading.value = true;
        try {
            const [provinceResponse, cityResponse] = await Promise.all([
                apiGetData('/public/province'),
                apiGetData('/public/city')
            ]);
            state.listProvince = provinceResponse.data;
            state.listCity = cityResponse.data;
        } catch (error) {
            console.error('Error fetching provinces and cities:', error);
        } finally {
            loading.value = false;
        }
    };



    onMounted(async() => {
        await getData();
        await getProvinceAndCity();
    });

    watch(search, async() => {
        useDebounceFn(async() => {
            await getData();
        }, 500)();
    });
</script>


<style scoped>
/* Ubah warna teks Properties menjadi hitam */
.properties-title {
    color: #222 !important;
}
</style>