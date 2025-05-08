<template>
    <div class="bg-white shadow-sm rounded-lg p-4 mb-4">
        <form @submit.prevent="handleSubmit" class="space-y-3">
            <!-- Title input -->
            <div>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 focus-visible:outline-none text-sm font-medium text-gray-900 px-0 py-1"
                    placeholder="Add a new todo..."
                    required
                    ref="titleInput"
                />
            </div>

            <!-- Body input -->
            <div>
                <textarea
                    v-model="form.body"
                    class="w-full bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 text-sm focus-visible:outline-none text-gray-500 px-0 py-1"
                    rows="2"
                    placeholder="Add details (optional, max 200 chars)"
                    maxlength="200"
                ></textarea>
            </div>

            <!-- Form actions -->
            <div class="flex justify-end space-x-2">
                <Button variant="ghost" size="sm" @click="$emit('cancel')">
                    Cancel
                </Button>
                <Button type="submit" size="sm" :disabled="isSubmitting">
                    <Loader
                        v-if="isSubmitting"
                        class="mr-2 h-4 w-4 animate-spin"
                    />
                    {{ isEditing ? "Update" : "Save" }}
                </Button>
            </div>
        </form>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed, onMounted, nextTick } from "vue";
import { Loader } from "lucide-vue-next";
import { TodoFormEmits, TodoFormProps } from "../../types/todo";
import Button from "../ui/Button.vue";

const props = defineProps<TodoFormProps>();
const emit = defineEmits<TodoFormEmits>();

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
        body: form.body?.trim() || undefined,
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
