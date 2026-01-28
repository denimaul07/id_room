<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Adjustment" title="List Adjustment" />

                <div class="card">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <Button label="Tambah Adjustment" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
                                            <th class="text-center bg-dark text-nowrap">Tanggal</th>
                                            <th class="text-center bg-dark">No Transaksi</th>
                                            <th class="text-center bg-dark">Kategori</th>
                                            <th class="text-center bg-dark text-nowrap">Product Code</th>
                                            <th class="text-center bg-dark text-nowrap">Product Name</th>
                                            <th class="text-center bg-dark">Product SKU</th>
                                            <th class="text-center bg-dark">Qty</th>
                                            <th class="text-center bg-dark">Keterangan</th>
                                            <th class="text-center bg-dark">Cogs</th>
                                            <th class="text-center bg-dark">Harga</th>
                                            <th class="text-center bg-dark text-nowrap">Create Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                        <tr v-if="loading"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.adjustment.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                    
                                        <tr v-for="(data, index) in state.adjustment.data" :key="index" v-else>
                                            <td class="text-center">{{ index + state.adjustment.from }}</td>
                                            <td class="text-center text-nowrap">{{ data.tanggal }}</td>
                                            <td class="text-center text-nowrap">{{ data.notransaksi }}</td>
                                            <td class="text-center text-nowrap">{{ data.kategori }}</td>
                                            <td class="text-center text-nowrap">{{ data.product_varian.product.product_code }}</td>
                                            <td class="text-center text-nowrap">{{ data.product_varian.product.product_name }}</td>
                                            <td class="text-center text-nowrap">{{ data.product_varian.product_sku }}</td>
                                            <td class="text-center text-nowrap">{{ Math.abs(data.qty) }}</td>
                                            <td class="text-center text-nowrap">{{ data.keterangan }}</td>
                                            <td class="text-center text-nowrap">{{ data.cogs.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center text-nowrap">{{ data.harga_jual.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                            <td class="text-center text-nowrap">{{ data.create_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                    Showing {{ state.adjustment.from }} to {{ state.adjustment.to }} of {{ state.adjustment.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.adjustment.current_page" :total="state.adjustment.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
            <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
            </div>

            <div style="align-items:center;justify-content: center;display: flex;">
                {{ pesan }}
            </div>
        </a-modal>

        <a-modal v-model:open="modalAdd" title="Product List" :footer="null" width="1600px" >

            <div class="d-flex align-items-center mb-3">
                <div class="d-flex gap-2">
                </div>

                <div class="ms-auto">
                    <a-input-search
                        v-model:value="searchProduct"
                        placeholder="Cari Data"
                        style="width: 300px"
                    />
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center bg-dark">No</th>
                            <th class="text-center bg-dark">Action</th>
                            <th class="text-center bg-dark">Product Code</th>
                            <th class="text-center bg-dark">Product Name</th>
                            <th class="text-center bg-dark">Product Varian</th>
                            <th class="text-center bg-dark">Product Size</th>
                            <th class="text-center bg-dark">Product SKU</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loadingProduct"> 
                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                        </tr>

                        <tr v-else-if="state.products.total==0">
                            <td class="text-center" colspan="13"><a-empty/></td>
                        </tr>

                        <tr v-for="(data, index) in state.products.data" :key="index" v-else>
                            <td class="text-center">{{ index + state.products.from }}</td>
                            <td class="text-center">
                                <a-tooltip title="Pilih Product">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="pilih(data)">
                                        <template #icon>
                                            <CheckCircleOutlined />
                                        </template>
                                    </a-button>
                                </a-tooltip>
                            </td>
                            <td class="text-center">{{ data.product.product_code }}</td>
                            <td class="text-center">{{ data.product.product_name }}</td>
                            <td class="text-center">{{ data.product_varian }}</td>
                            <td class="text-center">{{ data.product_size }}</td>
                            <td class="text-center">{{ data.product_sku }}</td>
                        </tr>
                    </tbody>    
                </table>
            </div>

            
            <div class="row py-2">
                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                    Showing {{ state.products.from }} to {{ state.products.to }} of {{ state.products.total }} entries
                </div>
                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                    <a-pagination :current="state.products.current_page" :total="state.products.total" v-model:pageSize="paggingProduct" @change="handlePageChangeProduct" />
                </div>
            </div>

            <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
                <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                    <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
                </div>

                <div style="align-items:center;justify-content: center;display: flex;">
                    {{ pesan }}
                </div>
            </a-modal>

            <a-modal v-model:open="modalPilih" title="Form Adjustment" :footer="null" width="600px" >
                <a-form
                    :layout="'vertical'"
                >
                    <a-form-item label="Kategori">
                        <a-select style="width: 100%" v-model:value="state.form.kategori" placeholder="Pilih Kategori">
                            <a-select-option value="Adjustment Stock">Adjustment Stock</a-select-option>
                            <a-select-option value="Restock">Restock</a-select-option>
                            <a-select-option value="Stock Awal">Stock Awal</a-select-option>
                        </a-select>
                    </a-form-item>

                    <a-form-item label="Cogs">
                        <a-input-number style="width: 100%" v-model:value="state.form.cogs" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, '.')" :parser="value => value.replace(/Rp\s?|(\\.*)/g, '')"  />
                    </a-form-item>

                    <a-form-item label="Harga Jual">
                        <a-input-number style="width: 100%" v-model:value="state.form.harga" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, '.')" :parser="value => value.replace(/Rp\s?|(\\.*)/g, '')"  />
                    </a-form-item>

                    <a-form-item label="Qty">
                        <a-input-number style="width: 100%" v-model:value="state.form.qty" :min="1" />
                    </a-form-item>

                    <a-form-item label="Keterangan">
                        <a-textarea rows="4" v-model:value="state.form.ket" />
                    </a-form-item>

                    <div class="d-flex justify-content-end">
                        <Button label="Submit" icon="pi pi-check" class="btn btn-dark" size="small" :loading="loadingSubmit" 
                        :disabled="loadingButton"  @click="save"/>
                    </div>
                </a-form>

                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </a-modal> 

        </a-modal>
    </div>
