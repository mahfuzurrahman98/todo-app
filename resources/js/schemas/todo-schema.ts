import {
    boolean,
    date,
    number,
    object,
    string,
    union,
    infer as zInfer,
} from "zod";

export const createTodoSchema = object({
    title: string().min(1, { message: "Title is required" }),
    body: string().optional(),
    completed: boolean().default(false),
});

export const updateTodoSchema = createTodoSchema.partial();

export const todoSchema = createTodoSchema.extend({
    id: number(),
    user_id: string(),
    created_at: union([string(), date()]),
    updated_at: union([string(), date()]),
});

export type CreateTodo = zInfer<typeof createTodoSchema>;
export type UpdateTodo = zInfer<typeof updateTodoSchema>;
export type Todo = zInfer<typeof todoSchema>;
