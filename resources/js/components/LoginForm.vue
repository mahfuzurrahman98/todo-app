<template>
    <div class="w-full max-w-md space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-bold">Login</h1>
            <p class="text-gray-500">
                Enter your credentials to access your account
            </p>
        </div>

        <form @submit.prevent="handleSubmit" class="space-y-4">
            <div class="space-y-2">
                <label
                    for="email"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    Email
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    placeholder="name@example.com"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    :class="{
                        'border-red-500 focus-visible:ring-red-500':
                            errors && errors.email,
                    }"
                />
                <ErrorMessage
                    v-if="errors && errors.email"
                    :message="errors.email"
                />
            </div>

            <div class="space-y-2">
                <label
                    for="password"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                >
                    Password
                </label>
                <input
                    id="password"
                    v-model="form.password"
                    type="password"
                    placeholder="••••••••"
                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    :class="{
                        'border-red-500 focus-visible:ring-red-500':
                            errors && errors.password,
                    }"
                />
                <ErrorMessage
                    v-if="errors && errors.password"
                    :message="errors.password"
                />
            </div>

            <div class="flex items-center space-x-2">
                <input
                    id="remember"
                    v-model="form.rememberMe"
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600"
                />
                <label for="remember" class="text-sm text-gray-600"
                    >Remember me</label
                >
            </div>

            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full bg-blue-600 text-white"
                :disabled="isSubmitting"
            >
                {{ isSubmitting ? "Logging in..." : "Login" }}
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from "vue";
import { ZodFormattedError } from "zod";
import { loginSchema, type LoginFormValues } from "../schemas/auth-schema";
import { extractFirstFieldErrors } from "../utils/form-utils";
import ErrorMessage from "./ErrorMessage.vue";

const form = reactive<LoginFormValues>({
    email: "",
    password: "",
    rememberMe: false,
});

const errors = ref<ZodFormattedError<LoginFormValues> | null>(null);
const isSubmitting = ref(false);

const handleSubmit = async () => {
    // Reset errors
    errors.value = null;

    try {
        isSubmitting.value = true;

        // Validate form
        const validSchema = loginSchema.safeParse(form);

        if (!validSchema.success) {
            try {
                // Initialize errors object if it's null
                if (!errors.value) {
                    errors.value = {};
                }
                
                // Extract first error for each field using our utility function
                const fieldErrors = extractFirstFieldErrors(validSchema.error);
                console.error("Invalid form:", fieldErrors);
                
                // Update the errors object with the extracted errors
                errors.value = {
                    ...errors.value,
                    ...fieldErrors
                };
            } catch (validationHandlingError) {
                console.error("Error handling validation:", validationHandlingError);
            }
            
            // Set isSubmitting to false since we're returning early
            isSubmitting.value = false;
            return;
        }

        // Log form values (we'll add API integration later)
        console.log("Form submitted successfully:", form);

        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1000));
    } catch (error: any) {
        console.error("error:", error);
    } finally {
        isSubmitting.value = false;
    }
};
</script>
