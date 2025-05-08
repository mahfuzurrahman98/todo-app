<template>
    <div
        class="py-2 bg-gray-50 border-b border-gray-200 grid grid-cols-3 gap-4 text-center"
    >
        <div>
            <p class="text-sm font-medium text-gray-500">Total</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">
                {{ loading ? "..." : statistics.total }}
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Completed</p>
            <p class="mt-1 text-xl font-semibold text-green-600">
                {{ loading ? "..." : statistics.completed }}
            </p>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500">Pending</p>
            <p class="mt-1 text-xl font-semibold text-amber-600">
                {{ loading ? "..." : statistics.pending }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { TodoStats } from '../../interfaces/todo';

defineProps<{
    statistics: TodoStats;
    loading: boolean;
}>();
</script>
