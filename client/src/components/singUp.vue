<script>
import InputText from 'primevue/inputtext'
import ButtonComponent from 'primevue/button'
import { mapState, mapActions } from 'pinia'
import { useUserStore } from '@/stores/UserStore'
import { useIndexStore } from '@/stores/IndexStore'
import Password from 'primevue/password'
import Validation from '@/components/ErrorMessage.vue'

export default {
  data() {
    return {
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: ''
      }
    }
  },
  computed: {
    ...mapState(useIndexStore, ['errors'])
  },
  components: {
    Validation,
    Password,
    InputText,
    ButtonComponent
  },
  methods: {
    ...mapActions(useUserStore, ['register'])
  }
}
</script>

<template>
  <form @submit.prevent="register(user)" aria-autocomplete="off">
    <label for="name">Имя</label>
    <InputText InputId="name" class="p-input" type="text" name="name" v-model="user.name" />
    <Validation :errors="errors" field="name" />
    <label for="email">Почта</label>
    <InputText InputId="email" class="p-input" type="email" name="email" v-model="user.email" />
    <Validation :errors="errors" field="email" />
    <label for="password">Пароль</label>
    <Password InputId="password" name="password" class="p-input" v-model="user.password" />
    <Validation :errors="errors" field="password" />
    <label for="password_confirmation">Подтверждение пароля</label>
    <Password InputId="password_confirmation" class="p-input" v-model="user.password_confirmation" />
    <ButtonComponent type="submit" size="large" label="Добавить" style="width: 100%" />
  </form>
</template>
