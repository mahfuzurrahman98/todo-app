<template>
  <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
      <div class="px-6 py-8">
        <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Your Profile</h2>
        
        <!-- Loading state -->
        <div v-if="loading" class="flex justify-center py-8">
          <Loader class="h-8 w-8 animate-spin text-gray-500" />
        </div>
        
        <!-- Error message -->
        <div v-else-if="error" class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
          {{ error }}
        </div>
        
        <!-- Profile form -->
        <form v-else @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Name field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            />
          </div>
          
          <!-- Email field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              disabled
            />
            <p class="mt-1 text-xs text-gray-500">Email cannot be changed</p>
          </div>
          
          <!-- Save button -->
          <div>
            <button
              type="submit"
              class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
              :disabled="isSubmitting"
            >
              <Loader v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
              {{ isSubmitting ? "Saving..." : "Save Changes" }}
            </button>
          </div>
        </form>
        
        <!-- Logout button -->
        <div class="mt-6 pt-6 border-t border-gray-200">
          <button
            @click="handleLogout"
            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            :disabled="isLoggingOut"
          >
            <Loader v-if="isLoggingOut" class="mr-2 h-4 w-4 animate-spin" />
            {{ isLoggingOut ? "Logging out..." : "Logout" }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/authStore';
import { Loader } from 'lucide-vue-next';

const router = useRouter();
const authStore = useAuthStore();

const form = reactive({
  name: '',
  email: ''
});

const loading = ref(true);
const error = ref<string | null>(null);
const isSubmitting = ref(false);
const isLoggingOut = ref(false);

// Load user profile on component mount
onMounted(async () => {
  try {
    loading.value = true;
    const user = await authStore.fetchUserProfile();
    
    if (user) {
      form.name = user.name;
      form.email = user.email;
    }
  } catch (err: any) {
    error.value = err.message || 'Failed to load profile';
  } finally {
    loading.value = false;
  }
});

// Handle form submission
const handleSubmit = async () => {
  try {
    isSubmitting.value = true;
    
    // Call API to update profile
    await authStore.updateProfile({
      name: form.name
    });
    
    // Show success message
    alert('Profile updated successfully');
  } catch (err: any) {
    error.value = err.message || 'Failed to update profile';
  } finally {
    isSubmitting.value = false;
  }
};

// Handle logout
const handleLogout = async () => {
  try {
    isLoggingOut.value = true;
    await authStore.logout();
    router.push('/login');
  } catch (err: any) {
    error.value = err.message || 'Failed to logout';
    isLoggingOut.value = false;
  }
};
</script>
