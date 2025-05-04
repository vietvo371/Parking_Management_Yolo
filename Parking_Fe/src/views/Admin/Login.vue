<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-4">
    <div class="w-full max-w-md">
      <div class="flex flex-col items-center space-y-3 mb-8">
        <div class="relative w-20 h-20 mb-3 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center text-white text-3xl font-bold transform transition-all duration-300 hover:scale-105 hover:rotate-3">
          P
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Hệ thống Quản lý Bãi giữ xe</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Đăng nhập để tiếp tục</p>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Đăng nhập</h2>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Nhập thông tin đăng nhập của bạn để truy cập hệ thống</p>
        </div>
        <div class="p-6">
          <form @submit.prevent="handleLogin" class="space-y-5">
            <div class="space-y-2">
              <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tên đăng nhập</label>
              <div class="relative">
                <User class="absolute left-3 top-3 h-5 w-5 text-gray-400" />
                <input 
                  v-model="user.email" 
                  id="username" 
                  placeholder="Nhập tên đăng nhập" 
                  class="pl-10 w-full h-11 px-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-200"
                  required 
                />
              </div>
            </div>
            <div class="space-y-2">
              <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mật khẩu</label>
                <a href="#" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 transition-colors duration-200">
                  Quên mật khẩu?
                </a>
              </div>
              <div class="relative">
                <Lock class="absolute left-3 top-3 h-5 w-5 text-gray-400" />
                <input 
                  v-model="user.password" 
                  id="password" 
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="••••••••"
                  class="pl-10 w-full h-11 px-4 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-200"
                  required 
                />
                <button 
                  type="button" 
                  @click="toggleShowPassword" 
                  class="absolute right-3 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                >
                  <EyeOff v-if="showPassword" class="h-5 w-5" />
                  <Eye v-else class="h-5 w-5" />
                </button>
              </div>
            </div>
            <button 
              type="submit" 
              class="w-full h-11 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/50 font-medium transition-all duration-200 transform hover:-translate-y-0.5"
              :disabled="isLoading"
              :class="{'opacity-75 cursor-not-allowed': isLoading}"
            >
              <span v-if="isLoading" class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Đang đăng nhập...
              </span>
              <span v-else>Đăng nhập</span>
            </button>
          </form>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900/30">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Hệ thống được phát triển bởi
            <a href="#" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 font-medium transition-colors duration-200">
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
import baseRequest from "../../core/baseRequest";
import { useNotificationStore } from "@/stores/notication";
import { useAuthStore } from "@/stores/auth";
export default {
  name: "Login",
  components: {
    User,
    Lock,
    Eye,
    EyeOff,
  },
  data() {
    return {
      user: {
        email: "",
        password: "",
      },
      showPassword: false,
      isLoading: false,
    }
  },
  methods: {
    toggleShowPassword() {
      this.showPassword = !this.showPassword;
    },
    handleLogin() {
      const authStore = useAuthStore();
      const notificationStore = useNotificationStore();
      this.isLoading = true;
      baseRequest.post("admin/login", this.user).then((response) => {
        if (response.data.status) {
          authStore.setToken(response.data.token);
          authStore.setUser(response.data.user);
          notificationStore.showSuccess(response.data.message);
          this.checkToken();
          this.isLoading = false;
        } else {
          notificationStore.showError(response.data.message);
          this.isLoading = false;
        }
      })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isLoading = false;
        });
    },
    checkToken() {
      baseRequest.get("admin/check-token")
        .then((response) => {
          if (response.data.status) {
            this.$router.push("/admin");
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
        });
    }
  }
}
</script>

<style></style>
