import {createStore} from 'vuex'
import auth from './auth'
import layout from './modules/layout';
import menu from './modules/menu';



export default createStore({
  state:{langIcon: '',langLangauge: '',isActive:false,  keranjang: [], notifikasi: [], notifikasipo: [], notifikasibarang: [], notifikasiPembayaran: [], notifikasiTagih:[], notifikasiClaim:[], notifikasiUangSaku:[], notifikasieBudget:[], notifappsurat:[],notifappsuratkirim:[], notifappreqbarang:[],notifikasiBarangATK:[], videotutorial:''},
  getters:{
    langIcon: (state)=>{ return state.langIcon},
    langLangauge:(state)=>{return state.langLangauge},
    currentUser(state) {
        return state.user// <-- return dengan data user
    }
  },
  mutations: {
      changeLang (state, payload) {
        localStorage.setItem('currentLanguage', payload.id);
        localStorage.setItem('currentLanguageIcon', payload.icon);
        state.langIcon = payload.icon || 'flag-icon-us'
        state.langLangauge = payload.id || 'EN'
      },
      change(state){
        state.isActive = !state.isActive
      },
      tambahKeKeranjang(state, barang) {
        
        state.keranjang.splice(0, state.keranjang.length); // Menghapus semua elemen dari array
        state.keranjang.push(...barang); // Menambahkan barang ke dalam array
      },

      notifikasi(state, barang) {
        
        state.notifikasi.splice(0, state.notifikasi.length); // Menghapus semua elemen dari array
        state.notifikasi.push(...barang); // Menambahkan barang ke dalam array
      },

      notifikasipo(state, barang) {
        
        state.notifikasipo.splice(0, state.notifikasipo.length); // Menghapus semua elemen dari array
        state.notifikasipo.push(...barang); // Menambahkan barang ke dalam array
      },


      notifikasibarang(state, barang) {
        
        state.notifikasibarang.splice(0, state.notifikasibarang.length); // Menghapus semua elemen dari array
        state.notifikasibarang.push(...barang); // Menambahkan barang ke dalam array
      },

      notifikasiPembayaran(state, barang) {
        
        state.notifikasiPembayaran.splice(0, state.notifikasiPembayaran.length); // Menghapus semua elemen dari array
        state.notifikasiPembayaran.push(...barang); // Menambahkan barang ke dalam array
      },

      notifikasiTagih(state, barang) {
        
        state.notifikasiTagih.splice(0, state.notifikasiTagih.length); // Menghapus semua elemen dari array
        state.notifikasiTagih.push(...barang); // Menambahkan barang ke dalam array
      },

      notifikasiClaim(state, barang) {
        
        state.notifikasiClaim.splice(0, state.notifikasiClaim.length); // Menghapus semua elemen dari array
        state.notifikasiClaim.push(...barang); // Menambahkan barang ke dalam array
      },

      notifikasiUangSaku(state, barang) {
        
        state.notifikasiUangSaku.splice(0, state.notifikasiUangSaku.length); // Menghapus semua elemen dari array
        state.notifikasiUangSaku.push(...barang); // Menambahkan barang ke dalam array
      },
      notifikasieBudget(state, barang) {
        
        state.notifikasieBudget.splice(0, state.notifikasieBudget.length); // Menghapus semua elemen dari array
        state.notifikasieBudget.push(...barang); // Menambahkan barang ke dalam array
      },
      notifappsurat(state, barang) {
        
        state.notifappsurat.splice(0, state.notifappsurat.length); // Menghapus semua elemen dari array
        state.notifappsurat.push(...barang); // Menambahkan barang ke dalam array
      },
      notifappsuratkirim(state, barang) {

        state.notifappsuratkirim.splice(0, state.notifappsuratkirim.length); // Menghapus semua elemen dari array
        state.notifappsuratkirim.push(...barang); // Menambahkan barang ke dalam array
      },
      notifappreqbarang(state, barang) {
        
        state.notifappreqbarang.splice(0, state.notifappreqbarang.length); // Menghapus semua elemen dari array
        state.notifappreqbarang.push(...barang); // Menambahkan barang ke dalam array
      },
      notifikasiBarangATK(state, barang) {
        
        state.notifikasiBarangATK.splice(0, state.notifikasiBarangATK.length); // Menghapus semua elemen dari array
        state.notifikasiBarangATK.push(...barang); // Menambahkan barang ke dalam array
      },
      videotutorial(state, barang) {
        
        state.videotutorial=barang; // Menambahkan barang ke dalam array
      },
      
  
    },
    actions: {
      setLang ({ commit }, payload) {
        commit('changeLang', payload);  
        
      }
    },
    modules: {
      auth,
      layout,
      menu,
  
    }
});

