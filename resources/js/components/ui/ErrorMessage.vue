<template>
    <p class="text-xs text-red-500">
        {{ message }}
    </p>
</template>

<script setup lang="ts">
defineProps<{
    message?: string;
}>();
</script>
