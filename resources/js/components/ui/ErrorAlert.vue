<template>
    <div class="px-4 py-3 text-sm text-red-700 bg-red-100 rounded-lg">
        {{ error }}
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    error: string;
}>();
</script>
