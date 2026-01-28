<template>
  <div>

    <div class="loader-wrapper" v-if="loading">
      <img
        class="img-fluid for-light"
        src="./assets/images/logo/logo_idroom.png"
        width="30%"
        alt="loading"
      />
    </div>

    <router-view></router-view>
  </div>
</template>

<script>
import Swal from "sweetalert2";
import { startIdleWatcher } from "./utils/idle";
import { Api } from '@/api/Api';
import store from "@/store";
import router from "@/router";

export default {
  name: "App",

  data() {
    return {
      loading: true,
    };
  },

  watch: {
    $route() {
      this.loading = true;
      setTimeout(() => (this.loading = false), 500);
    },
  },

  methods: {
    showCountdown(seconds, onExpired) {
      let timerInterval;

      Swal.fire({
        title: "Session akan berakhir!",
        html: `Anda akan logout dalam <b>${seconds}</b> detik.<br><br>Gerakkan mouse atau tekan tombol untuk tetap login.`,
        timer: seconds * 1000,
        timerProgressBar: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
        allowEnterKey: false,

        didOpen: () => {
          const b = Swal.getHtmlContainer().querySelector("b");

          timerInterval = setInterval(() => {
            b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
          }, 200);
        },

        willClose: () => {
          clearInterval(timerInterval);
        },
      }).then((result) => {
        // Jika modal ditutup karena timer habis → logout
        if (result.dismiss === Swal.DismissReason.timer) {
          onExpired();
        }
      });
    },

    forceLogout() {
        localStorage.removeItem("access_token_id_room");
        localStorage.removeItem("user_id_room");
        localStorage.removeItem("access_exp_id_room");
        localStorage.removeItem("refresh_token_id_room");
        localStorage.removeItem("refresh_exp_id_room");
        delete Api.defaults.headers.common["Authorization"];

        store.commit("auth/AUTH_LOGOUT");
        router.push({ name: "login" });
        
    },
  },

  mounted() {
    this.loading = false;

    // Idle watcher FE
    startIdleWatcher(
      (seconds, callback) => this.showCountdown(seconds, callback),
      () => this.forceLogout()
    );
  },
};
</script>

<style>
.swal2-container {
  z-index: 9000;
}
</style>
