<template>
    <div class="space-y-4">
        <TodoForm :todo="todo" :is-submitting="isSubmitting" @submit="handleSubmit" @cancel="$emit('cancel')" />
    </div>
</template>

<script setup lang="ts">
import { CreateTodo, Todo } from '../../schemas/todo-schema';
import { TodoFormValue } from '../../types/todo';
import TodoForm from './TodoForm.vue';

interface EditFormProps {
    todo: Todo;
    isSubmitting?: boolean;
}

interface EditFormEmits {
    (e: 'submit', id: number, updates: TodoFormValue): void;
    (e: 'cancel'): void;
}

const props = defineProps<EditFormProps>();
const emit = defineEmits<EditFormEmits>();

// Handle form submission
const handleSubmit = (data: TodoFormValue) => {
    emit('submit', props.todo.id, {
        title: data.title,
        body: data.body
    });
};
</script>
