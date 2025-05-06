<template>
    <div
        class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8"
    >
        <div class="max-w-md w-full space-y-8 text-center">
            <div>
                <h1 class="text-9xl font-extrabold text-gray-400">404</h1>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Page Not Found
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    The page you're looking for doesn't exist or has been moved.
                </p>
            </div>
            <div class="mt-8">
                <router-link
                    to="/"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-black hover:bg-black/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black"
                >
                    Go Home
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
// No additional logic needed for this page
</script>
