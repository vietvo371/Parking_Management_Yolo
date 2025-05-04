<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-100 to-blue-300 dark:from-gray-900 dark:to-gray-800 p-4">
    <div class="w-full max-w-md">
      <div class="flex flex-col items-center space-y-3 mb-8">
        <div class="relative w-20 h-20 mb-3 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center text-white text-3xl font-bold">
          <span class="tracking-widest">P</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Đăng nhập cư dân</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Vui lòng đăng nhập để sử dụng hệ thống</p>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Thông tin đăng nhập</h2>
        </div>
        <div class="p-8 space-y-5">
          <form @submit.prevent="handleLogin" class="space-y-5">
            <div class="relative">
              <input
                v-model="user.email"
                id="email"
                type="email"
                required
                class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                placeholder=" "
                autocomplete="username"
              />
              <label for="email"
                class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                Email
              </label>
              <User class="absolute right-3 top-3 h-5 w-5 text-gray-400" />
            </div>
            <div class="relative">
              <input
                v-model="user.password"
                id="password"
                :type="showPassword ? 'text' : 'password'"
                required
                minlength="6"
                class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                placeholder=" "
                autocomplete="current-password"
              />
              <label for="password"
                class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                Mật khẩu
              </label>
              <button
                type="button"
                @click="toggleShowPassword"
                class="absolute right-10 top-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
                tabindex="-1"
              >
                <EyeOff v-if="showPassword" class="h-5 w-5" />
                <Eye v-else class="h-5 w-5" />
              </button>
              <Lock class="absolute right-3 top-3 h-5 w-5 text-gray-400" />
            </div>
            <button
              type="submit"
              class="w-full h-12 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-lg transition-all duration-200 flex items-center justify-center"
              :disabled="isLoading"
              :class="{'opacity-75 cursor-not-allowed': isLoading}"
            >
              <svg v-if="isLoading" class="animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span v-if="isLoading">Đang đăng nhập...</span>
              <span v-else>Đăng nhập</span>
            </button>
          </form>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900/30">
          <p class="text-sm text-gray-500 dark:text-gray-400">
            Chưa có tài khoản?
            <router-link to="/user/register" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 font-medium">Đăng ký</router-link>
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
  name: "LoginUser",
  components: {
    User,
    Lock,
    Eye,
    EyeOff,
  },
  setup() {
    const router = useRouter();
    const notificationStore = useNotificationStore();
    const authStore = useAuthStore();
    const user = ref({
      email: "",
      password: "",
    });
    const showPassword = ref(false);
    const isLoading = ref(false);

    const toggleShowPassword = () => {
      showPassword.value = !showPassword.value;
    };

    const handleLogin = () => {
      isLoading.value = true;
      baseRequest.post("resident/login", user.value).then((response) => {
        if (response.data.status) {
          authStore.setToken(response.data.token);
          authStore.setUser(response.data.user);
          notificationStore.showSuccess(response.data.message || "Đăng nhập thành công!");
          router.push("/user");
        } else {
          notificationStore.showError(response.data.message || "Đăng nhập thất bại!");
        }
      })
      .catch((res) => {
        notificationStore.showError("Đăng nhập thất bại!");
      })
      .finally(() => {
        isLoading.value = false;
      });
    };

    return {
      user,
      showPassword,
      isLoading,
      toggleShowPassword,
      handleLogin,
    };
  }
};
</script>

<style></style>
  