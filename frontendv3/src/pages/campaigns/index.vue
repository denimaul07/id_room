<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Campaigns" title="List Campaigns" />

                <div class="card ms-2">
                    
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <Button label="Tambah Campaign" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
                                <Button label="WA Templates" icon="pi pi-whatsapp" class="btn btn-success" size="small" @click="goToWaTemplates" />
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
                                            <th class="text-center bg-dark text-nowrap">Name</th>
                                            <th class="text-center bg-dark text-nowrap">Template Name</th>
                                            <th class="text-center bg-dark text-nowrap">Images</th>
                                            <th class="text-center bg-dark text-nowrap">Contacts</th>
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
                                                <a-tooltip title="Kirim Campaign via WhatsApp">
                                                    <a-button type="primary" size="small" class="bg-success me-2" @click="kirimWA(data)">
                                                        <template #icon>
                                                            <CheckOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>

                                                <a-tooltip title="Edit Campaign">
                                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
                                                        <template #icon>
                                                            <EditOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>

                                                <a-tooltip title="Delete Campaign">
                                                    <a-button type="primary" size="small" danger @click="deleteData(data)">
                                                        <template #icon>
                                                            <DeleteOutlined />
                                                        </template>
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center">{{ data.name }}</td>
                                            <td class="text-center">{{ data.template_name }}</td>
                                            <td class="text-center">
                                                <a-image :src="imageBaseUrl + data.images" :alt="data.images" width="100px" />
                                            </td>
                                            <td class="text-center">
                                                <a-tooltip title="Detail Contacts">
                                                    <a-button type="primary" size="small" @click="viewContacts(data)" class="bg-dark">
                                                            {{ data.contacts.length }} Contacts
                                                    </a-button>
                                                </a-tooltip>
                                            </td>
                                            <td class="text-center">
                                                <span v-if="data.status == 'draft'" class="badge bg-warning">Draft</span>
                                                <span v-else-if="data.status == 'scheduled'">
                                                    <span class="badge bg-info">Scheduled</span>
                                                    <br>
                                                    <small>{{ dayjs(data.scheduled_at).format('YYYY-MM-DD HH:mm:ss') }}</small>
                                                </span>
                                                <span v-else-if="data.status == 'sent'" class="badge bg-success">Sent</span>
                                                <span v-else class="badge bg-primary">Completed</span>
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
                    </div>
                </div>
            </div>
        </div>

        <a-drawer
            title="Add Campaign"
            v-model:visible="modalAdd"
            placement="right"
            
            width="500px"
        >


            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Campaign Name</label>
                <div class="col-sm-8">
                    <a-input v-model:value="state.form.name" placeholder="Enter campaign name" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Template Name</label>
                <div class="col-sm-8">
                    <a-select
                        v-model:value="state.form.template_name"
                        style="width: 100%"
                        placeholder="Pilih template WA"
                        :loading="loadingWaTemplates"
                        show-search
                        option-filter-prop="label"
                        @dropdownVisibleChange="onTemplateDropdownOpen"
                    >
                        <a-select-option
                            v-for="tpl in waTemplates"
                            :key="tpl.name"
                            :value="tpl.name"
                            :label="tpl.name"
                            :disabled="tpl.status !== 'APPROVED'"
                        >
                            <span class="font-monospace">{{ tpl.name }}</span>
                            <span class="badge ms-2" :class="{
                                'bg-success': tpl.status === 'APPROVED',
                                'bg-warning text-dark': tpl.status === 'PENDING',
                                'bg-danger': tpl.status === 'REJECTED',
                            }">{{ tpl.status }}</span>
                        </a-select-option>
                    </a-select>
                    <small class="text-muted">Hanya template dengan status <strong>APPROVED</strong> yang bisa dipilih.
                        <a href="#" @click.prevent="goToWaTemplates">Kelola template →</a>
                    </small>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Images</label>
                <div class="col-sm-8">
                    <input type="file" class="form-control" @change="e => state.form.images = e.target.files[0]" accept="image/png, image/jpeg, image/jpg" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-4 col-form-label">Status</label>
                <div class="col-sm-8">
                    <a-select v-model:value="state.form.status" placeholder="Select status" style="width: 100%">
                        <a-select-option value="draft">Draft</a-select-option>
                        <a-select-option value="scheduled">Scheduled</a-select-option>
                    </a-select>
                </div>
            </div>

            <div class="row mb-3" v-if="state.form.status == 'scheduled'">
                <label class="col-sm-4 col-form-label">Schedule Time</label>
                <div class="col-sm-8">
                    <a-date-picker v-model:value="state.form.schedule_time" show-time style="width: 100%" placeholder="Select schedule time" />
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

        <a-modal v-model:visible="modalContacts" title="Campaign Contacts" style="top:20px" width="800px" :footer="false">
        
            <div class="mb-3 row">
                <Button label="Add Contact" icon="pi pi-plus" class="btn btn-dark mb-3" size="small" @click="addContact" />
                <Button label="Download Format" icon="pi pi-download" class="btn btn-primary mb-3 ms-2" size="small" @click="downloadFormat" />

                <div class="ms-auto col-sm-4">
                    <a-input-search
                        v-model:value="searchMembers"
                        placeholder="Cari Data"
                    />
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center bg-dark">No</th>
                            <th class="text-center bg-dark">Name</th>
                            <th class="text-center bg-dark">Phone</th>
                            <th class="text-center bg-dark">Status</th>
                            <th class="text-center bg-dark">Error</th>
                            <th class="text-center bg-dark">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="loadingContacts">
                            <td class="text-center" colspan="6">
                                <a-skeleton active />
                            </td>
                        </tr>
                        <tr v-else-if="state.listContacts.length == 0">
                            <td class="text-center" colspan="6"><a-empty/></td>
                        </tr>
                        <tr v-for="(contact, index) in paginatedContacts" :key="index" v-else>
                            <td class="text-center">{{ (currentPage - 1) * pageSize + index + 1 }}</td>
                            <td class="text-center">{{ contact.name }}</td>
                            <td class="text-center">{{ contact.phone }}</td>
                            <td class="text-center">
                                <span v-if="contact.status == 'pending'" class="badge bg-warning">Pending</span>
                                <span v-else-if="contact.status == 'sent'" class="badge bg-success">Sent</span>
                                <span v-else class="badge bg-danger">Failed</span>
                            </td>
                            <td class="text-center" style="max-width:220px">
                                <a-tooltip v-if="contact.error_message" :title="contact.error_message">
                                    <span class="text-danger small" style="cursor:pointer; word-break:break-word">
                                        <i class="fa fa-exclamation-circle me-1"></i>{{ contact.error_message.length > 60 ? contact.error_message.substring(0, 60) + '…' : contact.error_message }}
                                    </span>
                                </a-tooltip>
                                <span v-else class="text-muted small">-</span>
                            </td>
                            <td class="text-center">
                                <a-button type="primary" size="small" class="bg-dark me-2" @click="deleteContract(contact)">
                                    <template #icon>
                                        <DeleteOutlined />
                                    </template>
                                </a-button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-2">
                <div>
                    Showing {{ (currentPage - 1) * pageSize + 1 }} to
                    {{ Math.min(currentPage * pageSize, totalContacts) }} of
                    {{ totalContacts }} entries
                </div>

                <a-pagination
                    :current="currentPage"
                    :total="totalContacts"
                    :pageSize="pageSize"
                    show-size-changer
                    :pageSizeOptions="['5','10','20','50','100']"
                    @change="page => currentPage = page"
                    @showSizeChange="(page, size) => {
                        pageSize = size;
                        currentPage = 1;
                    }"
                />
            </div>

            <a-modal 
                v-model:visible="moadlAddContact" 
                title="Add Contact" 
                width="500px" 
                :footer="false"
            >
                <div class="container text-center">
                    <div class="row align-items-center justify-content-center">

                        <!-- LEFT SIDE (BUTTON) -->
                        <div class="col-6 d-flex flex-column justify-content-center align-items-center">
                            <Button label="Add Contact Members" icon="pi pi-plus" class="btn btn-dark" size="small" @click="addMembers" />
                        </div>

                        <!-- RIGHT SIDE (UPLOAD) -->
                        <div class="col-6 d-flex flex-column justify-content-center align-items-center">
                            <Button label="Upload Excel" icon="pi pi-upload" class="btn btn-primary mb-2" size="small" @click="uploadExcel" />
                        </div>

                    </div>
                </div>
            </a-modal>

            <!-- Modal Preview Excel Contacts -->
            <a-modal v-model:visible="modalPreviewExcel" title="Preview Excel Contacts" width="800px" style="top:20px">
                <div class="table-responsive">
                    <div v-if="totalError > 0" class="alert alert-danger">
                        <strong>{{ totalError }} data error</strong><br>

                        <div style="max-height: 100px; overflow:auto;">
                            <div v-for="(err, i) in errorLines" :key="i">
                                Line {{ err.line }} - {{ err.error }}
                            </div>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>    
                                <th class="text-center bg-dark">No</th>
                                <th v-for="col in excelPreviewColumns" :key="col" class="text-center bg-dark">{{ col }}</th>
                                <th class="text-center bg-dark">Error</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="excelPreviewData.length === 0">
                                <td class="text-center" :colspan="excelPreviewColumns.length">No data</td>
                            </tr>
                            <tr
                                v-for="(row, idx) in excelPreviewPageData"
                                :key="idx"
                            >
                                <td class="text-center" :class="{ 'bg-danger text-white': row.__error }">
                                    {{ (excelPreviewPage - 1) * excelPreviewPageSize + idx + 1 }}
                                </td>

                                <td v-for="col in excelPreviewColumns" :key="col" class="text-center" :class="{ 'bg-danger text-white': row.__error }">
                                    {{ row[col] }}
                                </td>

                                <td class="text-center" :class="{ 'bg-danger text-white': row.__error }">
                                    {{ row.__error || '-' }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        Showing {{ (excelPreviewPage - 1) * excelPreviewPageSize + 1 }} to
                        {{ Math.min(excelPreviewPage * excelPreviewPageSize, excelPreviewTotal) }} of
                        {{ excelPreviewTotal }} entries
                    </div>
                    <a-pagination
                        :current="excelPreviewPage"
                        :total="excelPreviewTotal"
                        :pageSize="excelPreviewPageSize"
                        @change="page => (excelPreviewPage = page)"
                        show-size-changer
                        :pageSizeOptions="[5, 10, 20, 50, 100]"
                        @showSizeChange="(current, size) => { excelPreviewPageSize = size; excelPreviewPage = 1; }"
                    />
                </div>

                
                <template #footer>
                    <button type="button" :disabled="loadingButton || totalError > 0" class="btn btn-primary ms-2" @click="saveExcelContacts">
                        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                        Proses Simpan
                    </button>
                    <br>
                    <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
                </template>
            </a-modal>

            <a-modal v-model:visible="modalMembers" title="List Members" width="800px" :footer="false">
                <div class="mb-2 d-flex align-items-center">
                    <!-- <a-checkbox v-model:checked="selectAllMembers" @change="toggleSelectAllMembers">Select All</a-checkbox> -->
                    <button class="btn btn-primary btn-sm ms-2" :disabled="selectedMembers.length === 0" @click="sendSelectedMembers">Kirim Data Terpilih</button>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center bg-dark">No</th>
                                <th class="text-center bg-dark"><a-checkbox :checked="selectAllMembers" @change="toggleSelectAllMembers" /> Select All</th>
                                <th class="text-center bg-dark">Name</th>
                                <th class="text-center bg-dark">Phone</th>
                                <th class="text-center bg-dark">Status</th>
                                <th class="text-center bg-dark">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="state.listMembers.total == 0">
                                <td class="text-center" colspan="5"><a-empty/></td>
                            </tr>
                            <tr v-for="(member, index) in state.listMembers.data" :key="index">
                                <td class="text-center">{{ (state.listMembers.current_page - 1) * state.listMembers.per_page + index + 1 }}</td>
                                <td class="text-center">
                                    <a-checkbox :checked="selectedMembers.includes(member.odata)" @change="() => toggleMemberSelection(member.odata)" :disabled="isMemberInCampaign(member)" />
                                </td>
                                <td class="text-center">{{ member.name }}</td>
                                <td class="text-center">{{ member.phone }}</td>
                                <td class="text-center">
                                    <span v-if="member.status_users == 0" class="badge bg-success">Active</span>
                                    <span v-else class="badge bg-danger">Inactive</span>
                                    <span v-if="isMemberInCampaign(member)" class="badge bg-info ms-2">Sudah di campaign</span>
                                </td>
                                <td class="text-center">
                                    <a-button type="primary" size="small" class="bg-dark me-2" @click="pilih(member)" :disabled="isMemberInCampaign(member)">
                                        <template #icon>
                                            <CheckOutlined />
                                        </template>
                                    </a-button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-2">
                    <div>
                        Showing {{ (state.listMembers.current_page - 1) * state.listMembers.per_page + 1 }} to
                        {{ Math.min(state.listMembers.current_page * state.listMembers.per_page, state.listMembers.total) }} of
                        {{ state.listMembers.total }} entries
                    </div>

                    <a-pagination
                        :current="state.listMembers.current_page"
                        :total="state.listMembers.total"
                        :pageSize="state.listMembers.per_page"
                        show-size-changer
                        :pageSizeOptions="[5,10,20,50,100]"
                        @change="page => addMembers(page)"
                        @showSizeChange="(page, size) => addMembers(1, size)"
                    />
                </div>
            </a-modal>
        </a-modal>

        <!-- Modal Progress Kirim WA -->
        <a-modal
            v-model:visible="modalSendProgress"
            title="Progres Pengiriman Campaign WhatsApp"
            width="780px"
            style="top:20px"
            :footer="null"
            :closable="!isSending || sendSummary.completed"
            :maskClosable="!isSending || sendSummary.completed"
            @cancel="onCloseProgressModal"
        >
            <!-- Header summary stats -->
            <div class="progress-summary mb-3">
                <div class="row g-2 text-center">
                    <div class="col">
                        <div class="stat-card stat-total">
                            <div class="stat-value">{{ sendSummary.total }}</div>
                            <div class="stat-label">Total</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-queued">
                            <div class="stat-value">{{ sendSummary.queued }}</div>
                            <div class="stat-label">Antrian</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-sending">
                            <div class="stat-value">{{ sendSummary.sending }}</div>
                            <div class="stat-label">Mengirim</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-sent">
                            <div class="stat-value">{{ sendSummary.sent }}</div>
                            <div class="stat-label">Terkirim</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="stat-card stat-failed">
                            <div class="stat-value">{{ sendSummary.failed }}</div>
                            <div class="stat-label">Gagal</div>
                        </div>
                    </div>
                </div>
    
                <!-- Progress bar overall -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1 small">
                        <span>Progress pengiriman</span>
                        <span>{{ sendSummary.done }} / {{ sendSummary.total }}</span>
                    </div>
                    <div class="progress" style="height: 10px; border-radius: 999px;">
                        <div
                            class="progress-bar bg-success"
                            :style="{ width: progressPercent + '%' }"
                            style="transition: width 0.4s ease; border-radius: 999px;"
                        ></div>
                    </div>
                </div>
    
                <!-- Status badge -->
                <div class="mt-2 text-center">
                    <span v-if="sendSummary.completed" class="badge bg-success fs-6 px-3 py-2">
                        ✅ Semua selesai diproses!
                    </span>
                    <span v-else-if="isSending" class="badge bg-primary fs-6 px-3 py-2">
                        <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                        Sedang mengirim...
                    </span>
                    <span v-else class="badge bg-secondary fs-6 px-3 py-2">
                        Menunggu mulai...
                    </span>
                </div>
            </div>

            <!-- Filter tabs -->
            <div class="mb-2 d-flex gap-2">
                <button
                    v-for="tab in progressTabs"
                    :key="tab.key"
                    class="btn btn-sm"
                    :class="progressFilter === tab.key ? 'btn-dark' : 'btn-outline-secondary'"
                    @click="progressFilter = tab.key"
                >
                    {{ tab.label }}
                    <span class="badge ms-1" :class="progressFilter === tab.key ? 'bg-light text-dark' : 'bg-secondary'">
                        {{ tab.count }}
                    </span>
                </button>
            </div>

            <!-- Contact list -->
            <div class="progress-contact-list" style="max-height: 340px; overflow-y: auto;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="sticky-top bg-white" style="z-index:1">
                        <tr>
                            <th class="text-center bg-dark text-white" style="width:40px">No</th>
                            <th class="bg-dark text-white">Nama</th>
                            <th class="bg-dark text-white">Nomor</th>
                            <th class="text-center bg-dark text-white">Status</th>
                            <th class="bg-dark text-white">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="filteredProgressContacts.length === 0">
                            <td colspan="5" class="text-center text-muted py-3">Tidak ada data</td>
                        </tr>
                        <tr
                            v-for="(c, idx) in filteredProgressContacts"
                            :key="c.odata"
                            :class="{
                                'table-success': c.status === 'sent',
                                'table-danger':  c.status === 'failed',
                                'table-warning': c.status === 'sending',
                            }"
                        >
                            <td class="text-center">{{ idx + 1 }}</td>
                            <td>{{ c.name }}</td>
                            <td>{{ c.phone }}</td>
                            <td class="text-center">
                                <span v-if="c.status === 'queued'" class="badge bg-secondary">Antri</span>
                                <span v-else-if="c.status === 'sending'" class="badge bg-primary">
                                    <span class="spinner-border spinner-border-sm" style="width:.65em;height:.65em"></span>
                                    Mengirim
                                </span>
                                <span v-else-if="c.status === 'sent'" class="badge bg-success">✓ Terkirim</span>
                                <span v-else-if="c.status === 'failed'" class="badge bg-danger">✗ Gagal</span>
                                <span v-else class="badge bg-secondary">{{ c.status }}</span>
                            </td>
                            <td>
                                <a-tooltip v-if="c.error_message" :title="c.error_message">
                                    <span class="text-danger small" style="cursor:pointer">
                                        <i class="fa fa-exclamation-circle me-1"></i>
                                        {{ c.error_message.length > 55 ? c.error_message.substring(0, 55) + '…' : c.error_message }}
                                    </span>
                                </a-tooltip>
                                <span v-else class="text-muted small">-</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer modal -->
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <button
                    v-if="sendSummary.completed"
                    class="btn btn-dark btn-sm"
                    @click="onCloseProgressModal"
                >
                    Tutup
                </button>
            </div>
        </a-modal>


    </div>
