<template>
  <div class="max-w-lg mx-auto bg-white p-8 rounded shadow mt-10 print:shadow-none print:mt-0">
    <h2 class="text-2xl font-bold mb-4">Invoice</h2>
    <div v-if="loading" class="text-center py-10">Loading...</div>
    <div v-else-if="error" class="text-red-500">{{ error }}</div>
    <div v-else>
      <div class="mb-2"><b>No. Invoice:</b> {{ invoice.invoice_number }}</div>
      <div class="mb-2"><b>Nama:</b> {{ invoice.user_name }}</div>
      <div class="mb-2"><b>Membership:</b> {{ invoice.title }}</div>
      <div class="mb-2"><b>Tanggal:</b> {{ invoice.paid_at }}</div>
      <div class="mb-2"><b>Nominal:</b> Rp {{ invoice.total_amount?.toLocaleString('id-ID') }}</div>
      <div class="mb-2"><b>Status:</b> {{ invoice.status }}</div>
      <hr class="my-4" />
      <div class="text-center">
        <button @click="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded print:hidden">Print</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';

const route = useRoute();
const invoice_number = route.params.invoice_number;
const invoice = ref({});
const loading = ref(true);
const error = ref('');

onMounted(async () => {
  try {
    const res = await axios.get(`/api/invoice/${invoice_number}`);
    invoice.value = res.data.data;
    loading.value = false;
    setTimeout(() => window.print(), 500); // auto print
  } catch (e) {
    error.value = 'Gagal memuat invoice';
    loading.value = false;
  }
});
</script>

<style scoped>
@media print {
  .print\:hidden { display: none !important; }
  .print\:shadow-none { box-shadow: none !important; }
  .print\:mt-0 { margin-top: 0 !important; }
}
</style>
