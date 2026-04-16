import { createApp } from "vue";
import App from "./App.vue";
import router from "@/router/router";
import PrimeVue from "primevue/config";
import ToastService from "primevue/toastservice";
import { createPinia } from "pinia";

const app = createApp(App);
const Pinia = createPinia();

app.use(PrimeVue);
app.use(router);
app.use(ToastService);
app.use(Pinia);

app.mount("#app");
