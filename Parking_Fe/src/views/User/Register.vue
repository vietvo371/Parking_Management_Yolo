<template>
    <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-100 to-blue-300 dark:from-gray-900 dark:to-gray-800 p-4">
      <div class="w-full max-w-lg">
        <div class="flex flex-col items-center space-y-3 mb-8">
          <div class="relative w-20 h-20 mb-3 bg-blue-600 rounded-2xl shadow-lg flex items-center justify-center text-white text-3xl font-bold">
            <span class="tracking-widest">P</span>
          </div>
          <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Đăng ký cư dân</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400">Tạo tài khoản để sử dụng hệ thống quản lý bãi xe</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-lg overflow-hidden">
          <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Thông tin đăng ký</h2>
          </div>
          <div class="p-8 space-y-5">
            <form @submit.prevent="handleRegister" class="space-y-5">
              <div class="relative">
                <input v-model="form.ho_va_ten" required id="name" type="text"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="name"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  Họ và tên
                </label>
              </div>
              <div class="relative">
                <input v-model="form.email" required id="email" type="email"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="email"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  Email
                </label>
              </div>
              <div class="relative">
                <input v-model="form.password" required id="password" type="password" minlength="6"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="password"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  Mật khẩu (tối thiểu 6 ký tự)
                </label>
              </div>
              <div class="relative">
                <input v-model="form.confirmPassword" required id="confirmPassword" type="password" minlength="6"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="confirmPassword"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  Xác nhận mật khẩu
                </label>
                <span v-if="passwordError" class="text-xs text-red-500 mt-1 block">{{ passwordError }}</span>
              </div>
              <div class="relative">
                <input v-model="form.so_dien_thoai" required id="phone" type="tel" pattern="^0[0-9]{9,10}$"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="phone"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  Số điện thoại
                </label>
              </div>
              <div class="relative">
                <input v-model="form.so_cccd" required id="cccd" type="text" minlength="9" maxlength="12"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="cccd"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  Số CCCD
                </label>
              </div>
              <div class="relative">
                <input v-model="form.id_can_ho" required id="apartment" type="text"
                  class="peer w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-700 outline-none transition-all"
                  placeholder=" " />
                <label for="apartment"
                  class="absolute left-4 top-3 text-gray-500 dark:text-gray-400 text-sm transition-all peer-focus:-top-4 peer-focus:text-xs peer-focus:text-blue-600 dark:peer-focus:text-blue-400 bg-white dark:bg-gray-800 px-1 pointer-events-none peer-placeholder-shown:top-3 peer-placeholder-shown:text-sm peer-placeholder-shown:text-gray-500 dark:peer-placeholder-shown:text-gray-400">
                  ID căn hộ
                </label>
              </div>
              <button type="submit"
                class="w-full h-12 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-lg transition-all duration-200 flex items-center justify-center"
                :disabled="isLoading">
                <svg v-if="isLoading" class="animate-spin mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span v-if="isLoading">Đang đăng ký...</span>
                <span v-else>Đăng ký</span>
              </button>
            </form>
          </div>
          <div class="p-6 border-t border-gray-200 dark:border-gray-700 text-center bg-gray-50 dark:bg-gray-900/30">
            <p class="text-sm text-gray-500 dark:text-gray-400">
              Đã có tài khoản?
              <router-link to="/login" class="text-blue-600 hover:text-blue-500 dark:text-blue-400 font-medium">Đăng nhập</router-link>
            </p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from "vue";
  import { useRouter } from "vue-router";
  import baseRequest from "../../core/baseRequest";
  import { useNotificationStore } from "@/stores/notication";
  export default {
    name: "Register",
    setup() {
      const router = useRouter();
      const notificationStore = useNotificationStore();
      const isLoading = ref(false);
      const form = ref({
        ho_va_ten: "",
        email: "",
        password: "",
        confirmPassword: "",
        so_dien_thoai: "",
        so_cccd: "",
        id_can_ho: ""
      });
  
      const passwordError = computed(() => {
        if (!form.value.confirmPassword) return "";
        if (form.value.password !== form.value.confirmPassword) return "Mật khẩu xác nhận không khớp!";
        if (form.value.password.length < 6) return "Mật khẩu phải từ 6 ký tự!";
        return "";
      });
  
      const handleRegister = () => {
        if (passwordError.value) {
          notificationStore.showError(passwordError.value);
          return;
        }
        isLoading.value = true;
        baseRequest.post("resident/register", form.value)
          .then((response) => {
            if (response.data.status) {
              notificationStore.showSuccess("Đăng ký thành công! Vui lòng đăng nhập.");
              router.push("/login");
            } else {
              notificationStore.showError(response.data.message || "Đăng ký thất bại!");
            }
          })
          .catch(() => {
            notificationStore.showError("Đăng ký thất bại!");
          })
          .finally(() => {
            isLoading.value = false;
          });
      };
  
      return {
        form,
        isLoading,
        handleRegister,
        passwordError
      };
    }
  };
  </script>