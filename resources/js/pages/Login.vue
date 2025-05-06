<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="w-full max-w-md space-y-8">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <LoginForm />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import LoginForm from "../components/LoginForm.vue";
</script>
