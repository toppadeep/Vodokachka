import './assets/main.css';
import 'primevue/resources/themes/lara-light-indigo/theme.css'
import ToastService from 'primevue/toastservice';
import 'primeicons/primeicons.css'


import { createApp } from 'vue';
import { createPinia } from 'pinia';
import PrimeVue from 'primevue/config';


import App from './App.vue';
import router from './router';

const app = createApp(App);

app.use(createPinia());
app.use(router);
app.use(PrimeVue);
app.use(ToastService);
app.mount('#app');
