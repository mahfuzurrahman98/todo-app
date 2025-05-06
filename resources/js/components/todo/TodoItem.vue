<template>
    <li class="px-6 py-4 flex items-center">
        <!-- Checkbox -->
        <div class="mr-4">
            <input :id="`todo-${todo.id}`" type="checkbox" :checked="todo.completed"
                @change="$emit('toggle-status', todo)"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
        </div>

        <!-- Todo content -->
        <div class="flex-1 min-w-0">
            <label :for="`todo-${todo.id}`" class="block">
                <p :class="[
                    'text-sm font-medium',
                    todo.completed
                        ? 'text-gray-400 line-through'
                        : 'text-gray-900',
                ]">
                    {{ todo.title }}
                </p>
                <p v-if="todo.body" class="text-sm text-gray-500 truncate">
                    {{ todo.body }}
                </p>
            </label>
        </div>

        <!-- Actions -->
        <div class="ml-4 flex-shrink-0 flex space-x-2">
            <button @click="$emit('edit', todo)"
                class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <PencilIcon class="h-5 w-5" />
            </button>
            <button @click="$emit('delete', todo)"
                class="p-1 rounded-full text-gray-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <TrashIcon class="h-5 w-5" />
            </button>
        </div>
    </li>
</template>

<script setup lang="ts">
import { PencilIcon, TrashIcon } from "lucide-vue-next";
import { Todo } from "../../types/models";

defineProps<{
    todo: Todo;
}>();

defineEmits<{
    (e: 'toggle-status', todo: Todo): void;
    (e: 'edit', todo: Todo): void;
    (e: 'delete', todo: Todo): void;
}>();
</script>
