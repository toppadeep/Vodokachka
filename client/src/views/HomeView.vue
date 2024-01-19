<script>
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Toast from 'primevue/toast'
import Validation from '@/components/ErrorMessage.vue'
import Skeleton from 'primevue/skeleton'
import Divider from 'primevue/divider'
import Banner from '@/assets/logo.jpg'
import ButtonComponent from 'primevue/button'
import Menubar from 'primevue/menubar'
import LogIn from '@/components/logIn.vue'
import SingUp from '@/components/singUp.vue'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import BreadCrumbs from '@/components/BreadCrumbs.vue'

export default {
  data() {
    return {
      visible: false,
      banner: Banner,
      view: 'Вход',
      views: ['Вход', 'Регистрация']
    }
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
  },
  methods: {
    switching() {
      this.visible = !this.visible
    }
  },
  components: {
    Регистрация: SingUp,
    Вход: LogIn,
    Skeleton,
    InputText,
    InputNumber,
    Toast,
    Validation,
    Divider,
    ButtonComponent,
    Menubar,
    TabView,
    TabPanel,
    BreadCrumbs
  }
}
</script>

<template>
  <header>
    <BreadCrumbs />
  </header>

  <main>
    <div
      v-if="!isAuthenticated"
      style="
        display: flex;
        width: 100%;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #c0c0c0;
      "
    >
      <img :src="banner" alt="" width="50%" height="100%" />
      <TabView style="padding: 4em; width: 50%">
        <TabPanel v-for="(view, index) of views" :key="index" :header="view">
          <keep-alive>
            <component :is="view"></component>
          </keep-alive>
        </TabPanel>
      </TabView>
    </div>

    <div
      v-if="visible"
      style="
        display: flex;
        width: 100%;
        height: 600px;
        justify-content: center;
        border: 1px solid #c0c0c0;
        border-radius: 1em;
      "
    >
      <div
        class="card"
        style="display: flex; justify-content: center; align-items: center; flex-direction: row"
      >
        <ButtonComponent label="Авторизуйтесь" rounded @click="switching" />
        <h4>, чтобы проверить наличие счетов</h4>
      </div>
    </div>
  </main>

  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column">
    <Divider align="right" type="none">
      <b>Developed by <span style="color: #385cfd; font-weight: 400">TOPPADEEP</span></b>
    </Divider>
  </div>
</template>
