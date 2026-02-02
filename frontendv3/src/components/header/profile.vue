<template>
  <li class="profile-nav onhover-dropdown pe-0 py-0">
    <div class="media profile-media">
      <div
        class="rounded-circle"
        style="width: 35px; height: 35px; overflow: hidden"
        v-if="foto != null"
      >
        <img
          style="width: 100%; height: 100%; object-fit: cover"
          :src="pathUrl + '/storage/foto/' + foto"
          alt=""
        />
      </div>

      <div
        class="rounded-circle"
        style="width: 35px; height: 35px; overflow: hidden"
        v-else
      >
        <img
          style="width: 100%; height: 100%; object-fit: cover"
          :src="pathUrl + '/storage/foto/user.png'"
          alt=""
        />
      </div>

      <div class="media-body">
        <span>{{ name }}</span>
        <p class="mb-0 font-roboto">
          {{ roles }} <i class="middle fa fa-angle-down"></i>
        </p>
      </div>
    </div>
    <ul class="profile-dropdown onhover-show-div">
      <li>
        <a @click="add"><vue-feather type="user"></vue-feather><span>Account</span></a>
      </li>

      <li>
        <a @click="logout"
          ><vue-feather type="log-in"></vue-feather><span>Log out</span></a
        >
      </li>
    </ul>
  </li>

  <a-modal v-model:open="modalAdd" title="My Account" width="1000px">
    <div class="col-12">
      <div class="row">
        <div class="col-xxl-4 col-md-4 col-sm-12">
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"> Email</label>
            <div class="col-sm-8">
              <input class="form-control" v-model="email" readonly />
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"> Old Password</label>
            <div class="col-sm-8">
              <div class="input-group">
                <input
                  class="form-control"
                  :type="active ? 'password' : 'text'"
                  v-model="oldpass"
                  placeholder="*********"
                  required
                />
                <button
                  type="button"
                  class="input-group-text pointer-event"
                  id="basic-addon1"
                  @click.prevent="show"
                >
                  <i
                    :class="active ? 'fa fa-eye' : 'fa fa-eye-slash'"
                    aria-hidden="true"
                  ></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-md-4 col-sm-12">
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"> Name</label>
            <div class="col-sm-8">
              <input class="form-control" v-model="name" readonly />
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"> New Password</label>
            <div class="col-sm-8">
              <div class="input-group">
                <input
                  class="form-control"
                  :type="active ? 'password' : 'text'"
                  v-model="newpass"
                  placeholder="*********"
                  required
                />
                <button
                  type="button"
                  class="input-group-text pointer-event"
                  id="basic-addon1"
                  @click.prevent="show"
                >
                  <i
                    :class="active ? 'fa fa-eye' : 'fa fa-eye-slash'"
                    aria-hidden="true"
                  ></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-xxl-4 col-md-4 col-sm-12">
          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"> No WA</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" v-model="wa" />
            </div>
          </div>

          <div class="mb-3 row">
            <label class="col-sm-4 col-form-label"> Starus Notif</label>
            <div class="col-sm-8">
              <select v-model="status_notif" class="form-control">
                <option value="0">Email Only</option>
                <option value="1">Wa Only</option>
                <option value="2">Email & WA</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>

    <template #footer>
      <button
        type="button"
        :disabled="loadingButton"
        class="btn btn-primary me-2"
        @click="saveData"
      >
        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
          <span class="sr-only">Loading...</span>
        </div>
        <i class="fa fa-send me-2" aria-hidden="true" v-else></i>
        Change Password
      </button>

      <button
        type="button"
        :disabled="loadingButton"
        class="btn btn-success me-2"
        @click="updateWa"
      >
        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
          <span class="sr-only">Loading...</span>
        </div>
        <i class="fa fa-send me-2" aria-hidden="true" v-else></i>
        Update Wa dan Status
      </button>

      <button
        type="button"
        :disabled="loadingButton"
        class="btn btn-info"
        @click="sendwa"
      >
        <div class="spinner-border spinner-border-sm" role="status" v-if="loadingSubmit">
          <span class="sr-only">Loading...</span>
        </div>
        <i class="fa fa-send me-2" aria-hidden="true" v-else></i>
        Test Send WhatsApp
      </button>
    </template>

    <a-modal v-model:open="processing" :footer="null" :closable="false" width="400px">
      <div
        style="align-items: center; justify-content: center; display: flex"
        width="100px"
      >
        <img class="img-fluid" :src="waitingicon" alt="vector women with leptop" />
      </div>

      <div style="align-items: center; justify-content: center; display: flex">
        {{ pesan }}
      </div>
    </a-modal>
  </a-modal>
</template>

