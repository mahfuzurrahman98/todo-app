import { Todo, ApiResponse } from "../types/models";
import { TODO_ENDPOINTS } from "../api/endpoints";
import { apiRequest, preparePrivateApiCall } from "../api/apiUtils";
import { ApiService } from "./ApiService";
import { CreateTodo, UpdateTodo } from "../schemas/todo-schema";

/**
 * Todo service for CRUD operations
 */
export class TodoService extends ApiService {
    /**
     * Get all todos
     *
     * @returns Promise with array of todos
     */
    async getAll(): Promise<Todo[]> {
        try {
            const response = await apiRequest<ApiResponse<{ todos: Todo[] }>>(
                TODO_ENDPOINTS.BASE,
                preparePrivateApiCall("GET")
            );
            return response.data.todos;
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Get a todo by ID
     *
     * @param id - Todo ID
     * @returns Promise with todo
     */
    async getById(id: number): Promise<Todo> {
        try {
            const response = await apiRequest<ApiResponse<{ todo: Todo }>>(
                TODO_ENDPOINTS.GET_BY_ID(id),
                preparePrivateApiCall("GET")
            );
            return response.data.todo;
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Create a new todo
     *
     * @param data - Todo data
     * @returns Promise with created todo
     */
    async create(data: CreateTodo): Promise<Todo> {
        try {
            const response = await apiRequest<ApiResponse<{ todo: Todo }>>(
                TODO_ENDPOINTS.BASE,
                preparePrivateApiCall("POST", data)
            );
            return response.data.todo;
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Update a todo
     *
     * @param id - Todo ID
     * @param data - Todo data
     * @returns Promise with updated todo
     */
    async update(id: number, data: UpdateTodo): Promise<Todo> {
        try {
            const response = await apiRequest<ApiResponse<{ todo: Todo }>>(
                TODO_ENDPOINTS.GET_BY_ID(id),
                preparePrivateApiCall("PUT", data)
            );
            return response.data.todo;
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Delete a todo
     *
     * @param id - Todo ID
     * @returns Promise<void>
     */
    async delete(id: number): Promise<void> {
        try {
            await apiRequest<void>(
                TODO_ENDPOINTS.GET_BY_ID(id),
                preparePrivateApiCall("DELETE")
            );
        } catch (error: any) {
            throw this.handleError(error);
        }
    }

    /**
     * Toggle todo completion status
     *
     * @param id - Todo ID
     * @param completed - Completion status
     * @returns Promise with updated todo
     */
    async toggleComplete(id: number, completed: boolean): Promise<Todo> {
        return this.update(id, { completed });
    }
}

// Export singleton instance
export const todoService = new TodoService();
