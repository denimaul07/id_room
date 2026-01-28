<template>
    <div>

        <div class="card">
            
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex gap-2">
                        <Button label="Export Excel" icon="pi pi-file-excel" class="p-button-success" size="small" @click="openModal" />
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
                                    <th class="text-center bg-dark">Action</th>
                                    <th class="text-center bg-dark">Sales Date</th>
                                    <th class="text-center bg-dark">No Invoice</th>
                                    <th class="text-center bg-dark">Invoice Date</th>
                                    <th class="text-center bg-dark">Customers Name</th>
                                    <th class="text-center bg-dark">Reference</th>
                                    <th class="text-center bg-dark">Store</th>
                                    <th class="text-center bg-dark">Created Invoice By</th>
                                    <th class="text-center bg-dark">Updated Invoice By</th>
                                </tr>
                            </thead>
                            <tbody>
                        
                                <tr v-if="loading"> 
                                    <td class="text-center" colspan="13"><a-skeleton active /></td>
                                </tr>

                                <tr v-else-if="state.sales.total==0">
                                    <td class="text-center" colspan="13"><a-empty/></td>
                                </tr>
                            
                                <tr v-for="(data, index) in state.sales.data" :key="index" v-else>
                                    <td class="text-center">{{ index + state.sales.from }}</td>
                                    <td class="text-center">
                                        <a-tooltip title="View Sales">
                                            <a-button type="primary" size="small" @click="view(data,'view')" class="bg-success me-2">
                                                <template #icon>
                                                    <EyeOutlined />
                                                </template>
                                            </a-button>
                                        </a-tooltip>

                                        <a-tooltip title="Edit Sales">
                                            <a-button type="success" size="small" @click="view(data,'edit')" class="bg-info me-2">
                                                <template #icon>
                                                    <EditOutlined />
                                                </template>
                                            </a-button>
                                        </a-tooltip>

                                        <a-tooltip title="Cetak PDF">
                                            <a-button type="danger" size="small" @click="cetakpdf(data)" class="bg-dark">
                                                <template #icon>
                                                    <PrinterOutlined />
                                                </template>
                                            </a-button>
                                        </a-tooltip>
                                    </td>
                                    <td class="text-center">{{ data.sales_date }}</td>
                                    <td class="text-center">{{ data.noinvoice }}</td>
                                    <td class="text-center">{{ data.tanggal_invoice }}</td>
                                    <td class="text-center">{{ data.customers_name }}</td>
                                    <td class="text-center">{{ data.reference }}</td>
                                    <td class="text-center">{{ data.department.deptname }}</td>
                                    <td class="text-center">{{ data.created_name }}</td>
                                    <td class="text-center">{{ data.updated_name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row py-2">
                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                            Showing {{ state.sales.from }} to {{ state.sales.to }} of {{ state.sales.total }} entries
                        </div>
                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                            <a-pagination :current="state.sales.current_page" :total="state.sales.total" v-model:pageSize="pagging" @change="handlePageChange" />
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

        <a-modal v-model:open="modalPDF" :footer="null" style="top: 20px" :closable=true  title="Cetak Form Pengadaan Barang" width="2000px">
            <div class="col-12">
                <iframe :src="pdfUrl"  width="100%" height="700px"  fullscreen="true" />
            </div>
        </a-modal>

        <a-modal v-model:open="modalView"  style="top: 20px" :closable=true  :title="action=='view' ? 'View Sales':'Edit Sales'" width="1700px">
            <div class="col-12">
                <div class="row">
                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">No Invoice</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.noinvoice" style="width: 100%;" readonly />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Bill To</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.customers_name" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Reference</label>
                            <div class="col-sm-8">
                                <a-select v-model:value="state.form.reference" style="width: 100%;" :options="state.reference.map(item => ({ label: item.reference, value: item.reference }))"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Phone Number</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.notelp" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.email" style="width: 100%;"  />
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Instagram ID</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.instagram_id" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Address</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.address" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">City</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.city" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Type</label>
                            <div class="col-sm-8">
                                <a-select v-model:value="state.form.type" style="width: 100%;" :options="state.type.map(item => ({ label: item.type, value: item.type }))"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Tanggal Bayar</label>
                            <div class="col-sm-8">
                                <a-date-picker v-model:value="state.form.tanggal_bayar" style="width: 100%;"  />
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Tanggal Invoice</label>
                            <div class="col-sm-8">
                                <a-date-picker v-model:value="state.form.tanggal_invoice" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Tanggal Sales</label>
                            <div class="col-sm-8">
                                <a-date-picker v-model:value="state.form.sales_date" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Cara Bayar</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.cara_bayar" style="width: 100%;"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Type Penjualan</label>
                            <div class="col-sm-8">
                                <a-select v-model:value="state.form.type_penjualan"  style="width: 100%;">
                                    <a-select-option value="0">Offline</a-select-option>
                                    <a-select-option value="1">Online</a-select-option>
                                </a-select>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center bg-dark">No</th>
                                        <th class="text-center bg-dark" v-if="action==='edit'">Action</th>
                                        <th class="text-center bg-dark">Type</th>
                                        <th class="text-center bg-dark">No Transaction</th>
                                        <th class="text-center bg-dark">Product Code</th>
                                        <th class="text-center bg-dark">Product Name</th>
                                        <th class="text-center bg-dark">Product Varian</th>
                                        <th class="text-center bg-dark">Product Size</th>
                                        <th class="text-center bg-dark">Product SKU</th>
                                        <th class="text-center bg-dark">Quantity</th>
                                        <th class="text-center bg-dark">Cogs</th>
                                        <th class="text-center bg-dark">Harga Jual</th>
                                        <th class="text-center bg-dark">Disc</th>
                                        <th class="text-center bg-dark">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data, index) in state.transaksi" :key="index">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center" v-if="action==='edit'">
                                            <a-tooltip title="Ganti Product">
                                                <a-button type="primary" size="small" class="bg-info me-2" @click="gantiProduct(data)">
                                                    <template #icon>
                                                        <SwapOutlined   />
                                                    </template>
                                                </a-button>
                                            </a-tooltip>

                                            <a-tooltip title="Update Product">
                                                <a-button type="primary" size="small" class="bg-dark me-2" @click="editProduct(data)">
                                                    <template #icon>
                                                        <EditOutlined />
                                                    </template>
                                                </a-button>
                                            </a-tooltip>

                                            <a-tooltip title="Batal Product">
                                                <a-button type="success" size="small" class="bg-danger" @click="batalProduct(data)">
                                                    <template #icon>
                                                        <CloseCircleOutlined />
                                                    </template>
                                                </a-button>
                                            </a-tooltip>
                                        </td>
                                        <td class="text-center">{{ data.cus_type?.type ?? 'Umum / Non Member' }}</td>
                                        <td class="text-center">{{ data.notransaksi }}</td>
                                        <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                        <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                        <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                        <td class="text-center">{{ data.product_varian.product_size }}</td>
                                        <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                        <td class="text-center">{{ Math.abs(data.qty) }}</td>
                                        <td class="text-center">
                                            <span v-if="isSuperAdmin">{{ data.cogs.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</span>
                                            <span v-else> Rp. xxx.xxx.xxx </span>
                                        </td>
                                        <td class="text-center">{{ data.harga_jual.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                        <td class="text-center">{{ data.discount }} %</td>
                                        <td class="text-left">
                                            {{
                                                (data.harga_jual - (data.harga_jual * ((data.discount || 0) / 100)))
                                                    .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
                                                    .slice(0, -3)
                                            }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Total</td>
                                        <td class="text-left bg-dark" width="150px">
                                            {{
                                                state.transaksi.reduce((total, item) => {
                                                    const itemTotal = item.harga_jual - (item.harga_jual * ((item.discount || 0) / 100));
                                                    return total + itemTotal;
                                                }, 0)
                                                .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
                                                .slice(0, -3)
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Ongkir</td>
                                        <td class="text-left bg-dark">
                                            <a-input-number v-model:value="state.form.ongkir" style="width: 100%;" min="0" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="value => value.replace(/Rp\s?|(,*)/g, '')" />
                                        </td>
                                    </tr>    

                                    <tr>
                                        <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Biaya Layanan</td>
                                        <td class="text-left bg-dark">
                                            <a-input-number v-model:value="state.form.layanan" style="width: 100%;" min="0" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="value => value.replace(/Rp\s?|(,*)/g, '')" />
                                        </td>
                                    </tr>   

                                    <tr>
                                        <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Biaya Aplikasi</td>
                                        <td class="text-left bg-dark">
                                            <a-input-number v-model:value="state.form.apps" style="width: 100%;" min="0" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="value => value.replace(/Rp\s?|(,*)/g, '')" />
                                        </td>
                                    </tr>   

                                    <tr>
                                        <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Grand Total</td>
                                        <td class="text-left bg-dark">
                                            {{
                                                (state.transaksi.reduce((total, item) => {
                                                    const itemTotal = item.harga_jual - (item.harga_jual * ((item.discount || 0) / 100));
                                                    return total + itemTotal;
                                                }, 0) - (Number(state.form.layanan) || 0) - (Number(state.form.apps) || 0) + (Number(state.form.ongkir) || 0))
                                                .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
                                                .slice(0, -3)
                                            }}
                                        </td>
                                    </tr>
                                </tbody>    
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-danger" @click="batal" v-if="action==='edit'">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-times me-2" aria-hidden="true" v-else></i>
                    Batal Invoice
                </button>

                <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="update" v-if="action==='edit'">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                    Update
                </button>
            
            </template>
            <br>
            <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>

            <a-modal v-model:open="modalEdit"  :closable=true  title="Edit Product" width="500px">
                <div class="col-12">
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Quantity</label>
                        <div class="col-sm-8">
                            <a-input-number v-model:value="state.formProduct.qty" style="width: 100%;" min="1" />
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Discount (%)</label>
                        <div class="col-sm-8">
                            <a-input-number v-model:value="state.formProduct.discount" style="width: 100%;" min="0" max="100" />
                        </div>
                    </div>
                </div>

                <template #footer>
                    <button type="button" :disabled="loadingButtonVarian" class="btn btn-primary me-2" @click="updateProduct" >
                        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                        Update
                    </button>
                
                </template>
                <br>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </a-modal>

            <a-modal v-model:open="modalGanti" :footer="null" :closable=true   title="Ganti Product" width="1200px">
                <div class="col-12">
                    <div class="ms-auto mb-2">
                        <a-input-search
                            v-model:value="searchProductVarian"
                            placeholder="Cari Data"
                            style="width: 300px"
                        />
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
                                    <th class="text-center bg-dark">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                        
                                <tr v-if="loading"> 
                                    <td class="text-center" colspan="13"><a-skeleton active /></td>
                                </tr>

                                <tr v-else-if="state.product_varian.total==0">
                                    <td class="text-center" colspan="13"><a-empty/></td>
                                </tr>
                            
                                <tr v-for="(data, index) in state.product_varian.data" :key="index" v-else>
                                    <td class="text-center">{{ index + state.product_varian.from }}</td>
                                    <td class="text-center">
                                        <a-tooltip title="Ganti Product">
                                            <a-button type="primary" size="small" @click="switchProduct(data)" class="bg-dark">
                                                <template #icon>
                                                    <CheckCircleOutlined />
                                                </template>
                                            </a-button>
                                        </a-tooltip>
                                    </td>
                                    <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                    <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                    <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                    <td class="text-center">{{ data.product_varian.product_size }}</td>
                                    <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                    <td class="text-center">{{ data.stock }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="row py-2">
                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                            Showing {{ state.product_varian.from }} to {{ state.product_varian.to }} of {{ state.product_varian.total }} entries
                        </div>
                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                            <a-pagination :current="state.product_varian.current_page" :total="state.product_varian.total" v-model:pageSize="pagging" @change="handlePageChangeProduct" />
                        </div>
                    </div>
                </div>
            </a-modal>
        </a-modal>

        <a-modal v-model:open="modalExport"  :closable=true  title="Export Excel Sales" width="500px">
            <div class="col-12">
                <div class="mb-3 row">
                    <label class="col-sm-4 col-form-label">Tanggal Delivery</label>
                    <div class="col-sm-8">
                        <a-range-picker v-model:value="Tanggal" style="width: 100%;" />
                    </div>
                </div>
            </div>

            <template #footer>
                <button type="button" :disabled="loadingButtonVarian" class="btn btn-primary me-2" @click="exportExcel" >
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                    Export Excel
                </button>
            
            </template>
            <br>
            <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>   
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
        PrinterOutlined,
        SwapOutlined
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import checkRole from '@/store/modules/role.js';
    const isSuperAdmin = checkRole(['superAdmin']);
    const store = useStore();
    const router = useRouter();
    const user = store.getters["auth/currentUser"];
    const loadingSubmitVarian = ref(false);
    const loadingButtonVarian = ref(false);
    const pagging = ref(10);
    const search = ref('');
    const modalPDF = ref(false);
    const pdfUrl = ref('');
    const action = ref('');
    const modalView = ref(false);
    const modalEdit = ref(false);
    const modalGanti = ref(false);
    const searchProductVarian = ref('');
    const state = reactive({
        sales: {},
        transaksi: {},
        reference: {},
        product_varian: {},
        type: {},
        form: {
            id: '',
            noinvoice: '',
            customers_name: '',
            reference: '',
            notelp: '',
            email: '',
            instagram_id: '',
            address: '',
            city: '',
            kode_toko_barang: '',
            type:[],
            tanggal_bayar: '',
            type_penjualan: [],
            sales_date: '',
            tanggal_invoice: '',
            cara_bayar: '',
            ongkir: 0,
            layanan: 0,
            apps:0
        },
        formProduct: {
            id: '',
            qty: 0,
            discount: 0,
        },
    });

    const getSalesList = async (page = state.sales.current_page) => {
        loading.value = true;
        const params = {page: page, per_page: pagging.value, search: search.value };
        const response = await apiGetData("/sales/index", params);
        state.sales = response.data;
        loading.value = false;
    };

    const cetakpdf = async (data) => {
        processing.value = true
        pesan.value="Sabar Dikit ya, Lagi Proses Generate  Data"

        const response = await apiCetakPDF(`/sales/printInvoice`, {
            noinvoice: data.noinvoice
        })

        if (response) {
            processing.value = false;
            modalPDF.value = true;
            pdfUrl.value = URL.createObjectURL(new Blob([response], { type: 'application/pdf' }));
            await getData()
        } else {
            processing.value = false;
        }
    };

    const handlePageChange = (page) => {
        getSalesList(page);
    };

    const view = async (data,mode) => {
        processing.value = true
        pesan.value="Sabar Dikit ya, Lagi Proses Load Data"
        action.value = mode

        state.form = {
            id: data.id,
            noinvoice: data.noinvoice,
            customers_name: data.customers_name,
            reference: data.reference,
            notelp: data.customer.notelp,
            instagram_id: data.customer.instagram_id,
            address: data.customer.address,
            city: data.customer.city,
            type: data.customer.type,
            type_penjualan: data.type_penjualan.toString(),
            tanggal_bayar: dayjs(data.tanggal_bayar),
            sales_date: dayjs(data.sales_date),
            tanggal_invoice: dayjs(data.tanggal_invoice),
            cara_bayar: data.cara_bayar,
            ongkir: data.ongkir,
            layanan: data.biaya_layanan,
            apps: data.biaya_apps
        }


        const params = {
            noinvoice: data.noinvoice,
        };

        const response = await apiGetData("/sales/getTransaksi", params);
        state.transaksi = response.data;

        processing.value = false
        modalView.value = true

    };

    const getreference = async () => {
        const params = {};
        const response = await apiGetData("/sales/getReference", params);
        state.reference = response.data;
    };

    const getType = async () => {
        const params = {};
        const response = await apiGetData("/sales/getType", params);
        state.type = response.data;
    };

    const update = async () => {
        loadingSubmit.value = true;
        const payload = {
            id: state.form.id,
            customers_name: state.form.customers_name,
            reference: state.form.reference,
            notelp: state.form.notelp,
            email: state.form.email,
            instagram_id: state.form.instagram_id,
            address: state.form.address,
            city: state.form.city,
            type: state.form.type,
            type_penjualan: state.form.type_penjualan,
            tanggal_bayar: state.form.tanggal_bayar ? dayjs(state.form.tanggal_bayar).format('YYYY-MM-DD') : null,
            sales_date: state.form.sales_date ? dayjs(state.form.sales_date).format('YYYY-MM-DD') : null,
            tanggal_invoice: state.form.tanggal_invoice ? dayjs(state.form.tanggal_invoice).format('YYYY-MM-DD') : null,
            cara_bayar: state.form.cara_bayar,
            ongkir: state.form.ongkir,
            biaya_layanan: state.form.layanan,
            biaya_apps: state.form.apps,
        };

        const response = await apiPostData("/sales/update", payload);

        if (response) {
            await getSalesList();
            modalView.value = false;
            loadingSubmit.value = false; 
        } else {
            loadingSubmit.value = false;
        }
        
    };

    const batal = () => {
        Swal.fire({
            title: 'Apa Anda Yakin ?',
            text: "Mau Batalkan Invoice " + state.form.noinvoice + "!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!'
        }).then(async(result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;

                const payload = {
                    id: state.form.id,
                };

                const response = await apiPostData("/sales/batal", payload);

                if (response) {
                    await getSalesList();
                    modalView.value = false;
                    loadingSubmit.value = false; 
                } else {
                    loadingSubmit.value = false;
                }
            }
        })
    };

    const batalProduct = async (data) => {
        Swal.fire({
            title: 'Apa Anda Yakin ?',
            text: "Mau Batalkan Product " + data.product_varian.product.product_name + "!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, batalkan!'
        }).then(async(result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;

                const payload = {
                    id: data.id,
                };

                const response = await apiPostData("/sales/batalProduct", payload);

                if (response) {
                    const params = {
                        noinvoice: state.form.noinvoice,
                    };

                    const res = await apiGetData("/sales/getTransaksi", params);
                    state.transaksi = res.data;

                    if(state.transaksi.length==0){
                        modalView.value = false;
                        await getSalesList();
                    }

                    loadingSubmit.value = false; 
                } else {
                    loadingSubmit.value = false;
                }
            }
        })
    };

    const editProduct = (data) => {
        state.formProduct = {
            id: data.id,
            qty: Math.abs(data.qty),
            discount: data.discount,
        };
        modalEdit.value = true;
    };

    const updateProduct = async () => {
        loadingSubmit.value = true;
        const payload = {
            id: state.formProduct.id,
            qty: state.formProduct.qty,
            discount: state.formProduct.discount,
        };

        const response = await apiPostData("/sales/updateProduct", payload);

        if (response) {
            const params = {
                noinvoice: state.form.noinvoice,
            };

            const res = await apiGetData("/sales/getTransaksi", params);
            state.transaksi = res.data;

            modalEdit.value = false;
            loadingSubmit.value = false; 
        } else {
            loadingSubmit.value = false;
        }
        
    };

    const Tanggal = ref([]);
    const modalExport = ref(false);
    const openModal = () => {
        modalExport.value = true;
    };

    const gantiProduct = async(data) => {
        state.formProduct = {
            id: data.id,
            kode_toko_barang: data.kode_toko_barang
        };
        const response = await apiGetData("/sales/getProductVarian", {page: state.product_varian.current_page, id: data.kode_toko_barang, search: searchProductVarian.value });
        state.product_varian = response.data;
        modalGanti.value = true;
    };

    const handlePageChangeProduct = async (page) => {
        const response = await apiGetData("/sales/getProductVarian", {page: page, id: state.formProduct.kode_toko_barang, search: searchProductVarian.value });
        state.product_varian = response.data;
    };

    const switchProduct = async(data) => {
        console.log(data);
        Swal.fire({
            title: 'Apa Anda Yakin ?',
            text: "Mau Ganti Product ke " + data.product_varian.product_sku + "!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Ganti!'
        }).then(async(result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;

                const payload = {
                    id_transaksi: state.formProduct.id,
                    id_product_varian: data.product_id,
                };

                const response = await apiPostData("/sales/switchProduct", payload);

                if (response) {
                    const params = {
                        noinvoice: state.form.noinvoice,
                    };

                    const res = await apiGetData("/sales/getTransaksi", params);
                    state.transaksi = res.data;

                    modalGanti.value = false;
                    loadingSubmit.value = false; 
                } else {
                    loadingSubmit.value = false;
                }
            }
        })
        
    };

    const exportExcel = async () => {
        processing.value = true
        pesan.value="Harap Sabar, Lagi Proses Export"

        const response= await apiExportExcel("/sales/exportExcel", {
            tanggal_start: Tanggal.value && Tanggal.value[0] ? dayjs(Tanggal.value[0]).format('YYYY-MM-DD') : '',
            tanggal_end: Tanggal.value && Tanggal.value[1] ? dayjs(Tanggal.value[1]).format('YYYY-MM-DD') : '',
        }, 'Data Invoice Sales')

        if(response){
            processing.value = false
        }else{
            processing.value = false
        }
    };

    onMounted(async() => {
        await Promise.all([
            getSalesList(),
            getreference(),
            getType(),
        ]);
    });

    watch(search, (newQuestion, oldQuestion) => {
        searchdata()
    })

    const searchdata = useDebounceFn(() => {
        getSalesList(1);
    }, 500)

    watch(searchProductVarian, (newQuestion, oldQuestion) => {
        searchdataProductVarian()
    })

    const searchdataProductVarian = useDebounceFn(async() => {
        const response = await apiGetData("/sales/getProductVarian", {page: 1, id: state.formProduct.kode_toko_barang, search: searchProductVarian.value });
        state.product_varian = response.data;
    }, 500)

</script>