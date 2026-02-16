
import axios from "axios";
import router from "../router";
import { useAuthStore } from '@/store/auth';
import { storeToRefs } from 'pinia';


const Api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    withCredentials: true
});

// Attach token from Pinia to every request
Api.interceptors.request.use(
    (config) => {
        const authStore = useAuthStore();
        if (authStore.token) {
            config.headers['Authorization'] = `Bearer ${authStore.token}`;
        }
        return config;
    },
    (error) => Promise.reject(error)
);

// Response interceptor for 401 handling and token refresh
let isRefreshing = false;
let failedQueue = [];

const processQueue = (error, token = null) => {
    failedQueue.forEach(prom => {
        if (error) {
            prom.reject(error);
        } else {
            prom.resolve(token);
        }
    });
    failedQueue = [];
};

Api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;
        const authStore = useAuthStore();

        if (originalRequest.url.includes('/auth/token/refresh')) {
            return Promise.reject(error)
        }


        if (
            error.response?.status === 401 &&
            !originalRequest._retry &&
            authStore.token // <-- hanya refresh kalau user punya token
        ) {
            if (isRefreshing) {
                return new Promise((resolve, reject) => {
                    failedQueue.push({ resolve, reject });
                })
                    .then((token) => {
                        originalRequest.headers['Authorization'] = 'Bearer ' + token;
                        return Api(originalRequest);
                    })
                    .catch((err) => Promise.reject(err));
            }

            originalRequest._retry = true;
            isRefreshing = true;

            try {
                const res = await axios.post(
                    `${import.meta.env.VITE_API_BASE_URL}/auth/token/refresh`,
                    {},
                    { withCredentials: true }
                );

                const newToken = res.data.token || res.data.access_token;

                authStore.setAuth({
                    token: newToken,
                    refresh_token: null,
                    users: authStore.user,
                    permissions: authStore.permissions,
                    expired_in: res.data.expired_in,
                    refresh_exp: res.data.refresh_exp
                });

                Api.defaults.headers['Authorization'] = 'Bearer ' + newToken;

                processQueue(null, newToken);

                return Api(originalRequest);
            } catch (err) {
                processQueue(err, null);

                authStore.clearAuth();

                router.push({
                    name: '/',
                    query: { session: 'expired' }
                });

                return Promise.reject(err);
            } finally {
                isRefreshing = false;
            }
        }

        return Promise.reject(error);
    }
);


export { Api };