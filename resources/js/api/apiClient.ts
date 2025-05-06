import axios, {
    AxiosError,
    AxiosInstance,
    AxiosRequestConfig,
    AxiosResponse,
} from "axios";
import { ApiError } from "./errors";
// import { ApiError } from "../types/models";

/**
 * API client configuration
 */
export interface ApiClientConfig extends AxiosRequestConfig {
    /**
     * Base URL for API requests
     */
    baseURL: string;

    /**
     * Whether to include credentials in requests
     */
    withCredentials?: boolean;

    /**
     * Default headers for all requests
     */
    headers?: Record<string, string>;

    /**
     * Timeout in milliseconds
     */
    timeout?: number;
}

/**
 * Default API client configuration
 */
const defaultConfig: ApiClientConfig = {
    baseURL: "/api",
    withCredentials: true,
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
    },
    timeout: 30000,
};

/**
 * Creates an API client instance
 *
 * @param config - API client configuration
 * @returns Axios instance
 */
export function createApiClient(
    config: Partial<ApiClientConfig> = {}
): AxiosInstance {
    // Merge default config with provided config
    const apiConfig: ApiClientConfig = {
        ...defaultConfig,
        ...config,
        headers: {
            ...defaultConfig.headers,
            ...config.headers,
        },
    };

    // Create axios instance
    const apiClient = axios.create(apiConfig);

    // Request interceptor to add auth token
    apiClient.interceptors.request.use(
        (config) => {
            const token = localStorage.getItem("auth_token");

            if (token && config.headers) {
                config.headers.Authorization = `Bearer ${token}`;
            }

            return config;
        },
        (error) => Promise.reject(error)
    );

    // Response interceptor for error handling
    apiClient.interceptors.response.use(
        (response: AxiosResponse) => response,
        (error: AxiosError) => {
            // const apiError: ApiError = {
            //     message: error.message || "An unexpected error occurred",
            //     status: error.response?.status,
            // };

            const apiError = new ApiError(
                error.message || "An unexpected error occurred",
                error.response?.status
            );

            // Handle specific error responses
            if (error.response) {
                const { data, status } = error.response;

                // Add response data to error
                if (data && typeof data === "object") {
                    apiError.message =
                        (data as any).message || apiError.message;
                }

                // Handle authentication errors
                if (status === 401) {
                    // Clear auth token if unauthorized
                    localStorage.removeItem("auth_token");

                    // Redirect to login if not already there
                    if (window.location.pathname !== "/login") {
                        window.location.href = "/login";
                    }
                }
            }

            return Promise.reject(apiError);
        }
    );

    return apiClient;
}

// Export a default API client instance
export const apiClient = createApiClient();
