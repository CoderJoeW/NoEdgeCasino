<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-slate-800 text-gray-100">
        <Sidebar>
            <nav class="space-y-1">
                <NavLink :active="true" link="/" linkValue="Dashboard"/>
                <NavLink :active="false" link="/limbo" linkValue="Play Limbo"/>
            </nav>
        </Sidebar>

        <!-- Main Content -->
        <div class="pl-0 md:pl-64 transition-all duration-200">
            <Header/>
            <MainContainer>
                <h4>This is the dashboard</h4>
            </MainContainer>
        </div>
    </div>
</template>
  
<script>
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import MainContainer from '@/components/MainContainer.vue';
import NavLink from '@/components/NavLink.vue';

import { useAuthStore } from '@/store/authStore';

export default {
    name: 'Dashboard',
    components: {
        Header,
        Sidebar,
        MainContainer,
        NavLink,
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