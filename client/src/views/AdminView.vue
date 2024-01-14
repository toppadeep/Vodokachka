<script>
import TheResidents from '@/components/TheResidents.vue';
import TheBills from '@/components/TheBills.vue';
import ThePeriods from '@/components/ThePeriods.vue';
import ThePump from '@/components/ThePump.vue';
import TheRate from '@/components/TheRate.vue';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Skeleton from 'primevue/skeleton';
import Divider from 'primevue/divider';
import Logo from '../assets/logo2.jpg';
import ButtonComponent from 'primevue/button';
import Menubar from 'primevue/menubar';
import { RouterLink } from 'vue-router';


export default {
  data() {
    return {
      logo: Logo,
      items: [
        {
          label: 'Главная',
          icon: 'pi pi-home',
          route: '/'
        },
      ],
      view: 'Периоды',
      views: ['Дачники', 'Периоды', 'Счётчик', 'Счета', 'Тарифы']
    }
  },
  methods: {
    switchingComponent(view) {
      this.view = view
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
    Menubar,
    RouterLink
}
}
</script>

<template>
  <header>
    <div class="card" style="margin-bottom: 2em; position: relative;">
      <div
        class="card"
        style="display: flex; justify-content: flex-end; align-items: center; height: 100px; padding: 0 2em 0 2em; flex-direction: row; border-radius: 1em;"
      >
        <img :src="logo" alt="" width="200px" height="200px"  style="position: absolute; top: -43%; right: 43%;  border-radius: 100%; margin-right: 1em;" />
      </div>
    </div>
    <div class="card" style="margin-bottom: 2em;">
        <Menubar :model="items">
          <template #item="{ item, props, hasSubmenu }">
                <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
                    <a :href="href" v-bind="props.action" @click="navigate">
                        <span :class="item.icon"></span>
                        <span class="ml-2">{{ item.label }}</span>
                    </a>
                </router-link>
                <a v-else  :href="item.url" :target="item.target" v-bind="props.action">
                    <span :class="item.icon"></span>
                    <span class="ml-2">{{ item.label }}</span>
                    <span v-if="hasSubmenu" class="pi pi-fw pi-angle-down ml-2"> </span>
                </a>
            </template>
        </Menubar>
      </div>
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
