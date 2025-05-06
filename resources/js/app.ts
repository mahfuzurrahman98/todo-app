import { createApp, App } from "vue";
import { createPinia, Pinia } from "pinia";
import router from "./router";
import Root from "./root.vue";
import { useAuthStore } from "./stores/authStore";

const app: App = createApp(Root);

const pinia: Pinia = createPinia();

app.use(router);
app.use(pinia);

// Initialize the auth store
const authStore = useAuthStore();
authStore.init().catch((error) => {
    console.error("Failed to initialize auth store:", error);
});

app.mount("#app");
