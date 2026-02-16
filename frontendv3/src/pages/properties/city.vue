<template>
	<div>
		<div class="d-flex align-items-center mb-3">
			<div class="ms-auto">
				<a-input-search v-model:value="search" placeholder="Cari Data" style="width: 300px" />
			</div>
		</div>

		<div class="mb-3 row">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th class="text-center bg-dark text-nowrap">No</th>
							<th class="text-center bg-dark text-nowrap">Action</th>
							<th class="text-center bg-dark text-nowrap">City</th>
							<th class="text-center bg-dark text-nowrap">Image</th>

						</tr>
					</thead>
					<tbody>
						<tr v-if="loading">
							<td class="text-center" colspan="4"><a-skeleton active /></td>
						</tr>

						<tr v-else-if="dataList.total === 0">
							<td class="text-center" colspan="4"><a-empty /></td>
						</tr>

						<tr v-for="(data, index) in dataList.data" :key="data.odata" v-else>
							<td class="text-center">{{ index + dataList.from }}</td>
							<td class="text-center">
								<a-tooltip title="Edit Properties" placement="top">
									<a-button type="primary" size="small" class="bg-dark me-2" @click="view(data)">
										<template #icon>
											<EditOutlined />
										</template>
									</a-button>
								</a-tooltip>
							</td>
							<td class="text-center text-nowrap">{{ data.city.city }}</td>
							<td class="text-center text-nowrap">
								<a-image :src="pathUrl + '/storage/' + data.image" alt="City Image" width="100px"
									height="100px"
									fallback="data:image/png;base64,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" />

							</td>

						</tr>
					</tbody>
				</table>
			</div>

			<div class="row py-2">
				<div class="col-sm-12 col-lg-4 col-xl-4 text-left">
					Showing {{ dataList.from }} to {{ dataList.to }} of {{ dataList.total }} entries
				</div>
				<div class="col-sm-12 col-lg-8 col-xl-8 text-end">
					<a-pagination :current="dataList.current_page" :total="dataList.total" v-model:pageSize="pagging"
						@change="handlePageChange" />
				</div>
			</div>
		</div>


		<a-drawer v-model:visible="modalAddEdit" :closable="false" :mask-closable="true" width="450"
			title="Update Popular City">
			<div class="mb-3 row">
				<label class="col-sm-3 col-form-label">Image</label>
				<div class="col-sm-9">
					<input type="file" accept="image/webp" @change="onFileChange" />
					<div v-if="previewUrl" class="mt-2">
						<a-image :src="previewUrl" width="96" height="96"
							style="object-fit: cover; border-radius: 6px;" />
					</div>
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
import { ref, onMounted, watch, reactive } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import { apiGetData, apiPostData, loadingSubmit, Swal } from '@/store/action'
import { EditOutlined } from '@ant-design/icons-vue'
import ProgressBar from 'primevue/progressbar';
const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
const search = ref('')
const loading = ref(false)
const dataList = ref([])
const pagging = ref(10)
const modalAddEdit = ref(false)
const previewUrl = ref(null)
const state = reactive({
	form: {
		odata: null,
		city: null,
		image: null,
	},
})

const getData = async (page = dataList.value.current_page) => {
	loading.value = true
	const params = {
		search: search.value,
		per_page: pagging.value,
		page: page,
	}
	const response = await apiGetData('/properties/city', params)
	dataList.value = response.data || []
	loading.value = false
}

const handlePageChange = (page) => {
	getData(page)
}

const view = (data) => {
	state.form = {
		odata: data.odata,
		city: data.city.odata,
		image: data.image,
	}
	previewUrl.value = imageUrl(data.image);
	modalAddEdit.value = true
}

const onFileChange = (event) => {
	const file = event.target.files[0];
	state.form.image = file || null;
	previewUrl.value = file ? URL.createObjectURL(file) : '';
};

const imageUrl = (value) => {
	if (!value) {
		return '';
	}
	return `${pathUrl}/storage/${value}`;
};

const save = async () => {
	loadingSubmit.value = true;
	const formData = new FormData();
	formData.append('odata', state.form.odata);
	formData.append('city', state.form.city);
	if (state.form.image instanceof File) {
		formData.append('image', state.form.image);
	}

	const response = await apiPostData('/properties/updatePopularCity', formData, {
		headers: {
			'Content-Type': 'multipart/form-data',
		},
	});

	if (response) {
		Swal.fire({
			icon: 'success',
			title: 'Success',
			text: response.message || 'Data saved successfully',
			timer: 2000,
			showConfirmButton: false,
		});
		modalAddEdit.value = false;
		await getData();
		loadingSubmit.value = false;
	} else {
		loadingSubmit.value = false;
	}


};

onMounted(async () => {
	await getData()
})

watch(search, useDebounceFn(async () => {
	await getData()
}, 500))
</script>
