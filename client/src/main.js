import './assets/main.css';
import 'primevue/resources/themes/bootstrap4-light-blue/theme.css'
import ToastService from 'primevue/toastservice';
import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import PrimeVue from 'primevue/config';
import 'primeicons/primeicons.css'
import App from './App.vue';
import router from './router';
import axios from 'axios'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true


const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
const app = createApp(App);
app.config.globalProperties.axios=axios

app.use(pinia);
app.use(router);
app.use(PrimeVue);
app.use(ToastService);
app.mount('#app');