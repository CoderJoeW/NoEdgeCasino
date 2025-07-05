<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-slate-800 text-gray-100">
        <Sidebar>
            <nav class="space-y-1">
                <NavLink :active="false" link="/" linkValue="Dashboard"/>
                <NavLink :active="true" link="/limbo" linkValue="Play Limbo"/>
            </nav>
        </Sidebar>

        <!-- Main Content -->
        <div class="pl-0 md:pl-64 transition-all duration-200">
            <Header/>
            <MainContainer>
                <Limbo/>
            </MainContainer>
        </div>
    </div>
</template>
  
<script>
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import MainContainer from '@/components/MainContainer.vue';
import NavLink from '@/components/NavLink.vue';

// Games
import Limbo from '@/components/Games/Limbo.vue';

import { useAuthStore } from '@/store/authStore';

export default {
    name: 'Limbo',
    components: {
        Header,
        Sidebar,
        MainContainer,
        NavLink,

        // Games
        Limbo,
    },
    data() {
        return {
            auth: useAuthStore(),
        };
    },
    mounted() {
        if (!this.auth.isAuthenticated) {
            window.location.href = '/login';
        }
    },
    methods: {

    },
}
</script>