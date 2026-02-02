<template>
    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">Banner</label>
        <div class="col-sm-9">
            <input type="file" class="form-control"  @change="(e) => { state.form.bannerRenov = e.target.files[0]; }" accept="image/webp" />
            <a-image :src="pathUrl + '/storage/' + state.form.bannerRenov" width="32" 
            fallback="data:image/png;base64,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"
            />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">Text Color</label>
        <div class="col-sm-9">
            <input type="color" class="form-control form-control-color" v-model="state.form.colorRenov" />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">Title Renovasi</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" v-model="state.form.titleRenov" />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">Subtitle Renovasi</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" v-model="state.form.subTitleRenov" />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">Title Section Renovasi</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" v-model="state.form.titleSectionRenov" />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">Description Section Renovasi</label>
        <div class="col-sm-9">
            <ckeditor :editor="editor" v-model="state.form.descSectionRenov" :config="editorConfig" />
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-3 col-form-label">URL Renovasi</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" v-model="state.form.urlRenov" />
        </div>
    </div>



    <div class="d-flex justify-content-end">
        <Button label="Save Settings" class="p-button-primary btn btn-dark mt-3" @click="save" :disabled="loadingSubmit"></Button>
    </div>
    <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>

    <PortofolioSettings />
    <ProcessWorkSettings />
</template>

<script setup>
    import { apiGetData, apiPostData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref } from 'vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
    import ServicesSettings from '../services/index.vue';
    import PortofolioSettings from '../portofolio/portofolio.vue';
    import ProcessWorkSettings from '../processwork/ProcessWorkSettings.vue';
    import Testimoni from '../testimoni/index.vue';
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

    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
    const state = reactive({
        form: {
            odata: '',
            bannerRenov: '',
            colorRenov:'',
            titleRenov:'',
            subTitleRenov:'',
            titleSectionRenov:'',
            descSectionRenov:'',
            urlRenov:'',
        }
    });

    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/site_setting', {});
        state.form = response.data[0];
        state.form.odata = response.data[0].odata;
        loading.value = false;
    };

    const save = async () => {
        processing.value = true;
        pesan.value = 'Mohon tunggu, sedang menyimpan data...';
        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('colorRenov', state.form.colorRenov);
        formData.append('titleRenov', state.form.titleRenov);
        formData.append('subTitleRenov', state.form.subTitleRenov);
        formData.append('titleSectionRenov', state.form.titleSectionRenov);
        formData.append('descSectionRenov', state.form.descSectionRenov);
        formData.append('urlRenov', state.form.urlRenov);

        if (state.form.bannerRenov instanceof File) {
            formData.append('bannerRenov', state.form.bannerRenov);
        }


        const response = await apiPostData('/setting/site_setting_renovasi', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        
        if(response){
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Settings have been saved successfully.',
                confirmButtonColor: '#2c323f',
                confirmButtonText: '<i class="fa fa-check me-2"></i> OKE',
            });
            await getData();
            processing.value = false;
        } else{
            processing.value = false;
        }
    };


    onMounted(async () => {
        await getData();
    });


</script>

<style scoped>
    ::v-deep(.ck-content) {
        min-height: 300px;
        max-height: 300px;
        overflow-y: auto;
    }
</style>