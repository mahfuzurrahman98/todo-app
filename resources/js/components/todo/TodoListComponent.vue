<template>
    <!-- Todo list -->
    <ul v-if="todos.length" class="divide-y divide-gray-200">
        <TodoItemComponent
            v-for="todo in todos"
            :key="todo.id"
            :todo="todo"
            @refetch="$emit('refetch')"
            @loadStats="$emit('loadStats')"
        />
    </ul>
</template>

<script setup lang="ts">
import TodoItemComponent from "./TodoItemComponent.vue";
import {
    TodoItemEmits as TodoListEmits,
    TodoListProps,
} from "../../utils/interfaces/todo";

const props = defineProps<TodoListProps>();
const emit = defineEmits<TodoListEmits>();
</script>
