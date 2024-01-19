<script>
import InputText from 'primevue/inputtext'
import ButtonComponent from 'primevue/button'
import Password from 'primevue/password'
import Validation from '@/components/ErrorMessage.vue'
import Toast from 'primevue/toast'

export default {
  data() {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      },
      errors: []
    }
  },
  components: {
    Validation,
    Password,
    InputText,
    ButtonComponent,
    Toast
  },
  methods: {
    async register({ ...user }) {
      await this.axios.get('http://localhost:8000/sanctum/csrf-cookie')

      const form = new FormData()
      form.append('name', user.name)
      form.append('email', user.email)
      form.append('password', user.password)
      form.append('password_confirmation', user.password_confirmation)

      await this.axios
        .post('http://localhost:8000/register', form)
        .then(() => {
          this.$toast.add({
            severity: 'success',
            summary: 'Успешно',
            detail: 'Зарегистрирован',
            life: 3000
          });
          location.reload();
        })
        .catch((errors) => {
          this.$toast.add({
            severity: 'error',
            summary: 'Ошибка',
            detail: 'Некорректные данные',
            life: 3000
          })
          this.errors = errors.response.data.errors
        })
    }
  }
}
</script>

<template>
  <Toast />
  <form @submit.prevent="register(user)">
    <div style="display: flex; flex-direction: column">
      <label for="name">Имя</label>
      <InputText name="name" class="p-input" type="text" v-model="user.name" />
    </div>
    <Validation :errors="errors" field="name" />
    <div style="display: flex; flex-direction: column">
      <label for="email">Почта</label>
      <InputText name="email" class="p-input" type="email" v-model="user.email" />
    </div>
    <Validation :errors="errors" field="email" />

    <div style="display: flex; flex-direction: column">
      <label for="password">Пароль</label>
      <Password
        name="password"
        class="p-input"
        toggleMask
        v-model="user.password"
        promptLabel="Введите пароль"
        weakLabel="Слишком простой"
        mediumLabel="Нормальный"
        strongLabel="Безопасный"
      />
    </div>
    <Validation :errors="errors" field="password" />
    <div style="display: flex; flex-direction: column">
      <label for="password_confirmation">Подтверждение пароля</label>
      <Password
        :feedback="false"
        name="password_confirmation"
        class="p-input"
        toggleMask
        v-model="user.password_confirmation"
      />
    </div>
    <ButtonComponent
      type="submit"
      size="large"
      label="Зарегистрироваться"
      style="width: 100%; margin-top: 1em"
    />
  </form>
</template>
