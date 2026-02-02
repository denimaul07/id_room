<template>
    <div>
        <Accordion value="0">
            <AccordionPanel value="0">
                <AccordionHeader>Profile Settings</AccordionHeader>
                <AccordionContent>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-6 col-xxl-6">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Site Name</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="state.settings.siteName" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Primary Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="primaryColor" v-model="state.settings.primaryColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Primary Color Hover</label>
                                <div class="col-sm-9">
                                    <input type="color" id="primaryColorHover" v-model="state.settings.primaryColorHover" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Primary Text Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="primaryTextColor" v-model="state.settings.primaryTextColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Secondary Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="secondColor" v-model="state.settings.secondColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Secondary Color Hover</label>
                                <div class="col-sm-9">
                                    <input type="color" id="secondColorHover" v-model="state.settings.secondColorHover" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Secondary Text Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="secondTextColor" v-model="state.settings.secondTextColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Favicon</label>
                                <div class="col-sm-9">
                                    <input
                                        type="file"
                                        class="form-control mb-2"
                                        @change="onFileChangeFavicon"
                                        accept="image/ico"
                                    />
                                    <a-image :src="pathUrl + '/storage/' + state.settings.favicon" width="32" 
                                    fallback="data:image/png;base64,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"
                                    />
                                </div>
                            </div>
                        
                        </div>

                        <div class="col-sm-12 col-md-6 col-xl-6 col-xxl-6">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Image Banner</label>
                                <div class="col-sm-9">
                                    <input
                                        type="file"
                                        class="form-control mb-2"
                                        @change="onFileChangeBanner"
                                        accept="image/webp"
                                    />
                                    <a-image :src="pathUrl + '/storage/' + state.settings.imageBanner" width="200" 
                                    fallback="data:image/png;base64,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"
                                    />
                                </div>
                            </div>     

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Title Banner</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="state.settings.titleBanner" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Sub Title Banner</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" v-model="state.settings.subTitleBanner" />
                                </div>
                            </div> 

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Color Title Banner</label>
                                <div class="col-sm-9">
                                    <input type="color" id="colorTitleBanner" v-model="state.settings.colorTitleBanner" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="1">
                <AccordionHeader>Header NavBar</AccordionHeader>
                <AccordionContent>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-6 col-xxl-6">
                        
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Logo</label>
                                <div class="col-sm-9">
                                    <input
                                        type="file"
                                        class="form-control mb-2"
                                        @change="onFileChange"
                                        accept="image/webp"
                                    />
                                    <a-image :src="pathUrl + '/storage/' + state.settings.logo" width="100" 
                                    fallback="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAMIAAADDCAYAAADQvc6UAAABRWlDQ1BJQ0MgUHJvZmlsZQAAKJFjYGASSSwoyGFhYGDIzSspCnJ3UoiIjFJgf8LAwSDCIMogwMCcmFxc4BgQ4ANUwgCjUcG3awyMIPqyLsis7PPOq3QdDFcvjV3jOD1boQVTPQrgSkktTgbSf4A4LbmgqISBgTEFyFYuLykAsTuAbJEioKOA7DkgdjqEvQHEToKwj4DVhAQ5A9k3gGyB5IxEoBmML4BsnSQk8XQkNtReEOBxcfXxUQg1Mjc0dyHgXNJBSWpFCYh2zi+oLMpMzyhRcASGUqqCZ16yno6CkYGRAQMDKMwhqj/fAIcloxgHQqxAjIHBEugw5sUIsSQpBobtQPdLciLEVJYzMPBHMDBsayhILEqEO4DxG0txmrERhM29nYGBddr//5/DGRjYNRkY/l7////39v///y4Dmn+LgeHANwDrkl1AuO+pmgAAADhlWElmTU0AKgAAAAgAAYdpAAQAAAABAAAAGgAAAAAAAqACAAQAAAABAAAAwqADAAQAAAABAAAAwwAAAAD9b/HnAAAHlklEQVR4Ae3dP3PTWBSGcbGzM6GCKqlIBRV0dHRJFarQ0eUT8LH4BnRU0NHR0UEFVdIlFRV7TzRksomPY8uykTk/zewQfKw/9znv4yvJynLv4uLiV2dBoDiBf4qP3/ARuCRABEFAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghggQAQZQKAnYEaQBAQaASKIAQJEkAEEegJmBElAoBEgghgg0Aj8i0JO4OzsrPv69Wv+hi2qPHr0qNvf39+iI97soRIh4f3z58/u7du3SXX7Xt7Z2enevHmzfQe+oSN2apSAPj09TSrb+XKI/f379+08+A0cNRE2ANkupk+ACNPvkSPcAAEibACyXUyfABGm3yNHuAECRNgAZLuYPgEirKlHu7u7XdyytGwHAd8jjNyng4OD7vnz51dbPT8/7z58+NB9+/bt6jU/TI+AGWHEnrx48eJ/EsSmHzx40L18+fLyzxF3ZVMjEyDCiEDjMYZZS5wiPXnyZFbJaxMhQIQRGzHvWR7XCyOCXsOmiDAi1HmPMMQjDpbpEiDCiL358eNHurW/5SnWdIBbXiDCiA38/Pnzrce2YyZ4//59F3ePLNMl4PbpiL2J0L979+7yDtHDhw8vtzzvdGnEXdvUigSIsCLAWavHp/+qM0BcXMd/q25n1vF57TYBp0a3mUzilePj4+7k5KSLb6gt6ydAhPUzXnoPR0dHl79WGTNCfBnn1uvSCJdegQhLI1vvCk+fPu2ePXt2tZOYEV6/fn31dz+shwAR1sP1cqvLntbEN9MxA9xcYjsxS1jWR4AIa2Ibzx0tc44fYX/16lV6NDFLXH+YL32jwiACRBiEbf5KcXoTIsQSpzXx4N28Ja4BQoK7rgXiydbHjx/P25TaQAJEGAguWy0+2Q8PD6/Ki4R8EVl+bzBOnZY95fq9rj9zAkTI2SxdidBHqG9+skdw43borCXO/ZcJdraPWdv22uIEiLA4q7nvvCug8WTqzQveOH26fodo7g6uFe/a17W3+nFBAkRYENRdb1vkkz1CH9cPsVy/jrhr27PqMYvENYNlHAIesRiBYwRy0V+8iXP8+/fvX11Mr7L7ECueb/r48eMqm7FuI2BGWDEG8cm+7G3NEOfmdcTQw4h9/55lhm7DekRYKQPZF2ArbXTAyu4kDYB2YxUzwg0gi/41ztHnfQG26HbGel/crVrm7tNY+/1btkOEAZ2M05r4FB7r9GbAIdxaZYrHdOsgJ/wCEQY0J74TmOKnbxxT9n3FgGGWWsVdowHtjt9Nnvf7yQM2aZU/TIAIAxrw6dOnAWtZZcoEnBpNuTuObWMEiLAx1HY0ZQJEmHJ3HNvGCBBhY6jtaMoEiJB0Z29vL6ls58vxPcO8/zfrdo5qvKO+d3Fx8Wu8zf1dW4p/cPzLly/dtv9Ts/EbcvGAHhHyfBIhZ6NSiIBTo0LNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiECRCjUbEPNCRAhZ6NSiAARCjXbUHMCRMjZqBQiQIRCzTbUnAARcjYqhQgQoVCzDTUnQIScjUohAkQo1GxDzQkQIWejUogAEQo121BzAkTI2agUIkCEQs021JwAEXI2KoUIEKFQsw01J0CEnI1KIQJEKNRsQ80JECFno1KIABEKNdtQcwJEyNmoFCJAhELNNtScABFyNiqFCBChULMNNSdAhJyNSiEC/wGgKKC4YMA4TAAAAABJRU5ErkJggg=="
                                    />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Nav Bar Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="navBarColor" v-model="state.settings.navBarColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-xl-6 col-xxl-6">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Nav Bar Text Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="navBarTextColor" v-model="state.settings.navBarTextColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </AccordionContent>
            </AccordionPanel>
            <AccordionPanel value="2">
                <AccordionHeader>Footer</AccordionHeader>
                <AccordionContent>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-xl-6 col-xxl-6">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Footer Description</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" v-model="state.settings.footerDesk" rows="4"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-xl-6 col-xxl-6">
                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Footer Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="footerColor" v-model="state.settings.footerColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <label class="col-sm-3 col-form-label">Footer Text Color</label>
                                <div class="col-sm-9">
                                    <input type="color" id="footerTextColor" v-model="state.settings.footerTextColor" class="form-control form-control-color" style="width: 2.5rem; height: 2.5rem; padding: 0; border: none; background: none;" />
                                </div>
                            </div>

                        </div>

                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Syarat dan Ketentuan</label>
                            <div class="col-sm-9">
                                <ckeditor :editor="editor" :config="editorConfig" v-model="state.settings.syaratketentuan"></ckeditor>
                            </div>
                        </div>

                        
                        <div class="mb-3 row">
                            <label class="col-sm-3 col-form-label">Privacy Policy</label>
                            <div class="col-sm-9">
                                <ckeditor :editor="editor" :config="editorConfig" v-model="state.settings.privacypolicy"></ckeditor>
                            </div>
                        </div>
                    </div>  
                </AccordionContent>
            </AccordionPanel>

            <div class="d-flex justify-content-end">
                <Button label="Save Settings" class="p-button-primary btn btn-dark mt-3" @click="save" :disabled="loadingSubmit"></Button>
            </div>
            <ProgressBar mode="indeterminate" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
        </Accordion>

        

        <a-modal v-model:open="processing"  :footer="null" :closable=false   width="400px">
            <div style="align-items:center;justify-content: center;display: flex;" width="100px">
                <img class="img-fluid" :src="waitingicon" alt="vector women with leptop"/>
            </div>

            <div style="align-items:center;justify-content: center;display: flex;">
                {{ pesan }}
            </div>
        </a-modal>
    </div>

