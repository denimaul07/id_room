
<template>
    <div>
        <Button class="btn btn-primary mb-3" @click="add">Add Process Work</Button>
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">Action</th>
                <th class="text-center">Title</th>
                <th class="text-center">Desc</th>
                <th class="text-center">Icon</th>
                <th class="text-center">Status</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="state.listData.length === 0">
                <td colspan="6" class="text-center">No Process Work Added</td>
            </tr>
            <tr v-for="(item, index) in state.listData" :key="index" v-else>
                <td class="text-center">{{ index + 1 }}</td>
                <td class="text-center text-nowrap">
                <a-tooltip title="View / Edit">
                    <a-button type="primary" size="small" @click="view(item)" class="bg-dark">
                    <template #icon>
                        <EyeOutlined />
                    </template>
                    </a-button>
                </a-tooltip>
                <a-tooltip title="Delete">
                    <a-button type="primary" size="small" class="bg-danger ms-2" @click="deleteItem(item)">
                    <template #icon>
                        <DeleteOutlined />
                    </template>
                    </a-button>
                </a-tooltip>
                </td>
                <td class="text-center text-nowrap">{{ item.title }}</td>
                <td class="text-center">{{ item.desc }}</td>
                <td class="text-center text-nowrap"><i :class="item.icon"></i> {{ item.icon }}</td>
                <td class="text-center text-nowrap">
                <span v-if="item.isActive === 0" class="badge bg-success">Active</span>
                <span v-else class="badge bg-danger">Inactive</span>
                </td>
            </tr>
            </tbody>
        </table>
        </div>

        <a-drawer v-model:visible="modalAdd" :width="400" :closable="false" :mask-closable="true" :title="action === 'add' ? 'Add Process Work' : 'Edit Process Work'">
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Title</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" v-model="state.form.title" placeholder="Masukan Title"/>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Desc</label>
                <div class="col-sm-9">
                <textarea class="form-control" v-model="state.form.desc" placeholder="Masukan Description"></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">Icon</label>
                <div class="col-sm-9">
                <a-select
                    v-model:value="state.form.icon"
                    style="width: 100%;"
                    show-search
                    placeholder="Pilih Icon Process Work"
                    size="large"
                    option-filter-prop="children"
                >
                    <a-select-option disabled value="">Pilih Icon Process Work</a-select-option>
                        <!-- PROPERTI -->
                        <a-select-opt-group label="🏠 Properti">
                            <a-select-option value="fa fa-home">🏠 fa fa-home (Rumah)</a-select-option>
                            <a-select-option value="fa fa-building">🏢 fa fa-building (Gedung / Apartemen)</a-select-option>
                            <a-select-option value="fa fa-city">🏙️ fa fa-city (Kawasan Properti)</a-select-option>
                            <a-select-option value="fa fa-warehouse">🏬 fa fa-warehouse (Gudang)</a-select-option>
                            <a-select-option value="fa fa-store">🏪 fa fa-store (Ruko)</a-select-option>
                            <a-select-option value="fa fa-hotel">🏨 fa fa-hotel (Hotel)</a-select-option>
                        </a-select-opt-group>

                        <!-- SEWA & JUAL BELI -->
                        <a-select-opt-group label="🔑 Sewa & Jual Beli">
                            <a-select-option value="fa fa-key">🔑 fa fa-key (Sewa Properti)</a-select-option>
                            <a-select-option value="fa fa-handshake">🤝 fa fa-handshake (Jual Beli)</a-select-option>
                            <a-select-option value="fa fa-file-contract">📄 fa fa-file-contract (Kontrak)</a-select-option>
                            <a-select-option value="fa fa-file-signature">✍️ fa fa-file-signature (Perjanjian)</a-select-option>
                            <a-select-option value="fa fa-search-location">🔍 fa fa-search-location (Cari Properti)</a-select-option>
                            <a-select-option value="fa fa-map-marker-alt">📍 fa fa-map-marker-alt (Lokasi)</a-select-option>
                        </a-select-opt-group>

                        <!-- INTERIOR -->
                        <a-select-opt-group label="🛋️ Interior">
                            <a-select-option value="fa fa-couch">🛋️ fa fa-couch (Interior Ruang)</a-select-option>
                            <a-select-option value="fa fa-bed">🛏️ fa fa-bed (Interior Kamar)</a-select-option>
                            <a-select-option value="fa fa-chair">🪑 fa fa-chair (Furniture)</a-select-option>
                            <a-select-option value="fa fa-utensils">🍽️ fa fa-utensils (Kitchen Set)</a-select-option>
                            <a-select-option value="fa fa-door-open">🚪 fa fa-door-open (Pintu)</a-select-option>
                            <a-select-option value="fa fa-lightbulb">💡 fa fa-lightbulb (Lighting)</a-select-option>
                            <a-select-option value="fa fa-border-all">🧱 fa fa-border-all (Partisi / Dinding)</a-select-option>
                        </a-select-opt-group>

                        <!-- DESAIN & ARSITEKTUR -->
                        <a-select-opt-group label="📐 Desain & Arsitektur">
                            <a-select-option value="fa fa-ruler-combined">📐 fa fa-ruler-combined (Desain Interior)</a-select-option>
                            <a-select-option value="fa fa-drafting-compass">🧭 fa fa-drafting-compass (Arsitektur)</a-select-option>
                            <a-select-option value="fa fa-pencil-ruler">✏️ fa fa-pencil-ruler (Perencanaan)</a-select-option>
                            <a-select-option value="fa fa-layer-group">🧩 fa fa-layer-group (Layout Ruang)</a-select-option>
                        </a-select-opt-group>

                        <!-- RENOVASI & KONSTRUKSI -->
                        <a-select-opt-group label="🔨 Renovasi & Konstruksi">
                            <a-select-option value="fa fa-hammer">🔨 fa fa-hammer (Renovasi)</a-select-option>
                            <a-select-option value="fa fa-tools">🛠️ fa fa-tools (Pengerjaan)</a-select-option>
                            <a-select-option value="fa fa-hard-hat">⛑️ fa fa-hard-hat (Proyek)</a-select-option>
                            <a-select-option value="fa fa-cubes">🧱 fa fa-cubes (Material Bangunan)</a-select-option>
                            <a-select-option value="fa fa-truck-loading">🚚 fa fa-truck-loading (Logistik Material)</a-select-option>
                        </a-select-opt-group>

                        <!-- ME & FASILITAS -->
                        <a-select-opt-group label="⚡ ME & Fasilitas">
                            <a-select-option value="fa fa-plug">🔌 fa fa-plug (Instalasi Listrik)</a-select-option>
                            <a-select-option value="fa fa-bolt">⚡ fa fa-bolt (Electrical)</a-select-option>
                            <a-select-option value="fa fa-fan">🌀 fa fa-fan (AC / HVAC)</a-select-option>
                            <a-select-option value="fa fa-water">💧 fa fa-water (Plumbing)</a-select-option>
                            <a-select-option value="fa fa-fire-extinguisher">🧯 fa fa-fire-extinguisher (Fire System)</a-select-option>
                            <a-select-option value="fa fa-video">📹 fa fa-video (CCTV)</a-select-option>
                            <a-select-option value="fa fa-shield-alt">🛡️ fa fa-shield-alt (Keamanan)</a-select-option>
                        </a-select-opt-group>

                        <!-- BISNIS & LAYANAN -->
                        <a-select-opt-group label="💼 Bisnis & Layanan">
                            <a-select-option value="fa fa-briefcase">💼 fa fa-briefcase (Jasa Profesional)</a-select-option>
                            <a-select-option value="fa fa-user-tie">🧑‍💼 fa fa-user-tie (Marketing)</a-select-option>
                            <a-select-option value="fa fa-users">👥 fa fa-users (Klien)</a-select-option>
                            <a-select-option value="fa fa-calculator">🧮 fa fa-calculator (Estimasi Harga)</a-select-option>
                            <a-select-option value="fa fa-wallet">💰 fa fa-wallet (Pembayaran)</a-select-option>
                            <a-select-option value="fa fa-credit-card">💳 fa fa-credit-card (Transaksi)</a-select-option>
                            <a-select-option value="fa fa-calendar-check">📅 fa fa-calendar-check (Booking / Survey)</a-select-option>
                            <a-select-option value="fa fa-headset">🎧 fa fa-headset (Customer Service)</a-select-option>
                        </a-select-opt-group>
                    </a-select>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-3 col-form-label">isActive</label>
                <div class="col-sm-9">
                <select v-model="state.form.isActive" class="form-select" >
                    <option :value="0">Active</option>
                    <option :value="1">Inactive</option>
                </select>
                </div>
            </div>
            <template #footer>
                <button type="button" :disabled="loadingSubmit" class="btn btn-primary ms-2" @click="action === 'add' ? save() : update()">
                <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
                    <span class="sr-only">Loading...</span>
                </div>
                <i class="fa fa-check me-2" aria-hidden="true" v-else></i>
                {{ action === 'add' ? 'Save' : 'Update' }}
                </button>
            </template>
        </a-drawer>
    </div>
