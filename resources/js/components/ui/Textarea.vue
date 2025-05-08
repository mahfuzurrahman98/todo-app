<template>
    <div>
        <label
            v-if="label"
            :for="id"
            class="block text-sm font-medium leading-none mb-1 text-gray-700"
        >
            {{ label }}
        </label>
        <textarea
            :id="id"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :required="required"
            :maxlength="maxlength"
            :rows="rows"
            :disabled="disabled"
            :class="[
                'w-full text-sm focus-visible:outline-none',
                variant === 'underlined'
                    ? 'bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 px-0 py-1'
                    : 'flex rounded-md border border-gray-400 bg-background px-3 py-2 ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-gray-600 focus-visible:ring-offset-2',
                disabled ? 'opacity-50 cursor-not-allowed' : '',
                error ? 'border-red-500 focus-visible:ring-red-500' : '',
                customClass
            ]"
            ref="textareaRef"
        ></textarea>
        <div v-if="error" class="mt-1 text-sm text-red-500">
            {{ error }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from 'vue';

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
     * Textarea label
     */
    label?: string;
    
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
     * Textarea variant
     * @default "default"
     */
    variant?: 'default' | 'underlined';
    
    /**
     * Custom class to add to the textarea
     */
    customClass?: string;
}

const props = withDefaults(defineProps<TextareaProps>(), {
    id: undefined,
    label: undefined,
    placeholder: '',
    required: false,
    maxlength: undefined,
    rows: 3,
    disabled: false,
    error: undefined,
    autofocus: false,
    variant: 'default',
    customClass: '',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'focus', event: FocusEvent): void;
    (e: 'blur', event: FocusEvent): void;
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
