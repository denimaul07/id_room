<template>
  <div
    ref="wrapperEl"
    class="lazy-image-wrapper"
    :style="wrapperStyle"
  >
    <!-- Skeleton shimmer while not loaded -->
    <div
      v-if="!loaded"
      class="absolute inset-0 bg-gray-200 animate-pulse rounded-inherit"
    ></div>
    <img
      v-if="shouldLoad"
      :src="currentSrc"
      :alt="alt"
      :class="imgClass"
      :style="loaded ? '' : 'opacity:0;position:absolute;inset:0;'"
      :loading="loading"
      @load="onLoad"
      @error="onError"
    />
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from 'vue'

const props = defineProps({
  src: { type: String, default: '' },
  alt: { type: String, default: '' },
  imgClass: { type: String, default: 'w-full h-full object-cover' },
  wrapperClass: { type: String, default: '' },
  loading: { type: String, default: 'lazy' },
  fallback: { type: String, default: '' },
})

const wrapperEl = ref(null)
const loaded = ref(false)
const shouldLoad = ref(false)
const currentSrc = ref('')

const wrapperStyle = computed(() => ({
  position: 'relative',
  width: '100%',
  height: '100%',
}))

let observer = null

onMounted(() => {
  observer = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        currentSrc.value = props.src
        shouldLoad.value = true
        observer?.disconnect()
      }
    },
    { threshold: 0.05 }
  )
  if (wrapperEl.value) observer.observe(wrapperEl.value)
})

onBeforeUnmount(() => {
  observer?.disconnect()
})

watch(() => props.src, (val) => {
  if (shouldLoad.value) {
    loaded.value = false
    currentSrc.value = val
  }
})

function onLoad() {
  loaded.value = true
}

function onError() {
  if (props.fallback && currentSrc.value !== props.fallback) {
    currentSrc.value = props.fallback
  }
}
</script>
