import Login from "../pages/Login.vue";
import Todos from "../pages/Todos.vue";
import NotFound from "../pages/NotFound.vue";
import {
    createRouter,
    createWebHistory,
    RouteRecordRaw,
    Router,
    NavigationGuardNext,
    RouteLocationNormalizedGeneric,
} from "vue-router";
import { useAuthStore } from "../stores/authStore";

const routes: RouteRecordRaw[] = [
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: {
            title: "Login",
            requiresAuth: false,
        },
    },
    {
        path: "/",
        name: "Todos",
        component: Todos,
        meta: {
            title: "Todos",
            requiresAuth: true,
        },
    },
    // Catch-all route for 404
    {
        path: "/:pathMatch(.*)*",
        name: "NotFound",
        component: NotFound,
        meta: {
            title: "Page Not Found",
        },
    },
];

const router: Router = createRouter({
    history: createWebHistory(),
    routes,
});

// apply filters
router.beforeEach(
    (
        to: RouteLocationNormalizedGeneric,
        from: RouteLocationNormalizedGeneric,
        next: NavigationGuardNext
    ) => {
        // skip the NotFound route
        if (to.name === "NotFound") {
            next();
            return;
        }

        const authStore = useAuthStore();
        const requiresAuth = to.meta.requiresAuth as boolean;

        alert(
            `For route ${String(
                to.name
            )} requiresAuth is ${requiresAuth} and isAuthenticated is ${
                authStore.isAuthenticated
            }`
        );

        if (requiresAuth && !authStore.isAuthenticated) {
            alert("You must be logged in to view this page.");
            // Redirect to login if authentication is required but user is not authenticated
            next({ name: "Login" });
        } else if (!requiresAuth && authStore.isAuthenticated) {
            alert("You must be logged out to view this page.");
            // Redirect to home if authentication is not required but user is authenticated
            next({ name: "Todos" });
        } else {
            alert("Continue as it is");
            // Continue navigation
            next();
        }
    }
);

export default router;
