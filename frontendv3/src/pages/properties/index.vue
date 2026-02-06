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
                                                        <th class="text-center bg-dark">Listing Type</th>
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
                                                        <td class="text-center">
                                                            {{ data.listing_type }}
                                                        </td>
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

        <a-modal v-model:visible="modalAdd" :title="action == 'add' ? 'Add Properties' : 'Edit Properties'" style="top:10px"  width="1400px" >
            <div class="row">
                <div class="col-sm-12 col-md-8 col-xl-8">
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
                                <label class="col-sm-4 col-form-label">Listing Type</label>
                                <div class="col-sm-8">
                                    <a-select v-model:value="state.form.listing_type" placeholder="Select Listing Type" mode="multiple" style="width: 100%;">
                                        <a-select-option value="Rent">Rent</a-select-option>
                                        <a-select-option value="Sale">Sale</a-select-option>
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
                                    <a-select v-model:value="state.form.province" placeholder="Select Province" style="width: 100%;" show-search @change="getCity" :filter-option="filterData">
                                        <a-select-option v-for="(province, index) in state.listProvince" :key="index" :value="province.odata" :label="province.province">
                                            {{ province.province }}
                                        </a-select-option>
                                    </a-select>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">City</label>
                                <div class="col-sm-8">
                                    <a-select v-model:value="state.form.city" placeholder="Select City" style="width: 100%;" show-search :filter-option="filterData">
                                        <a-select-option v-for="(city, index) in state.listCity" :key="index" :value="city.odata" :label="city.city">
                                            {{ city.city }}
                                        </a-select-option>
                                    </a-select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-xl-6">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Latitude</label>
                                <div class="col-sm-8">
                                    <a-input v-model:value="state.form.latitude" placeholder="Latitude" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Longitude</label>
                                <div class="col-sm-8">
                                    <a-input v-model:value="state.form.longitude" placeholder="Longitude" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Total Rooms</label>
                                <div class="col-sm-8">
                                    <a-input v-model:value="state.form.total_rooms" placeholder="Total Rooms" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Price Per Night</label>
                                <div class="col-sm-8">
                                    <a-input-number v-model:value="state.form.price_per_night"  :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" style="width:100%" placeholder="Price Per Night" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Price Per Monthly</label>
                                <div class="col-sm-8">
                                    <a-input-number v-model:value="state.form.price_per_monthly"  :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" style="width:100%" placeholder="Price Per Monthly" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Price Per Year</label>
                                <div class="col-sm-8">
                                    <a-input-number v-model:value="state.form.price_per_year"  :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                                    :parser="value => value.replace(/\Rp\s?|(,*)/g, '')" style="width:100%" placeholder="Price Per Year" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Sale Price</label>
                                <div class="col-sm-8">
                                    <a-input v-model:value="state.form.sale_price" placeholder="Sale Price" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label">Status</label>
                                <div class="col-sm-8">
                                    <a-select v-model:value="state.form.isActive" placeholder="Select Status" style="width: 100%;">
                                        <a-select-option :value="0">Active</a-select-option>
                                        <a-select-option :value="1">Inactive</a-select-option>
                                    </a-select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb-3 row">
                                <label class="col-sm-2 col-form-label">Maps</label>
                                <div class="col-sm-10">
                                    <iframe :src="mapsUrl" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-4 col-xl-4">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Description</label>
                        <div class="col-sm-8">
                            <ckeditor :editor="editor" v-model="state.form.description" :config="editorConfig"></ckeditor>
                        </div>
                    </div>  

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Information</label>
                        <div class="col-sm-8">
                            <ckeditor :editor="editor" v-model="state.form.information" :config="editorConfig"></ckeditor>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Image</label>
                        <div class="col-sm-8">
                            <input type="file" accept="image/webp" @change="e => state.form.images = e.target.files[0]" />

                            <a-image :src="pathUrl + '/storage/' + state.form.images" width="32" height="32" style="margin-top:10px" 
                                fallback="data:image/png;base64,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"
                                />
                        </div>
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
                <br>
                <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        </a-modal>
    </div>
