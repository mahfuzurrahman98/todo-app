<template>
  <div
    v-if="modelValue"
    class="fixed z-10 inset-0 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div
      class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
    >
      <!-- Background overlay -->
      <div
        class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        aria-hidden="true"
        @click="$emit('update:modelValue', false)"
      ></div>

      <!-- Modal panel -->
      <div
        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
      >
        <form @submit.prevent="handleSubmit">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div
                class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full"
              >
                <h3
                  class="text-lg leading-6 font-medium text-gray-900"
                  id="modal-title"
                >
                  {{ isEditing ? "Edit Todo" : "New Todo" }}
                </h3>

                <div class="mt-4 space-y-4">
                  <!-- Title field -->
                  <div>
                    <label
                      for="title"
                      class="block text-sm font-medium text-gray-700"
                      >Title</label
                    >
                    <input
                      id="title"
                      v-model="form.title"
                      type="text"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      placeholder="What needs to be done?"
                      required
                    />
                  </div>

                  <!-- Description field -->
                  <div>
                    <label
                      for="body"
                      class="block text-sm font-medium text-gray-700"
                      >Description (optional)</label
                    >
                    <textarea
                      id="body"
                      v-model="form.body"
                      rows="3"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                      placeholder="Add details about this task..."
                    ></textarea>
                  </div>

                  <!-- Completed checkbox -->
                  <div class="flex items-center">
                    <input
                      id="completed"
                      v-model="form.completed"
                      type="checkbox"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    />
                    <label
                      for="completed"
                      class="ml-2 block text-sm text-gray-900"
                    >
                      Mark as completed
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Modal actions -->
          <div
            class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
          >
            <button
              type="submit"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="isSubmitting"
            >
              <Loader
                v-if="isSubmitting"
                class="mr-2 h-4 w-4 animate-spin"
              />
              {{ isSubmitting ? "Saving..." : "Save" }}
            </button>
            <button
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
              @click="$emit('update:modelValue', false)"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch, computed } from "vue";
import { Loader } from "lucide-vue-next";
import { Todo } from "../../types/models";

const props = defineProps<{
  modelValue: boolean;
  todo?: Todo | null;
  isSubmitting: boolean;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void;
  (e: 'submit', data: { title: string; body?: string; completed: boolean }): void;
}>();

// Form state
const form = reactive({
  title: "",
  body: "",
  completed: false,
});

// Computed
const isEditing = computed(() => !!props.todo);

// Watch for todo changes to update form
watch(() => props.todo, (newTodo) => {
  if (newTodo) {
    form.title = newTodo.title;
    form.body = newTodo.body || "";
    form.completed = newTodo.completed;
  } else {
    // Reset form for new todo
    form.title = "";
    form.body = "";
    form.completed = false;
  }
}, { immediate: true });

// Handle form submission
const handleSubmit = () => {
  emit('submit', {
    title: form.title,
    body: form.body || undefined,
    completed: form.completed,
  });
};
</script>
