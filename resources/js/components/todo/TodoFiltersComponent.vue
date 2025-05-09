<template>
    <div
        class="space-y-4 sm:space-y-0 sm:flex sm:flex-wrap sm:gap-4 sm:items-end"
    >
        <!-- Status filter -->
        <div class="w-full sm:w-auto sm:flex-1">
            <label
                for="status-filter"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Status</label
            >
            <select
                id="status-filter"
                v-model="filters.status"
                class="w-full block px-2 py-1 text-base outline-1 outline-gray-300 sm:text-sm rounded-md shadow-sm"
                @change="emitFilterChange"
            >
                <option value="all">All</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
            </select>
        </div>

        <!-- Sort by -->
        <div class="w-full sm:w-auto sm:flex-1">
            <label
                for="sort-by"
                class="block text-sm font-medium text-gray-700 mb-1"
                >Sort by</label
            >
            <select
                id="sort-by"
                v-model="filters.sortBy"
                class="w-full block px-2 py-1 text-base outline-1 outline-gray-300 sm:text-sm rounded-md shadow-sm"
                @change="emitFilterChange"
            >
                <option value="title">Title</option>
                <option value="dateAsc">Date ASC</option>
                <option value="dateDesc">Date DESC</option>
            </select>
        </div>

        <!-- Reset filters -->
        <div class="w-full sm:w-auto flex justify-end mt-2 sm:mt-0">
            <Button
                variant="ghost"
                size="sm"
                @click="resetFilters"
                class="hover:bg-gray-100"
            >
                <!-- // animate-spin if resetting -->
                <RefreshCcw :class="['h-4 w-4', resetting && 'animate-spin']" />
                Reset
            </Button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from "vue";
import { RefreshCcw } from "lucide-vue-next";
import {
    TodoFiltersSortByEnum,
    TodoFiltersStatusEnum,
} from "../../utils/enums/todo";
import { TodoFilters } from "../../utils/interfaces/todo";
import Button from "../ui/Button.vue";

// States
const resetting = ref(false);

const props = defineProps<{
    initialStatus?: TodoFiltersStatusEnum;
    initialSortBy?: TodoFiltersSortByEnum;
}>();

const emit = defineEmits<{
    (e: "filter-change", filters: TodoFilters): void;
}>();

// Default values
const defaultValues: TodoFilters = {
    status: TodoFiltersStatusEnum.All,
    sortBy: TodoFiltersSortByEnum.DateDesc,
};

const filters = reactive<TodoFilters>({
    status: props.initialStatus || defaultValues.status,
    sortBy: props.initialSortBy || defaultValues.sortBy,
});

// Watch for prop changes
watch(
    () => props.initialStatus,
    (newVal) => {
        if (newVal) filters.status = newVal;
    },
    { immediate: true }
);

watch(
    () => props.initialSortBy,
    (newVal) => {
        if (newVal) filters.sortBy = newVal;
    },
    { immediate: true }
);

// Reset filters to default
const resetFilters = () => {
    resetting.value = true;
    filters.status = defaultValues.status;
    filters.sortBy = defaultValues.sortBy;
    emitFilterChange();
    // resetting.value = false;
    setTimeout(() => {
        resetting.value = false;
    }, 300);
};

const emitFilterChange = () => {
    emit("filter-change", {
        status: filters.status,
        sortBy: filters.sortBy,
    });
};
</script>
