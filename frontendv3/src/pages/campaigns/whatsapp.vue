<template>
    <div>
        <div class="container-fluid pb-5">
            <div class="row">
                <Breadcrumbs main="Campaigns" title="WhatsApp Templates" />

                <div class="card ms-2">
                    <div class="card-body">
                        <a-tabs v-model:activeKey="activeTab">

                            <!-- ────────────── SETTINGS TAB ────────────── -->
                            <a-tab-pane key="settings" tab="⚙️ WA Settings">
                                <div class="row mt-3" style="max-width:600px">
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label fw-bold">Phone Number ID</label>
                                        <div class="col-sm-8">
                                            <a-input v-model:value="settings.phone_number_id" placeholder="Masukkan Phone Number ID dari Meta" />
                                            <small class="text-muted">WhatsApp Business → Phone Number ID</small>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label fw-bold">WABA ID</label>
                                        <div class="col-sm-8">
                                            <a-input v-model:value="settings.waba_id" placeholder="Masukkan WhatsApp Business Account ID" />
                                            <small class="text-muted">WhatsApp Business Account → Business Account ID</small>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label fw-bold">Access Token</label>
                                        <div class="col-sm-8">
                                            <a-textarea v-model:value="settings.access_token" placeholder="Bearer token dari Meta Developer" :rows="4" />
                                            <small class="text-muted">Meta Developer → System User → Generate Token (permanent token)</small>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label fw-bold">Media IMAGE</label>
                                        <div class="col-sm-8">
                                            <div v-if="settings.media_url" class="mb-2 d-flex align-items-center gap-2">
                                                <img :src="settings.media_url" style="height:56px; width:56px; object-fit:cover; border-radius:6px; border:1px solid #ddd" />
                                                <small class="text-muted font-monospace" style="word-break:break-all">{{ settings.media_url }}</small>
                                            </div>
                                            <input type="file" class="form-control" accept="image/*" @change="e => mediaFiles.image = e.target.files[0]" />
                                            <small class="text-muted">Default untuk header template IMAGE (JPG/PNG/WEBP). Harus dapat diakses publik.</small>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label fw-bold">Media VIDEO</label>
                                        <div class="col-sm-8">
                                            <div v-if="settings.media_video_url" class="mb-2 d-flex align-items-center gap-2">
                                                <div class="bg-dark text-white rounded d-flex align-items-center justify-content-center" style="height:56px;width:56px;font-size:20px">▶</div>
                                                <small class="text-muted font-monospace" style="word-break:break-all">{{ settings.media_video_url }}</small>
                                            </div>
                                            <input type="file" class="form-control" accept="video/mp4,video/3gpp" @change="e => mediaFiles.video = e.target.files[0]" />
                                            <small class="text-muted">Default untuk header template VIDEO (MP4/3GP). Harus dapat diakses publik.</small>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <label class="col-sm-4 col-form-label fw-bold">Media DOCUMENT</label>
                                        <div class="col-sm-8">
                                            <div v-if="settings.media_document_url" class="mb-2 d-flex align-items-center gap-2">
                                                <div class="bg-warning rounded d-flex align-items-center justify-content-center" style="height:56px;width:56px;font-size:20px">📄</div>
                                                <small class="text-muted font-monospace" style="word-break:break-all">{{ settings.media_document_url }}</small>
                                            </div>
                                            <input type="file" class="form-control" accept="application/pdf" @change="e => mediaFiles.document = e.target.files[0]" />
                                            <small class="text-muted">Default untuk header template DOCUMENT (PDF). Harus dapat diakses publik.</small>
                                        </div>
                                    </div>
                                    <div class="mb-3 row">
                                        <div class="col-sm-8 offset-sm-4">
                                            <button class="btn btn-dark" @click="saveSettings" :disabled="loadingSubmit">
                                                <div class="spinner-border spinner-border-sm me-2" v-if="loadingSubmit"></div>
                                                <i class="fa fa-save me-2" v-else></i>Simpan Settings
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </a-tab-pane>

                            <!-- ────────────── TEMPLATES TAB ────────────── -->
                            <a-tab-pane key="templates" tab="📋 Templates">
                                <div class="d-flex align-items-center mb-3 mt-3 gap-2">
                                    <Button label="Buat Template" icon="pi pi-plus" class="btn btn-dark" size="small" @click="openCreateDrawer" />
                                    <button class="btn btn-outline-secondary btn-sm" @click="fetchTemplates" :disabled="loadingTemplates">
                                        <i class="fa fa-refresh me-1" :class="{ 'fa-spin': loadingTemplates }"></i>Refresh
                                    </button>
                                    <a-select v-model:value="filterStatus" style="width:160px" @change="fetchTemplates">
                                        <a-select-option value="">Semua Status</a-select-option>
                                        <a-select-option value="APPROVED">Approved</a-select-option>
                                        <a-select-option value="PENDING">Pending</a-select-option>
                                        <a-select-option value="REJECTED">Rejected</a-select-option>
                                    </a-select>
                                </div>

                                <div v-if="loadingTemplates" class="text-center py-5">
                                    <a-skeleton active />
                                </div>

                                <div v-else-if="!templates.length" class="text-center py-5">
                                    <a-empty description="Belum ada template. Buat template baru atau periksa WA Settings." />
                                </div>

                                <div v-else class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th class="bg-dark text-white text-nowrap">No</th>
                                                <th class="bg-dark text-white text-nowrap">Name</th>
                                                <th class="bg-dark text-white text-nowrap">Category</th>
                                                <th class="bg-dark text-white text-nowrap">Language</th>
                                                <th class="bg-dark text-white text-nowrap">Status</th>
                                                <th class="bg-dark text-white text-nowrap">Preview</th>
                                                <th class="bg-dark text-white text-nowrap">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(tpl, idx) in templates" :key="tpl.id">
                                                <td class="text-center">{{ idx + 1 }}</td>
                                                <td><span class="badge bg-secondary font-monospace">{{ tpl.name }}</span></td>
                                                <td>{{ tpl.category }}</td>
                                                <td>{{ tpl.language }}</td>
                                                <td>
                                                    <span class="badge" :class="{
                                                        'bg-success': tpl.status === 'APPROVED',
                                                        'bg-warning text-dark': tpl.status === 'PENDING',
                                                        'bg-danger': tpl.status === 'REJECTED',
                                                    }">{{ tpl.status }}</span>
                                                </td>
                                                <td style="max-width:320px">
                                                    <div v-for="comp in tpl.components" :key="comp.type" class="mb-1">
                                                        <small class="text-muted fw-bold">{{ comp.type }}:</small>
                                                        <small class="ms-1">{{ comp.text || (comp.format === 'IMAGE' ? '[Image]' : '') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a-tooltip title="Hapus Template">
                                                        <a-button type="primary" danger size="small" @click="deleteTemplate(tpl)">
                                                            <template #icon><DeleteOutlined /></template>
                                                        </a-button>
                                                    </a-tooltip>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </a-tab-pane>

                        </a-tabs>
                    </div>
                </div>
            </div>
        </div>

        <!-- ────────────── CREATE TEMPLATE DRAWER ────────────── -->
        <a-drawer
            v-model:visible="drawerCreate"
            title="Buat WA Template Baru"
            placement="right"
            width="640px"
            :body-style="{ paddingBottom: '80px' }"
        >
            <div class="mb-3">
                <label class="form-label fw-bold">Template Name <span class="text-danger">*</span></label>
                <a-input v-model:value="form.name" placeholder="contoh: promosi_apartemen (huruf kecil, underscore, angka)" />
                <small class="text-muted">Hanya huruf kecil, angka, dan underscore. Tidak bisa diubah setelah dibuat.</small>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                    <a-select v-model:value="form.category" style="width:100%" placeholder="Pilih kategori">
                        <a-select-option value="MARKETING">MARKETING</a-select-option>
                        <a-select-option value="UTILITY">UTILITY</a-select-option>
                        <a-select-option value="AUTHENTICATION">AUTHENTICATION</a-select-option>
                    </a-select>
                </div>
                <div class="col-6">
                    <label class="form-label fw-bold">Language <span class="text-danger">*</span></label>
                    <a-select v-model:value="form.language" style="width:100%" placeholder="Pilih bahasa">
                        <a-select-option value="id">Bahasa Indonesia (id)</a-select-option>
                        <a-select-option value="en_US">English (en_US)</a-select-option>
                        <a-select-option value="en">English (en)</a-select-option>
                    </a-select>
                </div>
            </div>

            <a-divider>Komponen Template</a-divider>

            <!-- HEADER -->
            <div class="mb-3 border rounded p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold">HEADER</span>
                    <a-switch v-model:checked="form.hasHeader" size="small" />
                </div>
                <div v-if="form.hasHeader">
                    <a-select v-model:value="form.headerFormat" style="width:100%" class="mb-2" @change="onHeaderFormatChange">
                        <a-select-option value="TEXT">Teks</a-select-option>
                        <a-select-option value="IMAGE">Gambar (IMAGE)</a-select-option>
                        <a-select-option value="VIDEO">Video (VIDEO)</a-select-option>
                        <a-select-option value="DOCUMENT">Dokumen / PDF (DOCUMENT)</a-select-option>
                    </a-select>
                    <a-input v-if="form.headerFormat === 'TEXT'" v-model:value="form.headerText" placeholder="Teks header (maks. 60 karakter)" :maxlength="60" show-count />
                    <div v-else>
                        <a-select v-model:value="form.headerFormat" style="width:100%" class="mb-2">
                            <a-select-option value="IMAGE">Gambar (IMAGE)</a-select-option>
                            <a-select-option value="VIDEO">Video (VIDEO)</a-select-option>
                            <a-select-option value="DOCUMENT">Dokumen / PDF (DOCUMENT)</a-select-option>
                        </a-select>
                        <div v-if="defaultMediaForFormat" class="mb-2 p-2 bg-light border rounded d-flex align-items-center gap-2">
                            <img v-if="form.headerFormat === 'IMAGE'" :src="defaultMediaForFormat" style="height:48px; width:48px; object-fit:cover; border-radius:4px" />
                            <div v-else-if="form.headerFormat === 'VIDEO'" class="bg-dark text-white rounded d-flex align-items-center justify-content-center" style="height:48px;width:48px;font-size:18px">▶</div>
                            <div v-else class="bg-warning rounded d-flex align-items-center justify-content-center" style="height:48px;width:48px;font-size:18px">📄</div>
                            <div>
                                <div class="small fw-bold text-success">Menggunakan media dari WA Settings</div>
                                <div class="small text-muted font-monospace" style="word-break:break-all">{{ defaultMediaForFormat }}</div>
                            </div>
                        </div>
                        <a-input
                            v-model:value="form.headerExampleUrl"
                            :placeholder="form.headerFormat === 'DOCUMENT' ? 'Override URL PDF (kosongkan untuk pakai media dari Settings)' : 'Override URL (kosongkan untuk pakai media dari Settings)'"
                        />
                        <small class="text-muted">
                            URL contoh ini hanya untuk review Meta, bukan yang dikirim ke user.
                            <span v-if="form.headerFormat === 'DOCUMENT'"> PDF harus bisa diakses publik.</span>
                        </small>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="mb-3 border rounded p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold">BODY <span class="text-danger">*</span></span>
                </div>
                <a-textarea v-model:value="form.bodyText" :rows="5" placeholder="Isi pesan. Gunakan {{1}}, {{2}} untuk variabel dinamis." :maxlength="1024" show-count />
                <small class="text-muted">Contoh: Halo {{1}}, promo spesial apartemen untuk Anda!</small>
            </div>

            <!-- FOOTER -->
            <div class="mb-3 border rounded p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold">FOOTER</span>
                    <a-switch v-model:checked="form.hasFooter" size="small" />
                </div>
                <a-input v-if="form.hasFooter" v-model:value="form.footerText" placeholder="Teks footer (maks. 60 karakter)" :maxlength="60" show-count />
            </div>

            <!-- BUTTONS -->
            <div class="mb-3 border rounded p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="fw-bold">BUTTONS</span>
                    <button class="btn btn-sm btn-outline-dark" @click="addButton" :disabled="form.buttons.length >= 3">
                        <i class="fa fa-plus me-1"></i>Tambah Button
                    </button>
                </div>
                <div v-for="(btn, i) in form.buttons" :key="i" class="d-flex gap-2 align-items-center mb-2">
                    <a-select v-model:value="btn.type" style="width:140px">
                        <a-select-option value="QUICK_REPLY">Quick Reply</a-select-option>
                        <a-select-option value="URL">URL</a-select-option>
                        <a-select-option value="PHONE_NUMBER">Phone</a-select-option>
                    </a-select>
                    <a-input v-model:value="btn.text" placeholder="Label button" style="flex:1" />
                    <a-input v-if="btn.type === 'URL'" v-model:value="btn.url" placeholder="https://..." style="flex:1" />
                    <a-input v-if="btn.type === 'PHONE_NUMBER'" v-model:value="btn.phone_number" placeholder="+6281xxxx" style="flex:1" />
                    <a-button danger size="small" @click="removeButton(i)"><template #icon><DeleteOutlined /></template></a-button>
                </div>
                <small class="text-muted" v-if="!form.buttons.length">Opsional — maks. 3 buttons.</small>
            </div>

            <!-- PREVIEW -->
            <a-divider>Preview</a-divider>
            <div class="border rounded p-3 bg-light" style="max-width:340px; font-family:sans-serif; font-size:13px">
                <div v-if="form.hasHeader && form.headerFormat === 'TEXT'" class="fw-bold mb-1">{{ form.headerText || '(Header)' }}</div>
                <div v-if="form.hasHeader && form.headerFormat === 'IMAGE'" class="mb-2 text-center bg-secondary text-white rounded p-3 small">[Image Header]</div>
                <div v-if="form.hasHeader && form.headerFormat === 'VIDEO'" class="mb-2 text-center bg-dark text-white rounded p-3 small">[Video Header]</div>
                <div v-if="form.hasHeader && form.headerFormat === 'DOCUMENT'" class="mb-2 text-center bg-warning rounded p-3 small">[PDF/Document Header]</div>
                <div style="white-space:pre-wrap">{{ form.bodyText || '(Body teks...)' }}</div>
                <div v-if="form.hasFooter" class="text-muted mt-1 small">{{ form.footerText }}</div>
                <div v-if="form.buttons.length" class="mt-2 d-flex gap-1 flex-wrap">
                    <span v-for="btn in form.buttons" :key="btn.text" class="badge bg-primary">{{ btn.text || '(button)' }}</span>
                </div>
            </div>

            <template #footer>
                <div class="d-flex gap-2">
                    <button class="btn btn-dark" @click="submitTemplate" :disabled="loadingSubmit">
                        <div class="spinner-border spinner-border-sm me-2" v-if="loadingSubmit"></div>
                        <i class="fa fa-paper-plane me-2" v-else></i>Kirim ke Meta
                    </button>
                    <button class="btn btn-outline-secondary" @click="drawerCreate = false">Batal</button>
                </div>
                <ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
            </template>
        </a-drawer>
    </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { apiGetData, apiPostData, loadingSubmit, Swal } from '@/store/action'
import { DeleteOutlined } from '@ant-design/icons-vue'
import Button from 'primevue/button'
import ProgressBar from 'primevue/progressbar'

const activeTab       = ref('settings')
const loadingTemplates = ref(false)
const drawerCreate    = ref(false)
const filterStatus    = ref('')
const templates       = ref([])

const settings = reactive({
    phone_number_id: '',
    waba_id: '',
    access_token: '',
    media_url: '',
    media_video_url: '',
    media_document_url: '',
})
const mediaFiles = reactive({ image: null, video: null, document: null })

const form = reactive({
    name:             '',
    category:         'MARKETING',
    language:         'id',
    hasHeader:        false,
    headerFormat:     'TEXT',
    headerText:       '',
    headerExampleUrl: '',
    bodyText:         '',
    hasFooter:        false,
    footerText:       '',
    buttons:          [],
})

// ─── SETTINGS ────────────────────────────────────────────────────────────────

const fetchSettings = async () => {
    const res = await apiGetData('/whatsapp/settings')
    if (res?.data) {
        settings.phone_number_id    = res.data.phone_number_id    || ''
        settings.waba_id            = res.data.waba_id            || ''
        settings.access_token       = res.data.access_token       || ''
        settings.media_url          = res.data.media_url          || ''
        settings.media_video_url    = res.data.media_video_url    || ''
        settings.media_document_url = res.data.media_document_url || ''
    }
}

const saveSettings = async () => {
    loadingSubmit.value = true
    const formData = new FormData()
    formData.append('phone_number_id', settings.phone_number_id)
    formData.append('waba_id', settings.waba_id)
    formData.append('access_token', settings.access_token)
    if (mediaFiles.image)    formData.append('media_image',    mediaFiles.image)
    if (mediaFiles.video)    formData.append('media_video',    mediaFiles.video)
    if (mediaFiles.document) formData.append('media_document', mediaFiles.document)
    const res = await apiPostData('/whatsapp/settings', formData)
    loadingSubmit.value = false
    if (res) {
        if (res.data?.media_url)          settings.media_url          = res.data.media_url
        if (res.data?.media_video_url)    settings.media_video_url    = res.data.media_video_url
        if (res.data?.media_document_url) settings.media_document_url = res.data.media_document_url
        mediaFiles.image = mediaFiles.video = mediaFiles.document = null
        Swal.fire('Berhasil', 'WA Settings berhasil disimpan', 'success')
    }
}

// ─── TEMPLATES ───────────────────────────────────────────────────────────────

const fetchTemplates = async () => {
    loadingTemplates.value = true
    const params = filterStatus.value ? { status: filterStatus.value } : {}
    const res = await apiGetData('/whatsapp/templates', params)
    templates.value = res?.data || []
    loadingTemplates.value = false
}

const openCreateDrawer = () => {
    Object.assign(form, {
        name: '', category: 'MARKETING', language: 'id',
        hasHeader: false, headerFormat: 'TEXT', headerText: '', headerExampleUrl: '',
        bodyText: '', hasFooter: false, footerText: '', buttons: [],
    })
    drawerCreate.value = true
}

// Media default dari Settings sesuai format header yang dipilih
const defaultMediaForFormat = computed(() => {
    if (form.headerFormat === 'IMAGE')    return settings.media_url
    if (form.headerFormat === 'VIDEO')    return settings.media_video_url
    if (form.headerFormat === 'DOCUMENT') return settings.media_document_url
    return ''
})

// Saat format header berubah ke media, kosongkan override URL (pakai default dari settings)
const onHeaderFormatChange = (val) => {
    if (['IMAGE', 'VIDEO', 'DOCUMENT'].includes(val)) {
        form.headerExampleUrl = ''
    }
}

const addButton = () => {
    form.buttons.push({ type: 'QUICK_REPLY', text: '', url: '', phone_number: '' })
}
const removeButton = (i) => form.buttons.splice(i, 1)

const buildComponents = () => {
    const components = []

    if (form.hasHeader) {
        const header = { type: 'HEADER', format: form.headerFormat }
        if (form.headerFormat === 'TEXT') {
            header.text = form.headerText
        } else if (['IMAGE', 'VIDEO', 'DOCUMENT'].includes(form.headerFormat)) {
            const mediaUrl = form.headerExampleUrl || defaultMediaForFormat.value
            if (mediaUrl) {
                header.example = { header_handle: [mediaUrl] }
            }
        }
        components.push(header)
    }

    if (form.bodyText) {
        const body = { type: 'BODY', text: form.bodyText }
        // Hitung variable count dari {{n}}
        const vars = [...form.bodyText.matchAll(/{{(\d+)}}/g)]
        if (vars.length) {
            body.example = {
                body_text: [vars.map(v => `contoh_variabel_${v[1]}`)]
            }
        }
        components.push(body)
    }

    if (form.hasFooter && form.footerText) {
        components.push({ type: 'FOOTER', text: form.footerText })
    }
    
    if (form.buttons.length) {
        components.push({
            type: 'BUTTONS',
            buttons: form.buttons.map((btn, idx) => {
                const b = { type: btn.type, text: btn.text }

                if (btn.type === 'URL') {
                    b.url = btn.url
                    // ✅ Jika URL mengandung {{1}}, wajib sertakan example
                    if (btn.url && btn.url.includes('{{')) {
                        b.example = [btn.url.replace(/\{\{\d+\}\}/g, 'https://idroom.id/verified/contoh-token-123')]
                    }
                }

                if (btn.type === 'PHONE_NUMBER') b.phone_number = btn.phone_number

                return b
            })
        })
    }

    return components
}

const submitTemplate = async () => {
    if (!form.name || !form.bodyText) {
        Swal.fire('Validasi', 'Name dan Body wajib diisi', 'warning')
        return
    }

    loadingSubmit.value = true
    const payload = {
        name:       form.name,
        category:   form.category,
        language:   form.language,
        components: buildComponents(),
    }

    const res = await apiPostData('/whatsapp/templates', payload)
    loadingSubmit.value = false

    if (res) {
    
        drawerCreate.value = false
        await fetchTemplates()
    } else if (res?.error) {
        loadingSubmit.value = false
    }
}

const deleteTemplate = async (tpl) => {
    Swal.fire({
        title: 'Hapus Template',
        text: `Apakah Anda yakin ingin menghapus template "${tpl.name}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
    }).then(async (result) => {
        if (result.isConfirmed) {
            const res = await apiPostData('/whatsapp/templates/delete', { name: tpl.name })
            if (res) {
                Swal.fire('Terhapus', 'Template berhasil dihapus', 'success')
                await fetchTemplates()
            }
        }
    })
}

onMounted(async () => {
    await fetchSettings()
    await fetchTemplates()
})
</script>
