<script>
import TheResidents from '@/components/TheResidents.vue'
import TheBills from '@/components/TheBills.vue'
import ThePeriods from '@/components/ThePeriods.vue'
import ThePump from '@/components/ThePump.vue'
import TheRate from '@/components/TheRate.vue'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Skeleton from 'primevue/skeleton'
import Divider from 'primevue/divider'
import Logo from '../assets/logo2.jpg'
import ButtonComponent from 'primevue/button'
import Breadcrumb from '@/components/BreadCrumbs.vue'
import { RouterLink } from 'vue-router'
import axios from 'axios'
import BreadCrumbs from '@/components/BreadCrumbs.vue'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

export default {
  data() {
    return {
      logo: Logo,
      view: 'Периоды',
      views: ['Дачники', 'Периоды', 'Счётчик', 'Счета', 'Тарифы'],
    }
  },
  computed: {
    user() {
      return JSON.parse(localStorage.getItem('user'))
    }
  },
  methods: {
    switchingComponent(view) {
      this.view = view
    },
    async logout() {
      await axios.post('http://localhost:8000/logout').then(() => {
        localStorage.removeItem('user')
        localStorage.setItem('isAuthenticated', false)
        this.$router.push({ name: 'home' })
      })
    }
  },
  components: {
    Дачники: TheResidents,
    Счета: TheBills,
    Периоды: ThePeriods,
    Счётчик: ThePump,
    Тарифы: TheRate,
    TabView,
    TabPanel,
    Skeleton,
    Divider,
    ButtonComponent,
    Breadcrumb,
    RouterLink,
    BreadCrumbs
}
}
</script>

<template>
  <header>
    <div class="card" style="margin-bottom: 2em; position: relative">
      <div
        class="card"
        style="
          display: flex;
          justify-content: space-between;
          align-items: center;
          height: 100px;
          flex-direction: row;
          border-radius: 1em;
        "
      >
        <div class="card" style="display: flex; flex-direction: row; align-items: center">
          <img :src="logo" alt="аватар" width="60px" height="60px" />
          <div class="p-component" style="margin-left: 1em">
            <h2>{{ user.user.name }}</h2>
            <p>{{ user.user.email }}</p>
          </div>
        </div>

        <ButtonComponent label="Выйти" rounded @click="logout()" />
      </div>
    </div>
    <BreadCrumbs/>
  </header>

  <main>
    <TabView style="width: 100%">
      <TabPanel v-for="(view, index) of views" :key="index" :header="view">
        <keep-alive>
          <component :is="view"></component>
        </keep-alive>
      </TabPanel>
    </TabView>
  </main>

  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column">
    <Divider align="right" type="none">
      <b>Developed by <span style="color: #730d92; font-weight: 400">TOPPADEEP</span></b>
    </Divider>
  </div>
</template>
