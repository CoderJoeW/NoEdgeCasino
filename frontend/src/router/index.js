import { createRouter, createWebHistory } from 'vue-router'
import Login from '@/pages/Login.vue'
import SignupPage from '@/pages/SignupPage.vue'
import Dashboard from '@/pages/Dashboard.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Dashboard,
    },
    {
        path: '/register',
        name: 'register',
        component: SignupPage,
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
    },
  ],
})

export default router
