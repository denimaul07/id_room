import { defineStore } from 'pinia'
import { apiGetData } from '@/store/action'

export const useCityStore = defineStore('city', {
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
                const res = await apiGetData('public/list-city', {})
                this.data = res.data // fix: ambil array data
                this.loaded = true
            } catch (e) {
                this.error = e
            } finally {
                this.loading = false
            }
        }
    }
})
