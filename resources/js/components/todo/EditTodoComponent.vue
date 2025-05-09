<template>
    <div class="space-y-4">
        <TodoFormComponent
            :formData="formData"
            :is-submitting="isSubmitting"
            :errors="errors"
            :todoEditMode="true"
            @submit="handleSubmit"
            @cancel="$emit('cancel')"
        />
    </div>
</template>

<script setup lang="ts">
import { reactive, ref, watch } from "vue";
import {
    EditTodoFormEmits,
    TodoItemProps as EditTodoFormProps,
    TodoFormValue,
} from "../../utils/interfaces/todo";
import TodoFormComponent from "./TodoFormComponent.vue";
import { todoService } from "../../services/TodoService";
import { ValidationErrorType } from "../../utils/interfaces";
import { updateTodoSchema } from "../../schemas/todo-schema";
import {
    extractFirstFieldErrors,
    setupFormErrorClearer,
} from "../../utils/form-utils";

const props = defineProps<EditTodoFormProps>();
const emit = defineEmits<EditTodoFormEmits>();

// States
const isSubmitting = ref(false);
const errors = ref<ValidationErrorType>(null);

const formData = reactive<TodoFormValue>({
    title: props.todo?.title || "",
    body: props.todo.body || "",
});

// Use the reusable function to clear field errors when typing
setupFormErrorClearer(formData, errors);

// Handle form submission
const handleSubmit = async (data: TodoFormValue) => {
    try {
        isSubmitting.value = true;
        errors.value = null;

        // Validate form with Zod
        const validSchema = updateTodoSchema.safeParse(data);

        if (!validSchema.success) {
            // Update the errors object with the extracted errors
            errors.value = extractFirstFieldErrors(validSchema.error);
            return;
        }

        const id = props.todo?.id;

        // Make sure we have a valid ID
        if (!id) {
            throw new Error("Invalid todo ID");
        }

        // Update existing todo
        const updatedTodo = await todoService.update(id, data);

        emit("refetch");
        emit("loadStats");
    } catch (err: any) {
        console.error("Error updating todo:", err);
        alert(err.message || "Failed to update todo"); // could have use a toaster instead
    } finally {
        isSubmitting.value = false;
    }
};
</script>
