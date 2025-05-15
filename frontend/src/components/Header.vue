<template>
    <header class="sticky top-0 z-10 bg-gray-900/30 backdrop-blur-sm border-b border-gray-700">
        <div class="flex items-center justify-between h-16 px-4">
            <div class="flex-1 px-4 md:px-0 flex justify-center items-center">
                <div class="flex items-center gap-2 bg-gray-800/40 px-4 py-2 rounded-lg border border-gray-700">
                    <span class="font-medium" v-if="auth !== null">${{ auth.user.no_edge_cash }}</span>
                </div>
            </div>
      
            <div class="flex items-center gap-4">
                <button class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/40">
                </button>
                <button class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-gray-800/40">
                </button>
                <div class="h-8 w-8 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500"></div>
            </div>
        </div>
    </header>
</template>

<script>
import { useAuthStore } from '@/store/authStore';

export default {
    name: 'Header',
    data() {
        return {
            auth: useAuthStore(),
        };
    }
}
</script>