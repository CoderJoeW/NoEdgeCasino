<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from './store/authStore';

const auth = useAuthStore();
auth.fetchUser();
</script>

<template>
    <RouterLink to="/">Home</RouterLink>
    <div class="bg-gradient-to-br from-gray-900 to-slate-800">
        <RouterView />
    </div>
</template>