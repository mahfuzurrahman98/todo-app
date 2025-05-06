<template>
    <p class="text-sm font-medium text-red-500 mt-1">
        {{ message }}
    </p>
</template>

<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>
