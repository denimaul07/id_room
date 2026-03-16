<template>
    <div class="dashboard-crm">
            <h2>Dashboard CRM</h2>
    </div>
    <div class="row mb-4">
        <div class="col-md-4">
            <a-date-picker placeholder="Pilih Bulan" v-model:value="bulan" picker="month" /> 
        </div>
        <div class="col-md-4 text-center">
            <span style="background:#fff8e1;padding:6px 16px;border-radius:4px;">Total Unit Aktif <span style="color:blue;font-weight:bold;">{{ state.totalProperti }}</span></span>
        </div>
        <div class="col-md-4 text-end">
            <span>
                {{ dayjs(bulan).locale('id').format('MMMM YYYY') }}<br>
                <span style="font-size:13px;color:#555;">Hari ke {{ dayInMonth }} dari {{ daysInMonth }} hari</span>
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div style="background:#1a4971;color:#fff;padding:6px 12px;border-radius:4px;font-weight:bold;">KPI OPERASIONAL BULANAN</div>
            <table class="table table-sm mt-2">
                <tbody>
                    <tr><td>Total Booking</td><td class="text-center">{{ state.kpioperational.totalBooking}}</td></tr>
                    <tr><td>Room Nights Sold</td><td class="text-center">{{ state.kpioperational.roomNightsSold }}</td></tr>
                    <tr>
                        <td>Occupancy Rate</td>
                        <td class="text-center">
                            <span :style="{
                                color: state.kpioperational.occupancyRate >= 75 ? '#28a745' : state.kpioperational.occupancyRate >= 50 ? '#ffc107' : '#dc3545',
                                fontWeight: 'bold'
                            }">
                                {{ state.kpioperational.occupancyRate }}%
                            </span>
                        </td>
                    </tr>
                    <tr><td>Target Occupancy</td><td class="text-center">{{ state.kpioperational.targetOccupancy }}%</td></tr>
                    <tr><td>Gap vs Target</td><td class="text-center">{{ state.kpioperational.gapVsTarget }}%</td></tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <div style="background:#1a4971;color:#fff;padding:6px 12px;border-radius:4px;font-weight:bold;">KPI CUSTOMER & CRO</div>
            <table class="table table-sm mt-2">
                <tbody>
                    <tr><td>Total Revenue</td><td>{{ parseInt(state.kpioperational.total_revenue).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td></tr>
                    <tr><td>Add-On Revenue</td><td>{{ parseInt(state.kpioperational.add_on_revenue).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td></tr>
                    <tr><td>Repeat Booking</td><td>{{ state.kpioperational.repeatBooking }}</td></tr>
                    <tr><td>Repeat Rate</td><td>{{ state.kpioperational.repeatRate }}%</td></tr>
                    <tr><td>Total Komisi CRO</td><td>{{ parseInt(state.kpioperational.totalKomisiCRO).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3)}}</td></tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script setup>
    import { apiGetData, apiCetakPDF, apiExportExcel, processing, loadingButton, loadingSubmit, dayjs , Swal, waitingicon, loading, pesan } from '@/store/action';
    import { useDebounceFn } from '@vueuse/core'
    import { ref, reactive, onUnmounted, onMounted, computed , watch, nextTick} from 'vue'
    import { useStore } from "vuex";
    import { useRouter } from "vue-router";

    const bulan = ref(dayjs());

    // Hari ke berapa hari ini dari bulan berjalan
    const dayInMonth = computed(() => {
        // Jika bulan dipilih, ambil hari dari tanggal hari ini jika bulan sama, atau hari terakhir jika bulan lain
        const now = dayjs();
        if (bulan.value.isSame(now, 'month')) {
            return now.date();
        } else {
            // Jika bulan lain, return hari terakhir
            return bulan.value.endOf('month').date();
        }
    });

    // Total hari dalam bulan
    const daysInMonth = computed(() => bulan.value.daysInMonth());
    const state = reactive({
        totalProperti: 0,
        kpioperational: {
            totalBooking: 0,
            roomNightsSold: 0,
            occupancyRate: 0,
            targetOccupancy: 0,
            gapVsTarget: 0,
            total_revenue: 0,
            add_on_revenue: 0,
            repeatBooking: 0,
            repeatRate: 0,
            totalKomisiCRO: 0,
        },
    });

    const getData = async () => {
        loading.value = true;
        const payload = {
            month: bulan.value.format('YYYY-MM')
        };

        const response = await apiGetData('/dashboard/crm', payload);
        state.totalProperti = response.total_properties;
        state.kpioperational.totalBooking = response.total_booking;
        state.kpioperational.roomNightsSold = response.room_nights_sold;
        state.kpioperational.occupancyRate = response.occupancy_rate;
        state.kpioperational.targetOccupancy = response.target_occupancy;
        state.kpioperational.gapVsTarget =  response.occupancy_rate - response.target_occupancy;
        state.kpioperational.total_revenue = response.total_revenue;
        state.kpioperational.add_on_revenue = response.add_on_revenue;
        state.kpioperational.repeatBooking = response.repeat_booking;
        state.kpioperational.repeatRate = response.repeat_rate;
        state.kpioperational.totalKomisiCRO = response.total_komisi_cro;
        loading.value = false;  
    };

    onMounted(() => {
        getData();
    });

    watch(bulan, () => {
        getData();
    });
</script>