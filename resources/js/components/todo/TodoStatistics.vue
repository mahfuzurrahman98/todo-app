<template>
  <div class="px-6 py-3 bg-gray-50 border-b border-gray-200 grid grid-cols-3 gap-4 text-center">
    <div>
      <p class="text-sm font-medium text-gray-500">Total</p>
      <p class="mt-1 text-xl font-semibold text-gray-900">
        {{ statistics.total }}
      </p>
    </div>
    <div>
      <p class="text-sm font-medium text-gray-500">
        Completed
      </p>
      <p class="mt-1 text-xl font-semibold text-green-600">
        {{ statistics.completed }}
      </p>
    </div>
    <div>
      <p class="text-sm font-medium text-gray-500">Pending</p>
      <p class="mt-1 text-xl font-semibold text-amber-600">
        {{ statistics.pending }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  statistics: {
    total: number;
    completed: number;
    pending: number;
  };
}>();
</script>
