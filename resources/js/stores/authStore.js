import { defineStore } from 'pinia'
import axios from 'axios'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null
    }),
    actions: {
        async fetchUser() {
            const response = await axios.get('/api/user')
            this.user = response.data
        },
        async login(credentials) {
            await axios.get('/sanctum/csrf-cookie')
            await axios.post('/api/login', credentials)
            await this.fetchUser();
        },
        async logout() {
            await axios.post('/api/logout')
            this.user = null
        }
    }
})
