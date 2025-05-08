<template>
    <div class="space-y-4">
        <h3 class="text-lg font-medium text-gray-900">Add New Todo</h3>
        <TodoForm
            :is-submitting="isSubmitting"
            @submit="handleSubmit"
            @cancel="$emit('cancel')"
        />
    </div>
</template>

<script setup lang="ts">
import {
    CreateTodoFormEmits,
    CreateTodoFormProps,
    TodoFormValue,
} from "../../types/todo";
import TodoForm from "./TodoForm.vue";

const props = defineProps<CreateTodoFormProps>();
const emit = defineEmits<CreateTodoFormEmits>();

// Handle form submission
const handleSubmit = (data: TodoFormValue) => {
    emit("submit", {
        title: data.title,
        body: data.body,
        completed: false,
    });
};
</script>
