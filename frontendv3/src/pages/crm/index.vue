<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="CRM" title="List Data CRM" />

                <div class="card ms-2">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <Button label="Add Leads" severity="primary" class="bg-dark" @click="AddLeads" />
                            </div>

                            <div class="ms-auto">
                                <a-range-picker
                                    v-model:value="filterDate"
                                    style="width: 260px; margin-right: 8px"
                                    :allowClear="false"
                                />
                                <a-input-search
                                    v-model:value="search"
                                    placeholder="Cari Data"
                                    style="width: 300px"
                                />
                                
                            </div>
                        </div>

                        <Tabs v-model:value="activeTab" @change="handleTabChange" scrollable>
                            <TabList>
                                <Tab value="TOTAL">Total Leads ({{ totalLeads }})</Tab>
                                <Tab value="NEEDFU">Need FollowUp (<span class="text-warning">{{ needFollowUp }}</span>)</Tab>
                                <Tab value="FOLLOWUP">Process Leads (<span class="text-info">{{ processFollowUp }}</span>)</Tab>
                                <Tab value="CLOSING">Closing Leads (<span class="text-success">{{ closingLeads }}</span>)</Tab>
                                <Tab value="LOST">Lost Leads (<span class="text-danger">{{ lostLeads }}</span>)</Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel v-for="tab in ['TOTAL','NEEDFU','FOLLOWUP','CLOSING','LOST']" :key="tab" :value="tab">

                                    <div class="mb-3 row">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center bg-dark sticky-No">No</th>
                                                        <th class="text-center bg-dark sticky-Action">Action</th>
                                                        <th class="text-center bg-dark text-nowrap sticky-Status">Status</th>
                                                        <th class="text-center bg-dark text-nowrap">Name</th>
                                                        <th class="text-center bg-dark text-nowrap">Email</th>
                                                        <th class="text-center bg-dark text-nowrap">No Telp</th>
                                                        <th class="text-center bg-dark text-nowrap">Source</th>
                                                        <th class="text-center bg-dark text-nowrap">Tanggal Leads</th>
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
                                                            <a-tooltip title="View Detail">
                                                                <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                                                    <template #icon>
                                                                        <EyeOutlined />
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                            <a-tooltip title="Process Leads" v-if="data.status == 'NEEDFU' || data.status == 'FOLLOWUP'">
                                                                <a-button type="primary" size="small" class="me-2" :class="data.status == 'NEEDFU' ? 'bg-warning' : 'bg-info'" @click="process(data)">
                                                                    <template #icon>
                                                                        <i class="fa-solid fa-arrow-alt-circle-right"></i>
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning" v-if="data.status == 'NEEDFU'">{{ data.status }}</span>
                                                            <span class="badge bg-info" v-else-if="data.status == 'FOLLOWUP'">{{ data.status }}</span>
                                                            <span class="badge bg-danger" v-else-if="data.status == 'LOST'">{{ data.status }}</span>
                                                            <span class="badge bg-success" v-else-if="data.status == 'CLOSING'">{{ data.status }}</span>
                                                            <span class="badge bg-secondary" v-else>{{ data.status }}</span>
                                                        </td>
                                                        <td class="text-center">{{ data.nama }}</td>
                                                        <td class="text-center">{{ data.email }}</td>
                                                        <td class="text-center">{{ data.notelp }}</td>
                                                        <td class="text-center">{{ data.source }}</td>
                                                        <td class="text-center">{{ data.tanggal_leads }}</td>
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
            v-model:open="modalAdd" 
            :width="'500px'" 
            :closable="true" 
            :maskClosable="true" 
            :title="action + ' Leads'"
        >
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nama</label>
                <div class="col-sm-9">
                    <a-input v-model:value="state.form.name" placeholder="Nama" style="width:100%" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-9">
                    <a-input v-model:value="state.form.email" placeholder="Email" style="width:100%" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">No Telp</label>
                <div class="col-sm-9 d-flex gap-2">
                    <a-select
                        v-model:value="state.form.kodenegara"
                        :options="state.kodenegara"
                        placeholder="Kode Negara"
                        style="width: 140px"
                        :field-names="{ label: 'nama', value: 'kode' }"
                    />
                    <a-input
                        v-model:value="state.form.telp"
                        placeholder="No Telp"
                        style="width: calc(100% - 140px - 8px)"
                        @input="onTelpInput"
                    />
                </div>
            </div>

            <div class="row mb-3" v-if="action === 'Edit'">
                <label class="col-sm-3 col-form-label">Tanggal Leads</label>
                <div class="col-sm-9">
                    <a-date-picker v-model:value="state.form.tanggal_leads" placeholder="Tanggal Leads" style="width:100%" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Source</label>
                <div class="col-sm-9">
                    <a-select
                        v-model:value="state.form.source"
                        :options="state.source"
                        placeholder="Source"
                        style="width: 100%"
                        :field-names="{ label: 'source', value: 'source' }"
                    />
                </div>
            </div>



            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Remaks</label>
                <div class="col-sm-9">
                    <a-input v-model:value="state.form.remaks" placeholder="Remaks" style="width:100%" />
                </div>
            </div>

            <div class="row mb-3" v-if="action === 'Edit'">
                <label class="col-sm-3 col-form-label">Keterangan Remaks</label>
                <div class="col-sm-9">
                    <a-textarea v-model:value="state.form.ket_remarks" placeholder="Keterangan Remaks"  style="width:100%" />
                </div>
            </div>

            <div class="row mb-3" v-if="action === 'Edit'">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <a-select
                        v-model:value="state.form.status"
                        placeholder="Status"
                        style="width: 100%"
                    >
                        <a-select-option value="NEEDFU">NEEDFU</a-select-option>
                        <a-select-option value="FOLLOWUP">FOLLOWUP</a-select-option>
                        <a-select-option value="CLOSING">CLOSING</a-select-option>
                        <a-select-option value="LOST">LOST</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="mt-4 row" v-if="state.history.length > 0 && action === 'Edit'">
                <label class="col-sm-3 col-form-label">History</label>
                <div class="col-sm-9">
                    <a-timeline>
                        <a-timeline-item v-for="(item, index) in state.history" :key="index">
                            <strong>{{ dayjs(item.created_at).format('YYYY-MM-DD HH:mm:ss') }}</strong><br>
                            {{ item.remarks }}
                        </a-timeline-item>
                    </a-timeline>
                </div>
            </div>



            <template #footer v-if="action === 'Edit' && state.form.status === 'NEEDFU'">
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

        <a-drawer 
            v-model:open="modalProsess" 
            :width="'500px'" 
            :closable="true" 
            :maskClosable="true" 
            title="Process Leads" 
        >
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Remark</label>
                <div class="col-sm-9">
                    <a-select
                        v-model:value="state.form.remaks"
                        :options="state.remark"
                        placeholder="Remark"
                        style="width: 100%"
                        :field-names="{ label: 'remark', value: 'remark' }"
                    />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Keterangan Remark</label>
                <div class="col-sm-9">
                    <a-textarea v-model:value="state.form.ket_remarks" placeholder="Keterangan Remark"  style="width:100%" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Status</label>
                <div class="col-sm-9">
                    <a-select
                        v-model:value="state.form.status"
                        placeholder="Status"
                        style="width: 100%"
                    >
                        <a-select-option value="FOLLOWUP">FOLLOWUP</a-select-option>
                        <a-select-option value="CLOSING">CLOSING</a-select-option>
                        <a-select-option value="LOST">LOST</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="mt-4 row" v-if="state.history.length > 0">
                <label class="col-sm-3 col-form-label">History</label>
                <div class="col-sm-9">
                    <a-timeline>
                        <a-timeline-item v-for="(item, index) in state.history" :key="index">
                            <strong>{{ dayjs(item.created_at).format('YYYY-MM-DD HH:mm:ss') }}</strong><br>
                            {{ item.remarks }}
                        </a-timeline-item>
                    </a-timeline>
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="saveProsess">
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
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch} from 'vue'
    import { useStore } from "vuex";
    import {
        EyeOutlined,
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import { useRoute, useRouter } from 'vue-router';
    const route = useRoute();
    const router = useRouter();
    // Only allow numbers, and prevent leading zero in real-time
    const onTelpInput = (e) => {
        let val = e.target.value.replace(/\D/g, ''); // Remove non-digits
        // Prevent typing 0 as the first character
        if (val.length === 1 && val === '0') {
            val = '';
        } else if (val.length > 1) {
            val = val.replace(/^0+/, '');
        }
        state.form.telp = val;
        // Update the input value directly to prevent cursor jump
        e.target.value = val;
    }
    const store = useStore();
    const pagging = ref(10);
    const search = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const activeTab = ref(route.query.tab || 'TOTAL');
    const filterDate = ref([]);
    if(route.query.date == 'today') {
        filterDate.value = [dayjs().startOf('day'), dayjs().endOf('day')];
    }else if(route.query.date == 'month') {
        filterDate.value = [dayjs().startOf('month'), dayjs().endOf('month')];
    }else if(route.query.date == 'year') {
        filterDate.value = [dayjs().startOf('year'), dayjs().endOf('year')];
    }else if(route.query.date == 'custom') {
        filterDate.value = [
            dayjs(route.query.customStart),
            dayjs(route.query.customEnd)
        ];
    }else {
        filterDate.value = [dayjs().startOf('day'), dayjs().endOf('day')];
    }
    const totalLeads = ref(0);
    const needFollowUp = ref(0);
    const processFollowUp = ref(0);
    const closingLeads = ref(0);
    const lostLeads = ref(0);
    const status = ref(['NEEDFU', 'FOLLOWUP', 'CLOSING', 'LOST']);
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'
    const modalProsess = ref(false);

    const state = reactive({
        listData: {},
        kodenegara : {},
        source : {},
        remark : {},
        history: {},
        form: {
            odata: "",
            name: "",
            email: "",
            kodenegara:[],
            telp: "",
            source: [],
            remaks: "",
            ket_remarks: "",
            status: [],
            tanggal_leads: "",
        }
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
            status: status.value,
            start_date: filterDate.value[0] ? dayjs(filterDate.value[0]).format('YYYY-MM-DD') : null,
            end_date: filterDate.value[1] ? dayjs(filterDate.value[1]).format('YYYY-MM-DD') : null,
        };
        const response = await apiGetData('/crm/index', params);
        state.listData = response.data;
        totalLeads.value = response.total_leads;
        needFollowUp.value = response.need_followup;
        processFollowUp.value = response.process_followup;
        closingLeads.value = response.closing_leads;
        lostLeads.value = response.lost_leads;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const  handleTabChange = async (value) => {
        switch (value) {
            case 'TOTAL':
                status.value = '';
                break;
            case 'NEEDFU':
                status.value = 'NEEDFU';
                break;
            case 'FOLLOWUP':
                status.value = 'FOLLOWUP';
                break;
            case 'CLOSING':
                status.value = 'CLOSING';
                break;
            case 'LOST':
                status.value = 'LOST';
                break;
        }
        state.listData.current_page = 1;
        await getData();
    };

    const AddLeads = () => {
        action.value = 'Add';
        modalAdd.value = true;
        state.form = {
            odata: "",
            name: "",
            email: "",
            kodenegara:[],
            telp: "",
            source: [],
            remaks: "",
            status: [],
            tanggal_leads: dayjs()
        }
    }

    const save = async () => {
        loadingSubmit.value = true;

        const payload = {
            odata: state.form.odata,
            name: state.form.name,
            email: state.form.email,
            kodenegara: state.form.kodenegara,
            telp: state.form.telp,
            source: state.form.source,
            remaks: state.form.remaks,
            status: state.form.status,
            tanggal_leads: state.form.tanggal_leads,
        };


        if (action.value === 'Add') {
            const response = await apiPostData('/crm/index', payload)
            if (response) {
                modalAdd.value = false;
                loadingSubmit.value = false;
                getData();
            } else {
                loadingSubmit.value = false;
            }
        } else {
            const response = await apiPutData('/crm/index/', payload)
            if (response) {
                modalAdd.value = false;
                loadingSubmit.value = false;
                getData();
            } else {
                loadingSubmit.value = false;
            }
        }
    };

    const view = async (data) => {
        action.value = 'Edit';
        modalAdd.value = true;

        // Cari kode negara dari daftar kode negara
        let kodenegara = '';
        let telp = '';
        const kodeNegaraList = state.kodenegara.map(item => item.kode);

        // Temukan kode negara yang cocok di awal nomor telepon
        const foundKode = kodeNegaraList.find(kode => data.notelp.startsWith(kode));
        if (foundKode) {
            kodenegara = foundKode;
            telp = data.notelp.slice(foundKode.length);
        } else {
            kodenegara = '';
            telp = data.notelp;
        }

        state.form = {
            odata: data.odata,
            name: data.nama,
            email: data.email,
            kodenegara: kodenegara,
            telp: telp,
            source: data.source,
            remaks: data.remaks,
            ket_remarks: data.ket_remaks,
            status: data.status,
            tanggal_leads: dayjs(data.tanggal_leads)
        }

        await apiGetData('/crm/history', { leads_odata: data.odata }).then((response) => {
            state.history = response.data;
        });
    }

    const process = async(data) => {
        if (data.status === 'NEEDFU') {
            Swal.fire({
                title: 'Process Leads',
                text: 'Are you sure you want to process this lead?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Process it!',
                cancelButtonText: 'No, cancel!',
            }).then(async (result) => {
                if (result.isConfirmed) {
                    processing.value = true;
                    const payload = {
                        odata: data.odata,
                        status: 'FOLLOWUP',
                    }
                    const response = await apiPutData('/crm/process/', payload);
                    if (response) {
                        pesan.value = 'Leads processed successfully';
                        getData();
                    }
                    processing.value = false;
                }
            });
        } else if (data.status === 'FOLLOWUP') {
            modalProsess.value = true;
            state.form = {
                odata: data.odata,
                remaks: [],
                ket_remarks: "",
                status: []
            };
            const response = await apiGetData('/crm/history', { leads_odata: data.odata });
            state.history = response.data;
        }
    
    }

    const saveProsess = async () => {
        loadingSubmit.value = true;

        const payload = {
            odata: state.form.odata,
            remaks: state.form.remaks,
            ket_remarks: state.form.ket_remarks,
            status: state.form.status,
        };

        const response = await apiPutData('/crm/process_followup/', payload)
        if (response) {
            modalProsess.value = false;
            loadingSubmit.value = false;
            getData();
        } else {
            loadingSubmit.value = false;
        }
    };

    const getKodeNegara = async () => {
        const response = await apiGetData('/public/kode-negara');
        state.kodenegara = response.data.map(item => ({
            kode: item.code,
            nama: `${item.code} (${item.name})`
        }));
    }

    const getSource = async () => {
        const response = await apiGetData('/crm/source');
        state.source = response.data.map(item => ({
            source: item.source,
        }));
    }

    const getRemark = async () => {
        const response = await apiGetData('/crm/remark');
        state.remark = response.data.map(item => ({
            remark: item.remark,
        }));
    }

    onMounted(async() => {
        await handleTabChange(activeTab.value),
        await getKodeNegara();
        await getSource();
        await getRemark();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));

    watch(activeTab, async (newValue) => {
        handleTabChange(newValue);
    });

    watch(filterDate, async () => {
        await handleTabChange(activeTab.value);
    });
</script>

<style scoped>
    .sticky-No {
        position: sticky;
        left: 0;
        z-index: 1;
    }
    .sticky-Action {
        position: sticky;
        left: 50px; /* Adjust based on the width of the No column */
        z-index: 1;
    }
    .sticky-Status {
        position: sticky;
        left: 150px; /* Adjust based on the width of the No and Action columns */
        z-index: 1;
    }
</style>