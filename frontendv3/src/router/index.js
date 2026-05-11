import { createWebHistory, createRouter } from "vue-router";
import store from '../store';

const Body = () => import('../components/body.vue');
const Dashboard = () => import('@/pages/dashboard/index.vue');
const login = () => import('@/auth/login.vue');
const change_password = () => import('@/pages/change_password.vue');
const users = () => import('@/pages/users.vue');
const ErrorPage404 = () => import('@/pages/ErrorPage404.vue');
const departements = () => import('@/pages/departements.vue');
const forgot_password = () => import('@/pages/forgot_password.vue');
const activityLog = () => import('@/pages/activity-log.vue');

const cms = () => import('@/pages/cms/index.vue');
const site_setting = () => import('@/pages/cms/settings/site_setting.vue');
const membership = () => import('@/pages/membership/index.vue');
const properties = () => import('@/pages/properties/index.vue');
const promotions = () => import('@/pages/promotions/index.vue');
const wallet = () => import('@/pages/transactions/wallet.vue');
const bookingTransactions = () => import('@/pages/transactions/booking-transactions.vue');
const membershipTransactions = () => import('@/pages/transactions/membership-transactions.vue');
const topUpTransactions = () => import('@/pages/transactions/top-up-transactions.vue');
const pointTransactions = () => import('@/pages/transactions/point-transactions.vue');
const allTransactions = () => import('@/pages/transactions/all-transactions.vue');
const member = () => import('@/pages/member/index.vue');
const DasboardStaff = () => import('@/pages/dashboard/index_properties.vue');
const DashboardReceptionis = () => import('@/pages/dashboard/index_booking.vue');
const contacts = () => import('@/pages/contacts/index.vue');
const crm = () => import('@/pages/crm/index.vue');
const referralCoupons = () => import('@/pages/coupon/index.vue');
const campaigns = () => import('@/pages/campaigns/index.vue');
const waTemplates = () => import('@/pages/campaigns/whatsapp.vue');

const routes = [
  {
    path: "/",
    name: "login",
    component: login,
    meta: {
      authPage: true
    }
  },

  {
    path: "/:pathMatch(.*)*",
    component: Body,
    children: [
      {
        path: '/:pathMatch(.*)*',
        name: 'ErrorPage404',
        component: ErrorPage404,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/forgot_password',
    name: 'forgot_password',
    component: forgot_password,
    meta: {
      authPage: true
    }
  },

  {
    path: '/change_password',
    name: 'change_password',
    component: change_password,
    meta: {
      requiresAuth: true
    }
  },

  {
    path: '/dashboard',
    component: Body,
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: {
          requiresAuth: true
        }
      },

    ]
  },

  {
    path: '/dashboard-staff',
    component: Body,
    children: [
      {
        path: '/dashboard-staff',
        name: 'index_properties',
        component: DasboardStaff,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/dashboard-receptionis', 
    component: Body,
    children: [
      {
        path: '/dashboard-receptionis',
        name: 'index_booking',
        component: DashboardReceptionis,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/membership',
    component: Body,
    children: [
      {
        path: '/membership',
        name: 'membership',
        component: membership,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },
  {
    path: '/crm',
    component: Body,
    children: [
      {
        path: '/crm',
        name: 'crm',
        component: crm,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },
  {
    path: '/campaigns',
    component: Body,
    children: [
      {
        path: '/campaigns',
        name: 'campaigns',
        component: campaigns,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },
  {
    path: '/wa-templates',
    component: Body,
    children: [
      {
        path: '/wa-templates',
        name: 'wa-templates',
        component: waTemplates,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },
  {
    path: '/contacts',
    component: Body,
    children: [
      {
        path: '/contacts',
        name: 'contacts',
        component: contacts,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/properties-list',
    component: Body,
    children: [
      {
        path: '/properties-list',
        name: 'properties',
        component: properties,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/promotions',
    component: Body,
    children: [
      {
        path: '/promotions',
        name: 'promotions',
        component: promotions,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/referral-coupons',
    component: Body,
    children: [
      {
        path: '/referral-coupons',
        name: 'referralCoupons',
        component: referralCoupons,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/activity-log',
    component: Body,
    children: [
      {
        path: '/activity-log',
        name: 'activity-log',
        component: activityLog,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/member',
    component: Body,
    children: [
      {
        path: '/member',
        name: 'member',
        component: member,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/settings',
    component: Body,
    children: [
      {
        path: '/settings',
        name: 'settings',
        component: cms,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/wallet-ledger',
    component: Body,
    children: [
      {
        path: '/wallet-ledger',
        name: 'wallet',
        component: wallet,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/booking-transactions',
    component: Body,
    children: [
      {
        path: '/booking-transactions',
        name: 'bookingTransactions',
        component: bookingTransactions,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/top-up-transactions',
    component: Body,
    children: [
      {
        path: '/top-up-transactions',
        name: 'topUpTransactions',
        component: topUpTransactions,
        meta: {
          requiresAuth: true
        }
      },
    ]  
  },

  {
    path: '/point-transactions',
    component: Body,
    children: [
      {
        path: '/point-transactions',
        name: 'pointTransactions',
        component: pointTransactions,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/all-transactions',
    component: Body,
    children: [
      {
        path: '/all-transactions',
        name: 'allTransactions',
        component: allTransactions,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/membership-transactions',
    component: Body,
    children: [
      {
        path: '/membership-transactions',
        name: 'membershipTransactions',
        component: membershipTransactions,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/site_setting',
    component: Body,
    children: [
      {
        path: '/site_setting',
        name: 'site_setting',
        component: site_setting,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/users',
    component: Body,
    children: [
      {
        path: '/users',
        name: 'users',
        component: users,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },

  {
    path: '/departements',
    component: Body,
    children: [
      {
        path: '/departements',
        name: 'departements',
        component: departements,
        meta: {
          requiresAuth: true
        }
      },
    ]
  },
]

const router = createRouter({
  history: createWebHistory("/"),
  
  routes,
  scrollBehavior(to, from, savedPosition) {
    return savedPosition || { left: 0, top: 0 };
  },
});

router.beforeEach(async (to, from, next) => {
  let token = store.getters["auth/isLoggedIn"];

  // **Cek apakah user sudah login dan mencoba ke halaman login**
  if (token && to.path === "/") {
    return next({ path: "/dashboard" });
  }

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!token) {
      next({ path: "/" }); // Redirect ke login jika tidak ada token
    } else {
      next(); // Biarkan Api.js yang handle refresh token
    }
  } else {
    next();
  }
});

export default router;
