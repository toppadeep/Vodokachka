<script>
import Breadcrumb from 'primevue/breadcrumb'
export default {
  data() {
    return {
      home: {
        icon: 'pi pi-home',
        route: '/'
      },
      items: [{ label: 'Панель управления', route: '/admin' }]
    }
  },
  components: {
    Breadcrumb
  }
}
</script>

<template>
  <div class="card flex justify-content-center" style="margin-bottom: 1em; background-color: aliceblue;">
    <Breadcrumb :home="home" :model="items">
      <template #item="{ item, props }">
        <router-link v-if="item.route" v-slot="{ href, navigate }" :to="item.route" custom>
          <a :href="href" v-bind="props.action" @click="navigate">
            <span :class="[item.icon, 'text-color']" />
            <span class="text-primary font-semibold">{{ item.label }}</span>
          </a>
        </router-link>
        <a v-else :href="item.url" :target="item.target" v-bind="props.action">
          <span class="text-color">{{ item.label }}</span>
        </a>
      </template>
    </Breadcrumb>
  </div>
</template>
