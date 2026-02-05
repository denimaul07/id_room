<template>
    <div>
        <Breadcrumbs main="System" title="Activity Logs" />

        <div class="card">
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Filter Event</label>
                        <a-select v-model:value="filters.event" style="width: 100%">
                            <a-select-option value="">All Events</a-select-option>
                            <a-select-option value="created">Created</a-select-option>
                            <a-select-option value="updated">Updated</a-select-option>
                            <a-select-option value="deleted">Deleted</a-select-option>
                        </a-select>
                    </div>
                    <div class="col-md-3">
                        <label>Filter Subject Type</label>
                        <a-select v-model:value="filters.subject_type" style="width: 100%">
                            <a-select-option value="">All Types</a-select-option>
                            <a-select-option value="App\Models\User">User Admin</a-select-option>
                            <a-select-option value="App\Models\ContactMe">Contact Me</a-select-option>
                            <a-select-option value="App\Models\Membership">Membership</a-select-option>
                            <a-select-option value="App\Models\MembershipBenefit">Membership Benefit</a-select-option>
                            <a-select-option value="App\Models\Faq">FAQ</a-select-option>
                            <a-select-option value="App\Models\Mitra">Mitra</a-select-option>
                            <a-select-option value="App\Models\ProcessWork">Process Work</a-select-option>
                            <a-select-option value="App\Models\Portofolio">Portofolio</a-select-option>
                            <a-select-option value="App\Models\Testimoni">Testimoni</a-select-option>
                            <a-select-option value="App\Models\Services">Services</a-select-option>
                            <a-select-option value="App\Models\Setting">Setting</a-select-option>
                            <a-select-option value="App\Models\Socials">Social Media</a-select-option>
                        </a-select>
                    </div>
                    <div class="col-md-3">
                        <label>Per Page</label>
                        <a-select v-model:value="paging" style="width: 100%">
                            <a-select-option :value="10">10</a-select-option>
                            <a-select-option :value="25">25</a-select-option>
                            <a-select-option :value="50">50</a-select-option>
                            <a-select-option :value="100">100</a-select-option>
                        </a-select>
                    </div>
                    <div class="col-md-3">
                        <label>Search</label>
                        <a-input-search
                            v-model:value="search"
                            placeholder="Search description..."
                        />
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4" v-if="statistics">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6>Total Logs</h6>
                                <h3>{{ statistics.total_activities }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6>Today</h6>
                                <h3>{{ statistics.today_activities }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6>This Week</h6>
                                <h3>{{ statistics.this_week_activities }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6>This Month</h6>
                                <h3>{{ statistics.this_month_activities }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Desktop -->
                <div class="table-responsive pt-2 d-md-block d-none">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr class="border-bottom-warning">
                                <th class="bg-warning text-center">No</th>
                                <th class="bg-warning text-center">Action</th>
                                <th class="bg-warning text-center">Log Name</th>
                                <th class="bg-warning text-center">Description</th>
                                <th class="bg-warning text-center">Subject</th>
                                <th class="bg-warning text-center">Event</th>
                                <th class="bg-warning text-center">Causer</th>
                                <th class="bg-warning text-center">Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="loading">
                                <td class="text-center" colspan="8"><a-skeleton active /></td>
                            </tr>
                            <tr v-else-if="state.data.total == 0">
                                <td colspan="8" class="text-center"><a-empty></a-empty></td>
                            </tr>
                            <tr v-for="(item, index) in state.data.data" :key="index" v-else>
                                <td class="text-center">{{ index + state.data.from }}</td>
                                <td class="text-center">
                                    <a-tooltip title="View Details">
                                        <a-button type="primary" size="small" @click="viewDetail(item)" class="bg-warning">
                                            <template #icon>
                                                <EyeOutlined />
                                            </template>
                                        </a-button>
                                    </a-tooltip>
                                </td>
                                <td class="text-center">
                                    <a-tag :color="getLogColor(item.log_name)">{{ item.log_name }}</a-tag>
                                </td>
                                <td>{{ item.description }}</td>
                                <td class="text-center">
                                    <small>{{ getSubjectName(item.subject_type) }}</small>
                                    <br>
                                    <small class="text-muted">#{{ item.subject_id }}</small>
                                </td>
                                <td class="text-center">
                                    <a-tag :color="getEventColor(item.event)">{{ item.event }}</a-tag>
                                </td>
                                <td class="text-center">
                                    <span v-if="item.causer">{{ item.causer.name }}</span>
                                    <span v-else class="text-muted">System</span>
                                </td>
                                <td class="text-center">
                                    {{ formatDate(item.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile View -->
                <div class="card-body p-0 d-md-none d-block">
                    <a-list v-if="!loading && state.data.data" :data-source="state.data.data" :loading="loading">
                        <template #renderItem="{ item }">
                            <a-list-item>
                                <div class="w-100">
                                    <div class="d-flex justify-content-between mb-2">
                                        <a-tag :color="getLogColor(item.log_name)">{{ item.log_name }}</a-tag>
                                        <a-tag :color="getEventColor(item.event)">{{ item.event }}</a-tag>
                                    </div>
                                    <div><strong>{{ item.description }}</strong></div>
                                    <div class="text-muted small">
                                        {{ getSubjectName(item.subject_type) }} #{{ item.subject_id }}
                                    </div>
                                    <div class="text-muted small">
                                        By: {{ item.causer ? item.causer.name : 'System' }}
                                    </div>
                                    <div class="text-muted small">{{ formatDate(item.created_at) }}</div>
                                    <a-button type="primary" size="small" @click="viewDetail(item)" class="bg-warning mt-2">
                                        View Details
                                    </a-button>
                                </div>
                            </a-list-item>
                        </template>
                    </a-list>
                    <a-empty v-else-if="!loading && state.data.total == 0" />
                    <a-skeleton v-else active />
                </div>

                <!-- Pagination -->
                <div class="row py-2">
                    <div class="col-sm-12 col-lg-4 col-xl-4 text-left">
                        Showing {{ state.data.from }} to {{ state.data.to }} of {{ state.data.total }} entries
                    </div>
                    <div class="col-sm-12 col-lg-8 col-xl-8 text-end">
                        <a-pagination :current="state.data.current_page" :total="state.data.total" v-model:pageSize="paging" @change="handlePageChange" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <a-modal v-model:open="detailModal" title="Activity Log Details" width="800px" :footer="null">
            <div v-if="selectedLog">
                <a-descriptions bordered :column="1">
                    <a-descriptions-item label="Log Name">
                        <a-tag :color="getLogColor(selectedLog.log_name)">{{ selectedLog.log_name }}</a-tag>
                    </a-descriptions-item>
                    <a-descriptions-item label="Description">
                        {{ selectedLog.description }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Event">
                        <a-tag :color="getEventColor(selectedLog.event)">{{ selectedLog.event }}</a-tag>
                    </a-descriptions-item>
                    <a-descriptions-item label="Subject Type">
                        {{ getSubjectName(selectedLog.subject_type) }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Subject ID">
                        {{ selectedLog.subject_id }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Causer">
                        {{ selectedLog.causer ? selectedLog.causer.name : 'System' }}
                        <span v-if="selectedLog.causer" class="text-muted">({{ selectedLog.causer.email }})</span>
                    </a-descriptions-item>
                    <a-descriptions-item label="Created At">
                        {{ formatDate(selectedLog.created_at) }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Batch UUID" v-if="selectedLog.batch_uuid">
                        {{ selectedLog.batch_uuid }}
                    </a-descriptions-item>
                </a-descriptions>

                <!-- Device Information Section (for authentication logs) -->
                <div class="mt-3" v-if="selectedLog.log_name === 'authentication' && selectedLog.properties">
                    <h6>Device Information:</h6>
                    <a-descriptions bordered :column="2" size="small">
                        <a-descriptions-item label="IP Address" v-if="selectedLog.properties.ip_address">
                            <a-tag color="blue">{{ selectedLog.properties.ip_address }}</a-tag>
                        </a-descriptions-item>
                        <a-descriptions-item label="Device Type" v-if="selectedLog.properties.device">
                            {{ selectedLog.properties.device || 'Unknown' }}
                        </a-descriptions-item>
                        <a-descriptions-item label="Platform" v-if="selectedLog.properties.platform">
                            {{ selectedLog.properties.platform }} 
                            <span v-if="selectedLog.properties.platform_version">{{ selectedLog.properties.platform_version }}</span>
                        </a-descriptions-item>
                        <a-descriptions-item label="Browser" v-if="selectedLog.properties.browser">
                            {{ selectedLog.properties.browser }}
                            <span v-if="selectedLog.properties.browser_version">{{ selectedLog.properties.browser_version }}</span>
                        </a-descriptions-item>
                        <a-descriptions-item label="Device Category" :span="2">
                            <a-tag v-if="selectedLog.properties.is_desktop" color="green">Desktop</a-tag>
                            <a-tag v-if="selectedLog.properties.is_mobile" color="blue">Mobile</a-tag>
                            <a-tag v-if="selectedLog.properties.is_tablet" color="orange">Tablet</a-tag>
                            <a-tag v-if="selectedLog.properties.is_robot" color="red">Robot: {{ selectedLog.properties.robot_name }}</a-tag>
                        </a-descriptions-item>
                        <a-descriptions-item label="User Agent" :span="2" v-if="selectedLog.properties.user_agent">
                            <small class="text-muted">{{ selectedLog.properties.user_agent }}</small>
                        </a-descriptions-item>
                    </a-descriptions>
                </div>

                <div class="mt-3" v-if="selectedLog.properties && Object.keys(selectedLog.properties).length > 0">
                    <h6>Full Properties:</h6>
                    <pre class="bg-light p-3 rounded text-black">{{ JSON.stringify(selectedLog.properties, null, 2) }}</pre>
                </div>
            </div>
        </a-modal>
    </div>
</template>

<script setup>
import { apiGetData, loading } from '@/store/action';
import { reactive, ref, onMounted, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';
import { EyeOutlined } from '@ant-design/icons-vue';
import { message } from 'ant-design-vue';
import moment from 'moment';

const search = ref('');
const paging = ref(25);
const detailModal = ref(false);
const selectedLog = ref(null);
const statistics = ref(null);

const filters = reactive({
    event: '',
    subject_type: '',
    per_page: 25
});

const state = reactive({
    data: {
        data: [],
        current_page: 1,
        total: 0,
        from: 0,
        to: 0
    }
});

const tampilData = async () => {
    loading.value = true;
    const params = {
        page: state.data.current_page,
        paging: paging.value,
        q: search.value,
    };

    if (filters.event) params.event = filters.event;
    if (filters.subject_type) params.subject_type = filters.subject_type;

    const response = await apiGetData('activity_logs/index', params);
    state.data = response.data;
    loading.value = false;
};

const fetchStatistics = async () => {
    try {
        const response = await apiGetData('activity_logs/statistics');
        statistics.value = response.data;
    } catch (error) {
        console.error('Failed to load statistics', error);
    }
};


const handlePageChange = async (page) => {
    state.data.current_page = page;
    await tampilData();
};

const viewDetail = (item) => {
    selectedLog.value = item;
    detailModal.value = true;
};

const formatDate = (date) => {
    return moment(date).format('DD MMM YYYY HH:mm:ss');
};

const getLogColor = (logName) => {
    const colors = {
        'authentication': 'blue',
        'payment': 'green',
        'crm': 'purple',
        'contract': 'orange',
        'member': 'cyan',
        'critical': 'red',
        'default': 'default'
    };
    return colors[logName] || colors['default'];
};

const getEventColor = (event) => {
    const colors = {
        'created': 'green',
        'updated': 'blue',
        'deleted': 'red'
    };
    return colors[event] || 'default';
};

const getSubjectName = (subjectType) => {
    if (!subjectType) return '-';
    const parts = subjectType.split('\\');
    return parts[parts.length - 1];
};

onMounted(async () => {
    await tampilData();
    await fetchStatistics();
});

watch(search, useDebounceFn(async (val) => {
    state.data.current_page = 1;
    await tampilData();
}, 500));

watch(() => [filters.event, filters.subject_type, paging.value], useDebounceFn(async () => {
    state.data.current_page = 1;
    await tampilData();
}, 300));
</script>

<style scoped>
.schedule-list {
    list-style: none;
    padding: 0;
}

.card {
    margin-bottom: 10px;
}

pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
