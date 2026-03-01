<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Referral & Coupons" title="List Referral & Coupons" />

                <div class="card ms-2">
                    <div class="card-body">
                        <Tabs value="0" class="p-tab-active">
                            <TabList class="p-tab-active" style="color: black;">
                                <Tab value="0">
                                    <i class="fa fa-users me-2" style="color: #222 !important;" />
                                    <span style="color: #222 !important;">Referral</span>
                                </Tab>
                                <Tab value="1">
                                    <i class="fa fa-ticket-alt me-2" style="color: #222 !important;" />
                                    <span style="color: #222 !important;">Coupons All</span>
                                </Tab>
                                <Tab value="2">
                                    <i class="fa fa-user me-2" style="color: #222 !important;" />
                                    <span style="color: #222 !important;">Coupons Members</span>
                                </Tab>
                            </TabList>
                            <TabPanels>
                                <TabPanel value="0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Action</th>
                                                    <th class="text-center">Reward Referrer</th>
                                                    <th class="text-center">Reward Refereed</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading">
                                                    <td colspan="5" class="text-center"><a-skeleton active /></td>
                                                </tr>
                                                <tr v-else-if="state.listData.length === 0">
                                                    <td colspan="5" class="text-center">No Referral Settings Added</td>
                                                </tr>
                                                <tr v-for="(item, index) in state.listData" :key="index" v-else>
                                                    <td class="text-center">{{ index + 1 }}</td>
                                                    <td class="text-center">
                                                        <a-tooltip title="View / Edit Referral Setting">
                                                            <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                                                                <template #icon>
                                                                    <EyeOutlined />
                                                                </template>
                                                            </a-button>
                                                        </a-tooltip>
                                                    </td>
                                                    <td class="text-center"> {{ item.reward_referrer }} Points  </td>
                                                    <td class="text-center"> {{ item.reward_referred }} Points  </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </TabPanel>
                                <TabPanel value="1">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            <Button label="Tambah" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
                                        </div>

                                        <div class="ms-auto">
                                            <a-input-search
                                                v-model:value="search"
                                                placeholder="Cari Data"
                                                style="width: 300px"
                                            />
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Action</th>
                                                    <th class="text-center text-nowrap">Code</th>
                                                    <th class="text-center text-nowrap">Type</th>
                                                    <th class="text-center text-nowrap">Jenis</th>
                                                    <th class="text-center text-nowrap">Type Coupon</th>
                                                    <th class="text-center text-nowrap">Value</th>
                                                    <th class="text-center text-nowrap">Value Cashback</th>
                                                    <th class="text-center text-nowrap">Minimum Transaction</th>
                                                    <th class="text-center text-nowrap">Maximum Discount</th>
                                                    <th class="text-center text-nowrap">Usage Limit</th>
                                                    <th class="text-center text-nowrap">Usege Per Users</th>
                                                    <th class="text-center text-nowrap">Usage Count</th>
                                                    <th class="text-center text-nowrap">Start Date</th>
                                                    <th class="text-center text-nowrap">End Date</th>
                                                    <th class="text-center text-nowrap">Show Coupon</th>
                                                    <th class="text-center text-nowrap">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading">
                                                    <td colspan="13" class="text-center"><a-skeleton active /></td>
                                                </tr>
                                                <tr v-else-if="state.listDataCoupon.total === 0">
                                                    <td colspan="13" class="text-center">No Referral Settings Added</td>
                                                </tr>
                                                <tr v-for="(item, index) in state.listDataCoupon.data" :key="index" v-else>
                                                    <td class="text-center">{{ index + state.listDataCoupon.from }}</td>
                                                    <td class="text-center">
                                                        <a-tooltip title="View / Edit Referral Setting">
                                                            <a-button type="primary" size="small" @click="viewCoupon(item)" class="bg-dark">
                                                                <template #icon>
                                                                    <EyeOutlined />
                                                                </template>
                                                            </a-button>
                                                        </a-tooltip>
                                                    </td>
                                                    <td class="text-center text-nowrap"> {{ item.code }}  </td>
                                                    <td class="text-center text-nowrap"> {{ item.type }}  </td>
                                                    <td class="text-center text-nowrap">
                                                        {{ item.jenis === 'all' ? 'Coupon & Cashback' : (item.jenis === 'coupon' ? 'Coupon' : 'Cashback') }}
                                                    </td>
                                                    <td class="text-center text-nowrap"> {{ item.type_coupon === 'all' ? 'All' : (item.type_coupon === 'member' ? 'Member' : 'Unknown') }}  </td>
                                                    <td class="text-center text-nowrap"> 
                                                        {{ item.type === 'percentage' ? item.value + '%' : parseInt(item.value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}
                                                    </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.value_cashback || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }} </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.minimum_transaction).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}  </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.maximum_discount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}  </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.usage_limit).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}  </td>
                                                    <td class="text-center text-nowrap"> {{ item.usage_per_user }}  </td>
                                                    <td class="text-center text-nowrap"> {{ item.used_count }}  </td>
                                                    <td class="text-center text-nowrap"> {{ dayjs(item.start_date).format('DD MMM YYYY') }}  </td>
                                                    <td class="text-center text-nowrap"> {{ dayjs(item.end_date).format('DD MMM YYYY') }}  </td>
                                                    <td class="text-center text-nowrap">
                                                        <a-tag :color="item.is_show == '0' ? 'green' : 'red'">
                                                            {{ item.is_show == '0' ? 'Show' : 'Hide' }}
                                                        </a-tag>
                                                    </td>
                                                    <td class="text-center text-nowrap">
                                                        <a-tag :color="item.is_active == '0' ? 'green' : 'red'">
                                                            {{ item.is_active == '0' ? 'Active' : 'Inactive' }}
                                                        </a-tag>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                            Showing {{ state.listDataCoupon.from }} to {{ state.listDataCoupon.to }} of {{ state.listDataCoupon.total }} entries
                                        </div>
                                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                            <a-pagination :current="state.listDataCoupon.current_page" :total="state.listDataCoupon.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                        </div>
                                    </div>
                                </TabPanel>

                                <TabPanel value="2">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="d-flex gap-2">
                                            <Button label="Tambah" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
                                        </div>

                                        <div class="ms-auto">
                                            <a-input-search
                                                v-model:value="searchUser"
                                                placeholder="Cari Data"
                                                style="width: 300px"
                                            />
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No</th>
                                                    <th class="text-center">Action</th>
                                                    <th class="text-center text-nowrap">Code</th>
                                                    <th class="text-center text-nowrap">Type</th>
                                                    <th class="text-center text-nowrap">Jenis</th>
                                                    <th class="text-center text-nowrap">Type Coupon</th>
                                                    <th class="text-center text-nowrap">Value</th>
                                                    <th class="text-center text-nowrap">Value Cashback</th>
                                                    <th class="text-center text-nowrap">Minimum Transaction</th>
                                                    <th class="text-center text-nowrap">Maximum Discount</th>
                                                    <th class="text-center text-nowrap">Usage Limit</th>
                                                    <th class="text-center text-nowrap">Usege Per Users</th>
                                                    <th class="text-center text-nowrap">Usage Count</th>
                                                    <th class="text-center text-nowrap">Start Date</th>
                                                    <th class="text-center text-nowrap">End Date</th>
                                                    <th class="text-center text-nowrap">Show Coupon</th>
                                                    <th class="text-center text-nowrap">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-if="loading">
                                                    <td colspan="13" class="text-center"><a-skeleton active /></td>
                                                </tr>
                                                <tr v-else-if="state.listDataCouponUser.total === 0">
                                                    <td colspan="13" class="text-center">No Referral Settings Added</td>
                                                </tr>
                                                <tr v-for="(item, index) in state.listDataCouponUser.data" :key="index" v-else>
                                                    <td class="text-center">{{ index + state.listDataCouponUser.from }}</td>
                                                    <td class="text-center">
                                                        <a-tooltip title="View / Edit Referral Setting">
                                                            <a-button type="primary" size="small" @click="viewCoupon(item)" class="bg-dark">
                                                                <template #icon>
                                                                    <EyeOutlined />
                                                                </template>
                                                            </a-button>
                                                        </a-tooltip>
                                                    </td>
                                                    <td class="text-center text-nowrap"> {{ item.code }}  </td>
                                                    <td class="text-center text-nowrap"> {{ item.type }}  </td>
                                                    <td class="text-center text-nowrap">
                                                        {{ item.jenis === 'all' ? 'Coupon & Cashback' : (item.jenis === 'coupon' ? 'Coupon' : 'Cashback') }}
                                                    </td>
                                                    <td class="text-center text-nowrap"> {{ item.type_coupon === 'all' ? 'All' : (item.type_coupon === 'member' ? 'Member' : 'Unknown') }}  </td>   
                                                    <td class="text-center text-nowrap"> 
                                                        {{ item.type === 'percentage' ? item.value + '%' : parseInt(item.value).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}
                                                    </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.value_cashback || 0).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }} </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.minimum_transaction).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}  </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.maximum_discount).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }}  </td>
                                                    <td class="text-center text-nowrap"> {{ parseInt(item.usage_limit).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}  </td>
                                                    <td class="text-center text-nowrap"> {{ item.usage_per_user }}  </td>
                                                    <td class="text-center text-nowrap"> {{ item.used_count }}  </td>
                                                    <td class="text-center text-nowrap"> {{ dayjs(item.start_date).format('DD MMM YYYY') }}  </td>
                                                    <td class="text-center text-nowrap"> {{ dayjs(item.end_date).format('DD MMM YYYY') }}  </td>
                                                    <td class="text-center text-nowrap">
                                                        <a-tag :color="item.is_show == '0' ? 'green' : 'red'">
                                                            {{ item.is_show == '0' ? 'Show' : 'Hide' }}
                                                        </a-tag>
                                                    </td>
                                                    <td class="text-center text-nowrap">
                                                        <a-tag :color="item.is_active == '0' ? 'green' : 'red'">
                                                            {{ item.is_active == '0' ? 'Active' : 'Inactive' }}
                                                        </a-tag>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="row py-2">
                                        <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                                            Showing {{ state.listDataCouponUser.from }} to {{ state.listDataCouponUser.to }} of {{ state.listDataCouponUser.total }} entries
                                        </div>
                                        <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                                            <a-pagination :current="state.listDataCouponUser.current_page" :total="state.listDataCouponUser.total" v-model:pageSize="pagging" @change="handlePageChange" />
                                        </div>
                                    </div>
                                </TabPanel>
                            </TabPanels>
                        </Tabs>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <a-drawer v-model:visible="modalAdd" :width="400" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add Referral Setting' : 'Edit Referral Setting'">
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Reward Referrer</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" v-model="state.form.reward_referrer" placeholder="Enter Reward for Referrer"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Reward Referred</label>
            <div class="col-sm-9">
                <input type="number" class="form-control" v-model="state.form.reward_referred" placeholder="Enter Reward for Referred"/>
            </div>
        </div>

        <template #footer>
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="update" v-if="action === 'edit'">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Update
            </button>
        </template>
        <br>
        <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
    </a-drawer>

    <a-drawer
        :title="actionCoupon === 'add' ? 'Add Coupon' : 'Edit Coupon'"
        :closable="false"
        :mask-closable="true"
        v-model:visible="modalAddCoupon"
        placement="right"
        width="600px"
    >
        <!-- Form fields for coupon settings go here -->
        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Code</label>
            <div class="col-sm-9">
                <a-input type="text" style="width: 100%" v-model:value="state.formCoupon.code" placeholder="Enter Coupon Code"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Nama Coupon</label>
            <div class="col-sm-9">
                <a-input type="text" style="width: 100%" v-model:value="state.formCoupon.name" placeholder="Enter Coupon Name"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Jenis</label>
            <div class="col-sm-9">
                <a-select v-model:value="state.formCoupon.jenis" style="width: 100%" placeholder="Select Jenis">
                    <a-select-option :value="'coupon'">Coupon</a-select-option>
                    <a-select-option :value="'cashback'">Cashback</a-select-option>
                    <a-select-option :value="'all'">All</a-select-option>
                </a-select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Type Coupon</label>
            <div class="col-sm-9">
                <a-select v-model:value="state.formCoupon.type_coupon" style="width: 100%" placeholder="Select Type Coupon">
                    <a-select-option :value="'all'">All</a-select-option>
                    <a-select-option :value="'member'">Member</a-select-option>
                </a-select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Type</label>
            <div class="col-sm-9">
                <a-select v-model:value="state.formCoupon.type" style="width: 100%" placeholder="Select Type">
                    <a-select-option :value="'percentage'">Percentage</a-select-option>
                    <a-select-option :value="'fixed'">Fixed Amount</a-select-option>
                </a-select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Value</label>
            <div class="col-sm-9">
                <a-input type="number" style="width: 100%" v-model:value="state.formCoupon.value" placeholder="Enter Coupon Value"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Value Cashback</label>
            <div class="col-sm-9">
                <a-input-number style="width: 100%" v-model:value="state.formCoupon.value_cashback" placeholder="Enter Value Cashback"
                    :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                    :parser="value => value.replace(/\Rp\s?|(,*)/g, '')"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Minimum Transaction</label>
            <div class="col-sm-9">
                <a-input-number style="width: 100%" v-model:value="state.formCoupon.min_transaction" placeholder="Enter Minimum Transaction"    
                :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                :parser="value => value.replace(/\Rp\s?|(,*)/g, '')"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Maximum Discount</label>
            <div class="col-sm-9">
                <a-input-number style="width: 100%" v-model:value="state.formCoupon.max_discount" placeholder="Enter Maximum Discount"    
                :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                        :parser="value => value.replace(/\Rp\s?|(,*)/g, '')"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Usage Limit</label>
            <div class="col-sm-9">
                <a-input-number style="width: 100%" v-model:value="state.formCoupon.usage_limit" 
                    :formatter="value => `Rp ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ',')"
                    :parser="value => value.replace(/\Rp\s?|(,*)/g, '')"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Usage Per User</label>
            <div class="col-sm-9">
                <a-input-number style="width: 100%" v-model:value="state.formCoupon.usage_limit_per_user" placeholder="Enter Usage Per User"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Used Count</label>
            <div class="col-sm-9">
                <a-input-number style="width: 100%" v-model:value="state.formCoupon.used_count" placeholder="Enter Used Count"/>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Start Date</label>
            <div class="col-sm-9">
                <a-date-picker v-model:value="state.formCoupon.start_date" style="width: 100%" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">End Date</label>
            <div class="col-sm-9">
                <a-date-picker v-model:value="state.formCoupon.end_date" style="width: 100%" />
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Status</label>
            <div class="col-sm-9">
                <a-select v-model:value="state.formCoupon.status" style="width: 100%" placeholder="Select Status">
                    <a-select-option :value="'0'">Active</a-select-option>
                    <a-select-option :value="'1'">Inactive</a-select-option>
                </a-select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-3 col-form-label">Show Coupon</label>
            <div class="col-sm-9">
                <a-select v-model:value="state.formCoupon.is_show" style="width: 100%" placeholder="Select Show Coupon">
                    <a-select-option :value="'0'">Show</a-select-option>
                    <a-select-option :value="'1'">Hide</a-select-option>
                </a-select>
            </div>
        </div>

        <template #footer>
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="saveCoupon" v-if="actionCoupon === 'add'">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Save
            </button>
            <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="updateCoupon" v-else>
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                Update
            </button>
        </template>

        <br>
        <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
    </a-drawer>

    <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
        <div style="align-items:center;justify-content: center;display: flex;" width="100px">
            <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
        </div>

        <div style="align-items:center;justify-content: center;display: flex;">
            {{ pesan }}
        </div>
    </a-modal>
</template>

<script setup>
    import { apiGetData, apiPostData,apiPutData,apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref, watch } from 'vue'; 
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EyeOutlined, DeleteOutlined } from '@ant-design/icons-vue';
    import Tabs from 'primevue/tabs';
    import TabList from 'primevue/tablist';
    import Tab from 'primevue/tab';
    import TabPanels from 'primevue/tabpanels';
    import TabPanel from 'primevue/tabpanel';
    import { useDebounceFn } from '@vueuse/core'

    const action = ref(null);
    const actionCoupon = ref(null);
    const modalAdd = ref(false);
    const search = ref('');
    const searchUser = ref('');
    const pagging = ref(10);
    const modalAddCoupon = ref(false);

    const state = reactive({
        listData:{},
        listDataCoupon:{},
        listDataCouponUser:{},
        form:{
            odata: '',
            reward_referrer: '',
            reward_referred: '',
        },
        formCoupon: {
            odata: '',
            code: '',
            name: '',
            jenis: 'all',
            type_coupon: 'all',
            type: '',
            value: '',
            value_cashback: '',
            min_transaction: '',
            max_discount: '',
            usage_limit: '',
            usage_limit_per_user: '',
            used_count: '',
            start_date: null,
            end_date: null,
            status: '0',
            is_show: '0',
        }
    });

    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/referral', {});
        state.listData = response.data
        loading.value = false;
    };


    const view = (item) => {
        state.form = {
            odata: item.odata,
            reward_referrer: item.reward_referrer,
            reward_referred: item.reward_referred,
        };

        action.value = 'edit';
        modalAdd.value = true;
    };


    const update = async () => {
        loadingSubmit.value = true;

        const payload = {
            odata: state.form.odata,
            reward_referrer: state.form.reward_referrer,
            reward_referred: state.form.reward_referred,
        };

        const response = await apiPostData('/setting/referral', payload);

        if (response) {
            loadingSubmit.value = false;
            modalAdd.value = false;
            getData();
        }else{
            loadingSubmit.value = false;
        }
    };

    const getDataCoupon = async (page = state.listDataCoupon.current_page) => {
        loading.value = true;
        const payload = {
            page: page,
            pagging: 10,
            serach : search.value,
            type : 'all',
        }
        const response = await apiGetData('/setting/coupons',  payload );
        state.listDataCoupon = response.data
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getDataCoupon(page);
    };

    const add = () => {
        state.formCoupon = {
            odata: '',
            code: '',
            name: '',
            jenis: 'all',
            type: [],
            type_coupon: 'all',
            value: '',
            value_cashback: '',
            min_transaction: '',
            max_discount: '',
            usage_limit: '',
            usage_per_user: '',
            start_date: dayjs(),
            end_date: dayjs(),
            status: '0',
            is_show: '0',   
        };
        actionCoupon.value = 'add';
        modalAddCoupon.value = true;
    };

    const viewCoupon = (item) => {
        state.formCoupon = {
            odata: item.odata,
            code: item.code,
            name: item.title,
            jenis: item.jenis || 'all',
            type: item.type,
            type_coupon: item.type_coupon || 'all',
            value: item.value,
            value_cashback: item.value_cashback,
            min_transaction: item.minimum_transaction,
            max_discount: item.maximum_discount,
            usage_limit: item.usage_limit,
            usage_limit_per_user: item.usage_per_user,
            used_count: item.used_count,
            start_date: dayjs(item.start_date),
            end_date: dayjs(item.end_date),
            status: item.is_active.toString(),
            is_show: item.is_show.toString(),
        };

        actionCoupon.value = 'edit';
        modalAddCoupon.value = true;
    };

    const saveCoupon = async () => {
        loadingSubmit.value = true;

        const payload = {
            code: state.formCoupon.code,
            jenis: state.formCoupon.jenis,
            type: state.formCoupon.type,
            type_coupon: state.formCoupon.type_coupon,
            name: state.formCoupon.name,
            value: state.formCoupon.value,
            value_cashback: state.formCoupon.value_cashback,
            min_transaction: state.formCoupon.min_transaction,
            max_discount: state.formCoupon.max_discount,
            usage_limit: state.formCoupon.usage_limit,
            usage_per_user: state.formCoupon.usage_limit_per_user,
            usage_count: state.formCoupon.used_count,
            start_date: dayjs(state.formCoupon.start_date).format('YYYY-MM-DD'),
            end_date: dayjs(state.formCoupon.end_date).format('YYYY-MM-DD'),
            status: state.formCoupon.status,
            is_show: state.formCoupon.is_show,
        };

        const response = await apiPostData('/setting/coupons', payload);

        if (response) {
            loadingSubmit.value = false;
            modalAddCoupon.value = false;
            getDataCoupon();
        }else{
            loadingSubmit.value = false;
        }
    };

    const updateCoupon = async () => {
        loadingSubmit.value = true;

        const payload = {
            odata: state.formCoupon.odata,
            code: state.formCoupon.code,
            jenis: state.formCoupon.jenis,
            type: state.formCoupon.type,
            type_coupon: state.formCoupon.type_coupon,
            name: state.formCoupon.name,
            value: state.formCoupon.value,
            value_cashback: state.formCoupon.value_cashback,
            min_transaction: state.formCoupon.min_transaction,
            max_discount: state.formCoupon.max_discount,
            usage_limit: state.formCoupon.usage_limit,
            usage_per_user: state.formCoupon.usage_limit_per_user,
            usage_count: state.formCoupon.used_count,
            start_date: dayjs(state.formCoupon.start_date).format('YYYY-MM-DD'),
            end_date: dayjs(state.formCoupon.end_date).format('YYYY-MM-DD'),
            status: state.formCoupon.status,
            is_show: state.formCoupon.is_show,
        };

        const response = await apiPutData('/setting/coupons', payload);

        if (response) {
            loadingSubmit.value = false;
            modalAddCoupon.value = false;
            getDataCoupon();
        }else{
            loadingSubmit.value = false;
        }
    };

    //coupon user
    const getDataCouponUser = async (page = state.listDataCouponUser.current_page) => {
        loading.value = true;
        const payload = {
            page: page,
            pagging: 10,
            serach : searchUser.value,
            type : 'member',
        }
        const response = await apiGetData('/setting/coupons',  payload );
        state.listDataCouponUser = response.data
        loading.value = false;
    };

    onMounted(async () => {
        await getData();
        await getDataCoupon();
        await getDataCouponUser();
    });

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));

    watch(searchUser, useDebounceFn(async () => {
        await getDataCouponUser();
    }, 500));

</script>