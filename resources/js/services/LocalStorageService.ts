/**
 * Service for handling localStorage operations
 */
export class LocalStorageService {
    /**
     * Get data from localStorage
     *
     * @param key - Optional key to retrieve specific data
     * @returns The data from localStorage or null if not found
     */
    getData<T>(key?: string): T | Record<string, any> | null {
        try {
            // If no key is provided, return all localStorage data
            if (!key) {
                const allData: Record<string, any> = {};
                for (let i = 0; i < localStorage.length; i++) {
                    const currentKey = localStorage.key(i);
                    if (currentKey) {
                        const value = localStorage.getItem(currentKey);
                        if (value) {
                            try {
                                allData[currentKey] = JSON.parse(value);
                            } catch {
                                allData[currentKey] = value;
                            }
                        }
                    }
                }
                return allData;
            }

            // Get data for a specific key
            const value = localStorage.getItem(key);
            if (!value) return null;

            try {
                return JSON.parse(value) as T;
            } catch {
                return value as unknown as T;
            }
        } catch (error: any) {
            console.error("Error accessing localStorage:", error);
            return null;
        }
    }

    /**
     * Set data in localStorage
     *
     * @param key - Key or object of key-value pairs to store
     * @param value - Value to store (not needed if key is an object)
     */
    setData(key: string | Record<string, any>, value?: any): void {
        try {
            // If key is an object, set multiple values
            if (
                typeof key === "object" &&
                key !== null &&
                !Array.isArray(key)
            ) {
                Object.entries(key).forEach(([k, v]) => {
                    localStorage.setItem(
                        k,
                        typeof v === "object" ? JSON.stringify(v) : String(v)
                    );
                });
                return;
            }

            // If key is a string, set a single value
            else if (typeof key === "string" && value !== undefined) {
                localStorage.setItem(
                    key,
                    typeof value === "object"
                        ? JSON.stringify(value)
                        : String(value)
                );
            }
        } catch (error: any) {
            console.error("Error setting localStorage data:", error);
        }
    }

    /**
     * Remove data from localStorage
     *
     * @param key - Key or array of keys to remove
     */
    removeData(key?: string | string[]): void {
        try {
            // If no key is provided, clear all localStorage
            if (!key) {
                localStorage.clear();
                return;
            }

            // If key is an array, remove all specified keys
            if (Array.isArray(key)) {
                key.forEach((k) => localStorage.removeItem(k));
                return;
            }

            // If key is a string, remove that specific key
            localStorage.removeItem(key);
        } catch (error: any) {
            console.error("Error removing localStorage data:", error);
        }
    }
}

// Export singleton instance
export const localStorageService = new LocalStorageService();
