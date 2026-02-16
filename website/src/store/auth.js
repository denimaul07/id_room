import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('auth', () => {
    const token = ref(null)
    const user = ref(null)
    const permissions = ref([])
    const expiredAt = ref(null)

    // restore session (ALL TAB will read this)
    if (typeof window !== 'undefined') {
        const saved = localStorage.getItem('auth_state_id_room')
        if (saved) {
            const parsed = JSON.parse(saved)
            token.value = parsed.token
            user.value = parsed.user
            permissions.value = parsed.permissions
            expiredAt.value = parsed.expiredAt
        }
    }

    function persist() {
        localStorage.setItem('auth_state_id_room', JSON.stringify({
            token: token.value,
            user: user.value,
            permissions: permissions.value,
            expiredAt: expiredAt.value
        }))
    }

    function setAuth(data) {
        token.value = data.token
        user.value = data.users ?? null
        permissions.value = data.permissions ?? []

        expiredAt.value = Date.now() + (data.expired_in * 1000)

        persist()
    }

    function setTokenOnly(newToken, expired_in) {
        token.value = newToken
        expiredAt.value = Date.now() + (expired_in * 1000)
        persist()
    }

    function clearAuth() {
        token.value = null
        user.value = null
        permissions.value = []
        expiredAt.value = null
        localStorage.removeItem('auth_state_id_room')
    }

    return {
        token,
        user,
        permissions,
        expiredAt,
        setAuth,
        setTokenOnly,
        clearAuth
    }
})
