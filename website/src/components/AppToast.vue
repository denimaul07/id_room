<template>
    <div v-if="show" class="app-toast">
        {{ message }}
    </div>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useToastStore } from '@/store/toast'

const { show, message } = storeToRefs(useToastStore())
</script>

<style>
.app-toast {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translate(-50%, 20px);
    background: #111827;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    opacity: 1;
    animation: toastUp .3s ease;
    z-index: 9999;
}

@keyframes toastUp {
    from { transform: translate(-50%,40px); opacity:0 }
    to { transform: translate(-50%,0); opacity:1 }
}
</style>
