import {
  createRouter,
  createWebHistory
} from 'vue-router'
import HomeView from '../views/HomeView.vue'


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
router.beforeEach((to, from, next) => {
  function isAuthenticated() {
    const status = JSON.parse(localStorage.getItem('isAuthenticated'));

    if (status) {
      return true;
    } else {
      return false
    }
  };
  if (to.name == 'admin' && !isAuthenticated()) next({
    name: 'home'
  })
  else next()
})


export default router