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

export default {
  data() {
    return {
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
    Divider
  }
}
</script>

<template>
  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column;">
    <div class="border-round border-1 surface-border p-4 surface-card">
      <div style=" display: flex; flex-direction: row; width: 100%; justify-content: space-between; margin-bottom: 1em;">
        <div>
          <Skeleton width="10rem" class="mb-2"></Skeleton>
          <Skeleton width="5rem" class="mb-2"></Skeleton>
          <Skeleton height="1rem"></Skeleton>
        </div>
        <Skeleton shape="circle" size="4rem" class="mr-2"></Skeleton>
      </div>
      <Skeleton width="100%" height="150px"></Skeleton>

    </div>
  </div>
  <main>
    <TabView style="width: 100%;">
      <TabPanel v-for="view, index of views" :key="index" :header="view">
        <keep-alive>
          <component :is="view"></component>
        </keep-alive>
      </TabPanel>
    </TabView>

  </main>

  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column;">
    <Divider align="center" type="solid">
      <b>developed by <span style="color: #3436c0; font-weight: bold;">TOPPADEEP</span></b>
    </Divider>
    <div class="border-round border-1 surface-border p-4 surface-card">
      <div style=" display: flex; flex-direction: row; width: 100%; justify-content: space-between; margin-bottom: 1em;">
        <Skeleton height="1rem"></Skeleton>
      </div>
    </div>
  </div>
</template>

