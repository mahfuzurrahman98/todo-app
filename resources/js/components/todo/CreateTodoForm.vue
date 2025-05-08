<template>
    <div class="mt-4">
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
} from "../../interfaces/todo";
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
