<template>
    <li class="px-6 py-4">
        <!-- View Mode -->
        <div v-if="!isEditing" class="flex items-center">
            <!-- Checkbox -->
            <div class="mr-4">
                <input :id="`todo-${todo.id}`" type="checkbox" :checked="todo.completed"
                    @change="$emit('toggle-status', todo)" class="h-4 w-4 accent-gray-900" />
            </div>

            <!-- Todo content -->
            <div class="flex-1 min-w-0" @click="startEditing">
                <div class="block cursor-pointer">
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
                </div>
            </div>

            <!-- Actions -->
            <div class="ml-4 flex-shrink-0 flex space-x-2">
                <button @click="startEditing"
                    class="p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700">
                    <PencilIcon class="h-5 w-5" />
                </button>
                <button @click="$emit('delete', todo)"
                    class="p-1 rounded-full text-gray-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <TrashIcon class="h-5 w-5" />
                </button>
            </div>
        </div>

        <!-- Edit Mode -->
        <div v-else>
            <EditTodoForm :todo="todo" :is-submitting="isSubmitting" @submit="handleSubmit" @cancel="cancelEditing" />
        </div>
    </li>
</template>

<script setup lang="ts">
import { PencilIcon, TrashIcon } from "lucide-vue-next";
import { Todo } from "../../schemas/todo-schema";
import { TodoFormValue } from "../../types/todo"
import { ref } from "vue";
import EditTodoForm from "./EditTodoForm.vue";

interface ItemProps {
    todo: Todo;
    isSubmitting?: boolean;
}

interface ItemEmits {
    (e: 'toggle-status', todo: Todo): void;
    (e: 'update', id: number, updates: TodoFormValue): void;
    (e: 'delete', todo: Todo): void;
}

const props = defineProps<ItemProps>();
const emit = defineEmits<ItemEmits>();

// Edit mode state
const isEditing = ref(false);

// Start editing mode
const startEditing = () => {
    isEditing.value = true;
};

// Cancel editing
const cancelEditing = () => {
    isEditing.value = false;
};

// Handle form submission
const handleSubmit = (id: number, updates: TodoFormValue) => {
    // Emit update event with todo ID and updated data
    emit("update", id, updates);
    
    // Exit edit mode
    isEditing.value = false;
};
</script>
