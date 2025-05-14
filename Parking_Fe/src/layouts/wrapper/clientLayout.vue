<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
      <!-- Mobile menu overlay -->
      <div 
        v-if="isMobileMenuOpen" 
        class="fixed inset-0 z-20 bg-black bg-opacity-50"
        @click="isMobileMenuOpen = false"
      ></div>
  
      <!-- Mobile header -->
      <header class="sticky top-0 z-10 md:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 h-16 flex items-center justify-between">
        <button @click="isMobileMenuOpen = true" class="p-2 rounded-md">
          <Menu class="h-6 w-6" />
        </button>
        <div class="flex items-center space-x-2">
          <h1 class="text-lg font-bold">Cư dân</h1>
        </div>
        <div class="relative">
          <button @click="isUserMenuOpen = !isUserMenuOpen" class="flex items-center">
            <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
              <User class="h-5 w-5 text-gray-500 dark:text-gray-400" />
            </div>
          </button>
          <!-- User dropdown menu -->
          <div 
            v-if="isUserMenuOpen" 
            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1"
          >
            <router-link to="/user/profile" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Thông tin tài khoản</router-link>
            <button @click="logout" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600">Đăng xuất</button>
          </div>
        </div>
      </header>
  
      <!-- Sidebar (desktop always visible, mobile hidden by default) -->
      <aside 
        class="fixed inset-y-0 left-0 z-30 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transform transition-transform duration-300 ease-in-out"
        :class="isMobileMenuOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
      >
        <div class="h-16 px-4 flex items-center border-b border-gray-200 dark:border-gray-700">
          <div class="flex items-center space-x-2">
            <h1 class="text-lg font-bold">Cư dân</h1>
          </div>
          <button @click="isMobileMenuOpen = false" class="ml-auto p-2 rounded-md md:hidden">
            <X class="h-5 w-5" />
          </button>
        </div>
        <div class="p-4">
          <nav class="space-y-1">
            <router-link 
              v-for="item in navigationItems" 
              :key="item.path" 
              :to="item.path" 
              class="flex items-center px-3 py-2 rounded-md text-sm font-medium"
              :class="isActive(item.path) ? 'bg-gray-100 dark:bg-gray-700 text-blue-600 dark:text-blue-400' : 'hover:bg-gray-100 dark:hover:bg-gray-700'"
              @click="isMobileMenuOpen = false"
            >
              <component :is="item.icon" class="mr-3 h-5 w-5" />
              {{ item.name }}
            </router-link>
          </nav>
        </div>
      </aside>
  
      <!-- Main content -->
      <main class="md:pl-64 pt-0 md:pt-0">
        <!-- Desktop header -->
        <header class="hidden md:flex sticky top-0 z-10 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 items-center justify-between">
          <div class="flex items-center">
            <h1 class="text-xl font-bold">{{ getCurrentPageTitle() }}</h1>
          </div>
          <div class="flex items-center space-x-4">
            <button class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 relative">
              <Bell class="h-5 w-5" />
              <span v-if="unreadNotifications > 0" class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
            <button @click="toggleDarkMode" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <Sun v-if="isDarkMode" class="h-5 w-5" />
              <Moon v-else class="h-5 w-5" />
            </button>
            <div class="relative">
              <button @click="isUserMenuOpen = !isUserMenuOpen" class="flex items-center space-x-2 user-menu">
                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                  <User class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                </div>
                <span>{{ userInfo?.ho_va_ten || 'Cư dân' }}</span>
                <ChevronDown class="h-4 w-4" />
              </button>
              <!-- User dropdown menu -->
              <div 
                v-if="isUserMenuOpen" 
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1 user-menu"
              >
                <router-link to="/user/profile" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Thông tin tài khoản</router-link>
                <button @click="handleLogout" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600">Đăng xuất</button>
              </div>
            </div>
          </div>
        </header>
  
        <!-- Page content -->
        <div class="p-6">
          <router-view></router-view>
        </div>
      </main>
    </div>
  </template>
  
  <script>
  import { ref, onMounted, onUnmounted } from 'vue'
  import { useRoute, useRouter } from 'vue-router'
  import { useAuthStore } from '../../stores/auth';
  import { useNotificationStore } from '../../stores/notication';
  import { 
    Home, 
    Users, 
    CreditCard, 
    FileText, 
    Clock, 
    Menu, 
    X, 
    User, 
    Bell, 
    Sun, 
    Moon, 
    ChevronDown, 
    LogOut,
    Car,
    UserRoundCheck
  } from 'lucide-vue-next'
  
  export default {
    name: 'ClientLayout',
    components: {
      Home,
      Users,
      CreditCard,
      FileText,
      Clock,
      Menu,
      X,
      User,
      Bell,
      Sun,
      Moon,
      ChevronDown,
      LogOut,
      Car,
      UserRoundCheck
    },
    setup() {
      const route = useRoute()
      const router = useRouter()
      const isMobileMenuOpen = ref(false)
      const isUserMenuOpen = ref(false)
      const isDarkMode = ref(localStorage.getItem('darkMode') === 'true')
      const unreadNotifications = ref(0)
      const authStore = useAuthStore();
      const notificationStore = useNotificationStore();
      const userInfo = ref(authStore.getUser());
      const navigationItems = [
        // { name: 'Trang chủ', path: '/user', icon: Home },
        { name: 'Chỗ để xe', path: '/user/quan-ly-cho-xe', icon: Car },
        { name: 'Quản lý xe', path: '/user/quan-ly-xe', icon: Car },
        { name: 'Báo cáo', path: '/user/bao-cao', icon: FileText },
        { name: 'Lịch sử', path: '/user/lich-su', icon: Clock },
        { name: 'Thanh toán', path: '/user/thanh-toan', icon: CreditCard },
      ]
  
      const isActive = (path) => {
        return route.path === path || route.path.startsWith(`${path}/`)
      }
  
      const getCurrentPageTitle = () => {
        const currentRoute = navigationItems.find(item => isActive(item.path))
        return currentRoute ? currentRoute.name : ''
      }
  
      const toggleDarkMode = () => {
        isDarkMode.value = !isDarkMode.value
        localStorage.setItem('darkMode', isDarkMode.value)
        
        if (isDarkMode.value) {
          document.documentElement.classList.add('dark')
        } else {
          document.documentElement.classList.remove('dark')
        }
      }
  
      const handleLogout = () => {
        authStore.logout();
        notificationStore.showSuccess('Đăng xuất thành công');
        router.push('/user/login');
      }
  
      // Close menus when clicking outside
      const handleClickOutside = (event) => {
        const userMenu = event.target.closest('.user-menu')
        if (!userMenu && isUserMenuOpen.value) {
          isUserMenuOpen.value = false
        }
      }
  
      // Handle responsive behavior
      const handleResize = () => {
        if (window.innerWidth >= 768) {
          isMobileMenuOpen.value = false
        }
      }
  
      onMounted(() => {
        // Initialize dark mode
        const storedUser = authStore.getUserUser();
        if (storedUser) {
          userInfo.value = storedUser;
        }
        if (isDarkMode.value) {
          document.documentElement.classList.add('dark')
        }
        
        document.addEventListener('click', handleClickOutside)
        window.addEventListener('resize', handleResize)
      })
  
      onUnmounted(() => {
        document.removeEventListener('click', handleClickOutside)
        window.removeEventListener('resize', handleResize)
      })
  
      return {
        isMobileMenuOpen,
        isUserMenuOpen,
        isDarkMode,
        navigationItems,
        isActive,
        getCurrentPageTitle,
        toggleDarkMode,
        handleLogout,
        userInfo,
        unreadNotifications
      }
    }
  }
  </script>
  
  <style>
  /* Add any custom styles here */
  </style>