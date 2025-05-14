<script setup>
import { RouterLink, RouterView } from 'vue-router'
</script>

<template>
    <RouterLink to="/">Home</RouterLink>
    <div class="bg-gradient-to-br from-gray-900 to-slate-800">
        <RouterView />
    </div>
</template>