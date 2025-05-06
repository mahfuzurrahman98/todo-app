<template>
    <div class="w-full max-w-md space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-bold">Login</h1>
            <p>Loggedin: {{ authStore.isAuthenticated }}</p>
            <p class="text-gray-500">
                Enter your credentials to access your account
            </p>
        </div>

        <!-- API Error Alert -->
        <div v-if="apiError" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
            {{ apiError }}
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div class="space-y-2">
                <label for="email"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Email
                </label>
                <input id="email" v-model="form.email" type="text" placeholder="name@example.com"
                    class="flex h-10 w-full rounded-md border border-gray-400 bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-600 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    :class="{
                        'border-red-500 focus-visible:ring-red-500':
                            errors && errors.email,
                    }" />
                <ErrorMessage v-if="errors && errors.email" :message="errors.email" />
            </div>

            <div class="space-y-2">
                <label for="password"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                    Password
                </label>
                <input id="password" v-model="form.password" type="password" placeholder="••••••••"
                    class="flex h-10 w-full rounded-md border border-gray-400 bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-gray-600 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    :class="{
                        'border-red-500 focus-visible:ring-red-500':
                            errors && errors.password,
                    }" />
                <ErrorMessage v-if="errors && errors.password" :message="errors.password" />
            </div>

            <button type="submit"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full bg-black text-white cursor-pointer"
                :disabled="isSubmitting">
                <Loader v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
                {{ isSubmitting ? "Logging in..." : "Login" }}
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import { useRouter } from "vue-router";
import { ZodFormattedError } from "zod";
import { loginSchema, type LoginFormValues } from "../schemas/auth-schema";
import { extractFirstFieldErrors } from "../utils/form-utils";
import { useAuthStore } from "../stores/authStore";
import { Loader } from "lucide-vue-next";
import ErrorMessage from "./ErrorMessage.vue";

const router = useRouter();
const authStore = useAuthStore();

const form = reactive<LoginFormValues>({
    email: "mahfuz@test.com",
    password: "Pass@123",
});

const errors = ref<Record<string, string> | null>(null);
const isSubmitting = ref(false);
const apiError = ref<string | null>(null);

// Use watch to clear specific field errors when typing
watch(
    form,
    (newForm, oldForm) => {
        // Only proceed if there are errors to clear
        if (!errors.value) return;

        // Get all field names from the form
        const fieldNames = Object.keys(newForm);

        // Clear specific field errors
        fieldNames.forEach((fieldName) => {
            if (errors.value && errors.value[fieldName]) {
                delete errors.value[fieldName];
            }
        });
    },
    { deep: true } // Watch all properties of the form object
);

const handleSubmit = async () => {
    // Reset errors
    errors.value = null;
    apiError.value = null;

    try {
        isSubmitting.value = true;

        // Validate form with Zod
        const validSchema = loginSchema.safeParse(form);

        if (!validSchema.success) {
            // Update the errors object with the extracted errors
            errors.value = {
                ...(errors.value || {}), // Preserve existing errors.value,
                ...extractFirstFieldErrors(validSchema.error),
            };
            return;
        }

        // Form is valid, attempt login with auth store
        await authStore.login({
            email: form.email,
            password: form.password,
        });

        // Redirect to home page on successful login
        router.push({ name: "Home" });
    } catch (error: any) {
        // Handle API errors
        apiError.value = error.message || "Failed to login. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
};
</script>
