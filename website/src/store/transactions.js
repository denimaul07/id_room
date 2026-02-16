import { defineStore } from 'pinia'
import { apiGetData } from '@/store/action'

export const useTransactionsStore = defineStore('transactions', {
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
                const res = await apiGetData('membership/my-transactions', {})
                this.data = res.data
                ? (Array.isArray(res.data) ? res.data : [res.data])
                : []
                this.loaded = true
            } catch (e) {
                this.error = e
            } finally {
                this.loading = false
            }
        }
    }
})