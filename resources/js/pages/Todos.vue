<template>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <!-- Header -->
                <div
                    class="px-4 py-4 border-b border-gray-200 flex justify-between items-center"
                >
                    <h1 class="text-xl font-semibold text-gray-900">Todos</h1>
                    <div class="flex items-center space-x-4">
                        <span
                            v-if="user"
                            class="text-sm font-medium text-gray-700 border border-gray-200 rounded-md px-3 py-1 bg-gray-50"
                        >
                            {{ userName }}
                        </span>
                        <Button
                            variant="ghost"
                            size="sm"
                            @click="handleLogout"
                            :disabled="isLoggingOut"
                            class="text-red-500 hover:text-red-700"
                        >
                            <LoaderCircle
                                v-if="isLoggingOut"
                                class="h-4 w-4 animate-spin"
                            />
                            <LogOut v-else class="h-4 w-4" />
                            Logout
                        </Button>
                    </div>
                </div>

                <!-- Error -->
                <div v-if="error" class="px-4 py-2">
                    <ErrorAlert :error="error" />
                </div>

                <div v-else>
                    <!-- Filters -->
                    <div class="px-4 py-4 border-b border-gray-200">
                        <TodoFiltersComponent
                            :initial-status="filters.status"
                            :initial-sort-by="filters.sortBy"
                            @filter-change="handleFilterChange"
                        />
                    </div>

                    <!-- Statistics -->
                    <TodoStatisticsComponent
                        :statistics="statistics"
                        :loading="loading"
                    />

                    <!-- Create Todo Form -->
                    <div class="px-4 py-2 border-b">
                        <CreateTodoComponent
                            @refetch="fetchTodos"
                            @loadStats="loadStatistics"
                        />
                    </div>

                    <!--Render Todo list -->
                    <!-- Loading state -->
                    <div v-if="loading" class="py-12 px-6 text-center">
                        <Loader
                            class="mx-auto h-12 w-12 text-gray-400 animate-spin"
                        />
                    </div>

                    <!-- Todo list -->
                    <TodoListComponent
                        v-else-if="todos.length > 0"
                        :todos="todos"
                        @refetch="fetchTodos"
                        @loadStats="loadStatistics"
                    />
                    <!-- Empty state only if filter status is "all" -->
                    <div
                        v-else-if="todos.length === 0"
                        class="py-12 px-6 text-center"
                    >
                        <ClipboardIcon
                            class="mx-auto h-12 w-12 text-gray-400"
                        />
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
                                    : "Try changing or reset the filters."
                            }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed, watch } from "vue";
import { useRouter } from "vue-router";
import { todoService } from "../services/TodoService";
import { Loader, ClipboardIcon, LogOut, LoaderCircle } from "lucide-vue-next";
import { TodoFilters, TodoStats } from "../utils/interfaces/todo";
import { useAuthStore } from "../stores/authStore";
import {
    TodoFiltersSortByEnum,
    TodoFiltersStatusEnum,
} from "../utils/enums/todo";
import { Todo } from "../schemas/todo-schema";
import TodoListComponent from "../components/todo/TodoListComponent.vue";
import TodoStatisticsComponent from "../components/todo/TodoStatisticsComponent.vue";
import TodoFiltersComponent from "../components/todo/TodoFiltersComponent.vue";
import CreateTodoComponent from "../components/todo/CreateTodoComponent.vue";
import Button from "../components/ui/Button.vue";
import ErrorAlert from "../components/ui/ErrorAlert.vue";

// Router and Auth
const router = useRouter();
const authStore = useAuthStore();
const user = computed(() => authStore.user);
const userName = computed(() => {
    if (!user.value || !user.value.name) return "";
    // Return only the first part of the name (before any spaces)
    return user.value.name.split(" ")[0];
});

// State
const todos = ref<Todo[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

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
    } catch (err: any) {
        // Silently fail, not critical
        console.error("Failed to load statistics", err);
    }
};

// Auth actions
const isLoggingOut = ref(false);

const handleLogout = async () => {
    if (isLoggingOut.value) return;

    try {
        isLoggingOut.value = true;
        await authStore.logout();
        router.push("/login");
    } catch (err: any) {
        console.error("Failed to logout", err);
    } finally {
        isLoggingOut.value = false;
    }
};

// Lifecycle hooks

// Load todos on component mount
onMounted(() => {
    (async function () {
        // Initialize auth store
        await authStore.init();

        // Fetch todos
        await fetchTodos();
        loadStatistics();
    })();
});
</script>
