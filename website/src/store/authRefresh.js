import axios from 'axios'
import { useAuthStore } from '@/store/auth'

let refreshTimeout = null
let isRefreshing = false

export function startSilentRefresh() {
    const authStore = useAuthStore()

    if (!authStore.expiredAt?.value) return   // ✅ FIX

    if (refreshTimeout) {
        clearTimeout(refreshTimeout)
        refreshTimeout = null
    }

    const now = Date.now()
    const expiredAtMs = Number(authStore.expiredAt.value) // ✅ FIX

    if (expiredAtMs <= now) {
        refreshToken()
        return
    }

    // refresh 10 detik sebelum expire (lebih aman)
    const refreshTime = expiredAtMs - now - 10000

    refreshTimeout = setTimeout(() => {
        refreshToken()
    }, Math.max(refreshTime, 0))

    console.log('Next refresh in', refreshTime / 1000, 'seconds')
}

async function refreshToken() {
    if (isRefreshing) return
    isRefreshing = true

    const authStore = useAuthStore()

    try {
        const res = await axios.post(
            `${import.meta.env.VITE_API_BASE_URL}/auth/token/refresh_apps`,
            {},
            { withCredentials: true }
        )

        authStore.setTokenOnly(res.data.token, res.data.expired_in)

        console.log('TOKEN REFRESHED')

        startSilentRefresh()
    } catch (e) {
        console.error('REFRESH FAILED', e)
        authStore.clearAuth()
        window.location.href = '/'
    } finally {
        isRefreshing = false
    }
}