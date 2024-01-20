<script>
import Toast from 'primevue/toast'
import Skeleton from 'primevue/skeleton'
import Divider from 'primevue/divider'
import ButtonComponent from 'primevue/button'
import Menubar from 'primevue/menubar'
import BreadCrumbs from '@/components/BreadCrumbs.vue'
import Logo from '@/assets/logo.jpg'
import Banner from '@/assets/2zn6v4tg262m9h1d.png'
import { RouterLink } from 'vue-router'

export default {
  data() {
    return {
      visible: false,
      logo: Logo,
      banner: Banner
    }
  },
  components: {
    Skeleton,
    Toast,
    Divider,
    ButtonComponent,
    Menubar,
    BreadCrumbs,
    RouterLink
  },
  computed: {
    isAuthenticated() {
      const status = JSON.parse(localStorage.getItem('isAuthenticated'))

      if (status) {
        return true
      } else {
        return false
      }
    }
  }
}
</script>

<template>
  <header>
    <div class="card" style="display: flex; flex-direction: row; align-items: center">
      <img :src="logo" alt="" width="90px" height="90px" />
      <p style="font-size: 18pt; font-weight: bold">Водокачка</p>
    </div>
    <div class="card">
      <ul style="display: flex; flex-direction: row">
        <RouterLink v-if="isAuthenticated" to="admin" style="margin-left: 1em">
          <ButtonComponent label="Панель управления" rounded />
        </RouterLink>
        <RouterLink v-else to="login" style="margin-left: 1em">
          <ButtonComponent label="Войти" rounded />
        </RouterLink>
      </ul>
    </div>
  </header>

  <main>
    <div
      style="
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
      "
    >
      <div style="width: 40%">
        <h1 style="font-size: 44pt; font-weight: bold; line-height: normal; margin-bottom: 0.5em">
          Добро пожаловать в Vodokachka
        </h1>
        <p style="font-size: 18pt; margin-bottom: 1em">
          Мы предоставляем высококачественные услуги по подключению воды для домов и предприятий.
        </p>
        <ButtonComponent text label="Подробнее" />
      </div>
      <div style="width: 60%">
        <img :src="banner" alt="" width="100%" height="700px" />
      </div>
    </div>
  </main>

  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column">
    <Divider align="right" type="none">
      <b>Developed by <span style="color: #385cfd; font-weight: 400">TOPPADEEP</span></b>
    </Divider>
  </div>
</template>
