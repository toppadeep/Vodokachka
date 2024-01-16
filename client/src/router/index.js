import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

import pinia from "@/stores/store.js";
import { useUserStore } from '@/stores/UserStore'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/admin',
      name: 'admin',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AdminView.vue'),
      meta: {
        requiresAuth: true
      }
    }
  ]
})

const UserStore = useUserStore(pinia );
console.log(UserStore.isAuthenticated)
router.beforeEach((to, from) => {
  if (to.meta.requiresAuth && !UserStore.isAuthenticated) {
    return {
      name: 'home'
    }
  }
})


export default router
