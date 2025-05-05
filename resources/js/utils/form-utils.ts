import { ZodError } from "zod";
import { Ref } from "vue";

/**
 * Extracts the first error message for each field from a Zod validation error
 *
 * @param zodError - The Zod validation error object
 * @returns An object with field names as keys and first error messages as values
 */
export function extractFirstFieldErrors(
    zodError: ZodError
): Record<string, string> | null {
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

/**
 * Handle form field changes and clear corresponding errors
 * 
 * @param newForm - The new form values
 * @param oldForm - The old form values
 * @param errors - Current errors object
 * @returns New errors object with cleared fields, or null if no errors remain
 */
export function handleFieldErrorClearing<T extends Record<string, any>>(
    newForm: T,
    oldForm: T,
    errors: Record<string, any> | null
): Record<string, any> | null {
    // Only proceed if there are errors to clear
    if (!errors) return errors;
    
    // Start with the current errors
    let updatedErrors = { ...errors };
    let hasChanges = false;
    
    // Get all field names from the form
    const fieldNames = Object.keys(newForm);
    
    // Check each field to see if it changed
    fieldNames.forEach(fieldName => {
        // If the field value changed and there's an error for this field
        if (newForm[fieldName] !== oldForm[fieldName] && updatedErrors[fieldName]) {
            // Remove this field's error
            delete updatedErrors[fieldName];
            hasChanges = true;
        }
    });
    
    // If we made changes
    if (hasChanges) {
        // Return null if no errors remain, otherwise return the updated errors
        return Object.keys(updatedErrors).length ? updatedErrors : null;
    }
    
    // If no changes were made, return the original errors
    return errors;
}
