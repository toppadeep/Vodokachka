<script>
import Divider from 'primevue/divider'
import Banner from '@/assets/logo.jpg'
import ButtonComponent from 'primevue/button'
import Menubar from 'primevue/menubar'
import LogIn from '@/components/logIn.vue'
import SingUp from '@/components/singUp.vue'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import BreadCrumbs from '@/components/BreadCrumbs.vue'
import { RouterLink } from 'vue-router'

export default {
  data() {
    return {
      banner: Banner,
      view: 'Вход',
      views: ['Вход', 'Регистрация']
    }
  },
  components: {
    Регистрация: SingUp,
    Вход: LogIn,
    Divider,
    ButtonComponent,
    Menubar,
    BreadCrumbs,
    TabPanel,
    TabView,
    RouterLink
}
}
</script>

<template>
  <main>
    <h2>Водокачка</h2>
    <div
      style="
        display: flex;
        width: 40%;
        justify-content: center;
        border: 1px solid #c0c0c0;
        border-radius: 0.5em;
        margin-top: 2em;
      "
    >
      <TabView style="padding: 2em 2em; width: 100%">
        <TabPanel v-for="(view, index) of views" :key="index" :header="view">
          <keep-alive>
            <component :is="view"></component>
          </keep-alive>
        </TabPanel>
      </TabView>
    </div>
    <div style="padding: 0.5em 1em; border-radius: 10%; background-color: #e6e6fd; margin-top: 1em;">
        <RouterLink to="/">Главная</RouterLink>
    </div>
  </main>
  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column">
    <Divider align="right" type="none">
      <b>Developed by <span style="color: #385cfd; font-weight: 400">TOPPADEEP</span></b>
    </Divider>
  </div>
</template>
