import { User, AuthToken } from "./models";

/**
 * Login request interface
 */
export interface LoginRequest {
    email: string;
    password: string;
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
