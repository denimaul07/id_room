import axios from "axios";
import router from "../router";
import store from "@/store";

const Api = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
    withCredentials: true
});

let isRefreshing = false;
let refreshPromise = null;

Api.interceptors.request.use(async (config) => {
    const url = config.url || "";

    // Skip interceptor untuk endpoint auth - CEK DENGAN BENAR!
    if (url.includes("auth/login")) {
        console.log('✅ SKIP AUTH ENDPOINT');
        return config;
    }

    let token = localStorage.getItem("access_token_id_room");
    let expiry = parseInt(localStorage.getItem("access_exp_id_room")) || 0;
    let currentTime = Math.floor(Date.now() / 1000);

    // Jangan refresh kalau token masih fresh (baru dibuat)
    if (token && currentTime > expiry) {
    
        if (!isRefreshing) {
            isRefreshing = true;
            refreshPromise = refreshToken().finally(() => {
                isRefreshing = false;
                refreshPromise = null;
                console.log('✅ Token refreshed');
            });
        }
        token = await refreshPromise;
    }

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
}, (error) => {
    console.error('❌ Request Interceptor Error:', error);
    return Promise.reject(error);
});

// **Interceptors untuk Response**

Api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const originalRequest = error.config;

        // Cegah infinite loop
        if (error.response && error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            // Coba refresh token
            const newToken = await refreshToken();
            if (newToken) {
                originalRequest.headers['Authorization'] = `Bearer ${newToken}`;
                return Api(originalRequest);
            } else {
                await handleLogout();
            }
        }

        // Jika sudah retry dan tetap gagal, logout
        if (error.response && error.response.status === 401 && originalRequest._retry) {
            await handleLogout();
        }

        return Promise.reject(error);
    }
);

// **Fungsi Refresh Token**
const refreshToken = async () => {
    console.log('🔄 Calling refresh token API...');
    const refreshToken = localStorage.getItem("refresh_token_id_room");

    if (!refreshToken) {
        console.warn('❌ No refresh token available');
        return null;
    }

    try {
        const response = await axios.post(
            import.meta.env.VITE_API_BASE_URL + "auth/token/refresh",
            { refresh_token: refreshToken }
        );

        const newToken = response.data.token;
        const newExpiry = response.data.expired_in;
        const newRefresh = response.data.refresh_token;
        const newRefreshExp = response.data.refresh_exp;

        localStorage.setItem("access_token_id_room", newToken);
        localStorage.setItem("access_exp_id_room", newExpiry);
        localStorage.setItem("refresh_token_id_room", newRefresh);
        localStorage.setItem("refresh_exp_id_room", newRefreshExp);

        console.log('✅ Token refreshed successfully');
        return newToken;

    } catch (err) {
        console.error('❌ Refresh token failed:', err);
        await handleLogout();
        return null;
    }
};

// **Fungsi Logout Global**
const handleLogout = async () => {
    console.log('🚪 Logging out...');
    localStorage.removeItem("access_token_id_room");
    localStorage.removeItem("user_id_room");
    localStorage.removeItem("access_exp_id_room");
    localStorage.removeItem("refresh_token_id_room");
    localStorage.removeItem("refresh_exp_id_room");

    delete Api.defaults.headers.common["Authorization"];

    store.commit("auth/AUTH_LOGOUT");

    if (router.currentRoute.value.name !== 'login') {
        router.push({ name: "login" });
    }
};

export { Api, refreshToken };