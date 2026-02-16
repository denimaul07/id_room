import { defineStore } from 'pinia'
import { apiGetData } from '@/store/action'

export const useCityStore = defineStore('city', {
    state: () => ({
        data: null,
        type: null,
        typeCount: null,
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
                this.type = res.type_properties // fix: ambil array type properties 
                this.typeCount = res.type_properties_count || []
                this.loaded = true
            } catch (e) {
                this.error = e
            } finally {
                this.loading = false
            }
        }
    }
})
