<template>
    <!-- Page Header -->
    <section
        class="relative flex items-center bg-center bg-cover transition-opacity duration-700
            h-[200px] md:h-[250px] lg:h-[230px]"
            :class="loaded ? 'scale-100' : 'scale-110'"
            :style="{ backgroundImage: `url(${imageBaseUrl}${currentInfo.bannerContactMe})` }"
        >
        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-2" :style="{ color: currentInfo.colorContactMe }">
            Hubungi Kami
            </h1>

            <p class="text-sm" :style="{ color: currentInfo.colorContactMe }">
            <router-link to="/" class="hover:underline">Home</router-link>
            <span class="mx-2">›</span> Hubungi Kami
            </p>
        </div>
    </section>

    <section class="bg-[#f7f8fa] py-16">
        <div class="max-w-7xl mx-auto px-4 flex flex-col lg:flex-row gap-10 items-center">
            <!-- Left: Cards -->
            <div class="flex-1 flex flex-col gap-6 min-w-[320px]">
                <div class="bg-white rounded-xl shadow p-8">
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Jam Operasional</h2>
                    <p class="text-gray-700 mb-3" v-html="currentInfo.jam"> </p>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Customer Service</h2>
                    <p class="text-gray-700" v-html="currentInfo.cs"> </p>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900 mb-2">Department Contact</h2>
                    <p class="text-gray-700" v-html="currentInfo.deptContact"> </p>
                </div>
            </div>
            <!-- Right: Image -->
            <div class="flex-1 flex items-center justify-center min-w-[320px]">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden w-full max-w-xl h-[340px] flex items-center justify-center">
                    <img :src="imageBaseUrl + currentInfo.imageContactMe" alt="Office Building" class="w-full h-full object-cover" />
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 mt-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl shadow p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border-2 border-amber-500">
                    <a
                            target="_blank"
                            rel="noopener"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition"
                        >
                        <i class="fas fa-envelope text-xl text-amber-500"></i>
                    </a>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 mb-1">Email Address</div>
                    <div class="text-gray-700 text-base">
                        <a v-if="currentInfo.email !== 'null'" :href="`mailto:${currentInfo.email}`" class="hover:underline">{{ currentInfo.email }}</a><br/>
                        <a v-if="currentInfo.email1 !== 'null'" :href="`mailto:${currentInfo.email1}`" class="hover:underline">{{ currentInfo.email1 }}</a><br/>
                        <a v-if="currentInfo.email2 !== 'null'" :href="`mailto:${currentInfo.email2}`" class="hover:underline">{{ currentInfo.email2 }}</a>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border-2 border-amber-500">
                    <a
                            target="_blank"
                            rel="noopener"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition"
                        >
                        <i class="fas fa-phone text-xl text-amber-500"></i>
                    </a>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 mb-1">Phone Number</div>
                    <div class="text-gray-700 text-base">
                        <a v-if="currentInfo.phone !== 'null'" :href="`tel:${currentInfo.phone}`" class="hover:underline">{{ currentInfo.phone }} (Office)</a><br/>
                        <a v-if="currentInfo.phone1 !== 'null'" :href="`tel:${currentInfo.phone1}`" class="hover:underline">{{ currentInfo.phone1 }} (Office)</a>
                        <a
                            v-if="currentInfo.wa !== 'null'"
                            :href="`https://wa.me/${currentInfo.wa.replace(/\D/g, '')}`"
                            target="_blank"
                            rel="noopener"
                            class="hover:underline"
                        >
                            {{ currentInfo.wa }} (WhatsApp)
                        </a><br/>
                        <a
                            v-if="currentInfo.wa1 !== 'null'"
                            :href="`https://wa.me/${currentInfo.wa1.replace(/\D/g, '')}`"
                            target="_blank"
                            rel="noopener"
                            class="hover:underline"
                        >
                            {{ currentInfo.wa1 }} (WhatsApp)
                        </a>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full border-2 border-amber-500">
                    <a 
                            target="_blank"
                            rel="noopener"
                            class="bg-white rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-200 transition"
                        >
                        <i class="fas fa-map-marker-alt text-xl text-amber-500"></i>
                    </a>
                </div>
                <div>
                    <div class="font-bold text-lg text-gray-900 mb-1">Alamat</div>
                    <div class="text-gray-700 text-base" v-html="currentInfo.alamat"></div>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="overflow-hidden flex items-center justify-center">
                <img :src="imageBaseUrl + currentInfo.imageForm" alt="Office Building" class="w-full object-cover" />
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col justify-center">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-6">Hubungi Kami</h2>
                <div class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Nama Anda</label>
                            <input type="text" v-model="nama" placeholder="Masukan Nama Anda" class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Email Anda</label>
                            <input type="email" v-model="email" placeholder="Masukan Email Anda" class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Nomor Telepon</label>
                            <div class="flex">
                                <span class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-200 rounded-l-md text-gray-500">+62</span>
                                <input
                                    type="text"
                                    v-model="phone"
                                    class="w-full rounded-r-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400"
                                    placeholder="81234567890"
                                    @input="onPhoneInput"
                                />
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-gray-700 font-semibold mb-1">Subject</label>
                            <input type="text" v-model="subject" placeholder="Masukan Subject" class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Deskripsi</label>
                        <textarea v-model="description"   class="w-full rounded-md bg-gray-50 border border-gray-200 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-teal-400" rows="3" placeholder="Komentar"></textarea>
                    </div>
                    <div v-if="processing" class="w-full h-2 mb-2 bg-gray-200 rounded overflow-hidden">
                        <div class="h-full animate-pulse" style="width: 100%" :style="{ backgroundColor: currentInfo.primaryColor }"></div>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 text-white font-semibold rounded-md py-3 mt-2 hover:bg-gray-800 transition" @click="kirim" :disabled="processing">
                        <span v-if="processing">Mengirim...</span>
                        <span v-else>Kirim</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto mt-10">
        <div class="overflow-hidden shadow-lg">
            <iframe
                :src="currentInfo.maps"
                width="100%"
                height="400"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                class="w-full h-[400px]"
            ></iframe>
        </div>
    </section>
