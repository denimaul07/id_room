<template>
  <a
    class="nav-link" @click="open"
  >
    <div class="settings">
      <i class="icon-info-alt" style="color: white;"></i>
    </div>
    <span>Lihat Tutorial</span>
  </a>

  <a-modal v-model:open="modalTutorial" style="top: 20px"  :footer="null"  width="1000px">
        <video controls style="max-width: 100%;" :key="videoKey">
            <source :src="'https://portal.hondaimora.com/iss/api/storage/video/'+videotutorial" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </a-modal>
</template>

<script setup>

  import { useRoute } from 'vue-router'
  import { ref,computed,watch } from 'vue'
  import {useStore } from "vuex";
  // import backgroundImages from '@/assets/images/video';
  const route = useRoute()
  const store = useStore()
  const modalTutorial=ref(false)
  const videotutorial= computed(() => store.state.videotutorial)
  const videoKey = ref(0);

  const open = () => {
    modalTutorial.value=true
  }

  watch(videotutorial, () => {
    videoKey.value++;
  });
</script>
