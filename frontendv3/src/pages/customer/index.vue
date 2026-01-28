<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Customer" title="List Customer" />

                <div class="card">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">

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
                                            <th class="text-center bg-dark">Sales Order</th>
                                            <th class="text-center bg-dark">No Invoice</th>
                                            <th class="text-center bg-dark text-nowrap">Customers Name</th>
                                            <th class="text-center bg-dark">Reference</th>
                                            <th class="text-center bg-dark">Telp</th>
                                            <th class="text-center bg-dark">Email</th>
                                            <th class="text-center bg-dark text-nowrap">Instagram ID</th>
                                            <th class="text-center bg-dark">Address</th>
                                            <th class="text-center bg-dark">City</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                
                                        <tr v-if="loading"> 
                                            <td class="text-center" colspan="13"><a-skeleton active /></td>
                                        </tr>

                                        <tr v-else-if="state.customer.total==0">
                                            <td class="text-center" colspan="13"><a-empty/></td>
                                        </tr>
                                    
                                        <tr v-for="(data, index) in state.customer.data" :key="index" v-else>
                                            <td class="text-center">{{ index + state.customer.from }}</td>
                                            <td class="text-center text-nowrap">{{ data.order_date }}</td>
                                            <td class="text-center">
                                                <button class="btn btn-link btn-sm" @click="view(data)">{{ data.noinvoice }}</button>
                                            </td>
                                            <td class="text-center">{{ data.customers_name }}</td>
                                            <td class="text-center">{{ data.reference }}</td>
                                            <td class="text-center">{{ data.telp }}</td>
                                            <td class="text-center">{{ data.email }}</td>
                                            <td class="text-center">{{ data.instagram_id }}</td>
                                            <td class="text-center">{{ data.address }}</td>
                                            <td class="text-center">{{ data.city }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row py-2">
                                <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                    Showing {{ state.customer.from }} to {{ state.customer.to }} of {{ state.customer.total }} entries
                                </div>
                                <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                    <a-pagination :current="state.customer.current_page" :total="state.customer.total" v-model:pageSize="pagging" @change="handlePageChange" />
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

        <a-modal v-model:open="modalView" title="Detail Transaksi Customer" :footer="null" width="1600px" >
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
                            <td class="text-center">{{ data.type }}</td>
                            <td class="text-center">{{ data.notransaksi }}</td>
                            <td class="text-center">{{ data.product_varian.product.product_code }}</td>
                            <td class="text-center">{{ data.product_varian.product.product_name }}</td>
                            <td class="text-center">{{ data.product_varian.product_varian }}</td>
                            <td class="text-center">{{ data.product_varian.product_size }}</td>
                            <td class="text-center">{{ data.product_varian.product_sku }}</td>
                            <td class="text-center">{{ Math.abs(data.qty) }}</td>
                            <td class="text-center">{{ data.cogs.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td>
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
                                {{ 
                                    state.form.ongkir.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) 
                                }}
                            </td>
                        </tr>    

                        <tr>
                            <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Biaya Layanan</td>
                            <td class="text-left bg-dark">
                               {{ 
                                    state.form.layanan.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) 
                                }}
                            </td>
                        </tr>   

                        <tr>
                            <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Biaya Aplikasi</td>
                            <td class="text-left bg-dark">
                                {{ 
                                    state.form.apps.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) 
                                }}
                            </td>
                        </tr>   

                        <tr>
                            <td class="text-end bg-dark" :colspan="action==='edit' ? 13 : 12">Grand Total</td>
                            <td class="text-left bg-dark">
                                {{
                                    (state.transaksi.reduce((total, item) => {
                                        const itemTotal = item.harga_jual - (item.harga_jual * ((item.discount || 0) / 100));
                                        return total + itemTotal;
                                    }, 0) + state.form.ongkir + state.form.layanan + state.form.apps)
                                    .toLocaleString('id-ID', { style: 'currency', currency: 'IDR' })
                                    .slice(0, -3)
                                }}
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>
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
    const modalView = ref(false);

    const state = reactive({
        customer: {},
        transaksi: {},
        form: {
            ongkir: 0,
            layanan: 0,
            apps: 0,
        },
    });

    const getCustomerList = async (page = state.customer.current_page) => {
        loading.value = true;
        const payload = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData("/customer/index", payload);
        state.customer = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getCustomerList(page);
    };

    const view = async (data) => {
        processing.value = true;
        pesan.value = 'Mohon Tunggu...Sedang memuat data';
    
        state.form = {
            ongkir: data.sales.ongkir || 0,
            layanan: data.sales.layanan || 0,
            apps: data.sales.apps || 0,
        };
        const params = {
            noinvoice: data.noinvoice,
        };

        const response = await apiGetData("/sales/getTransaksi", params);
        state.transaksi = response.data;
        modalView.value = true;
        processing.value = false;
    };


    onMounted(() => {
        getCustomerList();
    });

    watch(search, () => {
        useDebounceFn(() => {
            getCustomerList(1);
        }, 500)();
    });

</script>