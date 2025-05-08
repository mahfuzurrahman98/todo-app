/**
 * User interface
 */
export interface User {
    id: number;
    name: string;
    email: string;
    created_at?: string;
    updated_at?: string;
}

/**
 * Login response interface
 */
export interface LoginResponse {
    token: string;
}

/**
 * Auth state interface
 */
export interface AuthState {
    user: User | null;
    isAuthenticated: boolean;
    loading: boolean;
    error: string | null;
}
