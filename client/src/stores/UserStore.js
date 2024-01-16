import {
  defineStore
} from 'pinia'
import {
  useIndexStore
} from './IndexStore'

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    user: {},
  }),
  getters: {
    isAuthenticated: (state) => state.user, 
  },
  actions: {
    async register({
      ...data
    }) {
      const User = new FormData()

      User.append('name', data.name)
      User.append('email', data.email)
      User.append('password', data.password)
      User.append('password_confirmation', data.password_confirmation)

      const response = await fetch('http://127.0.0.1:8000/api/register', {
        method: 'POST',
        body: User
      })

      const request = await response.json()
      if (request.status == 'success') {
        app.$toast.add({
          severity: 'success',
          summary: 'Успешно',
          detail: request.response,
          life: 3000
        })
      } else {
        const indexState = useIndexStore()
        indexState.errors = request.errors
        this.$toast.add({
          severity: 'error',
          detail: 'Unauthorized',
          life: 3000
        })
      }
    },
    async login({
      ...data
    }) {
      const form = new FormData()
      form.append('email', data.email)
      form.append('password', data.password)
      await fetch('http://127.0.0.1:8000/sanctum/csrf-cookie', {
        method: 'GET',
        credentials: "include",
      });
      // const response = await fetch('http://127.0.0.1:8000/api/login', {
      //   method: 'POST',
      //   body: form,
      //   headers: {
      //     'Accept': 'application/json',
      //     'Content-Type': 'application/json',
      //   },
      //   credentials: "include",
      // });

      // const request = await response.json()
      // if (request.status == 'success') {
      //   this.user = request.user;
      //   this.$toast.add({
      //     severity: 'success',
      //     summary: 'Успешно',
      //     detail: request.response,
      //     life: 3000
      //   });
      // } else {
      //   const indexState = useIndexStore()
      //   indexState.errors = request.errors
      //   this.$toast.add({
      //     severity: 'error',
      //     detail: 'Unautentificate',
      //     life: 3000
      //   })
      // }
    },
    logout() {
      const response = fetch(`http://127.0.0.1:8000/api/logout`, {
        method: 'POST'
      })

      const request = response.json()
    }
  },
  persist: true,
})