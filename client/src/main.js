import './assets/main.css';
import 'primevue/resources/themes/bootstrap4-light-blue/theme.css'
import ToastService from 'primevue/toastservice';
import 'primeicons/primeicons.css'


import { createApp } from 'vue';
import { createPinia } from 'pinia';
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import PrimeVue from 'primevue/config';


import App from './App.vue';
import router from './router';

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);
const app = createApp(App);


app.use(pinia);
app.use(router);
app.use(PrimeVue);
app.use(ToastService);
app.mount('#app');
