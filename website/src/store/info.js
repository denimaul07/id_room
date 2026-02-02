import { defineStore } from 'pinia'
import { apiGetData } from '@/store/action'

export const useInfoStore = defineStore('info', {
    state: () => ({
        data: null,
        loading: false,
        loaded: false,
        error: null
    }),

    actions: {
        async fetch() {
            try {
                this.loading = true
                const res = await apiGetData('public/info', {})
                this.data = res.data
                this.loaded = true
            } catch (e) {
                this.error = e
            } finally {
                this.loading = false
            }
        }
    }
})
