import { createRouter, createWebHistory } from 'vue-router'
import NProgress from 'nprogress'
import Home from '@/views/Home.vue'
import NotFound from '@/views/NotFound.vue'
import Sewa from '@/views/Sewa.vue'
import Jual from '@/views/Jual.vue'
import InteriorRenovation from '@/views/InteriorRenovation.vue'
import TentangKami from '@/views/Tentang-Kami.vue'
import Kontak from '@/views/Kontak.vue'
import privacyPolicy from '@/views/PrivacyPolicy.vue'
import termsConditions from '@/views/TermsConditions.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },

    {
        path: '/sewa-properti',
        name: 'sewa-properti',
        component: Sewa
    },

    {
        path: '/jual-properti',
        name: 'jual-properti',
        component: Jual
    },

    {
        path: '/interior-renovation',
        name: 'interior-renovation',
        component : InteriorRenovation
    },
    {
        path: '/tentang-kami',
        name: 'tentang-kami',
        component: TentangKami
    },
    {
        path: '/hubungi-kami',
        name: 'hubungi-kami',
        component: Kontak
    },
    {
        path: '/kebijakan-privasi',
        name: 'privacy-policy',
        component: privacyPolicy
    },
    {
        path: '/syarat-dan-ketentuan',
        name: 'terms-and-conditions',
        component: termsConditions
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior() {
        return { top: 0 }
    }
})

NProgress.configure({
    showSpinner: false,
    trickleSpeed: 120
})

router.beforeEach((to, from, next) => {
    NProgress.start()
    next()
})

router.afterEach(() => {
    NProgress.done()
})

export default router
