<template>
	<div>
		<div class="d-flex align-items-center mb-3">
			<div class="d-flex gap-2">
				<Button label="Tambah Facilities" icon="pi pi-plus" class="btn btn-dark" size="small" @click="add" />
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
				<table class="table table-sticky">
					<thead>
						<tr>
							<th class="text-center bg-dark text-nowrap sticky-col sticky-left-1 col-no">No</th>
							<th class="text-center bg-dark text-nowrap sticky-col sticky-left-2 col-action">Action</th>
							<th class="text-center bg-dark text-nowrap">Name</th>
							<th class="text-center bg-dark text-nowrap">Type</th>
							<th class="text-center bg-dark text-nowrap">Icon</th>
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
							<td class="text-center sticky-col sticky-left-1 col-no">{{ index + state.listData.from }}</td>
							<td class="text-center text-nowrap sticky-col sticky-left-2 col-action">
								<a-tooltip title="Edit Facility" placement="top">
									<a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
										<template #icon>
											<EditOutlined />
										</template>
									</a-button>
								</a-tooltip>
							</td>
							<td class="text-center text-nowrap">{{ data.name }}</td>
							<td class="text-center text-nowrap">{{ formatType(data.type) }}</td>
							<td class="text-center text-nowrap">
								<span class="d-inline-flex align-items-center gap-2">
									<i class="fa" :class="data.icon"></i>
									{{ data.icon }}
								</span>
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

		<a-drawer
			v-model:visible="modalAdd"
		   :closable="false" :mask-closable="true"
		   width="450"
			:title="action === 'add' ? 'Tambah Facilities' : 'Edit Facilities'"
		>
    
			<div class="mb-3 row">
				<label class="col-sm-3 col-form-label">Name</label>
				<div class="col-sm-9">
					<a-input v-model:value="state.form.name" placeholder="Masukan Name" />
				</div>
			</div>

			<div class="mb-3 row">
				<label class="col-sm-3 col-form-label">Type</label>
				<div class="col-sm-9">
					<a-select
                        v-model:value="state.form.type"
                        placeholder="Pilih Type"
                        style="width: 100%"
                        mode="multiple"
                        >
                        <a-select-option value="Property">Property</a-select-option>
                        <a-select-option value="Room">Room</a-select-option>
                    </a-select>
				</div>
			</div>

			<div class="mb-3 row">
				<label class="col-sm-3 col-form-label">Icon</label>
				<div class="col-sm-9">
					<a-select
						v-model:value="state.form.icon"
						placeholder="Pilih Icon (Font Awesome)"
						style="width: 100%"
						show-search
						allow-clear
					>
						<a-select-option v-for="item in iconOptions" :key="item.value" :value="item.value">
							<span class="d-inline-flex align-items-center gap-2">
									<i class="fa" :class="item.value"></i>
								{{ item.label }}
							</span>
						</a-select-option>
					</a-select>
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
            
				<ProgressBar mode="indeterminate" class="mt-3" style="height: 6px" v-if="loadingSubmit"></ProgressBar>
			</template>
    

		</a-drawer>
	</div>
</template>

