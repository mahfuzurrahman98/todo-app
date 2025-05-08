<template>
    <button
        :class="[
            'cursor-pointer inline-flex items-center justify-center rounded-md font-medium transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-700 disabled:opacity-50 disabled:pointer-events-none',
            sizeClasses,
            variantClasses,
        ]"
        :disabled="disabled"
        :type="type"
        @click="$emit('click', $event)"
    >
        <slot></slot>
    </button>
</template>

<script setup lang="ts">
import { computed } from "vue";

interface ButtonProps {
    /**
     * Button size
     * @default "lg"
     */
    size?: "sm" | "lg";

    /**
     * Button variant
     * @default "normal"
     */
    variant?: "normal" | "ghost";

    /**
     * Whether the button is disabled
     * @default false
     */
    disabled?: boolean;

    /**
     * Button type
     * @default "button"
     */
    type?: "button" | "submit" | "reset";
}

interface ButtonEmits {
    (e: "click", event: MouseEvent): void;
}

const props = withDefaults(defineProps<ButtonProps>(), {
    size: "lg",
    variant: "normal",
    disabled: false,
    type: "button",
});

const emit = defineEmits<ButtonEmits>();

// Compute size classes based on size prop
const sizeClasses = computed(() => {
    switch (props.size) {
        case "sm":
            return "text-xs px-2 py-1";
        case "lg":
        default:
            return "text-sm px-4 py-2";
    }
});

// Compute variant classes based on variant prop
const variantClasses = computed(() => {
    switch (props.variant) {
        case "ghost":
            return "bg-transparent hover:bg-gray-100 text-gray-800 border border-gray-300";
        case "normal":
        default:
            return "bg-gray-800 hover:bg-black text-white border border-transparent shadow-sm";
    }
});
</script>
