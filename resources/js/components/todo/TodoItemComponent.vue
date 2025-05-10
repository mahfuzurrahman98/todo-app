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
                    @change="handleToggleStatus"
                    class="h-4 w-4 accent-gray-900 mt-1.5"
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
                @click="handleDelete"
                class="cursor-pointer p-1 rounded-full text-gray-400 hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
            >
                <TrashIcon class="h-4 w-4" />
            </button>
        </div>

        <!-- Edit Mode -->
        <div v-else>
            <EditTodoFormComponent
                :todo="todo"
                @cancel="cancelEditing"
                @refetch="$emit('refetch')"
                @loadStats="$emit('loadStats')"
            />
        </div>
    </li>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { TrashIcon } from "lucide-vue-next";
import { TodoItemEmits, TodoItemProps } from "../../utils/interfaces/todo";
import { todoService } from "../../services/TodoService";
import EditTodoFormComponent from "./EditTodoComponent.vue";

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

// Toggle todo status
const handleToggleStatus = async () => {
    try {
        await todoService.update(props.todo.id, {
            completed: !props.todo.completed,
        });

        emit("refetch");
        emit("loadStats");
    } catch (err: any) {
        console.error("Error updating todo status:", err);
        alert(err.message || "Failed to update todo status"); // could have use a toaster instead
    }
};

// handle delete
const handleDelete = async () => {
    try {
        await todoService.delete(props.todo.id);

        emit("refetch");
        emit("loadStats");
    } catch (err: any) {
        console.error("Error deleting todo:", err);
        alert(err.message || "Failed to delete todo"); // could have use a toaster instead
    }
};
</script>
