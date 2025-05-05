import { ZodError } from "zod";

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
