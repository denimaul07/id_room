//import global API
import { Api } from '@/api/Api';

const auth = {
    namespaced: true,

    state: {
        access_token: localStorage.getItem('access_token_id_room') || '',
        access_exp: localStorage.getItem('access_exp_id_room') || '',
        refresh_token: localStorage.getItem('refresh_token_id_room') || '',
        refresh_exp: localStorage.getItem('refresh_exp_id_room') || '',
        user: JSON.parse(localStorage.getItem('user_id_room')) || {},
    },

    mutations: {
        AUTH_SUCCESS(state, payload) {
            state.access_token = payload.access_token;
            state.access_exp = payload.access_exp;
            state.refresh_token = payload.refresh_token;
            state.refresh_exp = payload.refresh_exp;
            state.user = payload.user;
        },

        GET_USER(state, user) {
            state.user = user;
        },

        AUTH_LOGOUT(state) {
            state.access_token = "";
            state.access_exp = "";
            state.refresh_token = "";
            state.refresh_exp = "";
            state.user = {};
        }
    },

    actions: {
        getUser({ commit }) {
            const token = localStorage.getItem("access_token_id_room");
            Api.defaults.headers.common["Authorization"] = "Bearer " + token;

            return Api.get("/user")
                .then((response) => {
                    commit("GET_USER", response.data.user);
                });
        },

        login({ commit }, user) {
            return new Promise((resolve, reject) => {
                Api.post("/auth/login", {
                    email: user.email,
                    password: user.password,
                })
                    .then((res) => {
                    
                        const access_token = res.data.token;
                        const access_exp = res.data.expired_in;
                        const refresh_token = res.data.refresh_token;
                        const refresh_exp = res.data.refresh_exp;
                        const userData = res.data.users;


                        localStorage.setItem("access_token_id_room", access_token);
                        localStorage.setItem("access_exp_id_room", access_exp); // selalu timestamp absolut
                        localStorage.setItem("refresh_token_id_room", refresh_token);
                        localStorage.setItem("refresh_exp_id_room", refresh_exp);
                        localStorage.setItem("user_id_room", JSON.stringify(userData));

                        // Set header axios
                        Api.defaults.headers.common["Authorization"] = "Bearer " + access_token;

                        // Commit ke Vuex
                        commit("AUTH_SUCCESS", {
                            access_token,
                            access_exp,
                            refresh_token,
                            refresh_exp,
                            user: userData
                        });

                        resolve(true);
                    })
                    .catch((err) => {
                        localStorage.removeItem("access_token_id_room");
                        localStorage.removeItem("refresh_token_id_room");
                        reject(err.response.data);
                    });
            });
        },

        logout({ commit }) {
            return new Promise(async (resolve) => {
                const refresh = localStorage.getItem("refresh_token_id_room");

                // kirim ke backend supaya refresh token di-revoke
                if (refresh) {
                    await Api.post("/auth/logout", {
                        refresh_token: refresh
                    }).catch(() => {});
                }

                commit("AUTH_LOGOUT");

                localStorage.removeItem("access_token_id_room");
                localStorage.removeItem("access_exp_id_room");
                localStorage.removeItem("refresh_token_id_room");
                localStorage.removeItem("refresh_exp_id_room");
                localStorage.removeItem("user_id_room");

                delete Api.defaults.headers.common["Authorization"];

                resolve();
            });
        }
    },

    getters: {
        currentUser(state) {
            return state.user;
        },

        isLoggedIn(state) {
            return !!state.access_token;
        },
    }
}

export default auth;