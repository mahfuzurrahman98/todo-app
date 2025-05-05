import { createApp, App } from "vue";
import { createRouter, createWebHistory, Router } from "vue-router";
import { createPinia, Pinia } from "pinia";
import routes from "./routes";
import App from "./App.vue";

const app: App<Element> = createApp(App);
const pinia: Pinia = createPinia();
const router: Router = createRouter({
    history: createWebHistory(),
    routes,
});

app.use(pinia);
app.use(router);
app.mount("#app");
