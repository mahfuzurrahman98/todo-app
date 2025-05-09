export interface ApiResponse<T> {
    message: string;
    data: T;
}

export type ValidationErrorType = Record<string, string> | null;
