<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useAuthStore } from './store/authStore';

const auth = useAuthStore();
auth.fetchUser();
</script>

<template>
    <RouterView />
</template>