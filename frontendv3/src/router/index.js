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
