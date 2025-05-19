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
          <div class="relative">
            <button 
              @click="toggleNotifications" 
              class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 relative"
            >
              <Bell class="h-5 w-5" />
              <span v-if="unreadNotifications > 0" class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500"></span>
            </button>
            
            <!-- Mobile notification dropdown -->
            <div 
              v-if="showNotifications" 
              class="notification-dropdown fixed inset-x-4 top-20 bottom-4 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 overflow-y-auto"
            >
              <div class="sticky top-0 bg-white dark:bg-gray-800 p-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 dark:text-white">Thông báo</h3>
                <button @click="showNotifications = false" class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
                  <X class="h-5 w-5" />
                </button>
              </div>
              
              <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div 
                  v-for="notification in notifications" 
                  :key="notification.id" 
                  class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                  @click="markAsRead(notification.id)"
                >
                  <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                      <div class="w-2 h-2 mt-2 rounded-full" :class="notification.isRead ? 'bg-gray-400' : 'bg-blue-500'"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="whitespace-pre-line text-sm text-gray-900 dark:text-gray-100 leading-relaxed">
                        {{ notification.message }}
                      </div>
                      <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ notification.time }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-3 border-t border-gray-200 dark:border-gray-700">
                <button class="w-full py-2 px-4 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                  Xem tất cả thông báo
                </button>
              </div>
            </div>
          </div>
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
            <div class="relative">
              <button 
                @click="toggleNotifications" 
                class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 relative"
              >
                <Bell class="h-5 w-5" />
                <span v-if="unreadNotifications > 0" class="absolute top-1 right-1 h-2 w-2 rounded-full bg-red-500"></span>
              </button>
              
              <!-- Notification dropdown -->
              <div 
                v-if="showNotifications" 
                class="notification-dropdown absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 max-h-[500px] overflow-y-auto"
              >
                <div class="sticky top-0 bg-white dark:bg-gray-800 p-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                  <h3 class="font-semibold text-gray-900 dark:text-white">Thông báo</h3>
                  <span class="text-sm text-gray-500">{{ notifications.length }} thông báo mới</span>
                </div>
                
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                  <div 
                    v-for="notification in notifications" 
                    :key="notification.id" 
                    class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200"
                    @click="markAsRead(notification.id)"
                  >
                    <div class="flex items-start space-x-3">
                      <div class="flex-shrink-0">
                        <div class="w-2 h-2 mt-2 rounded-full" :class="notification.isRead ? 'bg-gray-400' : 'bg-blue-500'"></div>
                      </div>
                      <div class="flex-1 min-w-0">
                        <div class="whitespace-pre-line text-sm text-gray-900 dark:text-gray-100 leading-relaxed">
                          {{ notification.message }}
                        </div>
                        <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          {{ notification.time }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="sticky bottom-0 bg-white dark:bg-gray-800 p-3 border-t border-gray-200 dark:border-gray-700">
                  <button class="w-full py-2 px-4 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">
                    Xem tất cả thông báo
                  </button>
                </div>
              </div>
            </div>
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
  import baseRequestUser from '../../core/baseRequestUser';
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
      const showNotifications = ref(false)
      const notifications = ref([])

      const formatTimeAgo = (dateString) => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Vừa xong';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} phút trước`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} giờ trước`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)} ngày trước`;
        return date.toLocaleDateString('vi-VN');
      }

      const getThongBao = () => {
        baseRequestUser.get("user/lay-du-lieu-thong-bao").then((res) => {
          if (res.data.status) {
            notifications.value = res.data.data.map(notification => ({
              id: notification.id,
              message: notification.noi_dung_thong_bao,
              time: formatTimeAgo(notification.ngay_tao),
              isRead: notification.trang_thai === 0
            }));
            unreadNotifications.value = notifications.value.filter(n => n.isRead).length;
          }
        });
      };

      const markAsRead = (notificationId) => {
        baseRequestUser.put(`user/cap-nhat-trang-thai-thong-bao/${notificationId}`).then((res) => {
          if (res.data.status) {
            const notification = notifications.value.find(n => n.id === notificationId);
            if (notification) {
              notification.isRead = true;
              unreadNotifications.value = notifications.value.filter(n => n.isRead).length;
            }
          }
        });
      };

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
        authStore.logoutUser();
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
  
      const toggleNotifications = (e) => {
        e.stopPropagation()
        showNotifications.value = !showNotifications.value
        if (showNotifications.value) {
          getThongBao(); // Refresh notifications when opening
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
        getThongBao();
        document.addEventListener('click', handleClickOutside)
        window.addEventListener('resize', handleResize)
        
        // Set up polling for new notifications every minute
        const notificationInterval = setInterval(getThongBao, 60000);
        
        onUnmounted(() => {
          document.removeEventListener('click', handleClickOutside)
          window.removeEventListener('resize', handleResize)
          clearInterval(notificationInterval);
        })
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
        unreadNotifications,
        showNotifications,
        notifications,
        toggleNotifications,
        markAsRead,
      }
    }
  }
  </script>
  
  <style>
  /* Add any custom styles here */
  </style>