import { object, string, infer as zInfer } from "zod";

export const loginSchema = object({
    email: string()
        .min(1, { message: "Email is required" })
        .email({ message: "Invalid email address" }),
    password: string()
        .min(1, { message: "Password is required" })
        .min(8, { message: "Password must be at least 8 characters" }),
});

export type LoginFormValues = zInfer<typeof loginSchema>;
