/**
 * API Endpoints
 *
 * This file contains the endpoint paths for the API.
 * The actual API calls are made in the service files.
 */

/**
 * Auth Endpoints
 */
export const AUTH_ENDPOINTS = {
    /** Login user */
    LOGIN: "/auth/login",

    /** Logout user */
    LOGOUT: "/auth/logout",

    /** Get user profile */
    PROFILE: "/auth/profile",
};

/**
 * Todo Endpoints
 */
export const TODO_ENDPOINTS = {
    /** Get all todos */
    BASE: "/todos",

    /** Get a single todo by ID */
    GET_BY_ID: (id: number) => `/todos/${id}`,
};