<script setup>
import { Api } from "@/api/Api";
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { ref, onMounted } from "vue";
import waitingicon from "@/assets/images/logo/loading.gif";
const pathUrl = import.meta.env.VITE_PATH_FILE_BASE_URL;
import Swal from "sweetalert2";
const token = localStorage.getItem("access_token_iss");
Api.defaults.headers.common["Authorization"] = "Bearer " + token;
const store = useStore();
const router = useRouter();
const modalAdd = ref(false);

const user = ref(store.getters["auth/currentUser"]);
const id = ref();
const email = ref();
const name = ref();
const foto = ref();
const wa = ref();
const roles = ref();
const status_notif = ref();
const pesan = ref("");
const oldpass = ref("");
const newpass = ref("");
const active = ref(true);
const processing = ref(false);
const loadingButton = ref(false);
const loadingSubmit = ref(false);
const show = () => {
  active.value = !active.value;
};
function logout() {
  //panggil action "logout" di dalam module "auth"
  store.dispatch("auth/logout").then(() => {
    //jika berhasil, akan di arahkan ke route login
    router.push({
      name: "login",
    });
  });
}

const add = () => {
  modalAdd.value = true;
};

const saveData = async () => {
  pesan.value = "Mohon Sabar, Lagi Proses Simpan Data";
  processing.value = true;
  await Api.put("/admin/update_profile", {
    id: id.value,
    oldpass: oldpass.value,
    newpass: newpass.value,
  })
    .then(async (response) => {
      pesan.value = "Password Berhasil Di Update";
      Swal.fire({
        icon: "success",
        title: "Yeyy Successs",
        text: pesan.value,
        confirmButtonColor: "#1e3a8a",
        confirmButtonText: '<i class="fa fa-check"></i> OKE',
        footer: '<a href="#">Why do I have this issue?</a>',
      });
      processing.value = false;
      oldpass.value = "";
      newpass.value = "";
    })
    .catch((error) => {
      processing.value = false;
      const object1 = error.response.data.data;
      pesan.value = Object.values(object1).toString();
      Swal.fire({
        icon: "error",
        title: "Uppps",
        text: pesan.value,
        confirmButtonColor: "#1e3a8a",
        confirmButtonText: '<i class="fa fa-check"></i> OKE',
        footer: '<a href="#">Why do I have this issue?</a>',
      });
    });
};

const updateWa = async () => {
  pesan.value = "Mohon Sabar, Lagi Proses Simpan Data";
  processing.value = true;
  await Api.put("/admin/update_wa", {
    id: id.value,
    wa: wa.value,
    status_notif: status_notif.value,
  })
    .then(async (response) => {
      pesan.value = "No WA Berhasil Di Update";
      Swal.fire({
        icon: "success",
        title: "Yeyy Successs",
        text: pesan.value,
        confirmButtonColor: "#1e3a8a",
        confirmButtonText: '<i class="fa fa-check"></i> OKE',
        footer: '<a href="#">Why do I have this issue?</a>',
      });
      processing.value = false;
      oldpass.value = "";
      newpass.value = "";
    })
    .catch((error) => {
      processing.value = false;
      const object1 = error.response.data.data;
      pesan.value = Object.values(object1).toString();
      Swal.fire({
        icon: "error",
        title: "Uppps",
        text: pesan.value,
        confirmButtonColor: "#1e3a8a",
        confirmButtonText: '<i class="fa fa-check"></i> OKE',
        footer: '<a href="#">Why do I have this issue?</a>',
      });
    });
};

const sendwa = async () => {
  pesan.value = "Mohon Sabar, Lagi Proses Simpan Data";
  processing.value = true;
  await Api.post("/admin/send_wa", {
    wa: wa.value,
    status_notif: status_notif.value,
  })
    .then(async (response) => {
      pesan.value = "Sukses Kirim WA";
      Swal.fire({
        icon: "success",
        title: "Yeyy Successs",
        text: pesan.value,
        confirmButtonColor: "#1e3a8a",
        confirmButtonText: '<i class="fa fa-check"></i> OKE',
        footer: '<a href="#">Why do I have this issue?</a>',
      });
      processing.value = false;
      oldpass.value = "";
      newpass.value = "";
    })
    .catch((error) => {
      processing.value = false;
      const object1 = error.response.data.data;
      pesan.value = Object.values(object1).toString();
      Swal.fire({
        icon: "error",
        title: "Uppps",
        text: pesan.value,
        confirmButtonColor: "#1e3a8a",
        confirmButtonText: '<i class="fa fa-check"></i> OKE',
        footer: '<a href="#">Why do I have this issue?</a>',
      });
    });
};

onMounted(async () => {
  id.value = user.value.id;
  email.value = user.value.email;
  name.value = user.value.name;
  wa.value = user.value.wa;
  roles.value = user.value.roles[0].name;
  status_notif.value = user.value.status_notif;
  foto.value = user.value.foto;
});
</script>
