/**
 * Base API Error class
 */
export class ApiError extends Error {
    status?: number;
    data?: any;

    constructor(message: string, status?: number, data?: any) {
        super(message);
        this.name = "ApiError";
        this.status = status;
        this.data = data;
    }
}

/**
 * Access Token Error class for 401 unauthorized errors
 */
export class AccessTokenError extends Error {
    data?: any;
    status?: number = 401;
    constructor(
        message: string = "Authentication failed. Please log in again.",
        data?: any
    ) {
        super(message);
        this.name = "AccessTokenError";
        this.data = data;
    }
}

/**
 * Local Storage Data Error class for issues with local storage
 */
export class LocalStorageDataError extends Error {
    constructor(message: string = "Failed to access local storage data") {
        super(message);
        this.name = "LocalStorageDataError";
    }
}

/**
 * Network Error class for connection issues
 */
export class NetworkError extends ApiError {
    constructor(
        message: string = "Network error. Please check your connection."
    ) {
        super(message);
        this.name = "NetworkError";
    }
}

/**
 * Validation Error class for form validation errors
 */
export class ValidationError extends ApiError {
    errors: Record<string, string[]>;

    constructor(
        message: string = "Validation failed",
        errors: Record<string, string[]> = {},
        status: number = 422
    ) {
        super(message, status);
        this.name = "ValidationError";
        this.errors = errors;
    }
}

/**
 * Server Error class for 500 errors
 */
export class ServerError extends ApiError {
    constructor(
        message: string = "Server error. Please try again later.",
        data?: any
    ) {
        super(message, 500, data);
        this.name = "ServerError";
    }
}
