import { defineStore } from 'pinia';
import api from '@/scripts/axios';

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        isAuthenticated: false,
    }),
    persist: true,
    actions: {
        async fetchUser() {
            try {
                const res = await api.get('/api/user');
                this.user = res.data;
                this.isAuthenticated = true;
            } catch {
                this.user = null;
                this.isAuthenticated = false;
            }
        },
        async login(email: string, password: string) {
            await api.get('/sanctum/csrf-cookie');
            await api.post('/api/login', { email, password });
            await this.fetchUser();
        },
        async logout() {
            await api.post('/api/logout', {});
            this.user = null;
            this.isAuthenticated = false;
        }
    }
});