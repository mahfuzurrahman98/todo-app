<template>
    <div class="bg-white shadow-sm border border-gray-300 rounded-lg p-4">
        <form @submit.prevent="handleSubmit" class="space-y-3">
            <!-- Title input -->
            <Input
                v-model="formData.title"
                :placeholder="
                    isEditing ? 'Edit todo title' : 'Add a new todo...'
                "
                :maxlength="100"
                variant="underlined"
                autofocus
                customClass="font-medium text-gray-900"
                ref="titleInput"
            />
            <ErrorMessage v-if="errors?.title" :message="errors.title" />

            <!-- Body input -->
            <Textarea
                v-model="formData.body"
                :placeholder="
                    isEditing
                        ? 'Edit'
                        : 'Add' + ' details (optional, max 300 chars)'
                "
                :maxlength="300"
                :rows="2"
                variant="underlined"
                customClass="text-gray-500"
            />
            <ErrorMessage v-if="errors?.body" :message="errors.body" />

            <!-- Form actions -->
            <div class="flex justify-end space-x-2">
                <Button variant="ghost" size="sm" @click="$emit('cancel')">
                    Cancel
                </Button>
                <Button type="submit" size="sm" :disabled="isSubmitting">
                    <Loader v-if="isSubmitting" class="h-4 w-4 animate-spin" />
                    {{ isEditing ? "Update" : "Save" }}
                </Button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";
import { Loader } from "lucide-vue-next";
import { TodoFormEmits, TodoFormProps } from "../../utils/interfaces/todo";
import Button from "../ui/Button.vue";
import Input from "../ui/Input.vue";
import Textarea from "../ui/Textarea.vue";
import ErrorMessage from "../ui/ErrorMessage.vue";

const props = defineProps<TodoFormProps>();
const emit = defineEmits<TodoFormEmits>();

const titleInput = ref<HTMLInputElement | null>(null);

const isEditing = props.todoEditMode;

// Focus on title input when component is mounted
onMounted(async () => {
    await nextTick();
    if (titleInput.value) {
        titleInput.value.focus();
    }
});

// Handle form submission
const handleSubmit = () => {
    try {
        const submittedData = {
            title: props.formData.title.trim(),
            body: props.formData.body?.trim(),
        };

        emit("submit", submittedData);

        // Reset form if not editing
        if (!isEditing) {
            props.formData.title = "";
            props.formData.body = "";
            // Focus title input after reset
            nextTick(() => {
                if (titleInput.value) {
                    titleInput.value.focus();
                }
            });
        }
    } catch (err: any) {
        console.error("Error submitting todo form:", err);
    }
};
</script>
