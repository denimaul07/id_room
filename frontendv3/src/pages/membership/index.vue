<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Membership" title="List Membership Packages" />

                <div class="card ms-2">
                    
                    <div class="card-body">
                        <Tabs value="0" class="p-tab-active">
                            <TabList  class="p-tab-active">

                                <Tab value="0"> <span style="color: #222 !important;">Membership Packages</span></Tab>
                                <Tab value="1"> <span style="color: #222 !important;">Membership Benefits</span></Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
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
                                                        <th class="text-center bg-dark text-nowrap">Title</th>
                                                        <th class="text-center bg-dark">Desc</th>
                                                        <th class="text-center bg-dark">Price/Month</th>
                                                        <th class="text-center bg-dark text-nowrap">Discount Percent</th>
                                                        <th class="text-center bg-dark text-nowrap">Duration</th>
                                                        <th class="text-center bg-dark text-nowrap">Admin Fee</th>
                                                        <th class="text-center bg-dark text-nowrap">Cancel Booking Fee</th>
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
                                                        <td class="text-center">{{ data.title }}</td>
                                                        <td class="text-center">{{ data.desc }}</td>
                                                        <td class="text-center">{{ (data.price * 1).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                                        <td class="text-center">{{ data.discount_percent }}%</td>
                                                        <td class="text-center">
                                                            {{ 
                                                                data.duration >= 52 
                                                                    ? Math.floor(data.duration / 52) + ' tahun' 
                                                                        + (Math.floor((data.duration % 52) / 4) > 0 ? ' ' + Math.floor((data.duration % 52) / 4) + ' bulan' : '') 
                                                                        + ((data.duration % 52) % 4 !== 0 ? ' ' + ((data.duration % 52) % 4) + ' minggu' : '') 
                                                                    : (Math.floor(data.duration / 4) > 0 
                                                                        ? Math.floor(data.duration / 4) + ' bulan' + (data.duration % 4 !== 0 ? ' ' + (data.duration % 4) + ' minggu' : '') 
                                                                        : data.duration + ' minggu')
                                                            }}
                                                        </td>
                                                        <td class="text-center">
                                                            <span v-if="data.fee_admin == 1">Free Admin Fee</span>
                                                            <span v-else>Paid Admin Fee</span>
                                                        </td>

                                                        <td class="text-center">{{ data.cancel_booking_fee }} x</td>
                                                    
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
                                <TabPanel value="1">
                                    <MembershipBenefits />
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
            
            width="800"
        >

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                    <a-input v-model:value="state.form.title" placeholder="Title" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Description</label>
                <div class="col-sm-9">
                    <a-textarea v-model:value="state.form.desc" placeholder="Description" rows="4" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Price / Month</label>
                <div class="col-sm-9">
                    <a-input-number style="width:100%" 
                        v-model:value="state.form.price"
                        :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                        :parser="value => value.replace(/\Rp\s?|(,*)/g, '')"
                    />
                </div>  
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Discount Percent</label>
                <div class="col-sm-9">
                    <a-input-number v-model:value="state.form.discount_percent" style="width: 100%" placeholder="Discount Percent" />
                </div>
            </div>


            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Duration In Weeks </label>
                <div class="col-sm-9">
                    <a-input-number v-model:value="state.form.duration" style="width: 100%" placeholder="Duration in Weeks" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Cancel Booking Fee</label>
                <div class="col-sm-9">
                    <a-input-number style="width:100%" 
                        v-model:value="state.form.cancel_booking_fee" placeholder="Cancel Booking Fee"
                    />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Admin Fee</label>
                <div class="col-sm-9">
                    <a-select v-model:value="state.form.fee_admin" style="width: 100%" placeholder="Select Admin Fee">
                        <a-select-option :value="0">Paid Admin Fee</a-select-option>
                        <a-select-option :value="1">Free Admin Fee</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <a-select v-model:value="state.form.isActive" style="width: 100%" placeholder="Select Status">
                        <a-select-option :value="0">Active</a-select-option>
                        <a-select-option :value="1">Inactive</a-select-option>
                    </a-select>
                </div>   
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Membership Benefits</label>
                <div class="col-sm-9">
                    <table class="table table-bordered mb-2">
                        <thead>
                            <tr>
                                <th style="width:50%">Benefit</th>
                                <th style="width:30%">Value</th>
                                <th style="width:20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                    
                            <tr v-for="(row, idx) in state.form.benefitRows" :key="row.key">
                                <td>
                                    <a-select
                                        v-model:value="row.odata_benefit"
                                        style="width: 100%"
                                        placeholder="Pilih Benefit"
                                    >
                                        <a-select-option v-for="item in benefitOptions" :key="item.odata" :value="item.odata">
                                            {{ item.name }} ({{ item.benefit_type }})
                                        </a-select-option>
                                    </a-select>
                                </td>
                                <td>
                                    <a-select v-model:value="row.value" style="width: 100%" placeholder="Select Value">
                                        <a-select-option :value="1">Yes</a-select-option>
                                        <a-select-option :value="0">No</a-select-option>
                                    </a-select>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-success btn-sm me-2" @click="addBenefitRow" v-if="idx === state.form.benefitRows.length - 1"><i class="fa fa-plus"></i></button>
                                        <button class="btn btn-danger btn-sm" @click="removeBenefitRow(idx)" v-if="state.form.benefitRows.length > 1"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
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
    import MembershipBenefits from './membership_benefits.vue';
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
            odata: "",
            benefitRows: [], // [{key, odata_benefit, value}]
            title: "",
            desc: "",
            price: "",
            duration: "",
            discount_percent: "",
            cancel_booking_fee: "",
            fee_admin: "",
            isActive: []
        }
    });

    // Options untuk select benefit
    const benefitOptions = ref([]);

    // Ambil data benefit saat mounted
    const getBenefitOptions = async () => {
        const response = await apiGetData('/membership_benefit/index', { pagging: 1000 });
        benefitOptions.value = response.data.data || [];
    };

    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/membership/index', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = async () => {
        action.value = 'add';
        state.form = {
            benefitRows: [
                {
                    odata_benefit: [],
                    value: []
                }
            ],
            title: "",
            desc: "",
            price: 0,
            duration: "",
            cancel_booking_fee: 0,
            fee_admin: 0,
            discount_percent: 0,
            isActive: []
        }
        modalAdd.value = true;
    };

    const view = async (data) => {
        action.value = 'edit';
        state.form = {
            odata: data.odata,
            benefitRows: Array.isArray(data.benefits) && data.benefits.length > 0
                ? data.benefits.map(benefit => ({
                    key: Math.random().toString(36).substr(2, 9),
                    odata_benefit: benefit.odata_benefit,
                    value: benefit.value
                }))
                : [{
                    key: Math.random().toString(36).substr(2, 9),
                    odata_benefit: [],
                    value: []
                }],
            title: data.title,
            desc: data.desc,
            price: data.price,
            duration: data.duration,
            discount_percent: data.discount_percent,
            cancel_booking_fee: data.cancel_booking_fee,
            fee_admin: data.fee_admin,
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
            title: state.form.title,
            desc: state.form.desc,
            price: state.form.price,
            duration: state.form.duration,
            discount_percent: state.form.discount_percent,
            cancel_booking_fee: state.form.cancel_booking_fee,
            fee_admin: state.form.fee_admin,
            isActive: state.form.isActive
        };

        let response;
        if(action.value == 'add'){
            response = await apiPostData('/membership/index', payload);
        }else{
            response = await apiPutData('/membership/index', payload);
        }

        if(response){
            modalAdd.value = false;
            loadingSubmit.value = false;
            await getData();
        }else{
            loadingSubmit.value = false;
        }
    };

    // Fungsi add/remove row benefit
    const addBenefitRow = () => {
        state.form.benefitRows.push({
            key: Math.random().toString(36).substr(2, 9),
            odata_benefit: '',
            value: ''
        });
    };
    const removeBenefitRow = (idx) => {
        state.form.benefitRows.splice(idx, 1);
    };



    onMounted(async() => {
        await getData();
        await getBenefitOptions();
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