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
                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2 w-full bg-black text-white"
                :disabled="isSubmitting">
                <Loader v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
                {{ isSubmitting ? "Logging in..." : "Login" }}
            </button>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import { ZodFormattedError } from "zod";
import { loginSchema, type LoginFormValues } from "../schemas/auth-schema";
import { extractFirstFieldErrors, handleFieldErrorClearing } from "../utils/form-utils";
import ErrorMessage from "./ErrorMessage.vue";
import { Loader } from "lucide-vue-next";

const form = reactive<LoginFormValues>({
    email: "",
    password: "",
});

const errors = ref<ZodFormattedError<LoginFormValues> | null>(null);
const isSubmitting = ref(false);

// Use watch to clear specific field errors when typing - using utility function
watch(
    form, 
    (newForm, oldForm) => {
        // Use the utility function to handle error clearing
        handleFieldErrorClearing(newForm, oldForm, errors);
    },
    { deep: true } // Watch all properties of the form object
);

const handleSubmit = async () => {
    // Reset errors
    errors.value = null;

    try {
        isSubmitting.value = true;

        // Validate form
        const validSchema = loginSchema.safeParse(form);

        if (!validSchema.success) {
            // Update the errors object with the extracted errors
            errors.value = {
                ...errors.value,
                ...extractFirstFieldErrors(validSchema.error)
            };
            return;
        }

        // Log form values (we'll add API integration later)
        console.log("Form submitted successfully:", form);

        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 3000));
    } catch (error: any) {
        console.error("error:", error);
    } finally {
        isSubmitting.value = false;
    }
};
</script>
