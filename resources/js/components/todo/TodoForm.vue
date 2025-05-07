<template>
    <div class="bg-white shadow-sm rounded-lg p-4 mb-4">
        <form @submit.prevent="handleSubmit" class="space-y-3">
            <!-- Title input -->
            <div>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 text-sm font-medium text-gray-900 px-0 py-1"
                    placeholder="Add a new todo..."
                    required
                    ref="titleInput"
                />
            </div>

            <!-- Body input -->
            <div>
                <input
                    v-model="form.body"
                    type="text"
                    class="w-full bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 text-sm text-gray-500 px-0 py-1"
                    placeholder="Add details (optional, max 200 chars)"
                    maxlength="200"
                />
            </div>

            <!-- Form actions -->
            <div class="flex justify-end space-x-2">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="px-3 py-1 text-sm text-gray-700 hover:text-gray-900 focus:outline-none"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-800 hover:bg-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-700"
                    :disabled="isSubmitting"
                >
                    <Loader
                        v-if="isSubmitting"
                        class="mr-2 h-4 w-4 animate-spin"
                    />
                    {{ isEditing ? "Update" : "Add Todo" }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, nextTick } from "vue";
import { Loader } from "lucide-vue-next";
import { Todo } from "../../schemas/todo-schema";
import { TodoFormValue } from "../../types/todo";

interface FormProps {
    todo?: Todo | null;
    isSubmitting?: boolean;
}

interface FormEmits {
    (e: "submit", data: TodoFormValue): void;
    (e: "cancel"): void;
}

const props = defineProps<FormProps>();
const emit = defineEmits<FormEmits>();

// Form state
const form = reactive({
    title: props.todo?.title || "",
    body: props.todo?.body || "",
});

const titleInput = ref<HTMLInputElement | null>(null);

// Computed properties
const isEditing = computed(() => !!props.todo);

// Focus on title input when component is mounted
onMounted(async () => {
    await nextTick();
    if (titleInput.value) {
        titleInput.value.focus();
    }
});

// Handle form submission
const handleSubmit = () => {
    if (!form.title.trim()) return;

    emit("submit", {
        title: form.title.trim(),
        body: form.body.trim() || undefined,
    });

    // Reset form if not editing
    if (!isEditing.value) {
        form.title = "";
        form.body = "";

        // Focus title input after reset
        nextTick(() => {
            if (titleInput.value) {
                titleInput.value.focus();
            }
        });
    }
};
</script>
