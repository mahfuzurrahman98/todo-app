import { createApp, App } from "vue";
import { createPinia, Pinia } from "pinia";
import { useAuthStore } from "./stores/authStore";
import router from "./router";
import Root from "./root.vue";

const app: App = createApp(Root);
const pinia: Pinia = createPinia();

app.use(router);
app.use(pinia);

// Initialize the auth store
const authStore = useAuthStore();
authStore
    .init()
    .then(() => {
        console.log("Auth store initialized");
        app.mount("#app");
    })
    .catch((error: any) => {
        console.error("Failed to initialize auth store:", error);
    });
