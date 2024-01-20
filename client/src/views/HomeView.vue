<script>
import Toast from 'primevue/toast'
import Skeleton from 'primevue/skeleton'
import Divider from 'primevue/divider'
import Banner from '@/assets/logo.jpg'
import ButtonComponent from 'primevue/button'
import Menubar from 'primevue/menubar'
import BreadCrumbs from '@/components/BreadCrumbs.vue'

export default {
  data() {
    return {
      visible: false,
      banner: Banner
    }
  },
  components: {
    Skeleton,
    Toast,
    Divider,
    ButtonComponent,
    Menubar,
    BreadCrumbs
  }
}
</script>

<template>
  <header>
    <BreadCrumbs />
  </header>

  <main>
    <h1>Homepage</h1>
  </main>

  <div class="card" style="display: flex; justify-content: space-between; flex-direction: column">
    <Divider align="right" type="none">
      <b>Developed by <span style="color: #385cfd; font-weight: 400">TOPPADEEP</span></b>
    </Divider>
  </div>
</template>
