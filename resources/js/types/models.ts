/**
 * User model interface
 */
export interface User {
    id: number;
    name: string;
    email: string;
    created_at?: string;
    updated_at?: string;
}

/**
 * Todo model interface
 */
export interface Todo {
    id: number;
    title: string;
    body?: string;
    completed: boolean;
    user_id: number;
    created_at?: string;
    updated_at?: string;
}

/**
 * Authentication token interface
 */
export interface AuthToken {
    access_token: string;
    token_type: string;
    expires_in: number;
}

/**
 * API error interface
 */
export interface ApiError {
    message: string;
    errors?: Record<string, string[]>;
    status?: number;
}

export interface ApiResponse<T> {
    message: string;
    data: T;
}
