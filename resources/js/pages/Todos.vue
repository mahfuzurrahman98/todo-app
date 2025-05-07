<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h1 class="text-xl font-semibold text-gray-900">
                        Your Todos
                    </h1>
                </div>

                <!-- Filters -->
                <!-- <div class="px-6 py-4 border-b border-gray-200">
                    <TodoFilters
                        :initial-status="filters.status"
                        :initial-sort-by="sortBy"
                        :initial-sort-direction="sortDirection"
                        @filter-change="handleFilterChange"
                    />
                </div> -->

                <!-- Statistics -->
                <TodoStatistics :statistics="statistics" />

                <!--Remder Todo list -->
                <!-- Loading state -->
                <div v-if="loading" class="py-12 px-6 text-center">
                    <Loader class="mx-auto h-12 w-12 text-gray-400 animate-spin" />
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
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700">
                            <PlusIcon class="h-4 w-4 mr-1" />
                            New Todo
                        </button>
                    </div>
                </div>
                <!-- Todo list -->
                <TodoList v-else :todos="todos" :is-submitting="isSubmitting" @reload="fetchTodos"
                    @toggle-status="toggleTodoStatus" @delete="deleteTodo" @update="updateTodo" @create="createTodo" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import TodoList from "../components/todo/TodoList.vue";
import TodoStatistics from "../components/todo/TodoStatistics.vue";
import { todoService } from "../services/TodoService";
import {
    Loader,
    PlusIcon,
    ClipboardIcon
} from "lucide-vue-next";
import { CreateTodo, Todo } from "../schemas/todo-schema";
import { UpdateTodoFormValue } from "../types/todo";

// State
const todos = ref<Todo[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const isSubmitting = ref(false);
const isDeleting = ref(false);

// Filters
const filters = reactive<{
    status: "all" | "completed" | "pending";
    sortBy: "date" | "title";
    sortDirection: "asc" | "desc";
}>({
    status: "all",
    sortBy: "date",
    sortDirection: "desc",
});

const statistics = reactive<{
    total: number;
    completed: number;
    pending: number;
}>({
    total: 0,
    completed: 0,
    pending: 0,
});

// Actions

// Load todos from API
const fetchTodos = async () => {
    try {
        loading.value = true;
        error.value = null;

        // Get todos
        todos.value = await todoService.getAll();
    } catch (err: any) {
        error.value = err.message || "Failed to load todos";
    } finally {
        loading.value = false;
    }
};

// Load statistics
const loadStatistics = async () => {
    try {
        // from todos, count total, completed, and pending
        statistics.total = todos.value.length;
        statistics.completed = todos.value.filter((t) => t.completed).length;
        statistics.pending = todos.value.filter((t) => !t.completed).length;
    } catch (err) {
        // Silently fail, not critical
        console.error("Failed to load statistics", err);
    }
};

// Toggle todo status
const toggleTodoStatus = async (todo: Todo) => {
    try {
        const updatedTodo = { ...todo, completed: !todo.completed };
        await todoService.update(todo.id, { completed: !todo.completed });

        // Update local state
        const index = todos.value.findIndex((t) => t.id === todo.id);
        if (index !== -1) {
            todos.value[index] = {
                ...todos.value[index],
                completed: !todo.completed,
            };
        }

        // Refresh statistics
        loadStatistics();
    } catch (err: any) {
        error.value = err.message || "Failed to update todo status";
    }
};

// Create a new todo
const createTodo = async (data: CreateTodo) => {
    try {
        isSubmitting.value = true;
        error.value = null;

        // Create new todo
        await todoService.create({
            title: data.title,
            body: data.body,
            completed: data.completed || false,
        });

        // Refresh todos and statistics
        await fetchTodos();
        await loadStatistics();
    } catch (err: any) {
        error.value = err.message || "Failed to create todo";
    } finally {
        isSubmitting.value = false;
    }
};

// Update an existing todo
const updateTodo = async (id: number, updates: UpdateTodoFormValue) => {
    console.log("updating todo", id, updates);
    try {
        isSubmitting.value = true;
        error.value = null;

        // Make sure we have a valid ID
        if (!id) {
            throw new Error("Invalid todo ID");
        }

        // Update existing todo
        await todoService.update(id, updates);

        // Refresh todos and statistics
        await fetchTodos();
        await loadStatistics();
    } catch (err: any) {
        console.error("Error updating todo:", err);
        error.value = err.message || "Failed to update todo";
    } finally {
        isSubmitting.value = false;
    }
};

// Delete todo
const deleteTodo = async (todo: Todo) => {
    try {
        isDeleting.value = true;
        error.value = null;
        await todoService.delete(todo.id);

        // Refresh todos and statistics
        await fetchTodos();
        await loadStatistics();
    } catch (err: any) {
        error.value = err.message || "Failed to delete todo";
    } finally {
        isDeleting.value = false;
    }
};

// Lifecycle hooks

// Load todos on component mount
onMounted(() => {
    (async function () {
        await fetchTodos();
        await loadStatistics();
    }());
});
</script>
