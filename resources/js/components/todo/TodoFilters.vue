<template>
    <div class="flex space-x-4 items-center">
        <!-- Status filter -->
        <div>
            <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status-filter" v-model="filters.status"
                class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                @change="emitFilterChange">
                <option value="all">All</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <!-- Sort by -->
        <div>
            <label for="sort-by" class="block text-sm font-medium text-gray-700 mb-1">Sort by</label>
            <select id="sort-by" v-model="filters.sortBy"
                class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                @change="emitFilterChange">
                <option value="date">Date</option>
                <option value="title">Title</option>
            </select>
        </div>

        <!-- Sort direction -->
        <div>
            <label for="sort-direction" class="block text-sm font-medium text-gray-700 mb-1">Order</label>
            <select id="sort-direction" v-model="filters.sortDirection"
                class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                @change="emitFilterChange">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue';

const props = defineProps<{
    initialStatus?: string;
    initialSortBy?: string;
    initialSortDirection?: string;
}>();

const emit = defineEmits<{
    (e: 'filter-change', filters: {
        status: string;
        sortBy: string;
        sortDirection: string;
    }): void;
}>();

const filters = reactive({
    status: props.initialStatus || 'all',
    sortBy: props.initialSortBy || 'date',
    sortDirection: props.initialSortDirection || 'desc',
});

// Watch for prop changes
watch(() => props.initialStatus, (newVal) => {
    if (newVal) filters.status = newVal;
}, { immediate: true });

watch(() => props.initialSortBy, (newVal) => {
    if (newVal) filters.sortBy = newVal;
}, { immediate: true });

watch(() => props.initialSortDirection, (newVal) => {
    if (newVal) filters.sortDirection = newVal;
}, { immediate: true });

const emitFilterChange = () => {
    emit('filter-change', {
        status: filters.status,
        sortBy: filters.sortBy,
        sortDirection: filters.sortDirection,
    });
};
</script>
