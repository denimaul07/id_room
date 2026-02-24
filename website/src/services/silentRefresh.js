import axios from '@/plugins/axios'
import { useAuthStore } from '@/store/auth'

let interval = null

export function startSilentRefresh() {
    const auth = useAuthStore()

    // kalau belum login → jangan jalan
    if (!auth.token) return

    // clear dulu biar ga double interval
    if (interval) clearInterval(interval)

    // refresh tiap 10 menit (sebelum expired)
    interval = setInterval(async () => {
        try {
            const res = await axios.post('/refresh', {}, {
                withCredentials: true // penting kirim cookie
            })

            auth.setToken(res.data.token)
            console.log('✅ Silent refresh success')
        } catch (e) {
            console.log('❌ Silent refresh failed → logout')
            auth.logout()
        }
    }, 10 * 60 * 1000)
}