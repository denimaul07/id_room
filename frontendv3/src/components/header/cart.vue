<template>
  
  <li class="cart-nav onhover-dropdown">
    <div class="cart-box">
      <svg>
        <use href="@/assets/svg/icon-sprite.svg#stroke-ecommerce" @click="cart_open()"></use>
      </svg>
      <span class="badge rounded-pill badge-success">{{ daftarKeranjang.length }}</span>
    </div>
    <div class="cart-dropdown onhover-show-div" :class="{ active: cart }">
      <h6 class="f-18 mb-0 dropdown-title">Cart</h6>
      <ul>
        <li class="mt-0"  v-if="daftarKeranjang.length==0">
          <div class="media">
              <span>Tidak Ada Barang</span>
          </div>
        </li>

        

        <li class="mt-0"  v-for="(item, index) in daftarKeranjang" :key="index" v-else>
          <div class="media">
            <img class="img-fluid b-r-5 me-3 img-5" :src="'https://portal.hondaimora.com/iss/api/storage/barang/'+item.file" alt="" v-if="item.type==0"/>
            <img class="img-fluid b-r-5 me-3 img-60" :src="'https://portal.hondaimora.com/iss/api/storage/foto/user.png'" alt="" v-else/>
            <div class="media-body">
              <span v-if="item.type==0">{{ item.namabarang }}</span>
              <span v-if="item.type==1">{{ item.nmbarangjasa }}</span>

              <h6 class="font-primary mb-0" v-if="item.type==0">{{ item.harga_barang.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }} x {{ item.qty + ' ' + item.unit }}</h6>
              <h6 class="font-primary mb-0" v-if="item.type==1">{{ item.hargajasa.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' }).slice(0, -3) }} x {{ item.qty + ' ' + item.unit }}</h6>
            </div>
            <div class="close-circle">
              <button class="btn btn-link text-danger ms-5" @click="removebarang(item.idkeranjang)"><i class="fa fa-times"></i></button>
            </div>
          </div>
        </li>

        <li class="text-center" v-if="daftarKeranjang.length!=0">
          <router-link :to="'/checkout'" class="btn btn-primary view-checkout" >
            Checkout
          </router-link>
        </li>
      </ul>
    </div>
  </li>
</template>

<script setup>
  import { Api } from '@/api/Api';
  import { ref, onMounted, computed } from 'vue'
  import {useStore } from "vuex";
  import Swal from 'sweetalert2';
  import Pusher from 'pusher-js';
  const store = useStore()
  // const router = useRouter();
  const user = store.getters["auth/currentUser"];
  const cart = ref(false)
  const pesan = ref("")
  const listbarang = ref({})
  const cart_open = () => {
    cart.value = ! cart.value
  }

  const daftarKeranjang = computed(() => store.state.keranjang)



  const removebarang = async(id) => {
    const token = localStorage.getItem('access_access_token_iss')
    Api.defaults.headers.common['Authorization'] = "Bearer " +token
    await Api.delete('/staff/addToCart', {
      params:{
        id:id
      }
    })
    .then((response) => {
      pesan.value = "Barang Di Keranjang Anda Sudah Di Hapus"
      Swal.fire({
          icon: "success",
          title: "Yeyy Successs",
          text: pesan.value,
          confirmButtonColor: '#1e3a8a',
          confirmButtonText: '<i class="fa fa-check"></i> OKE',
          footer: '<a href="#">Why do I have this issue?</a>'
      });

      getDataCart()
    
    })
    .catch((error) => {
        
        if (error.response.data.status_code === 403) {
            pesan.value =  "Anda Tidak Memiliki Akses Ke Fungsi Ini"
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });

            return error.response.data.status_code
        } else {
            const object1 = error.response.data
            pesan.value =  Object.values(object1).toString()
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: pesan.value,
                confirmButtonColor: '#1e3a8a',
                confirmButtonText: '<i class="fa fa-check"></i> OKE',
                footer: '<a href="#">Why do I have this issue?</a>'
            });

            return error.response.data
        
        }
    });
  }

  const getDataCart = async() => {

        await Api.get('/staff/addToCart')
        .then((response) => {

            listbarang.value = response.data.data
            store.commit('tambahKeKeranjang',  listbarang.value)
          
        })
        .catch((error) => {

            if (error.response.data.status_code === 403) {
                pesan.value =  "Anda Tidak Memiliki Akses Ke Fungsi Ini"
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });

                return error.response.data.status_code
        
            } else {
                const object1 = error.response.data
                pesan.value =  Object.values(object1).toString()
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: pesan.value,
                    confirmButtonColor: '#1e3a8a',
                    confirmButtonText: '<i class="fa fa-check"></i> OKE',
                    footer: '<a href="#">Why do I have this issue?</a>'
                });

                return error.response.data
            
            }

        
        });
  }


  
  onMounted(async() => {
   
    await getDataCart()
    
  
  })

</script>
