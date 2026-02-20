import { createRouter, createWebHistory } from 'vue-router'
import NProgress from 'nprogress'
import Home from '@/views/Home.vue'
import NotFound from '@/views/NotFound.vue'
import Sewa from '@/views/Sewa.vue'
import Jual from '@/views/Jual.vue'
import RentDetails from '@/views/RentDetails.vue'
import BookingRoom from '@/views/BookingRoom.vue'
import SellDetails from '@/views/SellDetails.vue'
import InteriorRenovation from '@/views/InteriorRenovation.vue'
import TentangKami from '@/views/Tentang-Kami.vue'
import Kontak from '@/views/Kontak.vue'
import privacyPolicy from '@/views/PrivacyPolicy.vue'
import termsConditions from '@/views/TermsConditions.vue'
import verified from '@/views/Verified.vue'
import forgot_password from '@/components/auth/ForgotPassword.vue'

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
        path: '/rent-details',
        name: 'rent-details',
        component: RentDetails
    },
    {
        path: '/booking',
        name: 'booking',
        component: BookingRoom,
        meta: { requiresAuth: true }
    },
    {
        path: '/sell-details',
        name: 'sell-details',
        component: SellDetails
    },

    {
        path: '/interior-renovation',
        name: 'interior-renovation',
        component: InteriorRenovation
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
        path: '/forgot_password',
        name: 'forgot_password',
        component: forgot_password
    },
    {
        path: '/verified',
        name: 'verified',
        component: verified
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'NotFound',
        component: NotFound
    },
    {
        path: '/dashboard',
        component: () => import('@/views/dashboard/Layout.vue'),
        meta: { requiresAuth: true }
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

import { useAuthStore } from '@/store/auth'

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth && !authStore.token) {
        next('/')
    } else {
        next()
    }
})


export default router
