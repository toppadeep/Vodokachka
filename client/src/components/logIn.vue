<script>
import InputText from 'primevue/inputtext'
import ButtonComponent from 'primevue/button'
import Password from 'primevue/password'
import Validation from '@/components/ErrorMessage.vue'

export default {
  data() {
    return {
      user: {
        email: '',
        password: ''
      },
      errors: []
    }
  },
  components: {
    Validation,
    Password,
    InputText,
    ButtonComponent
  },
  methods: {
    async login({ ...user }) {
      await this.axios.get('http://localhost:8000/sanctum/csrf-cookie')

      const form = new FormData()
      form.append('email', user.email)
      form.append('password', user.password)

      await this.axios
        .post('http://localhost:8000/login', form)
        .then((response) => {
          this.$toast.add({
            severity: 'success',
            summary: 'Успешно',
            detail: 'Вы вошли',
            life: 3000
          })
          const user = {
            user: response.data.user,
            verificated: response.data.verificated
          }
          localStorage.setItem('user', JSON.stringify(user))
          localStorage.setItem('isAuthenticated', true)
          this.$router.push({ name: 'admin' })
        })
        .catch((errors) => {
          if (errors.response.status == 404) {
            this.$toast.add({
              severity: 'error',
              summary: 'Ошибка',
              detail: errors.response.data.message,
              life: 3000
            })
          } else {
            this.errors = errors.response.data.errors
          }
        })
    }
  }
}
</script>

<template>
  <form @submit.prevent="login(user)" autocomplete="off">
    <div style="display: flex; flex-direction: column">
      <label for="email">Почта</label>
      <InputText name="email" v-model="user.email" type="email" />
    </div>
    <Validation :errors="errors" field="email" />
    <div style="display: flex; flex-direction: column">
      <label for="password">Пароль</label>
      <Password
        name="password"
        toggleMask
        :feedback="false"
        v-model="user.password"
      />  
    </div>
    <Validation :errors="errors" field="password" />
    <ButtonComponent type="submit" size="large" label="Войти" style="width: 100%; margin-top: 2em;" />
  </form>
</template>
