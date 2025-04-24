<template>
  <div class="flex min-h-screen flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 p-4">
    <div class="w-full max-w-md">
      <div class="flex flex-col items-center space-y-2 mb-6">
        <div class="relative w-16 h-16 mb-2 bg-blue-600 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
          P
        </div>
        <h1 class="text-2xl font-bold">Hệ thống Quản lý Bãi giữ xe</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Đăng nhập để tiếp tục</p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Đăng nhập</h2>
          <p class="text-sm text-gray-500">Nhập thông tin đăng nhập của bạn để truy cập hệ thống</p>
        </div>
        <div class="p-6">
          <form @submit.prevent="handleLogin" class="space-y-4">
            <div class="space-y-2">
              <label for="username" class="block text-sm font-medium">Tên đăng nhập</label>
              <div class="relative">
                <User class="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                <input 
                  v-model="username"
                  id="username" 
                  placeholder="admin" 
                  class="pl-10 w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  required 
                />
              </div>
            </div>
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium">Mật khẩu</label>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400">
                  Quên mật khẩu?
                </a>
              </div>
              <div class="relative">
                <Lock class="absolute left-3 top-3 h-4 w-4 text-gray-500" />
                <input 
                  v-model="password"
                  id="password" 
                  :type="showPassword ? 'text' : 'password'" 
                  placeholder="••••••••" 
                  class="pl-10 w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  required 
                />
                <button 
                  type="button" 
                  @click="toggleShowPassword" 
                  class="absolute right-3 top-3 text-gray-500"
                >
                  <EyeOff v-if="showPassword" class="h-4 w-4" />
                  <Eye v-else class="h-4 w-4" />
                </button>
              </div>
            </div>
            <button 
              type="submit" 
              class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="isLoading"
            >
              {{ isLoading ? "Đang đăng nhập..." : "Đăng nhập" }}
            </button>
          </form>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-center">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Hệ thống được phát triển bởi
            <a href="#" class="text-blue-600 hover:text-blue-500 dark:text-blue-400">
              Công ty ABC
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { useRouter } from "vue-router";
import { User, Lock, Eye, EyeOff } from "lucide-vue-next";
import { useAuthStore } from "@/stores/auth";

export default {
  name: "Login",
  components: {
    User,
    Lock,
    Eye,
    EyeOff,
  },
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();

    const username = ref("");
    const password = ref("");
    const showPassword = ref(false);
    const isLoading = ref(false);

    const handleLogin = async () => {
      isLoading.value = true;
      try {
        await authStore.login(username.value, password.value);
        router.push("/");
      } catch (error) {
        console.error("Đăng nhập thất bại:", error);
      } finally {
        isLoading.value = false;
      }
    };

    const toggleShowPassword = () => {
      showPassword.value = !showPassword.value;
    };

    return {
      username,
      password,
      showPassword,
      isLoading,
      handleLogin,
      toggleShowPassword,
    };
  },
};
</script>

<style>
</style>
