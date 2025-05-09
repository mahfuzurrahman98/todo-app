<template>
    <div class="mb-1">
        <textarea
            :id="id"
            :value="modelValue"
            @input="$emit('update:modelValue', $event?.target?.value || '')"
            :placeholder="placeholder"
            :required="required"
            :maxlength="maxlength"
            :rows="rows"
            :disabled="disabled"
            :class="[
                'w-full text-sm focus-visible:outline-none bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 px-0 py-1',
                disabled ? 'opacity-50 cursor-not-allowed' : '',
                error ? 'border-red-500 focus-visible:ring-red-500' : '',
                customClass,
            ]"
            ref="textareaRef"
        ></textarea>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";
import ErrorMessage from "./ErrorMessage.vue";

interface TextareaProps {
    /**
     * The textarea model value
     */
    modelValue: string;

    /**
     * Textarea id
     */
    id?: string;

    /**
     * Textarea placeholder
     */
    placeholder?: string;

    /**
     * Whether the textarea is required
     * @default false
     */
    required?: boolean;

    /**
     * Maximum length of textarea
     */
    maxlength?: number;

    /**
     * Number of rows
     * @default 3
     */
    rows?: number;

    /**
     * Whether the textarea is disabled
     * @default false
     */
    disabled?: boolean;

    /**
     * Error message to display
     */
    error?: string;

    /**
     * Whether to autofocus the textarea
     * @default false
     */
    autofocus?: boolean;

    /**
     * Custom class to add to the textarea
     */
    customClass?: string;
}

const props = withDefaults(defineProps<TextareaProps>(), {
    id: undefined,
    placeholder: "",
    required: false,
    maxlength: undefined,
    rows: 3,
    disabled: false,
    error: undefined,
    autofocus: false,
    variant: "default",
    customClass: "",
});

const emit = defineEmits<{
    (e: "update:modelValue", value: string): void;
    (e: "focus", event: FocusEvent): void;
    (e: "blur", event: FocusEvent): void;
}>();

const textareaRef = ref<HTMLTextAreaElement | null>(null);

// Focus the textarea if autofocus is true
onMounted(async () => {
    if (props.autofocus && textareaRef.value) {
        await nextTick();
        textareaRef.value.focus();
    }
});

// Expose the textarea element and focus method
defineExpose({
    focus: () => {
        textareaRef.value?.focus();
    },
    blur: () => {
        textareaRef.value?.blur();
    },
    textareaElement: textareaRef,
});
</script>
