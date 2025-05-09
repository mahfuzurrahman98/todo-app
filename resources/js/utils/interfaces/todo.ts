import { ValidationErrorType } from ".";
import { TodoFiltersSortByEnum, TodoFiltersStatusEnum } from "../enums/todo";
import { Todo } from "../schemas/todo-schema";

export interface TodoFormValue {
    title: string;
    body: string;
}

export interface TodoItemEmits {
    (e: "refetch"): void;
    (e: "loadStats"): void;
}

export interface EditTodoFormEmits extends TodoItemEmits {
    (e: "cancel"): void;
}

export interface TodoItemProps {
    todo: Todo;
}

export interface TodoListProps {
    todos: Todo[];
}

export interface TodoFormProps {
    formData: TodoFormValue;
    isSubmitting?: boolean;
    todoEditMode?: boolean;
    errors: ValidationErrorType;
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
