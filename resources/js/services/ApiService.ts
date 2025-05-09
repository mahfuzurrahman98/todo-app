import { AUTH_ENDPOINTS } from "../api/endpoints";
import { ApiError, AccessTokenError } from "../utils/errors";
import { localStorageService } from "./LocalStorageService";

/**
 * Base API service class with common error handling
 */
export class ApiService {
    /**
     * Handle API errors
     *
     * @param error - Error from API
     * @returns Error
     */
    protected handleError(error: any): Error {
        // If it's already an instance of our custom error classes, just return it
        // if (error instanceof ApiError) {
        //     return error;
        // }

        // If it's a 401 error, ensure we remove the token
        if (error.status === 401) {
            localStorageService.removeData("auth_token");
            return new AccessTokenError(
                error.message || "Authentication failed"
            );
        }

        // For other errors, return a generic error
        return new ApiError(error.message || "An error occurred", error.status);
    }
}
