<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600 mb-6">Please sign in to your account</p>
            </div>
        
            <form @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Enter your email"
                    />
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Enter your password"
                    />
                </div>
                
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            v-model="remember"
                            type="checkbox"
                            class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                        />
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    
                    <a href="#" class="text-sm text-emerald-600 hover:text-emerald-500">Forgot password?</a>
                </div>
                
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full bg-emerald-600 text-white py-2 px-4 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors"
                >
                    <span v-if="isLoading">Signing in...</span>
                    <span v-else>Sign in</span>
                </button>
            </form>
            
            <div v-if="errorMessage" class="mt-4 text-sm text-center text-red-600">
                {{ errorMessage }}
            </div>
            
            <div class="mt-6 text-center text-sm">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="/register" class="ml-1 text-emerald-600 hover:text-emerald-500">Sign up</a>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { useAuthStore } from '@/store/authStore'
import { useRouter } from 'vue-router'

export default {
    name: 'Login',
    data() {
        return {
            email: '',
            password: '',
            remember: false,
            isLoading: false,
            errorMessage: ''
        }
    },
    methods: {
        async handleLogin() {
            this.isLoading = true
            this.errorMessage = ''
            try {
                const auth = useAuthStore()
                await auth.login(this.email, this.password)
                if (auth.isAuthenticated) {
                    const router = useRouter()
                    router.push({ name: 'Home' })
                } else {
                    this.errorMessage = 'Invalid email or password. Please try again.'
                }
            } catch (error: any) {
                this.errorMessage = error?.response?.data?.message || 'Login failed. Please try again.'
            } finally {
                this.isLoading = false
            }
        }
    }
}
</script>