</template>


<script setup>
import { apiGetData, apiPostData, apiPutData, apiDeleteData, loadingSubmit, processing, loadingButton, Swal, waitingicon, loading, pesan } from '@/store/action';
import { reactive, onMounted, ref } from 'vue';
import Button from 'primevue/button';
import { EyeOutlined, DeleteOutlined } from '@ant-design/icons-vue';
const action = ref(null);
const modalAdd = ref(false);
const state = reactive({
  listData: [],
  form: {
    odata: '',
    odata_setting: '',
    title: '',
    desc: '',
    icon: '',
    isActive: 0
  }
});

const getData = async () => {
  loading.value = true;
  const response = await apiGetData('/setting/process', {});
  state.listData = response.data;
  loading.value = false;
};

const add = () => {
  state.form = {
    odata: '',
    odata_setting: '',
    title: '',
    desc: '',
    icon: '',
    isActive: 0
  };
  action.value = 'add';
  modalAdd.value = true;
};

const view = (item) => {
  state.form = {
    odata: item.odata,
    title: item.title,
    desc: item.desc,
    icon: item.icon,
    isActive: item.isActive
  };
  action.value = 'edit';
  modalAdd.value = true;
};

const save = async () => {
  loadingSubmit.value = true;
  const payload = {
    odata_setting: 'd73e5120-5b9c-44c1-9154-b718794e8fc3',
    title: state.form.title,
    desc: state.form.desc,
    icon: state.form.icon,
    isActive: state.form.isActive
  };
  const response = await apiPostData('/setting/process', payload);
  if (response) {
    loadingSubmit.value = false;
    modalAdd.value = false;
    getData();
  } else {
    loadingSubmit.value = false;
  }
};

const update = async () => {
  loadingSubmit.value = true;
  const payload = {
    odata: state.form.odata,
    title: state.form.title,
    desc: state.form.desc,
    icon: state.form.icon,
    isActive: state.form.isActive,
    odata_setting: 'd73e5120-5b9c-44c1-9154-b718794e8fc3'
  };
  const response = await apiPutData('/setting/process', payload);
  if (response) {
    loadingSubmit.value = false;
    modalAdd.value = false;
    getData();
  } else {
    loadingSubmit.value = false;
  }
};

const deleteItem = async (item) => {
  Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to revert this!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Yes, delete it!'
  }).then(async (result) => {
    if (result.isConfirmed) {
      processing.value = true;
      pesan.value = 'Mohon tunggu sedang proses...';
      const response = await apiDeleteData('/setting/process', {
        odata: item.odata
      });
      if (response) {
        processing.value = false;
        getData();
      } else {
        processing.value = false;
      }
    }
  });
};

onMounted(async () => {
  await getData();
});
</script>
