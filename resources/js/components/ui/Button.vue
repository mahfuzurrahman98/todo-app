<template>
    <button
        :class="[
            'inline-flex items-center justify-center gap-2 whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0',
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
    size?: "sm" | "lg";
    variant?: "normal" | "ghost";
    disabled?: boolean;
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

const sizeClasses = computed(() => {
    switch (props.size) {
        case "sm":
            return "h-8 rounded-md px-3 text-xs";
        case "lg":
        default:
            return "h-10 rounded-md px-8";
    }
});

const variantClasses = computed(() => {
    switch (props.variant) {
        case "ghost":
            return "hover:bg-accent hover:text-accent-foreground";
        case "normal":
        default:
            return "bg-primary text-primary-foreground shadow hover:bg-primary/90";
    }
});
</script>