</template> 


<script setup>
    import { apiGetData, apiCetakPDF, apiPostData,apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
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
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const pagging = ref(10);
    const search = ref('');
    const modalAdd = ref(false);
    const paggingProduct = ref(10);
    const searchProduct = ref('');
    const loadingProduct = ref(false);
    const modalPilih = ref(false);

    const state = reactive({
        adjustment: {},
        products: {},
        form: {
            id:"",
            kategori:"",
            cogs:"",
            product_id:"",
            ket:"",
            qty:0,
            harga:0
        }
    });

    const getAdjustmentList = async (page = state.adjustment.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/adjustment/index', params);
        state.adjustment = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getAdjustmentList(page);
    };

    const add = async (page = state.products.current_page || 1) => {
        processing.value = true;
        pesan.value = 'Mohon Sabar, Sedang Memuat Data ...';
        const params = {
            page: page,
            pagging: paggingProduct.value,
            search: searchProduct.value,
        };

        const response = await apiGetData('/adjustment/product', params)
        state.products = response.data;
        modalAdd.value = true;
        processing.value = false;
    };

    const handlePageChangeProduct = async(page) => {
        loadingProduct.value = true;
        const params = {
            page: page,
            pagging: paggingProduct.value,
            search: searchProduct.value,
        };

        const response = await apiGetData('/adjustment/product', params)
        state.products = response.data;
        loadingProduct.value = false;

    };

    const pilih = (data) => {
        processing.value = true;
        state.form = {
            product_id: data.id,
            cogs: data.harga_cogs,
            kategori: [],
            qty: 1,
            ket: '',
            harga: data.harga
        }
        processing.value = false;
        modalPilih.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;
        loadingButton.value = true;

        const payload = {
            product_id: state.form.product_id,
            kategori: state.form.kategori,
            cogs: state.form.cogs,
            qty: state.form.qty,
            ket: state.form.ket,
            harga: state.form.harga
        };

        const response =  await apiPostData('/adjustment/store', payload);

        if (response) {
            await getAdjustmentList();
            modalPilih.value = false;
            loadingSubmit.value = false;
            loadingButton.value = false;
        } else {
            loadingSubmit.value = false;
            loadingButton.value = false;
        }
    };

    onMounted(async() => {
        await getAdjustmentList();
    });

    watch(search, async() => {
        useDebounceFn(async() => {
            await getAdjustmentList();
        }, 500)();
    });

    watch(searchProduct, async() => {
        useDebounceFn(async() => {
        loadingProduct.value = true;
        const params = {
            page: 1,
            pagging: paggingProduct.value,
            search: searchProduct.value,
        };

        const response = await apiGetData('/adjustment/product', params)
        state.products = response.data;
        loadingProduct.value = false;
        }, 500)();
    });

</script>