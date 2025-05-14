<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Account</h2>
                <p class="text-gray-600 mb-6">Sign up to get started</p>
            </div>
            <form @submit.prevent="handleSignup">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input
                        id="name"
                        v-model="name"
                        type="text"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Enter your full name"
                    />
                </div>
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
                    <p v-if="emailError" class="mt-1 text-sm text-red-600">{{ emailError }}</p>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Create a password"
                    />
                </div>
                <div class="mb-6">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input
                        id="confirmPassword"
                        v-model="confirmPassword"
                        type="password"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                        placeholder="Confirm your password"
                    />
                    <p v-if="passwordMismatch" class="mt-1 text-sm text-red-600">Passwords do not match</p>
                </div>
                <button
                    type="submit"
                    :disabled="isLoading || !isFormValid"
                    class="w-full bg-emerald-600 text-white py-2 px-4 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span v-if="isLoading">Creating account...</span>
                    <span v-else>Sign up</span>
                </button>
            </form>
            <div v-if="errorMessage" class="mt-4 text-sm text-center text-red-600">
                {{ errorMessage }}
            </div>
            <div class="mt-6 text-center text-sm">
                <span class="text-gray-600">Already have an account?</span>
                <a href="/login" class="ml-1 text-emerald-600 hover:text-emerald-500">Sign in</a>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { useRouter } from 'vue-router'
import api from '@/scripts/axios';

export default {
    name: 'SignupPage',
    data() {
        return {
            name: '',
            email: '',
            password: '',
            confirmPassword: '',
            isLoading: false,
            errorMessage: '',
            emailError: ''
        }
    },
    computed: {
        passwordMismatch() {
            return this.confirmPassword && this.password !== this.confirmPassword
        },
        isFormValid() {
            return (
                this.name.trim() !== '' &&
                this.email.trim() !== '' &&
                this.validateEmail(this.email) &&
                this.password.length >= 8 &&
                this.password === this.confirmPassword
            )
        }
    },
    watch: {
        email(newValue) {
            if (newValue && !this.validateEmail(newValue)) {
                this.emailError = 'Please enter a valid email address'
            } else {
                this.emailError = ''
            }
        }
    },
    methods: {
        validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
            return re.test(email)
        },
        async handleSignup() {
            this.isLoading = true
            this.errorMessage = ''

            if (!this.validateEmail(this.email)) {
                this.emailError = 'Please enter a valid email address'
                throw new Error('Invalid email')
            }

            if (this.password !== this.confirmPassword) {
                throw new Error('Passwords do not match')
            }

            await api.get('/sanctum/csrf-cookie');

            try {
                await api.post('/api/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password
                });

                this.$router.push({ name: 'login'});
            } catch (error) {
                if (error.message === 'Passwords do not match') {
                    // This is handled by the computed property
                } else if (error.message === 'Invalid email') {
                    // This is handled by the watch
                } else if (error.response && error.response.data && error.response.data.message) {
                    this.errorMessage = error.response.data.message
                } else if (error.response && error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors
                    this.errorMessage = Object.values(errors).flat().join(' ')
                } else {
                    this.errorMessage = 'Failed to create account. Please try again.'
                    console.error('Signup error:', error)
                }
            } finally {
                this.isLoading = false
            }
        }
    }
}
</script>