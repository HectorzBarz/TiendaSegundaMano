import { createApp } from "vue";
import App from "./App.vue";
import router from "@/router/router";
import PrimeVue from "primevue/config";
import ToastService from "primevue/toastservice";
import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

const app = createApp(App);
const Pinia = createPinia();
Pinia.use(piniaPluginPersistedstate);

app.use(PrimeVue);
app.use(router);
app.use(ToastService);
app.use(Pinia);

app.mount("#app");