</template>

<script setup>
    import { useHead } from '@vueuse/head'
    import { ref } from 'vue'
    import { apiPostData, Swal, waitingicon, processing, pesan } from '@/store/action'
    const loaded = ref(true)
    import { useInfoStore } from '@/store/info'
    import { computed } from 'vue'
    import { storeToRefs } from 'pinia'
    const { data: info } = storeToRefs(useInfoStore())
    const currentInfo = computed(() => {
        return info.value?.[0] ?? {}
    })

    const imageBaseUrl = import.meta.env.VITE_PATH_FILE_BASE_URL + '/storage/'

    const nama = ref('')
    const email = ref('')
    const phone = ref('')
    const subject = ref('')
    const description = ref('')

    // Phone input validation: only digits, cannot start with 0
    function onPhoneInput(e) {
        let val = e.target.value.replace(/\D/g, '');
        if (val.startsWith('0')) {
            val = val.replace(/^0+/, '');
        }
        phone.value = val;
    }

    const kirim = async (e) => {
        processing.value = true;
        pesan.value = 'Mohon tunggu, sedang mengirim pesan...';
        const payload = {
            nama: nama.value,
            email: email.value,
            phone: phone.value,
            subject: subject.value,
            description: description.value,
        };

    
        const response = await apiPostData('/public/contact-me', payload);
        if (response) {
            nama.value = '';
            email.value = '';
            phone.value = '';
            subject.value = '';
            description.value = '';
            processing.value = false;
        } else{
            processing.value = false;
        }
    };
    
    useHead({
        title: 'Kontak Kami - IDROOM',
        meta: [
            {
                name: 'description',
                content: 'Hubungi IDROOM untuk informasi properti, dukungan pelanggan, atau pertanyaan lainnya. Tim kami siap membantu Anda menemukan solusi properti terbaik.'
            },
            {
                name: 'keywords',
                content: 'kontak idroom, hubungi idroom, layanan pelanggan idroom, dukungan properti, informasi properti, pertanyaan real estate, bantuan sewa properti'
            },
            {
                name: 'author',
                content: 'IDROOM'
            }
        ]
    })
</script>