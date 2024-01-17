import {
  defineStore
} from 'pinia'
import pinia from "@/stores/store.js";
import {
  useIndexStore
} from './IndexStore'
import axios from 'axios';
axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

export const useUserStore = defineStore({
  id: 'user',
  state: () => ({
    user: {},
  }),
  getters: {
    isAuthenticated: (state) => {
      const key = localStorage.getItem('user');
      const user = JSON.parse(key);
      return user.isAuthenticated;
    },
  },
  actions: {
    async register({
      ...user
    }) {

      await axios.get("http://localhost:8000/sanctum/csrf-cookie");

      const form = new FormData();
      form.append('name', user.name);
      form.append('email', user.email);
      form.append('password', user.password);
      form.append('password_confirmation', user.password_confirmation);

      await axios.post('http://localhost:8000/register', form)
        .then((response) => {
          console.log('Зарегистрирован')
          this.$toast.add({
            severity: 'success',
            summary: 'Успешно',
            detail: 'Создано',
            life: 3000
          })
        })
        .catch((error) => {
          const indexState = useIndexStore(pinia);
          indexState.errors = error.response.data.errors;
        })
    },
    async login({
      ...user
    }) {

      await axios.get("http://localhost:8000/sanctum/csrf-cookie");

      const form = new FormData();
      form.append('email', user.email);
      form.append('password', user.password);
      await axios.post('http://localhost:8000/login', form)

      await axios.get('http://localhost:8000/api/user')
        .then((resp) => {
          console.log('Вошел')
          this.user = resp.data;
          this.isAuthenticated = true;
        });

    },
    async logout() {
      await axios.get('http://localhost/sanctum/csrf-cookie');
      await axios.post('http://localhost:8000/logout')
    }
  },
  persist: true,
})