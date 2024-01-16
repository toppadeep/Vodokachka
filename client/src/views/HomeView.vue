<script>
import InputText from 'primevue/inputtext'
import InputNumber from 'primevue/inputnumber'
import Toast from 'primevue/toast'
import Validation from '@/components/ErrorMessage.vue'
import Skeleton from 'primevue/skeleton'
import Divider from 'primevue/divider'
import Logo from '../assets/logo2.jpg'
import Banner from '../assets/banner.jpg'
import ButtonComponent from 'primevue/button'
import Menubar from 'primevue/menubar'
import LogIn from '@/components/logIn.vue'
import SingUp from '@/components/singUp.vue'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'

export default {
  data() {
    return {
      bills: [],
      loading: true,
      errors: [],
      visible: false,
      logo: Logo,
      banner: Banner,
      items: [
        {
          label: 'Главная',
          icon: 'pi pi-home',
          route: '/'
        },
        {
          label: 'Панель',
          icon: 'pi pi-envelope',
          route: '/admin'
        }
      ],
      view: 'Регистрация',
      views: ['Регистрация', 'Вход']
    }
  },
  methods: {
    switching() {
      this.visible = !this.visible
    },
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
    TabPanel
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
          justify-content: flex-end;
          align-items: center;
          height: 100px;
          padding: 0 2em 0 2em;
          flex-direction: row;
          border-radius: 1em 2em 0 0;
          margin-top: 2em;
          background-color: #385cfd98;
        "
      >
        <img
          :src="logo"
          alt=""
          width="200px"
          height="200px"
          style="position: absolute; top: -33%; right: 43%; border-radius: 100%; margin-right: 1em"
        />
      </div>
      <div class="card">
        <Menubar :model="items">
          <template #item="{ item, props, hasSubmenu }">
            <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
              <a :href="href" v-bind="props.action" @click="navigate">
                <span :class="item.icon" />
                <span class="ml-2">{{ item.label }}</span>
              </a>
            </router-link>
            <a v-else :href="item.url" :target="item.target" v-bind="props.action">
              <span :class="item.icon" />
              <span class="ml-2">{{ item.label }}</span>
              <span v-if="hasSubmenu" class="pi pi-fw pi-angle-down ml-2" />
            </a>
          </template>
        </Menubar>
      </div>
    </div>
  </header>

  <main>
    <div
      v-if="visible"
      style="
        display: flex;
        width: 100%;
        height: 600px;
        justify-content: space-between;
        align-items: center;
        border: 1px solid #c0c0c0;
      "
    >
    <img
          :src="banner"
          alt=""
          width="50%"
          height="100%"
        />
      <TabView  style="padding: 4em; width: 50%;">
        <TabPanel  v-for="(view, index) of views" :key="index" :header="view">
          <keep-alive>
            <component :is="view"></component>
          </keep-alive>
        </TabPanel>
      </TabView>
    </div>

    <div
      v-if="!visible"
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
