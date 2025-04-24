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
          <!-- <img src="/logo.png" alt="Logo" class="h-8 w-8" /> -->
          <h1 class="text-lg font-bold">Parking Management</h1>
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
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Thông tin tài khoản</a>
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cài đặt</a>
            <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600">Đăng xuất</a>
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
            <!-- <img src="/logo.png" alt="Logo" class="h-8 w-8" /> -->
            <h1 class="text-lg font-bold">Parking Management</h1>
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
        <div class="absolute bottom-0 w-full p-4 border-t border-gray-200 dark:border-gray-700">
          <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
              <User class="h-6 w-6 text-gray-500 dark:text-gray-400" />
            </div>
            <div>
              <p class="font-medium">Admin</p>
              <p class="text-sm text-gray-500">admin@example.com</p>
            </div>
            <button class="ml-auto p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <LogOut class="h-5 w-5" />
            </button>
          </div>
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
              <span class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
            <button @click="toggleDarkMode" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <Sun v-if="isDarkMode" class="h-5 w-5" />
              <Moon v-else class="h-5 w-5" />
            </button>
            <div class="relative">
              <button @click="isUserMenuOpen = !isUserMenuOpen" class="flex items-center space-x-2">
                <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                  <User class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                </div>
                <span>Admin</span>
                <ChevronDown class="h-4 w-4" />
              </button>
              <!-- User dropdown menu -->
              <div 
                v-if="isUserMenuOpen" 
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1"
              >
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Thông tin tài khoản</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">Cài đặt</a>
                <a href="#" class="block px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 text-red-600">Đăng xuất</a>
              </div>
            </div>
          </div>
        </header>
  
        <!-- Page content -->
        <div class="p-6">
          <slot></slot>
        </div>
      </main>
    </div>
  </template>
  
  <script>
  import { ref, onMounted, onUnmounted } from 'vue'
  import { useRoute } from 'vue-router'
  import { 
    Home, 
    Users, 
    CreditCard, 
    Camera, 
    FileText, 
    Clock, 
    Settings as SettingsIcon, 
    Menu, 
    X, 
    User, 
    Bell, 
    Sun, 
    Moon, 
    ChevronDown, 
    LogOut 
  } from 'lucide-vue-next'
  
  export default {
    name: 'ClientLayout',
    components: {
      Home,
      Users,
      CreditCard,
      Camera,
      FileText,
      Clock,
      SettingsIcon,
      Menu,
      X,
      User,
      Bell,
      Sun,
      Moon,
      ChevronDown,
      LogOut
    },
    setup() {
      const route = useRoute()
      const isMobileMenuOpen = ref(false)
      const isUserMenuOpen = ref(false)
      const isDarkMode = ref(localStorage.getItem('darkMode') === 'true')
  
      const navigationItems = [
        { name: 'Trang chủ', path: '/', icon: Home },
        { name: 'Cư dân', path: '/residents', icon: Users },
        { name: 'Giao dịch', path: '/transactions', icon: CreditCard },
        { name: 'Camera & AI', path: '/cameras', icon: Camera },
        { name: 'Báo cáo', path: '/reports', icon: FileText },
        { name: 'Lịch sử', path: '/history', icon: Clock },
        { name: 'Cài đặt', path: '/settings', icon: SettingsIcon }
      ]
  
      const isActive = (path) => {
        return route.path === path || route.path.startsWith(`${path}/`)
      }
  
      const getCurrentPageTitle = () => {
        const currentRoute = navigationItems.find(item => isActive(item.path))
        return currentRoute ? currentRoute.name : 'Trang chủ'
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
  
      // Close menus when clicking outside
      const handleClickOutside = (event) => {
        if (isUserMenuOpen.value && !event.target.closest('.user-menu')) {
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
        toggleDarkMode
      }
    }
  }
  </script>
  
  <style>
  /* Add any custom styles here */
  </style>