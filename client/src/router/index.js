import {
  createRouter,
  createWebHistory
} from 'vue-router'
import HomeView from '../views/HomeView.vue'

import pinia from "@/stores/store.js";
import {
  useUserStore
} from '@/stores/UserStore'

const router = createRouter({
  history: createWebHistory(
    import.meta.env.BASE_URL),
  routes: [{
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

const userStore = useUserStore(pinia );
const isAuthenticated = userStore.isAuthenticated;
router.beforeEach((to, from, next) => {
  if (to.name !== 'home' && !isAuthenticated) next({ name: 'home' })
  else next()
})


export default router