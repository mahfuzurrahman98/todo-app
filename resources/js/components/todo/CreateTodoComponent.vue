<template>
    <!-- Add Todo Button -->
    <div v-if="!showCreateForm" class="py-2">
        <Button @click="showCreateForm = true" size="sm">
            <PlusIcon class="h-4 w-4" />
            Add Todo
        </Button>
    </div>

    <TodoFormComponent
        v-else
        :formData="formData"
        :is-submitting="isSubmitting"
        :errors="errors"
        @submit="handleSubmit"
        @cancel="showCreateForm = false"
    />
</template>

<script setup lang="ts">
import { reactive, ref, watch } from "vue";
import {
    TodoItemEmits as CreateTodoFormEmits,
    TodoFormValue,
} from "../../utils/interfaces/todo";
import { createTodoSchema } from "../../schemas/todo-schema";
import {
    extractFirstFieldErrors,
    setupFormErrorClearer,
} from "../../utils/form-utils";
import { todoService } from "../../services/TodoService";
import { ValidationErrorType } from "../../utils/interfaces";
import { PlusIcon } from "lucide-vue-next";
import TodoFormComponent from "./TodoFormComponent.vue";
import Button from "../ui/Button.vue";

const emit = defineEmits<CreateTodoFormEmits>();

// States
const showCreateForm = ref(false);
const isSubmitting = ref(false);
const errors = ref<ValidationErrorType>(null);

// Form state
const formData = reactive<TodoFormValue>({
    title: "",
    body: "",
});

// Use the reusable function to clear field errors when typing
setupFormErrorClearer(formData, errors);

// Handle form submission
const handleSubmit = async (data: TodoFormValue) => {
    try {
        isSubmitting.value = true;
        errors.value = null;

        // Validate form with Zod
        const validSchema = createTodoSchema.safeParse(data);

        if (!validSchema.success) {
            // Update the errors object with the extracted errors
            errors.value = extractFirstFieldErrors(validSchema.error);
            console.log(errors.value);
            return;
        }

        // Create new todo
        const newTodo = await todoService.create({
            title: data.title,
            body: data.body,
            completed: false,
        });

        showCreateForm.value = false;

        emit("refetch");
        emit("loadStats");
    } catch (err: any) {
        console.error("Error creating todo, in Todos.vue:", err);
        alert(err.message || "Failed to submit todo"); // could have use a toaster instead
    } finally {
        isSubmitting.value = false;
    }
};
</script>
