
<template>
    <form class="bg-white rounded-xl shadow-lg p-4 md:p-6 w-full max-w-7xl">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-2 md:gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-600">Keyword</label>
                <input type="text" placeholder="Cari Properti" class="w-full border rounded-lg px-3 py-2" />
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-600">Type Property</label>
                <select class="w-full border rounded-lg px-3 py-2" placeholder="Pilih Type Property" v-model="propertyType">
                    <option value="" disabled selected>Pilih Type Property</option>
                    <option>Apartment</option>
                    <option>House</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-600">Kota</label>
                <select class="w-full border rounded-lg px-3 py-2">
                    <option value="" disabled selected>Pilih Kota</option>
                    <option>Jakarta</option>
                    <option>Bandung</option>
                    <option>Surabaya</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-600">Min Price</label>
                <input type="text" placeholder="Rp" class="w-full border rounded-lg px-3 py-2" v-model="minPrice" @input="formatRupiah('minPrice')" />
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1 text-gray-600">Max Price</label>
                <div class="flex">
                    <input type="text" placeholder="Rp" class="w-full border rounded-lg px-3 py-2" v-model="maxPrice" @input="formatRupiah('maxPrice')" />
                    <button type="submit" class="ml-2 bg-[#d5bd7d] text-white px-3 py-2 rounded-lg hover:bg-[#d5b356] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>


<script setup>
    import { ref } from 'vue'

    const minPrice = ref('')
    const maxPrice = ref('')
    const keyword = ref('')
    const city = ref('')
    const propertyType = ref('')


    function formatRupiah(field) {
        let val = field === 'minPrice' ? minPrice.value : maxPrice.value
        val = val.replace(/[^\d]/g, '')
        if (val) {
            val = 'Rp' + parseInt(val, 10).toLocaleString('id-ID')
        } else {
            val = ''
        }
        if (field === 'minPrice') minPrice.value = val
        else maxPrice.value = val
    }
</script>