<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 flex justify-between items-center"
                >
                    <h1 class="text-xl font-semibold text-gray-900">
                        Your Todos
                    </h1>
                </div>

                <!-- Filters -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <TodoFiltersComponent
                        :initial-status="filters.status"
                        :initial-sort-by="filters.sortBy"
                        @filter-change="handleFilterChange"
                    />
                </div>

                <!-- Statistics -->
                <TodoStatistics :statistics="statistics" />

                <!--Remder Todo list -->
                <!-- Loading state -->
                <div v-if="loading" class="py-12 px-6 text-center">
                    <Loader
                        class="mx-auto h-12 w-12 text-gray-400 animate-spin"
                    />
                </div>
                <!-- Todo list -->
                <TodoList
                    v-else-if="todos.length > 0"
                    :todos="todos"
                    :is-submitting="isSubmitting"
                    @reload="fetchTodos"
                    @toggle-status="toggleTodoStatus"
                    @delete="deleteTodo"
                    @create="createTodo"
                    @update="updateTodo"
                />
                <!-- Empty state only if filter status is "all" -->
                <div
                    v-else-if="todos.length === 0"
                    class="py-12 px-6 text-center"
                >
                    <ClipboardIcon class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        {{
                            filters.status === "all"
                                ? "No todos found"
                                : filters.status === "completed"
                                ? "No completed todos found"
                                : "No pending todos found"
                        }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{
                            filters.status === "all"
                                ? "Get started by creating a new todo."
                                : "Try changing the filters."
                        }}
                    </p>
                    <div v-if="filters.status === 'all'" class="mt-6">
                        <Button @click="$emit('new-todo')">
                            <PlusIcon class="h-4 w-4 mr-1" />
                            New Todo
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from "vue";
import TodoList from "../components/todo/TodoList.vue";
import TodoStatistics from "../components/todo/TodoStatistics.vue";
import TodoFiltersComponent from "../components/todo/TodoFilters.vue";
import { todoService } from "../services/TodoService";
import { Loader, PlusIcon, ClipboardIcon } from "lucide-vue-next";
import { CreateTodo, Todo } from "../schemas/todo-schema";
import {
    TodoFilters,
    TodoFiltersSortByEnum,
    TodoFiltersStatusEnum,
    TodoFormValue,
    TodoStats,
} from "../types/todo";
import Button from "../components/ui/Button.vue";

// State
const todos = ref<Todo[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const isSubmitting = ref(false);
const isDeleting = ref(false);

// Filters
const filters = reactive<TodoFilters>({
    status: TodoFiltersStatusEnum.All,
    sortBy: TodoFiltersSortByEnum.DateDesc,
});

const statistics = reactive<TodoStats>({
    total: 0,
    completed: 0,
    pending: 0,
});

// Actions

// All todos (unfiltered)
const allTodos = ref<Todo[]>([]);

// Load todos from API
const fetchTodos = async () => {
    try {
        loading.value = true;
        error.value = null;

        // Get todos
        allTodos.value = await todoService.getAll();

        // Apply filters
        applyFilters();
    } catch (err: any) {
        error.value = err.message || "Failed to load todos";
    } finally {
        loading.value = false;
    }
};

// Apply filters to todos
const applyFilters = () => {
    // Filter by status
    let filteredTodos = [...allTodos.value];

    if (filters.status === "completed") {
        filteredTodos = filteredTodos.filter((todo) => todo.completed);
    } else if (filters.status === "pending") {
        filteredTodos = filteredTodos.filter((todo) => !todo.completed);
    }

    // Sort todos
    filteredTodos.sort((a, b) => {
        if (filters.sortBy === "title") {
            // Sort by title alphabetically
            return a.title.localeCompare(b.title);
        } else {
            // Sort by date (created_at)
            const dateA = new Date(a.created_at).getTime();
            const dateB = new Date(b.created_at).getTime();
            return filters.sortBy === "dateAsc" ? dateA - dateB : dateB - dateA;
        }
    });

    todos.value = filteredTodos;
};

// Handle filter changes
const handleFilterChange = (newFilters: TodoFilters) => {
    // Update filters
    filters.status = newFilters.status;
    filters.sortBy = newFilters.sortBy;

    // Re-apply filters to existing todos
    applyFilters();
};

// Load statistics
const loadStatistics = () => {
    try {
        // Calculate statistics from all todos, not just filtered ones
        statistics.total = allTodos.value.length;
        statistics.completed = allTodos.value.filter((t) => t.completed).length;
        statistics.pending = allTodos.value.filter((t) => !t.completed).length;
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

        // Update both all todos and filtered todos
        const allIndex = allTodos.value.findIndex((t) => t.id === todo.id);
        if (allIndex !== -1) {
            allTodos.value[allIndex] = {
                ...allTodos.value[allIndex],
                completed: !todo.completed,
            };
        }

        // Also update the filtered list
        const filteredIndex = todos.value.findIndex((t) => t.id === todo.id);
        if (filteredIndex !== -1) {
            todos.value[filteredIndex] = {
                ...todos.value[filteredIndex],
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
        const newTodo = await todoService.create({
            title: data.title,
            body: data.body,
            completed: data.completed || false,
        });

        // Add to all todos
        allTodos.value.push(newTodo);

        // Re-apply filters and update statistics
        applyFilters();
        loadStatistics();
    } catch (err: any) {
        error.value = err.message || "Failed to create todo";
    } finally {
        isSubmitting.value = false;
    }
};

// Update an existing todo
const updateTodo = async (id: number, updates: TodoFormValue) => {
    try {
        isSubmitting.value = true;
        error.value = null;

        // Make sure we have a valid ID
        if (!id) {
            throw new Error("Invalid todo ID");
        }

        // Update existing todo
        const updatedTodo = await todoService.update(id, updates);

        // Update in allTodos
        const allIndex = allTodos.value.findIndex((t) => t.id === id);
        if (allIndex !== -1) {
            allTodos.value[allIndex] = updatedTodo;
        }

        // Re-apply filters and update statistics
        applyFilters();
        loadStatistics();
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

        // Remove from allTodos
        allTodos.value = allTodos.value.filter((t) => t.id !== todo.id);

        // Re-apply filters and update statistics
        applyFilters();
        loadStatistics();
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
        loadStatistics();
    })();
});
</script>
