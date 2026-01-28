<template>
  <div>
    <div class="customizer-links" :class="{ open: customizer }">
      <div class="nav flex-column nac-pills bg-primary mb-1" id="c-pills-tab">
        <CustomizerTool />
        <!-- <CustomizerSetting />
        <Support />
        <Document />
        <CheckFeatures />
        <BuyNow /> -->
      </div>
  
      <div class="nav flex-column nac-pills" style="background-color:#2cd46b;" id="c-pills-tab" v-if="route.path=='/dashboard_purchase' || route.path=='/detail_permintaan' || route.path=='/request_permintaan'">
        <Support />
        <!-- <CustomizerSetting />
        <Support />
        <Document />
        <CheckFeatures />
        <BuyNow /> -->
      </div>
    </div>
    <div class="customizer-contain" :class="{ open: customizer }">
      <div class="tab-content">
        <CustomizerConfiguration />
        <div class="customizer-body custom-scrollbar">
          <Customlayout />
          <CustomSetting />
        </div>
      </div>
    </div>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import BuyNow from './buynow.vue';
  import CheckFeatures from './checkfeatures.vue';
  import Document from './document.vue';
  import Support from './support.vue';
  import CustomizerTool from './customizertool.vue';
  import CustomizerSetting from './customizersetting.vue';
  import CustomizerConfiguration from './customizerconfiguration.vue';
  import Customlayout from './customlayout.vue';
  import CustomSetting from './customsetting.vue';


  export default {
    
    name: 'customizer',
    components : {
      BuyNow,
      CheckFeatures,
      Document,
      Support,
      CustomizerTool,
      CustomizerSetting,
      CustomizerConfiguration,
      Customlayout,
      CustomSetting
      
    },
    computed: {
      ...mapState({
        customizer: (state) => state.menu.customizer,
      }),
    },
  };
</script>

<script setup>
  import { useRoute } from 'vue-router'
  const route = useRoute()
</script>

<style scoped>
    .customizer-links {
        display: block;
    }
</style>