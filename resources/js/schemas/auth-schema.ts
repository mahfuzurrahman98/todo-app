import { z, string, infer as zInfer } from "zod";

export const loginSchema = z.object({
    email: string()
        .min(1, { message: "Email is required" })
        .email({ message: "Invalid email address" }),
    password: string()
        .min(1, { message: "Password is required" })
        .min(8, { message: "Password must be at least 8 characters" }),
    rememberMe: z.boolean().optional().default(false),
});

export type LoginFormValues = zInfer<typeof loginSchema>;
