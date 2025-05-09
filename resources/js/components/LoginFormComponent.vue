<template>
    <div class="w-full max-w-md space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-bold">Login</h1>
            <p class="text-gray-500">
                Enter your credentials to access your account
            </p>
        </div>

        <!-- API Error Alert -->
        <ErrorAlert v-if="apiError" :error="apiError" />

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div>
                <label
                    for="email"
                    class="block text-sm font-medium leading-none mb-1 text-gray-700"
                >
                    Email
                </label>
                <Input
                    id="email"
                    v-model="formData.email"
                    type="text"
                    placeholder="name@example.com"
                    :error="errors?.email"
                />
                <ErrorMessage v-if="errors?.email" :message="errors.email" />
            </div>

            <div>
                <label
                    for="password"
                    class="block text-sm font-medium leading-none mb-1 text-gray-700"
                >
                    Password
                </label>
                <Input
                    id="password"
                    v-model="formData.password"
                    type="password"
                    label="Password"
                    placeholder="••••••••"
                />
                <ErrorMessage
                    v-if="errors?.password"
                    :message="errors.password"
                />
            </div>

            <Button type="submit" :disabled="isSubmitting" class="w-full">
                <Loader v-if="isSubmitting" class="h-4 w-4 animate-spin" />
                {{ isSubmitting ? "Logging in" : "Login" }}
            </Button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import { useRouter } from "vue-router";
import { loginSchema, type LoginFormValues } from "../schemas/auth-schema";
import {
    extractFirstFieldErrors,
    setupFormErrorClearer,
} from "../utils/form-utils";
import { useAuthStore } from "../stores/authStore";
import { Loader } from "lucide-vue-next";
import { ValidationErrorType } from "../utils/interfaces";
import Button from "./ui/Button.vue";
import Input from "./ui/Input.vue";
import ErrorMessage from "./ui/ErrorMessage.vue";
import ErrorAlert from "./ui/ErrorAlert.vue";

const router = useRouter();
const authStore = useAuthStore();

const formData = reactive<LoginFormValues>({
    email: "mahfuz@test.com",
    password: "Pass@123",
});

const errors = ref<ValidationErrorType>(null);
const isSubmitting = ref(false);
const apiError = ref<string | null>(null);

// Use the reusable function to clear field errors when typing
setupFormErrorClearer(formData, errors);

const handleSubmit = async () => {
    // Reset errors
    errors.value = null;
    apiError.value = null;

    try {
        isSubmitting.value = true;

        // Validate form with Zod
        const validSchema = loginSchema.safeParse(formData);

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
            email: formData.email,
            password: formData.password,
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
