<template>
    <div>
        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center py-12">
            <Loader class="h-8 w-8 animate-spin text-gray-500" />
        </div>

        <!-- Error message -->
        <div v-else-if="error" class="p-6 text-center">
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg inline-block">
                {{ error }}
            </div>
            <button @click="$emit('reload')"
                class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <RefreshCcwIcon class="h-4 w-4 mr-1" />
                Retry
            </button>
        </div>

        <!-- Empty state -->
        <div v-else-if="todos.length === 0" class="py-12 px-6 text-center">
            <ClipboardIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                No todos found
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Get started by creating a new todo.
            </p>
            <div class="mt-6">
                <button @click="$emit('new-todo')"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <PlusIcon class="h-4 w-4 mr-1" />
                    New Todo
                </button>
            </div>
        </div>

        <!-- Todo list -->
        <ul v-else class="divide-y divide-gray-200">
            <TodoItem v-for="todo in sortedTodos" :key="todo.id" :todo="todo"
                @toggle-status="$emit('toggle-status', $event)" @edit="$emit('edit', $event)"
                @delete="$emit('delete', $event)" />
        </ul>

        <!-- Pagination -->
        <div v-if="todos.length > 0 && pagination.total > pagination.perPage"
            class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div class="flex-1 flex justify-between items-center">
                <button @click="$emit('change-page', pagination.currentPage - 1)"
                    :disabled="pagination.currentPage === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <span class="text-sm text-gray-700">
                    Page {{ pagination.currentPage }} of
                    {{ Math.ceil(pagination.total / pagination.perPage) }}
                </span>
                <button @click="$emit('change-page', pagination.currentPage + 1)" :disabled="pagination.currentPage >=
                    Math.ceil(pagination.total / pagination.perPage)
                    " class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Loader, PlusIcon, ClipboardIcon, RefreshCcwIcon } from "lucide-vue-next";
import { Todo } from "../../types/models";
import TodoItem from "./TodoItem.vue";

const props = defineProps<{
    todos: Todo[];
    loading: boolean;
    error: string | null;
    pagination: {
        currentPage: number;
        perPage: number;
        total: number;
    };
    sortBy?: 'title' | 'date';
    sortDirection?: 'asc' | 'desc';
}>();

defineEmits<{
    (e: 'reload'): void;
    (e: 'new-todo'): void;
    (e: 'toggle-status', todo: Todo): void;
    (e: 'edit', todo: Todo): void;
    (e: 'delete', todo: Todo): void;
    (e: 'change-page', page: number): void;
}>();

// Sort todos based on sortBy and sortDirection props
const sortedTodos = computed(() => {
    if (!props.sortBy) return props.todos;

    return [...props.todos].sort((a, b) => {
        let comparison = 0;

        if (props.sortBy === 'title') {
            comparison = a.title.localeCompare(b.title);
        } else if (props.sortBy === 'date') {
            const dateA = a.created_at ? new Date(a.created_at).getTime() : 0;
            const dateB = b.created_at ? new Date(b.created_at).getTime() : 0;
            comparison = dateA - dateB;
        }

        return props.sortDirection === 'desc' ? -comparison : comparison;
    });
});
</script>
