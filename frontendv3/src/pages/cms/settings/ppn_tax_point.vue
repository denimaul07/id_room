<template>
    <div>
        <h4>PPN, Fee dan Point Settings</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Action</th>
                        <th class="text-center">PPN</th>
                        <th class="text-center">Fee</th>
                        <th class="text-center">Convert Point</th>
                        <th class="text-center">Deposite</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="loading">
                        <td colspan="6" class="text-center"><a-skeleton active /></td>
                    </tr>
                    <tr v-else-if="state.listData.length === 0">
                        <td colspan="6" class="text-center">No PPN, Fee dan Point Settings Added</td>
                    </tr>
                    <tr v-for="(item, index) in state.listData" :key="index" v-else>
                        <td class="text-center">{{ index + 1 }}</td>
                        <td class="text-center">
                            <a-tooltip title="View / Edit PPN, Fee dan Point Setting">
                                <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                                    <template #icon>
                                        <EyeOutlined />
                                    </template>
                                </a-button>
                            </a-tooltip>
                        </td>
                        <td class="text-center"> {{ item.ppn }} %  </td>
                        <td class="text-center"> {{ item.fee }} %  </td>
                        <td class="text-center"> Rp. {{ parseInt(item.convert_point).toLocaleString() }}</td>
                        <td class="text-center"> Rp. {{ parseInt(item.deposite).toLocaleString() }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <a-drawer v-model:visible="modalView" width="400px" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add PPN, Fee dan Point Setting' : 'Edit PPN, Fee dan Point Setting'">
            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">PPN</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" v-model="state.form.ppn" placeholder="Enter PPN"/>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Fee</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" v-model="state.form.fee" placeholder="Enter Fee"/>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Convert Point</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" v-model="state.form.convert_point" placeholder="Enter Convert Point"/>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-4 col-form-label">Deposite</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" v-model="state.form.deposite" placeholder="Enter Deposite"/>
                </div>
            </div>
            <template #footer>
                <button type="button" :disabled="loadingButton" class="btn btn-primary ms-2" @click="update">
                    <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                    Update
                </button>
                <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        
        </a-drawer>
    </div>
</template>

<script setup>
    import { apiGetData, apiPostData,apiPutData,apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref, watch } from 'vue'; 
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import { EyeOutlined, DeleteOutlined } from '@ant-design/icons-vue';
    const modalView = ref(false);
    const state = reactive({
        listData:{},
        form: {
            odata: '',
            ppn: '',
            fee: '',
            convert_point: '',
            deposite: '',
        },
    }); 
    
    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/ppn_tax_point', {});
        state.listData = response.data
        loading.value = false;
    };

    const view = (item) => {
        state.form.odata = item.odata;
        state.form.ppn = item.ppn;
        state.form.fee = item.fee;
        state.form.convert_point = item.convert_point;
        state.form.deposite = item.deposite;
        modalView.value = true;
    };

    const update = async () => {
        loadingSubmit.value = true;

        const payload = {
            odata: state.form.odata,
            ppn: state.form.ppn,
            fee: state.form.fee,
            convert_point: state.form.convert_point,
            deposite: state.form.deposite,
        };

        const response = await apiPostData('/setting/ppn_tax_point', payload);

        if (response) {
            loadingSubmit.value = false;
            modalView.value = false;
            getData();
        }else{
            loadingSubmit.value = false;
        }
    };


    onMounted(() => {
        getData();
    });
</script>
