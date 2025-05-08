<template>
    <div class="space-y-4">
        <TodoForm
            :todo="todo"
            :is-submitting="isSubmitting"
            @submit="handleSubmit"
            @cancel="$emit('cancel')"
        />
    </div>
</template>

<script setup lang="ts">
import {
    EditTodoFormEmits,
    TodoItemProps as EditTodoFormProps,
    TodoFormValue,
} from "../../interfaces/todo";
import TodoForm from "./TodoForm.vue";

const props = defineProps<EditTodoFormProps>();
const emit = defineEmits<EditTodoFormEmits>();

// Handle form submission
const handleSubmit = (data: TodoFormValue) => {
    emit("submit", props.todo.id, {
        title: data.title,
        body: data.body,
    });
};
</script>