<script setup>
	import { apiGetData, apiPostData, apiDeleteData, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
	import { reactive, onMounted, ref } from 'vue'; 
	import Button from 'primevue/button';
	import ProgressBar from 'primevue/progressbar';
	import { EditOutlined, DeleteOutlined } from '@ant-design/icons-vue';

	const action = ref(null);
	const modalAdd = ref(false);
	const pagging = ref(10);
	const search = ref('');

	const state = reactive({
		listData:{},
		form:{
			odata: '',
			name: '',
			type: [],
			icon: ''
		}
	});

const iconOptions = [
	// 🛏️ Kamar & Properti
	{ label: 'fa-bed', value: 'fa-bed' },
	{ label: 'fa-house', value: 'fa-house' },
	{ label: 'fa-building', value: 'fa-building' },
	{ label: 'fa-couch', value: 'fa-couch' },
	{ label: 'fa-door-open', value: 'fa-door-open' },
	{ label: 'fa-key', value: 'fa-key' },
	{ label: 'fa-lock', value: 'fa-lock' },

	// 🚿 Kamar Mandi
	{ label: 'fa-bath', value: 'fa-bath' },
	{ label: 'fa-shower', value: 'fa-shower' },
	{ label: 'fa-toilet', value: 'fa-toilet' },
	{ label: 'fa-soap', value: 'fa-soap' },

	// 📺 Elektronik & Internet
	{ label: 'fa-tv', value: 'fa-tv' },
	{ label: 'fa-wifi', value: 'fa-wifi' },
	{ label: 'fa-phone', value: 'fa-phone' },
	{ label: 'fa-plug', value: 'fa-plug' },

	// ❄️ Kenyamanan
	{ label: 'fa-snowflake', value: 'fa-snowflake' }, // AC
	{ label: 'fa-fan', value: 'fa-fan' },
	{ label: 'fa-temperature-high', value: 'fa-temperature-high' },
	{ label: 'fa-temperature-low', value: 'fa-temperature-low' },

	// 🍽️ Dapur & Makan
	{ label: 'fa-utensils', value: 'fa-utensils' },
	{ label: 'fa-mug-hot', value: 'fa-mug-hot' },
	{ label: 'fa-coffee', value: 'fa-coffee' },
	{ label: 'fa-fire-burner', value: 'fa-fire-burner' },

	// 🏊‍♂️ Fasilitas Umum
	{ label: 'fa-person-swimming', value: 'fa-person-swimming' }, // swimming pool
	{ label: 'fa-dumbbell', value: 'fa-dumbbell' },               // gym
	{ label: 'fa-spa', value: 'fa-spa' },
	{ label: 'fa-hot-tub-person', value: 'fa-hot-tub-person' },
	{ label: 'fa-umbrella-beach', value: 'fa-umbrella-beach' },

	// 🚗 Parkir & Akses
	{ label: 'fa-parking', value: 'fa-parking' },
	{ label: 'fa-car', value: 'fa-car' },
	{ label: 'fa-motorcycle', value: 'fa-motorcycle' },
	{ label: 'fa-elevator', value: 'fa-elevator' },
	{ label: 'fa-charging-station', value: 'fa-charging-station' },

	// 🔐 Keamanan
	{ label: 'fa-video', value: 'fa-video' }, // CCTV
	{ label: 'fa-shield-halved', value: 'fa-shield-halved' },
	{ label: 'fa-fire-extinguisher', value: 'fa-fire-extinguisher' },
	{ label: 'fa-bell', value: 'fa-bell' },

	// 🧹 Layanan
	{ label: 'fa-broom', value: 'fa-broom' },
	{ label: 'fa-shirt', value: 'fa-shirt' }, // laundry
	{ label: 'fa-concierge-bell', value: 'fa-concierge-bell' },
	{ label: 'fa-truck-fast', value: 'fa-truck-fast' },

	// 🌿 Outdoor & Lingkungan
	{ label: 'fa-tree', value: 'fa-tree' },
	{ label: 'fa-seedling', value: 'fa-seedling' },
	{ label: 'fa-sun', value: 'fa-sun' },
	{ label: 'fa-water', value: 'fa-water' },

	// ⚡ Utilitas
	{ label: 'fa-bolt', value: 'fa-bolt' },
	{ label: 'fa-droplet', value: 'fa-droplet' },
	{ label: 'fa-gas-pump', value: 'fa-gas-pump' },
	{ label: 'fa-recycle', value: 'fa-recycle' }
];




	const getData = async (page = state.listData.current_page) => {
		loading.value = true;
		const payload = {
			page: page,
			search: search.value,
			pagging: pagging.value
		};
		const response = await apiGetData('/facilities/index', payload);
		state.listData = response.data
		loading.value = false;
	};

	const add = () => {
		state.form = {
			odata: '',
			name: '',
			type: [],
			icon: ''
		};

		action.value = 'add';
		modalAdd.value = true;
	};

	const view = (item) => {
		state.form = {
			odata: item.odata,
			name: item.name,
			type: normalizeType(item.type),
			icon: item.icon
		};

		action.value = 'edit';
		modalAdd.value = true;
	};

	const save = async () => {
		loadingSubmit.value = true;
		const payload = {
			odata : state.form.odata,
			name: state.form.name,
			type: state.form.type,
			icon: state.form.icon
		};

		let response;
		if (action.value === 'add') {
			response = await apiPostData('/facilities/store', payload);
		}else {
			response = await apiPostData('/facilities/update', payload);
		}

		if (response) {
			loadingSubmit.value = false;
			modalAdd.value = false;
			getData();
		}else{
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
				const response = await apiDeleteData('/facilities/index', {
					odata: item.odata
				});

				if (response) {
					processing.value = false;
					getData();
				}else{
					processing.value = false;
				}
			}
		});
	};

	const handlePageChange = async (page) => {
		await getData(page);
	};

	const normalizeType = (value) => {
		if (Array.isArray(value)) {
			return value;
		}
		if (!value) {
			return [];
		}
		try {
			const parsed = JSON.parse(value);
			return Array.isArray(parsed) ? parsed : [value];
		} catch (e) {
			return [value];
		}
	};

	const formatType = (value) => {
		const list = normalizeType(value);
		return list.length ? list.join(', ') : '-';
	};

	onMounted(async () => {
		await getData();
	});

</script>
