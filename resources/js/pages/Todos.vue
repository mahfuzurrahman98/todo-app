<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Header -->
                <div
                    class="px-6 py-4 border-b border-gray-200 flex justify-between items-center"
                >
                    <h1 class="text-xl font-semibold text-gray-900">
                        Your Todos
                    </h1>

                    <div class="flex space-x-2">
                        <!-- Add new todo button -->
                        <button
                            @click="openNewTodoModal"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <PlusIcon class="h-4 w-4 mr-1" />
                            New Todo
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <TodoFilters
                        :initial-status="filters.status"
                        :initial-sort-by="sortBy"
                        :initial-sort-direction="sortDirection"
                        @filter-change="handleFilterChange"
                    />
                </div>

                <!-- Statistics -->
                <TodoStatistics :statistics="statistics" />

                <!-- Todo list -->
                <TodoList
                    :todos="todos"
                    :loading="loading"
                    :error="error"
                    :sort-by="sortBy"
                    :sort-direction="sortDirection"
                    @reload="loadTodos"
                    @new-todo="openNewTodoModal"
                    @toggle-status="toggleTodoStatus"
                    @edit="editTodo"
                    @delete="confirmDeleteTodo"
                    @change-page="changePage"
                />
            </div>
        </div>

        <!-- Todo Modal -->
        <TodoModal
            v-model="showModal"
            :todo="editingTodo"
            :is-submitting="isSubmitting"
            @submit="saveTodo"
        />

        <!-- Delete Confirmation Modal -->
        <DeleteConfirmationModal
            v-model="showDeleteModal"
            :is-deleting="isDeleting"
            @confirm="deleteTodo"
        />
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import { todoService } from "../services/TodoService";
import { Todo } from "../types/models";
import {
    Loader,
    PlusIcon,
    ClipboardIcon,
    PencilIcon,
    TrashIcon,
    RefreshCcwIcon,
    AlertTriangleIcon,
} from "lucide-vue-next";

// State
const todos = ref<Todo[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const isSubmitting = ref(false);
const isDeleting = ref(false);
const showModal = ref(false);
const showDeleteModal = ref(false);
const editingTodo = ref<Todo | null>(null);
const todoToDelete = ref<Todo | null>(null);

// Form state
const todoForm = reactive({
    title: "",
    body: "",
    completed: false,
});

// Filters
const filters = reactive({
    status: "all",
});

const statistics = reactive({
    total: 0,
    completed: 0,
    pending: 0,
});

// Load todos on component mount
onMounted(() => {
    loadTodos();
    loadStatistics();
});

// Load todos from API
const loadTodos = async () => {
    try {
        loading.value = true;
        error.value = null;

        // Convert filter status to API parameters
        const filterOptions =
            filters.status !== "all"
                ? {
                      completed: filters.status === "completed",
                  }
                : {};

        // Get todos
        const response = await todoService.getAll();
    } catch (err: any) {
        error.value = err.message || "Failed to load todos";
    } finally {
        loading.value = false;
    }
};

// Load statistics
const loadStatistics = async () => {
    try {
        const stats = await todoService.getStatistics();
        statistics.total = stats.total;
        statistics.completed = stats.completed;
        statistics.pending = stats.pending;
    } catch (err) {
        // Silently fail, not critical
        console.error("Failed to load statistics", err);
    }
};

// Change page
const changePage = (page: number) => {
    pagination.currentPage = page;
    loadTodos();
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

// Open modal for new todo
const openNewTodoModal = () => {
    editingTodo.value = null;
    todoForm.title = "";
    todoForm.body = "";
    todoForm.completed = false;
    showModal.value = true;
};

// Open modal for editing todo
const editTodo = (todo: Todo) => {
    editingTodo.value = todo;
    todoForm.title = todo.title;
    todoForm.body = todo.body || "";
    todoForm.completed = todo.completed;
    showModal.value = true;
};

// Close modal
const closeModal = () => {
    showModal.value = false;
};

// Save todo (create or update)
const saveTodo = async () => {
    try {
        isSubmitting.value = true;

        if (editingTodo.value) {
            // Update existing todo
            await todoService.update(editingTodo.value.id, {
                title: todoForm.title,
                body: todoForm.body || undefined,
                completed: todoForm.completed,
            });
        } else {
            // Create new todo
            await todoService.create({
                title: todoForm.title,
                body: todoForm.body || undefined,
                completed: todoForm.completed,
            });
        }

        // Refresh todos and statistics
        await loadTodos();
        await loadStatistics();

        // Close modal
        closeModal();
    } catch (err: any) {
        error.value = err.message || "Failed to save todo";
    } finally {
        isSubmitting.value = false;
    }
};

// Confirm delete todo
const confirmDeleteTodo = (todo: Todo) => {
    todoToDelete.value = todo;
    showDeleteModal.value = true;
};

// Close delete modal
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    todoToDelete.value = null;
};

// Delete todo
const deleteTodo = async () => {
    if (!todoToDelete.value) return;

    try {
        isDeleting.value = true;
        await todoService.delete(todoToDelete.value.id);

        // Refresh todos and statistics
        await loadTodos();
        await loadStatistics();

        // Close modal
        closeDeleteModal();
    } catch (err: any) {
        error.value = err.message || "Failed to delete todo";
    } finally {
        isDeleting.value = false;
    }
};
</script>
