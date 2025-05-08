<template>
    <div>
        <label
            v-if="label"
            :for="id"
            class="block text-sm font-medium leading-none mb-1 text-gray-700"
        >
            {{ label }}
        </label>
        <input
            :id="id"
            :type="type"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :required="required"
            :maxlength="maxlength"
            :disabled="disabled"
            :class="[
                'w-full text-sm focus-visible:outline-none bg-transparent border-0 border-b border-gray-300 focus:border-gray-700 focus:ring-0 px-0 py-1',
                disabled ? 'opacity-50 cursor-not-allowed' : '',
                error ? 'border-red-500 focus-visible:ring-red-500' : '',
                customClass,
            ]"
            ref="inputRef"
        />
        <div v-if="error" class="mt-1 text-sm text-red-500">
            {{ error }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";

interface InputProps {
    /**
     * The input model value
     */
    modelValue: string;

    /**
     * Input type
     * @default "text"
     */
    type?: string;

    /**
     * Input id
     */
    id?: string;

    /**
     * Input label
     */
    label?: string;

    /**
     * Input placeholder
     */
    placeholder?: string;

    /**
     * Whether the input is required
     * @default false
     */
    required?: boolean;

    /**
     * Maximum length of input
     */
    maxlength?: number;

    /**
     * Whether the input is disabled
     * @default false
     */
    disabled?: boolean;

    /**
     * Error message to display
     */
    error?: string;

    /**
     * Whether to autofocus the input
     * @default false
     */
    autofocus?: boolean;

    /**
     * Custom class to add to the input
     */
    customClass?: string;
}

const props = withDefaults(defineProps<InputProps>(), {
    type: "text",
    id: undefined,
    label: undefined,
    placeholder: "",
    required: false,
    maxlength: undefined,
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

const inputRef = ref<HTMLInputElement | null>(null);

// Focus the input if autofocus is true
onMounted(async () => {
    if (props.autofocus && inputRef.value) {
        await nextTick();
        inputRef.value.focus();
    }
});

// Expose the input element and focus method
defineExpose({
    focus: () => {
        inputRef.value?.focus();
    },
    blur: () => {
        inputRef.value?.blur();
    },
    inputElement: inputRef,
});
</script>
