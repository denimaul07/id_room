<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Product Sales" title="List Product Sales" />

                <Tabs value="0" class="p-tab-active">
                    <TabList  class="p-tab-active">
                        <Tab value="0">  <i class="fa fa-list" /> Product List</Tab>
                        <Tab value="1"> <i class="fa fa-info-circle"></i> Sales List</Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel value="0">
                            <div class="card">
                                
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            <Button label="Refresh Data" icon="pi pi-refresh" class="p-button-sm btn-dark" @click="refreshData()" :loading="loading" />
                                            <Button :label="'Total Product: ' + state.cart.length" icon="pi pi-shopping-cart" class="p-button-sm p-button-secondary" @click="getProductInCart()" :loading="loading" />
                                            
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
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center bg-dark">No</th>
                                                        <th class="text-center bg-dark">Action</th>
                                                        <th class="text-center bg-dark">Image</th>
                                                        <th class="text-center bg-dark">Product Code</th>
                                                        <th class="text-center bg-dark">Product Name</th>
                                                        <th class="text-center bg-dark">Product Varian</th>
                                                        <th class="text-center bg-dark">Product Size</th>
                                                        <th class="text-center bg-dark">Product SKU</th>
                                                        <th class="text-center bg-dark">Stock</th>
                                                        <th class="text-center bg-dark">Disc</th>
                                                        <th class="text-center bg-dark">Create Name</th>
                                                        <th class="text-center bg-dark">Delivery Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-if="loading"> 
                                                        <td class="text-center" colspan="13"><a-skeleton active /></td>
                                                    </tr>

                                                    <tr v-else-if="state.product.total==0">
                                                        <td class="text-center" colspan="13"><a-empty/></td>
                                                    </tr>
                                                    
                                                    <tr v-for="(data, index) in state.product.data" :key="index" v-else>
                                                        <td class="text-center">{{ index + state.product.from }}</td>
                                                        <td class="text-center">
                                                            <a-tooltip title="Tambah Produk ke Penjualan">
                                                                <a-button type="primary" size="small" class="bg-dark me-2" @click="add(data)">
                                                                    <template #icon>
                                                                        <CheckOutlined />
                                                                    </template>
                                                                </a-button>
                                                            </a-tooltip>
                                                        </td>
                                                        <td class="text-center">
                                                            <a-image  :width="50" :height="50" 
                                                                :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                                fallback="data:image/png;base64,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"
                                                            />
                                                        </td>
                                                        <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                                        <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                                        <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                                        <td class="text-center">{{ data.product_varian.product_size }}</td>
                                                        <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                                        <td class="text-center">{{ data.stock }}</td>
                                                        <td class="text-center">{{ data.disc }} %</td>
                                                        <td class="text-center">{{ data.create_name }}</td>
                                                        <td class="text-center">{{ dayjs(data.created_at).format('DD-MM-YYYY HH:mm:ss') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="row py-2">
                                            <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                                Showing {{ state.product.from }} to {{ state.product.to }} of {{ state.product.total }} entries
                                            </div>
                                            <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                                <a-pagination :current="state.product.current_page" :total="state.product.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </TabPanel>

                        <TabPanel value="1">
                            <Sales />
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>
        </div>

        <a-modal v-model:open="modalCart"  style="top: 20px"  title="Checkout Product" width="1700px">
            <div class="col-12">
                <div class="row">
                    <div class="col-xxl-4 col-md-4 col-sm-12">

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Bill To</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.customer_name" style="width: 100%;" placeholder="Bill TO"/>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Reference</label>
                            <div class="col-sm-8">
                                <a-select v-model:value="state.form.reference" style="width: 100%;" placeholder="Pilih Reference" :options="state.reference.map(item => ({ label: item.reference, value: item.reference }))"  />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Phone Number</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.telp" style="width: 100%;" placeholder="Phone Number"/>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Email</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.email" style="width: 100%;" placeholder="Email"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Instagram ID</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.instagram" style="width: 100%;" placeholder="Instagram ID"/>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Address</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.address" style="width: 100%;" placeholder="Address"/>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">City</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.city" style="width: 100%;" placeholder="City"/>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-md-4 col-sm-12">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Tanggal Bayar</label>
                            <div class="col-sm-8">
                                <a-date-picker v-model:value="state.form.tanggal_bayar" style="width: 100%;"  />
                            </div>
                        </div>

    
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Sales Name</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.sales_name" style="width: 100%;" placeholder="Sales Name"/>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Cara Bayar</label>
                            <div class="col-sm-8">
                                <a-input v-model:value="state.form.caraBayar" style="width: 100%;" placeholder="Cara Bayar" />
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label">Type Penjualan</label>
                            <div class="col-sm-8">
                                <a-select v-model:value="state.form.type_penjualan"  style="width: 100%;" placeholder="Pilih Type Penjualan">
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
                                        <th class="text-center bg-dark">Action</th>
                                        <th class="text-center bg-dark">Set Disc</th>
                                        <th class="text-center bg-dark">Image</th>
                                        <th class="text-center bg-dark">Product Code</th>
                                        <th class="text-center bg-dark">Product Name</th>
                                        <th class="text-center bg-dark">Product Varian</th>
                                        <th class="text-center bg-dark">Product Size</th>
                                        <th class="text-center bg-dark">Product SKU</th>
                                        <th class="text-center bg-dark">Quantity</th>
                                        <th class="text-center bg-dark">Harga Jual</th>
                                        <th class="text-center bg-dark">Disc</th>
                                        <th class="text-center bg-dark">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(data, index) in state.cart" :key="index">
                                        <td class="text-center">{{ index + 1 }}</td>
                                        <td class="text-center">
                                            <a-tooltip title="Batal Product">
                                                <a-button type="primary" size="small" class="bg-dark me-2" @click="batalProduct(data)">
                                                    <template #icon>
                                                        <CloseCircleOutlined />
                                                    </template>
                                                </a-button>
                                            </a-tooltip>
                                        </td>
                                        <td class="text-center" width="200px">
                                            <a-select v-model:value="data.type" allowClear style="width: 100%;" @change="setDisc(data)" placeholder="Disc Product">
                                                <a-select-option value="0">Umum / Non Member </a-select-option>
                                                <a-select-option v-for="item in state.type" :key="item.id" :value="item.id">
                                                    {{ item.type }} - {{ item.discount }} %
                                                </a-select-option>
                                            </a-select>
                                        </td>
                                        <td class="text-center">
                                            <a-image  :width="50" :height="50" 
                                                    :src="`${pathUrl}storage/barang/${data.product_varian.image}`" 
                                                    fallback="data:image/png;base64,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"
                                                />
                                        </td>
                                        <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                                        <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                                        <td class="text-center">{{ data.product_varian.product_varian }}</td>
                                        <td class="text-center">{{ data.product_varian.product_size }}</td>
                                        <td class="text-center">{{ data.product_varian.product_sku }}</td>
                                        <td class="text-center" width="50px">
                                            <a-input-number v-model:value="data.qty" style="width: 100%;" min="1"  @change="updateQty(data)"/>
                                        </td>
                                        <td class="text-center">{{ (data.product_varian.harga * 1).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}</td>
                                        <td class="text-center">{{ data.disc }} %</td>
                                        <td class="text-left">
                                            {{
                                                ((data.product_varian.harga - (data.product_varian.harga * ((data.disc || 0) / 100))) * (data.qty || 1))
                                                    .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
                                                    .slice(0, -3)
                                            }}
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-end bg-dark" colspan="12">Total</td>
                                        <td class="text-left bg-dark" width="150px">
                                            {{
                                                state.cart.reduce((total, item) => {
                                                    const itemTotal = (item.product_varian.harga - (item.product_varian.harga * ((item.disc || 0) / 100))) * (item.qty || 1);
                                                    return total + itemTotal;
                                                }, 0)
                                                .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
                                                .slice(0, -3)
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-end bg-dark" colspan="12">Ongkir</td>
                                        <td class="text-left bg-dark">
                                            <a-input-number v-model:value="state.form.ongkir" style="width: 100%;" :min="0" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="value => value.replace(/Rp\s?|(,*)/g, '')" />
                                        </td>
                                    </tr>    

                                    <tr>
                                        <td class="text-end bg-dark" colspan="12">Biaya Layanan</td>
                                        <td class="text-left bg-dark">
                                            <a-input-number v-model:value="state.form.layanan" style="width: 100%;" :min="0" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="value => value.replace(/Rp\s?|(,*)/g, '')" />
                                        </td>
                                    </tr>   

                                    <tr>
                                        <td class="text-end bg-dark" colspan="12">Biaya Aplikasi</td>
                                        <td class="text-left bg-dark">
                                            <a-input-number v-model:value="state.form.apps" style="width: 100%;" :min="0" :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')" :parser="value => value.replace(/Rp\s?|(,*)/g, '')" />
                                        </td>
                                    </tr>   

                                    <tr>
                                        <td class="text-end bg-dark" colspan="12">Grand Total</td>
                                        <td class="text-left bg-dark">
                                            {{
                                                (state.cart.reduce((total, item) => {
                                                    const itemTotal = (item.product_varian.harga - (item.product_varian.harga * ((item.disc || 0) / 100))) * (item.qty || 1);
                                                    return total + itemTotal;
                                                }, 0) - state.form.layanan - state.form.apps + state.form.ongkir)
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
                <div class="text-end">
                    <a-button type="primary" class="bg-dark" @click="submitCheckout" :loading="loadingSubmit">
                        <template #icon>
                            <CheckCircleOutlined />
                        </template>
                        Proses
                    </a-button>
                </div>
            </template>
            <br></br>
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
    import Sales from '@/pages/sales/sales.vue';
    import {
        EditOutlined,
        CheckOutlined,
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
    const pagging = ref(10);
    const search = ref('');
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
    const modalCart = ref(false);
    const state = reactive({
        product: {},
        cart: [],
        reference: [],
        type:{},
        form: {
            customer_name: '',
            reference: [],
            telp : '',
            email : '',
            instagram : '',
            address : '',
            city : '',
            province : '',
            sales_name : '',
            tanggal_bayar : dayjs(),
            type_penjualan :[0],
            caraBayar : 'cash',
            ongkir : 0,
            layanan : 0,
            apps : 0
        },
    });

    const refreshData = () => {
        search.value = '';
        getProduct(1);
        state.cart = [];
    };

    const getProduct = async (page = state.product.current_page || 1) => {
        loading.value = true;
        const params = {page: page, per_page: pagging.value, search: search.value };
        const response = await apiGetData("/sales/productStore", params);
        state.product = response.data;
        loading.value = false;
    };

    const handlePageChange= (page) => {
        getProduct(page);
    };

    const add = (item) => {
        if (state.cart.find(cartItem => cartItem.id === item.id)) {
            Swal.fire({
                icon: 'warning',
                title: 'Warning',
                text: 'Product ' + item.product_varian.product_sku + ' sudah ada di keranjang',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',

            });
            return;
        }

        if (item.stock <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Upps',
                text: 'Product ' + item.product_varian.product_sku + ' stok habis',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            return;
        }

        const cartItem = { ...item, qty: item.qty ?? 1, type: [] };
        state.cart.push(cartItem);
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: 'Product ' + item.product_varian.product_sku + ' added to cart',
            confirmButtonColor: '#2c323f',
            confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
        });
    };

    const getProductInCart = () => {
        if (state.cart.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Upps',
                text: 'Keranjang masih kosong, silahkan pilih product terlebih dahulu',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            return false;
        }

        state.form = {
            customer_name: '',
            reference: [],
            telp : '',
            email : '',
            instagram : '',
            address : '',
            city : '',
            province : '',
            sales_name : '',
            type_penjualan :[],
            tanggal_bayar : dayjs(),
            caraBayar : '',
            ongkir : 0,
            layanan : 0,
            apps : 0
        };
        modalCart.value = true;
    };

    const getreference = async () => {
        const params = {};
        const response = await apiGetData("/sales/getReference", params);
        state.reference = response.data;
    };

    const getType = async () => {
        const params = {};
        const response = await apiGetData("/sales/getTypeStore", params);
        state.type = response.data;
    };

    const setDisc = (data) => {
        const selectedType = state.type.find(item => item.id === data.type);
        if (selectedType) {
            data.disc = selectedType.discount;
        } else {
            data.disc = 0;
        }
    };

    const updateQty = (data) => {
        // normalize qty
        const qty = Number(data.qty);
        data.qty = Number.isFinite(qty) && qty > 0 ? qty : 1;

        // clamp to stock
        if (data.stock != null && data.qty > data.stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops',
                text: 'Quantity Tidak Boleh Melebihi Stok yang ada',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OK',
            });
            data.qty = data.stock;
        }

        setDisc(data);
    };

    const batalProduct = (data) => {
        state.cart = state.cart.reduce((acc, item) => {
            if (item.id !== data.id) {
                acc.push(item);
            }
            return acc;
        }, []);

        if (state.cart.length === 0) {
            modalCart.value = false;
            state.form = {
                customer_name: '',
                reference: [],
                telp : '',
                email : '',
                instagram : '',
                address : '',
                city : '',
                province : '',
                sales_name : '',
                tanggal_bayar : dayjs(),
                caraBayar : '',
                ongkir : 0,
                layanan : 0,
                apps : 0
            };
        }
    };

    const submitCheckout = async () => {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            text: "Anda akan memproses checkout ini. Cek kembali data sebelum melanjutkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2c323f',
            cancelButtonColor: '#d33',
            confirmButtonText: '<i class="fa fa-check me-2"></i> Yes, proceed!',
            cancelButtonText: '<i class="fa fa-times me-2"></i> Cancel'
        }).then(async (result) => {
            if (result.isConfirmed) {
                loadingSubmit.value = true;
                const payload = {
                    customers_name: state.form.customer_name,
                    reference: state.form.reference,
                    telp : state.form.telp,
                    email : state.form.email,
                    instagram : state.form.instagram,
                    address : state.form.address,
                    city : state.form.city,
                    sales_name : state.form.sales_name,
                    tanggal_bayar : dayjs(state.form.tanggal_bayar).format('YYYY-MM-DD'),
                    caraBayar : state.form.caraBayar,
                    type_penjualan : state.form.type_penjualan,
                    ongkir : state.form.ongkir,
                    layanan : state.form.layanan,
                    apps : state.form.apps,
                    cart: state.cart,
                    grand_total:
                        state.cart.reduce((total, item) => {
                            const itemTotal = (item.product_varian.harga - (item.product_varian.harga * ((item.disc || 0) / 100))) * (item.qty || 1);
                            return total + itemTotal;
                        }, 0) - state.form.layanan - state.form.apps + state.form.ongkir
                };
            
                const response = await apiPostData("/sales/checkoutStore", payload);
                if(response) {
                    loadingSubmit.value = false;
                    modalCart.value = false;
                    refreshData();
                } else {
                    loadingSubmit.value = false;
                }
            
            }
        });
    };

    onMounted(async() => {
        Promise.all([
            await getProduct(),
            await getreference(),
            await getType()
        ]);
    });

    watch(search, async() => {
        useDebounceFn(() => {
            getProduct(1);
        }, 500)();
    });
</script>