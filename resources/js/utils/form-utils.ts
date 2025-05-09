import { ZodError } from "zod";
import { Ref, WatchOptions, watch } from "vue";
import { ValidationErrorType } from "./interfaces";

/**
 * Creates a watch function that clears specific field errors when the form data changes
 *
 * @param formData - The reactive form data object to watch
 * @param errors - Ref to the validation errors object
 * @param options - Optional watch options (defaults to deep: true)
 * @returns The watch stop function
 */
export function setupFormErrorClearer<T extends Record<string, any>>(
    formData: T | Ref<T>,
    errors: Ref<ValidationErrorType>,
    options: WatchOptions = { deep: true }
): () => void {
    return watch(
        formData,
        (newForm) => {
            // Only proceed if there are errors to clear
            if (!errors.value) return;

            // Get all field names from the form
            const fieldNames = Object.keys(newForm);

            // Clear specific field errors
            fieldNames.forEach((fieldName) => {
                if (errors.value && errors.value[fieldName]) {
                    delete errors.value[fieldName];
                }
            });
        },
        options
    );
}

/**
 * Extracts the first error message for each field from a Zod validation error
 *
 * @param zodError - The Zod validation error object
 * @returns An object with field names as keys and first error messages as values
 */
export function extractFirstFieldErrors(
    zodError: ZodError
): ValidationErrorType {
    try {
        const formattedErrors = zodError.format();
        const fieldErrors: Record<string, string> = {};

        Object.keys(formattedErrors).forEach((key) => {
            // Skip the special _errors key
            if (key === "_errors") {
                return;
            }

            // Safely access _errors array
            const errorObj = formattedErrors[key];
            if (!errorObj || typeof errorObj !== "object") {
                return;
            }

            const allErrors = errorObj._errors;
            if (!Array.isArray(allErrors) || allErrors.length === 0) {
                return;
            }

            // Get the first error message
            const firstError = allErrors[0];
            if (typeof firstError === "string" && firstError.trim() !== "") {
                fieldErrors[key] = firstError;
            }
        });

        return fieldErrors;
    } catch (error: any) {
        console.error("Error extracting field errors:", error);
        return null;
    }
}
