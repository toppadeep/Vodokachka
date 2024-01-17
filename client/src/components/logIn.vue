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
        email: '',
        password: ''
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
    ...mapActions(useUserStore, ['login'])
  }
}
</script>

<template>
  <form @submit.prevent="login(user)" aria-autocomplete="off">
    <label for="email">Почта</label>
    <InputText InputId="email" class="p-input" type="email" name="email" v-model="user.email" />
    <Validation :errors="errors" field="email" />
    <label for="password">Пароль</label>
    <Password InputId="password" name="password" class="p-input" v-model="user.password" />
    <Validation :errors="errors" field="password" />
    <ButtonComponent type="submit" size="large" label="Добавить" style="width: 100%" />
  </form>
</template>