</template>

<script setup>
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch} from 'vue'
    // Computed property for Google Maps embed URL
    const mapsUrl = computed(() => {
        const lat = state.form.latitude;
        const lng = state.form.longitude;
        if (lat && lng) {
            return `https://www.google.com/maps?q=${lat},${lng}&hl=id&z=16&output=embed`;
        }
        return '';
    });
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
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    const ckeditor = CKEditor.component
    const editor = ClassicEditor;
    const editorConfig = {
        list: {
            properties: {
                styles: true,
                startIndex: true,
                reversed: true
            }
        },
        toolbar: [
            'heading', '|',
            'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
            'fontSize', // tambahkan ini
            'undo', 'redo'
        ],
        fontSize: {
            options: [
                9,
                11,
                13,
                'default',
                17,
                19,
                21,
                27,
                35
            ],
            supportAllValues: false
        }
    };
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
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
        allCity: [], // store all cities
        form: {
            odata: "",
            properties: "",
            type: [],
            listing_type: [],
            address: "",
            total_rooms: 0,
            city: "",
            province: "",
            maps: "",
            notelp: "",
            description: "",
            information: "",
            price_per_night: 0,
            price_per_monthly: 0,
            price_per_year: 0,
            sale_price: 0,
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
            listing_type: [],
            address: "",
            city: [],
            province: [],
            maps: "",
            latitude: "",
            longitude: "",
            description: "",
            information: "",
            price_per_night: 0,
            price_per_monthly: 0,
            price_per_year: 0,
            sale_price: 0,
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
            listing_type: data.listing_type,
            address: data.address,
            city: data.city,
            province: data.province,
            maps: data.maps,
            latitude: data.latitude,
            longitude: data.longitude,
            description: data.description,
            information: data.information,
            price_per_night: data.price_per_night,
            price_per_monthly: data.price_per_monthly,
            price_per_year: data.price_per_year,
            sale_price: data.sale_price,
            total_rooms: data.total_rooms,
            images: data.image,
            isActive: data.isActive
        }
        modalAdd.value = true;
    };
    const save = async () => {
        loadingSubmit.value = true;
        const payload = new FormData();
        payload.append('odata', state.form.odata);
        payload.append('properties', state.form.properties);
        payload.append('type', state.form.type);
        payload.append('listing_type', state.form.listing_type);
        payload.append('address', state.form.address);
        payload.append('city', state.form.city);
        payload.append('province', state.form.province);
        payload.append('latitude', state.form.latitude);
        payload.append('longitude', state.form.longitude);
        payload.append('description', state.form.description);
        payload.append('information', state.form.information);
        payload.append('price_per_night', state.form.price_per_night);
        payload.append('price_per_monthly', state.form.price_per_monthly);
        payload.append('price_per_year', state.form.price_per_year);
        payload.append('sale_price', state.form.sale_price);
        payload.append('total_rooms', state.form.total_rooms);
        payload.append('isActive', state.form.isActive);
        if(state.form.images instanceof File){
            payload.append('images', state.form.images);
        }

        let response;
        if(action.value == 'add'){
            response = await apiPostData('/properties/store', payload, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
        }else{
            response = await apiPostData('/properties/update', payload, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
        }

        if(response){
            Swal.fire('Success', 'Data has been saved', 'success');
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
            state.allCity = cityResponse.data; // store all cities
            state.listCity = cityResponse.data; // default: show all or empty
        } catch (error) {
            console.error('Error fetching provinces and cities:', error);
        } finally {
            loading.value = false;
        }
    };

    const getCity = async () => {
        // Filter from allCity, not from listCity
        state.listCity = state.allCity.filter(city => city.province_odata === state.form.province);
    };

    const filterData = (input, option) => {
        return option.label.toLowerCase().includes(input.toLowerCase());
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

    ::v-deep(.ck-content) {
        min-height: 150px;
        max-height: 150px;
        overflow-y: auto;
    }
</style>