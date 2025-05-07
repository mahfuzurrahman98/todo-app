<template>
    <div>
        <!-- Add Todo Button -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-end">
            <button
                v-if="!showCreateForm"
                @click="showCreateForm = true"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700"
            >
                <PlusIcon class="h-4 w-4 mr-1" />
                New Todo
            </button>
        </div>

        <!-- Create Todo Form -->
        <div class="px-6" v-if="showCreateForm">
            <CreateTodoForm
                :is-submitting="isSubmitting"
                @submit="handleCreate"
                @cancel="showCreateForm = false"
            />
        </div>

        <!-- Empty state -->
        <div v-if="todos.length === 0" class="py-12 px-6 text-center">
            <ClipboardIcon class="mx-auto h-12 w-12 text-gray-400" />
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                No todos found
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                Add your first todo using the form above.
            </p>
        </div>

        <!-- Todo list -->
        <ul v-else class="divide-y divide-gray-200">
            <TodoItem
                v-for="todo in todos"
                :key="todo.id"
                :todo="todo"
                :is-submitting="isSubmitting"
                @toggle-status="$emit('toggle-status', todo)"
                @update="handleUpdate"
                @delete="$emit('delete', todo)"
            />
        </ul>
    </div>
</template>

<script setup lang="ts">
import { PlusIcon, ClipboardIcon } from "lucide-vue-next";
import { ref } from "vue";
import { CreateTodo, Todo } from "../../schemas/todo-schema";
import TodoItem from "./TodoItem.vue";
import CreateTodoForm from "./CreateTodoForm.vue";
import { TodoFormValue } from "../../types/todo";

interface ListProps {
    todos: Todo[];
    isSubmitting?: boolean;
}

interface ListEmits {
    (e: "reload"): void;
    (e: "toggle-status", todo: Todo): void;
    (e: "delete", todo: Todo): void;
    (e: "create", data: CreateTodo): void;
    (e: "update", id: number, updates: { title: string; body?: string }): void;
}

const props = defineProps<ListProps>();
const emit = defineEmits<ListEmits>();

// UI state
const showCreateForm = ref(false);

// Handle create form submission
const handleCreate = (data: CreateTodo) => {
    emit("create", data);

    // Hide form after submission
    showCreateForm.value = false;
};

// Handle update form submission
const handleUpdate = (id: number, data: TodoFormValue) => {
    emit("update", id, data);
};
</script>