</template>

<script setup>
    import { apiGetData, apiPostData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { reactive, onMounted, ref } from 'vue';
    import Accordion from 'primevue/accordion';
    import AccordionPanel from 'primevue/accordionpanel';
    import AccordionHeader from 'primevue/accordionheader';
    import AccordionContent from 'primevue/accordioncontent';
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
    const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;


    const state = reactive({
        settings: {
            odata: '',
            siteName: '',
            primaryColor: '#42A5F5',
            primaryColorHover: '#1E88E5',
            primaryTextColor: '#ffffff',
            secondColor: '#ffffff',
            secondColorHover: '#f0f0f0',
            secondTextColor: '#000000',
            logo: '',
            favicon: '',
            imageBanner: '',
            titleBanner: '',
            subTitleBanner: '',
            colorTitleBanner: '#000000',
            navBarColor: '#ffffff',
            navBarTextColor: '#000000',
            footerDesk: '',
            footerColor: '#ffffff',
            footerTextColor: '#000000',
            socials: [],
            syaratketentuan: '',
            privacypolicy: '',
        }
    });

    const getData = async () => {
        loading.value = true;
        const response = await apiGetData('/setting/site_setting', {});
        state.settings = response.data[0];
        state.settings.odata = response.data[0].odata;
        loading.value = false;
    };

    const onFileChange = (event) => {
        const file = event.target.files[0];
        state.settings.logo = file;
    };

    const onFileChangeBanner = (event) => {
        const file = event.target.files[0];
        state.settings.imageBanner = file;
    };

    const onFileChangeFavicon = (event) => {
        const file = event.target.files[0];
        state.settings.favicon = file;
    };

    const save = async () => {
        processing.value = true;
        pesan.value = 'Mohon tunggu, sedang menyimpan data...';
        const formData = new FormData();
        formData.append('odata', state.settings.odata);
        formData.append('siteName', state.settings.siteName);
        formData.append('primaryColor', state.settings.primaryColor);
        formData.append('primaryColorHover', state.settings.primaryColorHover);
        formData.append('primaryTextColor', state.settings.primaryTextColor);
        formData.append('secondColor', state.settings.secondColor);
        formData.append('secondColorHover', state.settings.secondColorHover);
        formData.append('secondTextColor', state.settings.secondTextColor);
        formData.append('titleBanner', state.settings.titleBanner);
        formData.append('subTitleBanner', state.settings.subTitleBanner);
        formData.append('colorTitleBanner', state.settings.colorTitleBanner);
        formData.append('navBarColor', state.settings.navBarColor);
        formData.append('navBarTextColor', state.settings.navBarTextColor);
        formData.append('footerDesk', state.settings.footerDesk);
        formData.append('footerColor', state.settings.footerColor);
        formData.append('footerTextColor', state.settings.footerTextColor);
        formData.append('syaratketentuan', state.settings.syaratketentuan);
        formData.append('privacypolicy', state.settings.privacypolicy);

        if (state.settings.logo instanceof File) {
            formData.append('logo', state.settings.logo);
        }

        if (state.settings.favicon instanceof File) {
            formData.append('favicon', state.settings.favicon);
        }

        if (state.settings.imageBanner instanceof File) {
            formData.append('imageBanner', state.settings.imageBanner);
        }

        const response = await apiPostData('/setting/site_setting', formData, {
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
        min-height: 500px;
        max-height: 500px;
        overflow-y: auto;
    }
</style>