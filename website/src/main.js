import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import 'nprogress/nprogress.css'
import './style.css'
import '@fortawesome/fontawesome-free/css/all.min.css'
import { createHead } from '@vueuse/head'

createApp(App)
.use(createPinia())
.use(router)
.use(createHead())
.mount('#app')
