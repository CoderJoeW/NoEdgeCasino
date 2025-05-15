<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 to-slate-800 text-gray-100">
        <Sidebar>
            <nav class="space-y-1">
                <RouterLink to="/" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors bg-gray-800/70 text-teal-400">Dashboard</RouterLink>
                <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-gray-400 hover:text-gray-100 hover:bg-gray-800/40">Test inactive</a>
            </nav>
        </Sidebar>

        <!-- Main Content -->
        <div class="pl-0 md:pl-64 transition-all duration-200">
            <Header/>
            <MainContainer>
                <div class="flex items-center justify-center h-[calc(100vh-4rem)]">
                    <div class="p-8 rounded-xl bg-gray-800/40 border border-gray-700 backdrop-blur-sm max-w-md w-full">
                        <h2 class="text-2xl font-bold mb-6 text-center">Welcome Back</h2>
                        <p class="text-gray-400 text-center mb-6">Your dashboard is ready. All systems are operational.</p>
                        <div class="flex justify-center">
                            <button class="px-4 py-2 bg-gradient-to-r from-teal-500 to-emerald-400 rounded-lg text-gray-900 font-medium hover:opacity-90 transition-opacity">
                                View Reports
                            </button>
                        </div>
                    </div>
                </div>
            </MainContainer>
        </div>
    </div>
</template>
  
<script>
import Header from '@/components/Header.vue';
import Sidebar from '@/components/Sidebar.vue';
import MainContainer from '@/components/MainContainer.vue';

export default {
    name: 'Dashboard',
    components: {
        Header,
        Sidebar,
        MainContainer,
    },
    data() {
        return {

        };
    },
    mounted() {

    },
    methods: {

    },
}
</script>