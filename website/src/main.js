import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import 'nprogress/nprogress.css'
import './style.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import { createHead } from '@vueuse/head'
import { startSilentRefresh } from '@/store/authRefresh'
import { useAuthStore } from '@/store/auth'
import '@/plugins/axios'


const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(createHead())

router.isReady().then(() => {
    const authStore = useAuthStore()

    if (authStore.token) {
        startSilentRefresh()
    }

    // Sync login/logout antar tab
    window.addEventListener('storage', (event) => {
        if (event.key === 'auth_state_id_room') {
            if (!event.newValue) {
                authStore.clearAuth()
                window.location.href = '/'
            } else {
                const data = JSON.parse(event.newValue)
                authStore.setAuth({
                    token: data.token,
                    users: data.user,
                    permissions: data.permissions,
                    expired_in: (data.expiredAt - Date.now()) / 1000
                })
            }
        }
    })
})

app.mount('#app')

