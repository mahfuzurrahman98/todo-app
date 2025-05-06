import { ApiResponse, User } from "../types/models";
import { LoginRequest, LoginResponse } from "../types/auth";
import { AUTH_ENDPOINTS } from "../api/endpoints";
import {
    apiRequest,
    prepareApiCall,
    preparePrivateApiCall,
} from "../api/apiUtils";
import { ApiService } from "./ApiService";
import { localStorageService } from "./LocalStorageService";

/**
 * Authentication service
 */
export class AuthService extends ApiService {
    /**
     * Login with email and password
     *
     * @param data - Login credentials
     * @returns Promise with login response
     */
    async login(data: LoginRequest): Promise<{ token: string }> {
        try {
            const response = await apiRequest<ApiResponse<{ token: string }>>(
                AUTH_ENDPOINTS.LOGIN,
                prepareApiCall("POST", data)
            );

            return response.data;
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Logout the current user
     *
     * @returns Promise<void>
     */
    async logout(): Promise<void> {
        try {
            await apiRequest<void>(
                AUTH_ENDPOINTS.LOGOUT,
                preparePrivateApiCall("POST")
            );

            // Remove token from localStorage
            localStorageService.removeData("auth_token");
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Get the current user profile
     *
     * @returns Promise with user data
     */
    async getProfile(): Promise<User> {
        try {
            const response = await apiRequest<ApiResponse<{ user: User }>>(
                AUTH_ENDPOINTS.PROFILE,
                preparePrivateApiCall("GET")
            );
            return response.data.user;
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Check if user is authenticated
     *
     * @returns boolean indicating if user is authenticated
     */
    isAuthenticated(): boolean {
        return !!localStorageService.getData("auth_token");
    }
}

// Export singleton instance
export const authService = new AuthService();
