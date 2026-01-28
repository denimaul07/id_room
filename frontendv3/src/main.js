import { createApp } from 'vue'

import App from './App.vue'
import router from './router';
import store from './store'
import '@/assets/scss/app.scss'
import VueFeather from 'vue-feather';
import Breadcrumbs from '@/components/bread_crumbs.vue';
import settingPage from "./components/settingPage.vue" 
import Multiselect from 'vue-multiselect'
import "vue-multiselect/dist/vue-multiselect.css"
import Antd from 'ant-design-vue';
import Toast, {POSITION, useToast} from "vue-toastification";
import VueApexCharts from 'vue3-apexcharts';
import "vue-toastification/dist/index.css";
import Vue3Signature from "vue3-signature"
import Lightbox from 'vue-easy-lightbox'
import ganttastic from '@infectoone/vue-ganttastic'
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import { definePreset } from '@primeuix/themes';
import 'primeicons/primeicons.css'


const MyPreset = definePreset(Aura, {
    darkMode: false,
    semantic: {
        primary: {
            50: '{sky.50}',
            100: '{sky.100}',
            200: '{sky.200}',
            300: '{sky.300}',
            400: '{sky.400}',
            500: '{sky.500}',
            600: '{sky.600}',
            700: '{sky.700}',
            800: '{sky.800}',
            900: '{sky.900}',
            950: '{sky.950}'
        }
    }
});


createApp(App)
.use(PrimeVue, {
    theme: {
        preset: MyPreset,
        options: {
            prefix: 'p',
            darkModeSelector: '#ffffff',
            cssLayer: false
        }
    },
    ripple: true
})
.use(store)
.use(router)
.use(Antd)
.use(Toast)
.use(ganttastic)
.component("settingPage", settingPage)
.component(VueFeather.name, VueFeather)
.component('Breadcrumbs', Breadcrumbs)
.component('apexchart', VueApexCharts)
.component('multiselect', Multiselect)
.use(Vue3Signature)
.use(Lightbox)
.mount('#app')

