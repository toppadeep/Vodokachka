import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

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
      component: () => import('../views/AdminView.vue'),
      meta: {
        requiresAuth: true
      }
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/AuthServiceView.vue'),
    }
  ]
})
router.beforeEach((to, from, next) => {
  function isAuthenticated() {
    const status = JSON.parse(localStorage.getItem('isAuthenticated'))

    if (status) {
      return true
    } else {
      return false
    }
  }
  if (to.name == 'admin' && !isAuthenticated())
    next({
      name: 'login'
    })
  else next()
})

export default router