</template> 


<script setup>
    import { apiGetData, apiPutData, apiPostData,apiExportExcel, apiPostDataWithReturn, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import * as XLSX from 'xlsx';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onMounted , watch, computed, onUnmounted, nextTick } from 'vue'
    import { ref as vueRef } from 'vue';
    import { useStore } from "vuex";
    import { useRouter } from 'vue-router';
    import {
        CheckOutlined,
        EditOutlined, DeleteOutlined, PlusOutlined
    } from '@ant-design/icons-vue';
    import Button from 'primevue/button';
    import ProgressBar from 'primevue/progressbar';
    const store = useStore();
    const router = useRouter();
    const loadingContacts = ref(false);
    const pagging = ref(10);
    const search = ref('');
    const searchMembers = ref('');
    const action = ref('');
    const modalAdd = ref(false);
    const modalContacts = ref(false);
    const moadlAddContact = ref(false);
    const modalMembers = ref(false);
    const modalPreviewExcel = ref(false);
    const excelPreviewData = ref([]);
    const excelPreviewColumns = ref([]);
    const excelFile = ref(null);
    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    // ─── WA Templates ────────────────────────────────────────────────────────
    const waTemplates = ref([])
    const loadingWaTemplates = ref(false)
    const waTemplatesFetched = ref(false)

    const fetchWaTemplates = async () => {
        if (loadingWaTemplates.value) return
        loadingWaTemplates.value = true
        const res = await apiGetData('/whatsapp/templates')
        waTemplates.value = res?.data || []
        waTemplatesFetched.value = true
        loadingWaTemplates.value = false
    }

    const onTemplateDropdownOpen = (open) => {
        if (open && !waTemplatesFetched.value) {
            fetchWaTemplates()
        }
    }

    const goToWaTemplates = () => {
        router.push({ name: 'wa-templates' })
    }
    const excelPreviewPage = ref(1);
    const excelPreviewPageSize = ref(10);
    const excelPreviewTotal = computed(() => excelPreviewData.value.length);
    const excelPreviewPageData = computed(() => {
        const start = (excelPreviewPage.value - 1) * excelPreviewPageSize.value;
        return excelPreviewData.value.slice(start, start + excelPreviewPageSize.value);
    });
    const excelPreviewErrors = ref([]); // array of { row: index, message: string }
    const currentPage = ref(1);
    const pageSize = ref(10);
    const totalContacts = computed(() => state.listContacts.length);
    const paginatedContacts = computed(() => {
        const start = (currentPage.value - 1) * pageSize.value;
        const end = start + pageSize.value;
        return state.listContacts.slice(start, end);
    });
    const state = reactive({
        listData: {},
        listContacts: {},
        listMembers: {},
        form: {
            odata: "",
            name: "",
            images: null,
            template_name: "",
            schedule_time: "",
            status: ""
        }
    });


    const getData = async (page = state.listData.current_page) => {
        loading.value = true;
        const params = {
            page: page,
            pagging: pagging.value,
            search: search.value,
        };
        const response = await apiGetData('/campaigns/index', params);
        state.listData = response.data;
        loading.value = false;
    };

    const handlePageChange = (page) => {
        getData(page);
    };

    const add = async () => {
        action.value = 'add';
        state.form = {
            odata: "",
            images: null,
            name: "",
            template_name: "",
            schedule_time: "",
            status: []
        }
        modalAdd.value = true;
    };

    const view = async (data) => {
        action.value = 'edit';
        state.form = {
            odata: data.odata,
            images: data.images,
            name: data.name,
            template_name: data.template_name,
            schedule_time: dayjs(data.scheduled_at),
            status: data.status
        }
        modalAdd.value = true;
    };

    const save = async () => {
        loadingSubmit.value = true;

        // Membuat FormData untuk upload file dan data lainnya
        const formData = new FormData();
        formData.append('odata', state.form.odata);
        formData.append('status', state.form.status);
        formData.append('name', state.form.name);
        formData.append('template_name', state.form.template_name);
        formData.append('schedule_time', dayjs(state.form.schedule_time).format('YYYY-MM-DD HH:mm:ss'));

        if (state.form.images) {
            formData.append('images', state.form.images);
        }

        let response;
        if (action.value == 'add') {
            response = await apiPostData('/campaigns/store', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
            });
        } else {
            response = await apiPostData('/campaigns/update', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
            });
        }

        if(response){
            Swal.fire('Success', 'Data saved successfully', 'success');
            modalAdd.value = false;
            loadingSubmit.value = false;
            await getData();
        }else{
            loadingSubmit.value = false;
        }
    };

    const viewContacts = async (data) => {
        modalContacts.value = true;
        state.form.odata = data.odata;
        state.listContacts = data.contacts;
    };

    const addContact = async () => {
        moadlAddContact.value = true;
    };

    const uploadExcel = async () => {
        excelPreviewPage.value = 1;
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = '.xlsx,.xls';
        input.onchange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            excelFile.value = file;
            const data = await file.arrayBuffer();
            const workbook = XLSX.read(data);
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const json = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
            if (json.length > 0) {
                excelPreviewColumns.value = json[0];
                excelPreviewErrors.value = [];
                excelPreviewData.value = json.slice(1).map((row, rowIdx) => {
                    const obj = {};
                    let errorMessage = '';

                    json[0].forEach((col, colIdx) => {
                        obj[col] = row[colIdx] || '';
                    });

                    const phoneRaw = obj['Phone'] ? String(obj['Phone']).trim() : '';
                    const nameRaw = obj['Name'] ? String(obj['Name']).trim() : '';
                    let phone = formatPhone(phoneRaw);
                    obj['Phone'] = phone;
                    obj['Name'] = nameRaw;

                    if (!nameRaw) {
                        errorMessage = 'Nama kosong';
                    } else if (!phone) {
                        errorMessage = 'Nomor kosong';
                    } else if (/[a-zA-Z]/.test(phone)) {
                        errorMessage = 'Nomor tidak valid (huruf)';
                    } else if (!validatePhoneNumber(phone)) {
                        errorMessage = 'Format harus +628xxxx';
                    }

                    return {
                        ...obj,
                        __error: errorMessage
                    };
                });
            } else {
                excelPreviewColumns.value = [];
                excelPreviewData.value = [];
            }
            modalPreviewExcel.value = true;
        };
        input.click();
    };

    const totalError = computed(() => {
        return excelPreviewData.value.filter(row => row.__error).length;
    });

    const errorLines = computed(() => {
        return excelPreviewData.value
            .map((row, index) => ({
                line: index + 2, // +2 karena row 1 header
                error: row.__error
            }))
            .filter(item => item.error);
    });

    function formatPhone(phone) {
        if (!phone) return '';

        phone = phone.replace(/\s+/g, '');
        phone = phone.replace('.0', '');

        // hapus karakter aneh selain + dan angka
        phone = phone.replace(/[^0-9+]/g, '');

        if (phone.startsWith('08')) {
            return '+62' + phone.slice(1);
        }

        if (phone.startsWith('62')) {
            return '+' + phone;
        }

        return phone;
    }

    function validatePhoneNumber(phone) {
        return /^\+[0-9]{8,15}$/.test(phone);
    }

    const saveExcelContacts = async () => {
        loadingSubmit.value = true;
        loadingButton.value = true;

        const validData = excelPreviewData.value.filter(row => !row.__error);

        const payload = {
            odata: state.form.odata,
            contacts: validData.map(row => ({
                name: row['Name'],
                phone: row['Phone']
            }))
        };

        const response = await apiPostData('/campaigns/upload', payload);
        if (response) {
            modalPreviewExcel.value = false;
            moadlAddContact.value = false;
            loadingSubmit.value = false;
            loadingButton.value = false;
            await getData();
            state.listContacts = response.data.contacts;
        } else {
            loadingSubmit.value = false;
            loadingButton.value = false;
        }
    };

    const selectedMembers = vueRef([]);
    const selectAllMembers = vueRef(false);

    const toggleSelectAllMembers = () => {
        if (selectAllMembers.value) {
            // Unselect all
            selectedMembers.value = [];
            selectAllMembers.value = false;
        } else {
            // Select all
            if (state.listMembers.data && Array.isArray(state.listMembers.data)) {
                selectedMembers.value = state.listMembers.data.map(m => m.odata);
            }
            selectAllMembers.value = true;
        }
    };

    const toggleMemberSelection = (odata) => {
        const idx = selectedMembers.value.indexOf(odata);
        if (idx > -1) {
            selectedMembers.value.splice(idx, 1);
        } else {
            selectedMembers.value.push(odata);
        }
        // Update selectAllMembers checkbox
        if (state.listMembers.data && Array.isArray(state.listMembers.data)) {
            selectAllMembers.value = selectedMembers.value.length === state.listMembers.data.length;
        }
    };

    const sendSelectedMembers = async () => {
        if (!selectedMembers.value.length) return;
        loading.value = true;
        const payload = {
            odata: state.form.odata,
            member_ids: selectedMembers.value
        };
        const response = await apiPostData('/campaigns/add-members-bulk', payload);
        if (response) {
            await getData();
            await getContacts();
            modalMembers.value = false;
            moadlAddContact.value = false;
            selectedMembers.value = [];
            selectAllMembers.value = false;
        }
        loading.value = false;
    };

    const addMembers = async (page = state.listMembers.current_page, perPage = state.listMembers.per_page) => {
        loading.value = true;
        const payload = {
            page: page,
            search: searchMembers.value,
            per_page: perPage
        };
        const response = await apiGetData('/campaigns/members', payload);
        state.listMembers = response.data;
        modalMembers.value = true;
        // Reset selection when data changes
        selectedMembers.value = [];
        selectAllMembers.value = false;
        loading.value = false;
    };

    const getContacts = async () => {
        loadingContacts.value = true;
        const params = {
            odata: state.form.odata,
            search: searchMembers.value
        };
        const response = await apiGetData('/campaigns/get-contacts', params);
        state.listContacts = response.data
        loadingContacts.value = false;
    };

    const pilih = async (member) => {
        const payload = {
            odata: state.form.odata,
            member_id: member.odata
        };
        const response = await apiPostData('/campaigns/add-member', payload);
        if(response){
            await getData();
            await getContacts();
            modalMembers.value = false;
            moadlAddContact.value = false;
        }
    };

    const deleteContract = async (contact) => {
        const confirmResult = await Swal.fire({
            title: 'Are you sure?',
            text: `Delete contact ${contact.name} - ${contact.phone}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel'
        });

        if (confirmResult.isConfirmed) {
            // Call API to delete contact
            const response = await apiPostData('/campaigns/delete-contact', {
                contact_id: contact.odata
            });

            if (response) {
                await getContacts();
                await getData();
            }
        }
    };

    // Helper untuk cek apakah member sudah ada di campaign contacts
    function isMemberInCampaign(member) {
        if (!state.listContacts || !Array.isArray(state.listContacts)) return false;
        return state.listContacts.some(c => c.phone === member.phone);
    }

    const modalSendProgress  = ref(false);
    const isSending          = ref(false);
    const sendBatchKey       = ref('');
    const sendProgressData   = ref({}); // { [contactOdata]: { name, phone, status, error_message } }
    const sendSummary        = ref({ total: 0, sent: 0, failed: 0, sending: 0, queued: 0, done: 0, completed: false });
    const progressFilter     = ref('all');
    let   pollingTimer       = null;
    
    const progressPercent = computed(() => {
        if (!sendSummary.value.total) return 0;
        return Math.round((sendSummary.value.done / sendSummary.value.total) * 100);
    });
    
    const progressTabs = computed(() => [
        { key: 'all',     label: 'Semua',   count: sendSummary.value.total },
        { key: 'queued',  label: 'Antri',   count: sendSummary.value.queued },
        { key: 'sending', label: 'Proses',  count: sendSummary.value.sending },
        { key: 'sent',    label: 'Terkirim',count: sendSummary.value.sent },
        { key: 'failed',  label: 'Gagal',   count: sendSummary.value.failed },
    ]);
    
    const filteredProgressContacts = computed(() => {
        const contacts = Object.entries(sendProgressData.value).map(([odata, c]) => ({ odata, ...c }));
        if (progressFilter.value === 'all') return contacts;
        return contacts.filter(c => c.status === progressFilter.value);
    });
    
    // ─── Ganti seluruh fungsi kirimWA dengan ini ─────────────────────────────────
    
    const kirimWA = async (data) => {
        const contactCount = data.contacts?.length || 0;
        const sentCount    = data.contacts?.filter(c => c.status === 'sent').length || 0;
        const pendingCount = contactCount - sentCount;
    
        // Fetch template untuk detect variabel
        let extraVarCount = 0;
        if (!waTemplatesFetched.value) await fetchWaTemplates();
        const tpl = waTemplates.value.find(t => t.name === data.template_name);
        if (tpl?.components) {
            const body = tpl.components.find(c => c.type === 'BODY');
            if (body?.text) {
                const matches = body.text.match(/\{\{\d+\}\}/g) || [];
                const maxIdx  = Math.max(0, ...matches.map(m => parseInt(m.replace(/[{}]/g, ''))));
                extraVarCount = Math.max(0, maxIdx - 2);
            }
        }

        let hasCopyCode = false;
        if (tpl?.components) {
            const buttons = tpl.components.find(c => c.type === 'BUTTONS');
            if (buttons?.buttons) {
                hasCopyCode = buttons.buttons.some(b => b.type === 'COPY_CODE');
            }
        }
        
        // Build extra inputs HTML
        let extraInputsHtml = '';
        for (let i = 3; i <= 2 + extraVarCount; i++) {
            extraInputsHtml += `
                <div class="mb-2 text-start">
                    <label class="form-label small fw-bold">Variabel {{${i}}}:</label>
                    <input id="swal-var-${i}" class="swal2-input"
                        style="width:100%;margin:0;height:34px;font-size:13px"
                        placeholder="Nilai untuk {{${i}}}" />
                </div>`;
        }

        if (hasCopyCode) {
            extraInputsHtml += `
                <div class="mb-2 text-start">
                    <label class="form-label small fw-bold">Kode Kupon (Copy Code Button):</label>
                    <input id="swal-coupon-code" class="swal2-input"
                        style="width:100%;margin:0;height:34px;font-size:13px"
                        placeholder="Contoh: PROMO50" />
                </div>`;
        }
    
        const confirmResult = await Swal.fire({
            title: 'Kirim Campaign via WhatsApp?',
            html: `
                <div class="text-start small mb-3">
                    Template: <strong>${data.template_name || '-'}</strong><br>
                    Total kontak: <strong>${contactCount}</strong> &nbsp;|
                    Belum terkirim: <strong>${pendingCount}</strong> &nbsp;|
                    Sudah terkirim: <strong>${sentCount}</strong>
                </div>
                <div class="text-start small mb-2 text-muted">
                    <i class="fa fa-info-circle me-1"></i>
                    <strong>{{1}}</strong> = Nama Kontak &nbsp; <strong>{{2}}</strong> = Nomor HP
                </div>
                ${extraInputsHtml}
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            preConfirm: () => {
                const extraParams = [];
                for (let i = 3; i <= 2 + extraVarCount; i++) {
                    extraParams.push(document.getElementById(`swal-var-${i}`)?.value || '');
                }

                const couponCode = hasCopyCode 
                    ? (document.getElementById('swal-coupon-code')?.value || null)
                    : null;

                console.log('preConfirm result:', { extraParams, couponCode }); // ← tambah ini
                return { extraParams, couponCode };
            }
        });
    
        if (!confirmResult.isConfirmed) return;

        // ── Buka modal progress dulu sebelum API call ────────────────────────
        sendBatchKey.value     = '';
        sendProgressData.value = {};
        sendSummary.value      = { total: 0, sent: 0, failed: 0, sending: 0, queued: 0, done: 0, completed: false };
        progressFilter.value   = 'all';
        isSending.value        = true;
        Swal.close();
        await nextTick();
        modalSendProgress.value = true;

        // ── Dispatch ke backend ───────────────────────────────────────────────
        const response = await apiPostDataWithReturn('/campaigns/send-wa', {
            odata:        data.odata,
            extra_params: confirmResult.value?.extraParams || [],  // ✅ ambil dari .extraParams
            coupon_code:  confirmResult.value?.couponCode  || null, // ✅ ambil dari .couponCode
        }, {}, false);

        if (!response?.success) {
            modalSendProgress.value = false;
            isSending.value         = false;
            return;
        }

        const batchKey = response.data?.batch_key;
        if (!batchKey) {
            modalSendProgress.value = false;
            isSending.value         = false;
            Swal.fire('Info', response.data?.message || 'Campaign dikirim.', 'info');
            await getData();
            return;
        }

        sendBatchKey.value = batchKey;
        sendSummary.value  = { 
            total: response.data.total, 
            sent: 0, failed: 0, sending: 0, 
            queued: response.data.total, 
            done: 0, completed: false 
        };
        startProgressPolling(batchKey, data.odata);
    };
    
    // ─── Polling progress ──────────────────────────────────────────────────────
    
    const startProgressPolling = (batchKey, campaignOdata) => {
        stopProgressPolling();
    
        const poll = async () => {
            try {
                const res = await apiGetData('/campaigns/send-wa-progress', { batch_key: batchKey });
                if (!res) return;

                // apiGetData return response.data langsung — jadi contacts, summary, completed ada di root
                const contacts  = res?.contacts;
                const summary   = res?.summary;
                const completed = res?.completed;

                if (!contacts || !summary) return;

                sendProgressData.value = contacts;
                sendSummary.value      = { ...summary, completed };

                if (completed) {
                    isSending.value = false;
                    stopProgressPolling();
                    await getData();
                }
            } catch (e) {
                console.error('Polling error', e);
            }
        };
    
        poll(); // langsung poll sekali
        pollingTimer = setInterval(poll, 2500); // poll tiap 2.5 detik
    };
    
    const stopProgressPolling = () => {
        if (pollingTimer) {
            clearInterval(pollingTimer);
            pollingTimer = null;
        }
    };
    
    const onCloseProgressModal = () => {
        stopProgressPolling();
        modalSendProgress.value = false;
        isSending.value         = false;
    };

    const deleteData = async (data) => {
        const confirmResult = await Swal.fire({
            title: 'Hapus Campaign',
            text: `Apakah Anda yakin ingin menghapus campaign "${data.name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (confirmResult.isConfirmed) {
            loading.value = true;
            const response = await apiPostData('/campaigns/delete', { odata: data.odata });
            if (response) {
                await getData();
            }
            loading.value = false;
        }
    };

    const downloadFormat = () => {
        apiExportExcel('/campaigns/export-template');
    };

    onMounted(async() => {
        await getData();
    });

    onUnmounted(() => stopProgressPolling());

    watch(search, useDebounceFn(async () => {
        await getData();
    }, 500));

    watch(searchMembers, useDebounceFn(async () => {
        await getContacts();
    }, 500));
</script>

<style scoped>
    /* Ubah warna teks Properties menjadi hitam */
    .properties-title {
    color: #222 !important;
    }

    .avatar-uploader > .ant-upload {
        width: 128px;
        height: 128px;
    }
    .ant-upload-select-picture-card i {
        font-size: 32px;
        color: #999;
    }

    .ant-upload-select-picture-card .ant-upload-text {
        margin-top: 8px;
        color: #666;
    }

    .progress-summary .stat-card {
        border-radius: 8px;
        padding: 8px 4px;
        font-weight: 600;
    }
    .stat-card .stat-value {
        font-size: 1.5rem;
        line-height: 1.1;
    }
    .stat-card .stat-label {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: .03em;
        opacity: .75;
    }
    .stat-total   { background: #f0f0f0; color: #333; }
    .stat-queued  { background: #e8e8e8; color: #555; }
    .stat-sending { background: #e0f0ff; color: #0055cc; }
    .stat-sent    { background: #e6f9ec; color: #1a7a36; }
    .stat-failed  { background: #fff0f0; color: #c0392b; }
    
    .progress-contact-list::-webkit-scrollbar        { width: 5px; }
    .progress-contact-list::-webkit-scrollbar-track  { background: #f5f5f5; }
    .progress-contact-list::-webkit-scrollbar-thumb  { background: #ccc; border-radius: 4px; }
</style>