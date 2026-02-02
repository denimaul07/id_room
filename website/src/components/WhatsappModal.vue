<template>
    <!-- Overlay -->
    <div
        v-if="open"
        class="fixed inset-0 z-50 bg-black/70 flex items-center justify-center px-4"
        @click.self="close"
    >
        <!-- Modal -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden animate-scale">
        <!-- Header -->
        <div class="bg-green-500 text-white p-5">
            <h3 class="text-lg font-bold">Hubungi Kami</h3>
            <p class="text-sm opacity-90">Ceritakan kebutuhan Anda</p>
        </div>

        <!-- Body -->
        <form class="p-5 space-y-4" @submit.prevent="submit">
            <div>
            <label class="text-sm font-medium">Nama Lengkap *</label>
            <input v-model="form.name" required class="input" placeholder="Masukkan nama lengkap" />
            </div>

            <div>
            <label class="text-sm font-medium">Email *</label>
            <input v-model="form.email" type="email" required class="input" placeholder="contoh@email.com" />
            </div>

            <div>
            <label class="text-sm font-medium">No. Telepon *</label>
            <input v-model="form.phone" required class="input" placeholder="+62 812-xxxx-xxxx" />
            </div>

            <div>
            <label class="text-sm font-medium">Pesan *</label>
            <textarea
                v-model="form.message"
                required
                rows="3"
                class="input"
                placeholder="Ceritakan kebutuhan atau pertanyaan Anda..."
            ></textarea>
            </div>

            <!-- CTA -->
            <button
            type="submit"
            class="w-full flex items-center justify-center gap-2 bg-green-500 text-white font-semibold py-3 rounded-xl hover:bg-green-600 transition"
            >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                d="M20.52 3.48A11.91 11.91 0 0012 0C5.37 0 0 5.37 0 12a11.9 11.9 0 001.64 6L0 24l6.16-1.6A11.93 11.93 0 0012 24c6.63 0 12-5.37 12-12a11.91 11.91 0 00-3.48-8.52z"
                />
            </svg>
            Lanjutkan ke WhatsApp
            </button>
        </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted, reactive } from 'vue'

const props = defineProps({ open: Boolean, nowa: String })
const emit = defineEmits(['close'])

const form = reactive({
    nowa: '',
    name: '',
    email: '',
    phone: '',
    message: ''
})

const close = () => emit('close')

const submit = () => {
    const text = `
    Nama: ${form.name}
    Email: ${form.email}
    Telepon: ${form.phone}

    Pesan:
    ${form.message}
    `
    // Gunakan nomor dari prop nowa, fallback jika kosong
    const nomor = props.nowa ? props.nowa.replace(/\D/g, '') : '62812XXXXXXX'
    const url = `https://wa.me/${nomor}?text=${encodeURIComponent(text)}`
    window.open(url, '_blank')
    close()
}

    // ESC close
    const onEsc = e => e.key === 'Escape' && close()
    onMounted(() => window.addEventListener('keydown', onEsc))
    onUnmounted(() => window.removeEventListener('keydown', onEsc))
    </script>

    <style scoped>
    .input {
        @apply w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500;
        }
        .animate-scale {
        animation: scaleIn .25s ease-out;
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>
