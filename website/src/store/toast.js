import { defineStore } from 'pinia'

export const useToastStore = defineStore('toast', {
    state: () => ({
        show: false,
        message: ''
    }),

    actions: {
        open(msg) {
            this.message = msg
            this.show = true

            setTimeout(() => {
                this.show = false
            }, 2000)
        }
    }
})
