<template>
    <div class="w-full max-w-md space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-bold">Login</h1>
            <p class="text-gray-500">
                Enter your credentials to access your account
            </p>
        </div>

        <!-- API Error Alert -->
        <div
            v-if="apiError"
            class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"
        >
            {{ apiError }}
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <Input
                id="email"
                v-model="form.email"
                type="text"
                label="Email"
                placeholder="name@example.com"
            />

            <Input
                id="password"
                v-model="form.password"
                type="password"
                label="Password"
                placeholder="••••••••"
            />

            <Button type="submit" :disabled="isSubmitting" class="w-full">
                <Loader v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
                {{ isSubmitting ? "Logging in" : "Login" }}
            </Button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import { useRouter } from "vue-router";
import { loginSchema, type LoginFormValues } from "../schemas/auth-schema";
import { extractFirstFieldErrors } from "../utils/form-utils";
import { useAuthStore } from "../stores/authStore";
import { Loader } from "lucide-vue-next";
import Button from "./ui/Button.vue";
import Input from "./ui/Input.vue";

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
        router.push({ name: "Todos" });
    } catch (error: any) {
        // Handle API errors
        apiError.value = error.message || "Failed to login. Please try again.";
    } finally {
        isSubmitting.value = false;
    }
};
</script>
