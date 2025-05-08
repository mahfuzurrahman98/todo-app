<template>
    <div>
        <!-- Add Todo Button -->
        <div class="px-4 py-2 border-b border-gray-200 flex justify-">
            <Button
                v-if="!showCreateForm"
                @click="showCreateForm = true"
                size="sm"
            >
                <PlusIcon class="h-4 w-4 mr-1" />
                Add Todo
            </Button>
        </div>

        <!-- Create Todo Form -->
        <div class="px-6" v-if="showCreateForm">
            <CreateTodoForm
                :is-submitting="isSubmitting"
                @submit="handleCreate"
                @cancel="showCreateForm = false"
            />
        </div>

        <!-- Todo list -->
        <ul v-if="todos.length" class="divide-y divide-gray-200">
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
import { CreateTodo } from "../../schemas/todo-schema";
import TodoItem from "./TodoItem.vue";
import CreateTodoForm from "./CreateTodoForm.vue";
import Button from "../ui/Button.vue";
import { TodoListEmits, TodoListProps, TodoFormValue } from "../../interfaces/todo";

const props = defineProps<TodoListProps>();
const emit = defineEmits<TodoListEmits>();

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
