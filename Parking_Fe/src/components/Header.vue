<template>
  <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-3 px-4 flex items-center justify-between">
    <div class="flex items-center w-full max-w-md">
      <div class="relative w-full">
        <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
        <input 
          type="search" 
          placeholder="Tìm kiếm biển số, cư dân..." 
          class="pl-9 w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
        />
      </div>
    </div>

    <div class="flex items-center space-x-2">
      <div class="relative">
        <button 
          @click="toggleNotifications" 
          class="relative p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
        >
          <Bell class="h-5 w-5" />
          <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
        </button>
        
        <div 
          v-if="showNotifications" 
          class="notification-dropdown absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50"
        >
          <div class="p-2 font-medium border-b">Thông báo</div>
          <div v-for="notification in notifications" :key="notification.id" class="p-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
            <p class="text-sm">{{ notification.message }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ notification.time }}</p>
          </div>
          <div class="p-2 text-center text-sm text-blue-600 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
            Xem tất cả
          </div>
        </div>
      </div>

      <button 
        @click="toggleTheme" 
        class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
      >
        <Sun v-if="isDark" class="h-5 w-5" />
        <Moon v-else class="h-5 w-5" />
      </button>
    </div>
  </header>
</template>

<script>
import { ref, onMounted, onUnmounted } from "vue";
import { Bell, Moon, Sun, Search } from "lucide-vue-next";

export default {
  name: "Header",
  components: {
    Bell,
    Moon,
    Sun,
    Search,
  },
  setup() {
    const isDark = ref(false);
    const showNotifications = ref(false);
    const notifications = ref([
      {
        id: 1,
        message: "Xe mới đăng ký: 51F-12345",
        time: "5 phút trước",
      },
      {
        id: 2,
        message: "Cảnh báo: Xe không xác định vào bãi",
        time: "10 phút trước",
      },
      {
        id: 3,
        message: "Cư dân Nguyễn Văn A đã thanh toán phí gửi xe",
        time: "30 phút trước",
      },
    ]);

    const toggleTheme = () => {
      isDark.value = !isDark.value;
      document.documentElement.classList.toggle("dark", isDark.value);
      localStorage.setItem("theme", isDark.value ? "dark" : "light");
    };

    const toggleNotifications = () => {
      showNotifications.value = !showNotifications.value;
    };

    const closeNotifications = (e) => {
      if (showNotifications.value && !e.target.closest(".notification-dropdown")) {
        showNotifications.value = false;
      }
    };

    onMounted(() => {
      // Kiểm tra theme từ localStorage hoặc system preference
      const savedTheme = localStorage.getItem("theme");
      const systemPrefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

      isDark.value = savedTheme === "dark" || (!savedTheme && systemPrefersDark);
      document.documentElement.classList.toggle("dark", isDark.value);

      document.addEventListener("click", closeNotifications);
    });

    onUnmounted(() => {
      document.removeEventListener("click", closeNotifications);
    });

    return {
      isDark,
      showNotifications,
      notifications,
      toggleTheme,
      toggleNotifications,
    };
  },
};
</script>

<style>
</style>
