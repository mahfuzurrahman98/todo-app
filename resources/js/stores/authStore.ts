import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { authService } from "../services/AuthService";
import { localStorageService } from "../services/LocalStorageService";
import { LoginFormValues } from "../schemas/auth-schema";
import { User } from "../utils/interfaces/auth";

/**
 * Authentication store interface
 */
export const useAuthStore = defineStore("auth", () => {
    // State
    const user = ref<User | null>(null);
    const loading = ref<boolean>(false);
    const errorMessage = ref<string | null>(null);

    // Getters
    const isAuthenticated = computed(() => {
        return authService.isAuthenticated();
    });

    const isLoading = computed(() => loading.value);

    const getUser = computed(() => user.value);

    const getError = computed(() => errorMessage.value);

    // Actions
    /**
     * Initialize the auth store
     * Loads user profile if token exists
     */
    async function init() {
        if (authService.isAuthenticated() && !user.value) {
            await fetchUserProfile();
        }
    }

    /**
     * Login with email and password
     *
     * @param credentials - Login credentials
     */
    const login = async (credentials: LoginFormValues) => {
        try {
            loading.value = true;
            errorMessage.value = null;

            const response = await authService.login(credentials);

            // set token to localStorage
            localStorageService.setData("auth_token", response.token);

            return response;
        } catch (error: any) {
            errorMessage.value = error.message || "Login failed";
            throw error;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Logout the current user
     */
    const logout = async () => {
        try {
            loading.value = true;
            errorMessage.value = null;

            await authService.logout();
            user.value = null;
        } catch (error: any) {
            errorMessage.value = error.message || "Logout failed";
            throw error;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Fetch the current user profile
     */
    const fetchUserProfile = async () => {
        try {
            loading.value = true;
            errorMessage.value = null;

            const userData = await authService.getProfile();
            user.value = userData;
            return userData;
        } catch (err: any) {
            errorMessage.value = err.message || "Failed to fetch user profile";
            throw err;
        } finally {
            loading.value = false;
        }
    };

    /**
     * Clear authentication errors
     */
    function clearErrors() {
        errorMessage.value = null;
    }

    return {
        // State
        user,
        loading,
        errorMessage,
        isAuthenticated,

        // Actions
        init,
        login,
        logout,
        fetchUserProfile,
        clearErrors,
    };
});
