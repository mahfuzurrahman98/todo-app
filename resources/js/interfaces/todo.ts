import { TodoFiltersSortByEnum, TodoFiltersStatusEnum } from "../enums/todo";
import { CreateTodo, Todo } from "../schemas/todo-schema";

export interface TodoFormValue {
    title: string;
    body?: string;
}

export interface CreateTodoFormProps {
    isSubmitting?: boolean;
}

export interface CreateTodoFormEmits {
    (e: "submit", data: CreateTodo): void;
    (e: "cancel"): void;
}

export interface EditTodoFormEmits {
    (e: "submit", id: number, updates: TodoFormValue): void;
    (e: "cancel"): void;
}

export interface TodoItemProps {
    todo: Todo;
    isSubmitting?: boolean;
}

export interface TodoItemEmits {
    (e: "toggle-status", todo: Todo): void;
    (e: "update", id: number, updates: TodoFormValue): void;
    (e: "delete", todo: Todo): void;
}

export interface TodoListProps {
    todos: Todo[];
    isSubmitting?: boolean;
}

export interface TodoListEmits {
    (e: "reload"): void;
    (e: "toggle-status", todo: Todo): void;
    (e: "delete", todo: Todo): void;
    (e: "create", data: CreateTodo): void;
    (e: "update", id: number, updates: { title: string; body?: string }): void;
}

export interface TodoFormProps {
    todo?: Todo | null;
    isSubmitting?: boolean;
}

export interface TodoFormEmits {
    (e: "submit", data: TodoFormValue): void;
    (e: "cancel"): void;
}

export interface TodoFilters {
    status: TodoFiltersStatusEnum;
    sortBy: TodoFiltersSortByEnum;
}

export interface TodoStats {
    total: number;
    completed: number;
    pending: number;
}
