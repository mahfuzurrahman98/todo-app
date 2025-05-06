import { createApp, App } from "vue";
import { createRouter, createWebHistory, Router } from "vue-router";
import { createPinia, Pinia } from "pinia";
import routes from "./routes";
import Root from "./root.vue";

const app: App = createApp(Root);
const router: Router = createRouter({
    history: createWebHistory(),
    routes,
});
const pinia: Pinia = createPinia();

app.use(router);
app.use(pinia);
app.mount("#app");
