<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-slate-800 text-gray-100">
        <Sidebar>
            <nav class="space-y-1">
                <RouterLink to="/" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors bg-gray-800/70 text-teal-400">Dashboard</RouterLink>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-gray-400 hover:text-gray-100 hover:bg-gray-800/40">Test inactive</a>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-gray-400 hover:text-gray-100 hover:bg-gray-800/40" @click="playGame('Limbo')">Play Limbo</a>
            </nav>
        </Sidebar>

        <!-- Main Content -->
        <div class="pl-0 md:pl-64 transition-all duration-200">
            <Header/>
            <MainContainer>
                <div v-if="loadedGame === 'Limbo'">
                    <Limbo/>
                </div>
            </MainContainer>
        </div>
    </div>
</template>
  
<script>
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import MainContainer from '@/components/MainContainer.vue';

// Games
import Limbo from '@/components/Games/Limbo.vue';

import { useAuthStore } from '@/store/authStore';
import api from '@/scripts/axios';

export default {
    name: 'Dashboard',
    components: {
        Header,
        Sidebar,
        MainContainer,

        // Games
        Limbo,
    },
    data() {
        return {
            auth: useAuthStore(),
            loadedGame: null,
        };
    },
    mounted() {
        if (!this.auth.isAuthenticated) {
            window.location.href = '/login';
        }
    },
    methods: {
        playGame(gameName) {
            this.loadedGame = gameName;
        }
    },
}
</script>