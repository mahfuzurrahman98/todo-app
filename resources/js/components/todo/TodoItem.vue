<template>
    <li class="px-4 py-2">
        <!-- View Mode -->
        <div v-if="!isEditing" class="flex items-start">
            <!-- Checkbox -->
            <div class="mr-4">
                <input
                    :id="`todo-${todo.id}`"
                    type="checkbox"
                    :checked="todo.completed"
                    @change="$emit('toggle-status', todo)"
                    class="h-4 w-4 accent-gray-900"
                    :class="{
                        'mt-1.5': todo.body,
                        'mt-1': !todo.body,
                    }"
                />
            </div>

            <!-- Todo content -->
            <div class="flex-1 min-w-0" @click="startEditing">
                <div class="block cursor-pointer">
                    <p
                        :class="[
                            'text-sm font-medium',
                            todo.completed
                                ? 'text-gray-400 line-through'
                                : 'text-gray-900',
                        ]"
                    >
                        {{ todo.title }}
                    </p>
                    <p v-if="todo.body" class="text-sm text-gray-500 truncate">
                        {{ todo.body }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <button
                @click="$emit('delete', todo)"
                class="cursor-pointer p-1 rounded-full text-gray-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
                <TrashIcon class="h-4 w-4" />
            </button>
        </div>

        <!-- Edit Mode -->
        <div v-else>
            <EditTodoForm
                :todo="todo"
                :is-submitting="isSubmitting"
                @submit="handleSubmit"
                @cancel="cancelEditing"
            />
        </div>
    </li>
</template>

<script setup lang="ts">
import { TrashIcon } from "lucide-vue-next";
import {
    TodoFormValue,
    TodoItemEmits,
    TodoItemProps,
} from "../../interfaces/todo";
import { ref } from "vue";
import EditTodoForm from "./EditTodoForm.vue";

const props = defineProps<TodoItemProps>();
const emit = defineEmits<TodoItemEmits>();

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
const handleSubmit = (id: number, data: TodoFormValue) => {
    // Emit update event with todo ID and updated data
    emit("update", id, data);

    // Exit edit mode
    isEditing.value = false;
};
</script>
