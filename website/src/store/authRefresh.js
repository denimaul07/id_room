import axios from 'axios'
import { useAuthStore } from '@/store/auth'

let refreshTimeout = null

export function startSilentRefresh() {
    const authStore = useAuthStore()

    if (!authStore.expiredAt) return

    const now = Date.now()

    // refresh 1 menit sebelum expired
    const refreshTime = authStore.expiredAt - now - 60000

    if (refreshTime <= 0) {
        refreshToken()
        return
    }

    refreshTimeout = setTimeout(() => {
        refreshToken()
    }, refreshTime)
}

async function refreshToken() {
    const authStore = useAuthStore()

    try {
        const res = await axios.post(
            `${import.meta.env.VITE_API_BASE_URL}/auth/token/refresh`,
            {},
            { withCredentials: true }
        )

        authStore.setTokenOnly(
            res.data.token,
            res.data.expired_in
        )

        startSilentRefresh()

    } catch (e) {
        authStore.clearAuth()
        window.location.href = '/login'
    }
}
