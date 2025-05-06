import {
    AccessTokenError,
    ApiError,
    LocalStorageDataError,
    NetworkError,
    ServerError,
    ValidationError,
} from "./errors";
import { localStorageService } from "../services/LocalStorageService";

// Base URL for API
let API_BASE_URL = "/api";

// Use environment variable if available
try {
    // @ts-ignore - Vite specific environment variable access
    if (import.meta.env && import.meta.env.VITE_API_URL) {
        API_BASE_URL = import.meta.env.VITE_API_URL;
    }
} catch (e) {
    // Fallback to default if environment variables aren't available
}

// Request method types
export type ReqMethod = "GET" | "POST" | "PUT" | "PATCH" | "DELETE";

/**
 * Get tokens from local storage
 */
export function getTokensFromLocal() {
    try {
        const accessToken = localStorageService.getData<string>("auth_token");
        return { accessToken };
    } catch (error: any) {
        throw new LocalStorageDataError(
            "Failed to get tokens from local storage"
        );
    }
}

/**
 * Prepare a public API call
 *
 * @param method - HTTP method
 * @param data - Optional data to send
 * @returns Request configuration
 */
export function prepareApiCall(
    method: ReqMethod = "POST",
    data?: any
): RequestInit {
    const headers: HeadersInit = {
        "Content-Type": "application/json",
        Accept: "application/json",
    };

    return {
        method,
        headers,
        body: data ? JSON.stringify(data) : undefined,
    };
}

/**
 * Prepare a private API call (with auth token)
 *
 * @param method - HTTP method
 * @param payload - Optional data to send
 * @returns Request configuration
 */
export function preparePrivateApiCall(
    method: ReqMethod,
    payload?: any
): RequestInit {
    try {
        const { accessToken } = getTokensFromLocal();

        if (!accessToken) {
            throw new AccessTokenError("No access token found");
        }

        const headers: HeadersInit = {
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `Bearer ${accessToken}`,
        };

        return {
            method,
            headers,
            body: payload ? JSON.stringify(payload) : undefined,
        };
    } catch (error: any) {
        if (error instanceof LocalStorageDataError) {
            throw error;
        }
        throw new AccessTokenError("Authentication required");
    }
}

/**
 * Handle API response
 *
 * @param response - Fetch response
 * @returns Promise with parsed response data
 */
export async function handleApiResponse<T>(response: Response): Promise<T> {
    // For empty responses or 204 No Content
    if (
        response.status === 204 ||
        response.headers.get("content-length") === "0"
    ) {
        return {} as T;
    }

    // Try to parse JSON response
    let data;
    try {
        data = await response.json();
    } catch (error: any) {
        throw new ApiError("Invalid response format", response.status);
    }

    // Handle error responses
    if (!response.ok) {
        // Handle 401 Unauthorized for private endpoints
        if (response.status === 401) {
            // Logout user by clearing token
            localStorageService.removeData("auth_token");
            throw new AccessTokenError(
                data.message || "Authentication failed",
                data
            );
        }

        // Handle validation errors (422)
        if (response.status === 422 && data.errors) {
            throw new ValidationError(
                data.message || "Validation failed",
                data.errors,
                response.status
            );
        }

        // Handle server errors (500)
        if (response.status >= 500) {
            throw new ServerError(data.message || "Server error", data);
        }

        // Handle other errors
        throw new ApiError(
            data.message || `Error ${response.status}`,
            response.status,
            data
        );
    }

    return data as T;
}

/**
 * Make an API request
 *
 * @param endpoint - API endpoint
 * @param config - Request configuration
 * @returns Promise with response data
 */
export async function apiRequest<T>(
    endpoint: string,
    config: RequestInit
): Promise<T> {
    const url = `${API_BASE_URL}${endpoint}`;

    try {
        const response = await fetch(url, config);
        return await handleApiResponse<T>(response);
    } catch (error: any) {
        if (error instanceof ApiError) {
            throw error;
        }

        // Handle network errors
        if (error instanceof TypeError && error.message.includes("fetch")) {
            throw new NetworkError();
        }

        throw new ApiError(
            error instanceof Error ? error.message : "Unknown error"
        );
    }
}
