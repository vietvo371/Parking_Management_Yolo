<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Cài đặt hệ thống</h1>
      </div>
  
      <div class="flex flex-col space-y-4 md:flex-row md:space-x-4 md:space-y-0">
        <div class="md:w-1/5">
          <div class="flex flex-col h-full space-y-1 w-full">
            <button 
              v-for="tab in tabs" 
              :key="tab.value"
              @click="activeTab = tab.value"
              class="flex items-center justify-start px-4 py-2 h-10 text-sm font-medium rounded-md"
              :class="activeTab === tab.value ? 'bg-gray-100 dark:bg-gray-700' : ''"
            >
              <component :is="tab.icon" class="mr-2 h-4 w-4" />
              {{ tab.label }}
            </button>
          </div>
        </div>
        <div class="flex-1">
          <!-- General Settings -->
          <div v-if="activeTab === 'general'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-medium">Cài đặt chung</h2>
              <p class="text-sm text-gray-500">Quản lý các cài đặt chung của hệ thống</p>
            </div>
            <div class="p-6 space-y-6">
              <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="system-name" class="block text-sm font-medium">Tên hệ thống</label>
                    <input 
                      id="system-name" 
                      v-model="settings.general.systemName"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="admin-email" class="block text-sm font-medium">Email quản trị</label>
                    <input 
                      id="admin-email" 
                      v-model="settings.general.adminEmail"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="timezone" class="block text-sm font-medium">Múi giờ</label>
                    <select 
                      id="timezone" 
                      v-model="settings.general.timezone"
                      class="w-full p-2 border rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                    >
                      <option value="UTC+7">UTC+7 (Việt Nam)</option>
                      <option value="UTC+8">UTC+8 (Singapore, Malaysia)</option>
                      <option value="UTC+9">UTC+9 (Japan, Korea)</option>
                    </select>
                  </div>
                  <div class="space-y-2">
                    <label for="language" class="block text-sm font-medium">Ngôn ngữ</label>
                    <select 
                      id="language" 
                      v-model="settings.general.language"
                      class="w-full p-2 border rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                    >
                      <option value="vi">Tiếng Việt</option>
                      <option value="en">English</option>
                    </select>
                  </div>
                </div>
                <div class="space-y-2">
                  <label for="logo" class="block text-sm font-medium">Logo hệ thống</label>
                  <input id="logo" type="file" class="w-full" />
                </div>
              </div>
  
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4"></div>
  
              <div class="space-y-4">
                <h3 class="text-lg font-medium">Tùy chọn hệ thống</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                      <label for="dark-mode" class="block text-sm font-medium">Chế độ tối</label>
                      <p class="text-sm text-gray-500">Bật chế độ tối mặc định cho hệ thống</p>
                    </div>
                    <div class="relative inline-flex items-center">
                      <input 
                        type="checkbox" 
                        id="dark-mode" 
                        v-model="settings.general.darkMode"
                        class="sr-only"
                      />
                      <div 
                        class="block h-6 w-10 rounded-full"
                        :class="settings.general.darkMode ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                      ></div>
                      <div 
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                        :class="settings.general.darkMode ? 'transform translate-x-4' : ''"
                      ></div>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                      <label for="auto-backup" class="block text-sm font-medium">Sao lưu tự động</label>
                      <p class="text-sm text-gray-500">Tự động sao lưu dữ liệu hàng ngày</p>
                    </div>
                    <div class="relative inline-flex items-center">
                      <input 
                        type="checkbox" 
                        id="auto-backup" 
                        v-model="settings.general.autoBackup"
                        class="sr-only"
                      />
                      <div 
                        class="block h-6 w-10 rounded-full"
                        :class="settings.general.autoBackup ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                      ></div>
                      <div 
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                        :class="settings.general.autoBackup ? 'transform translate-x-4' : ''"
                      ></div>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                      <label for="analytics" class="block text-sm font-medium">Phân tích dữ liệu</label>
                      <p class="text-sm text-gray-500">Thu thập dữ liệu phân tích để cải thiện hệ thống</p>
                    </div>
                    <div class="relative inline-flex items-center">
                      <input 
                        type="checkbox" 
                        id="analytics" 
                        v-model="settings.general.analytics"
                        class="sr-only"
                      />
                      <div 
                        class="block h-6 w-10 rounded-full"
                        :class="settings.general.analytics ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                      ></div>
                      <div 
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                        :class="settings.general.analytics ? 'transform translate-x-4' : ''"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="flex justify-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                  <Save class="mr-2 h-4 w-4" />
                  Lưu thay đổi
                </button>
              </div>
            </div>
          </div>
  
          <!-- Parking Settings -->
          <div v-if="activeTab === 'parking'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-medium">Cài đặt bãi đỗ xe</h2>
              <p class="text-sm text-gray-500">Quản lý cấu hình bãi đỗ xe</p>
            </div>
            <div class="p-6 space-y-6">
              <div class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="car-slots" class="block text-sm font-medium">Số lượng chỗ đỗ ô tô</label>
                    <input 
                      id="car-slots" 
                      type="number" 
                      v-model="settings.parking.carSlots"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="motorbike-slots" class="block text-sm font-medium">Số lượng chỗ đỗ xe máy</label>
                    <input 
                      id="motorbike-slots" 
                      type="number" 
                      v-model="settings.parking.motorbikeSlots"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="car-fee" class="block text-sm font-medium">Phí gửi xe ô tô (VND/tháng)</label>
                    <input 
                      id="car-fee" 
                      type="number" 
                      v-model="settings.parking.carFee"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="motorbike-fee" class="block text-sm font-medium">Phí gửi xe máy (VND/tháng)</label>
                    <input 
                      id="motorbike-fee" 
                      type="number" 
                      v-model="settings.parking.motorbikeFee"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="visitor-car-fee" class="block text-sm font-medium">Phí gửi xe ô tô khách (VND/giờ)</label>
                    <input 
                      id="visitor-car-fee" 
                      type="number" 
                      v-model="settings.parking.visitorCarFee"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="visitor-motorbike-fee" class="block text-sm font-medium">Phí gửi xe máy khách (VND/giờ)</label>
                    <input 
                      id="visitor-motorbike-fee" 
                      type="number" 
                      v-model="settings.parking.visitorMotorbikeFee"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                </div>
              </div>
  
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4"></div>
  
              <div class="space-y-4">
                <h3 class="text-lg font-medium">Quy định bãi đỗ xe</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                      <label for="allow-visitors" class="block text-sm font-medium">Cho phép khách vãng lai</label>
                      <p class="text-sm text-gray-500">Cho phép xe không đăng ký vào bãi đỗ</p>
                    </div>
                    <div class="relative inline-flex items-center">
                      <input 
                        type="checkbox" 
                        id="allow-visitors" 
                        v-model="settings.parking.allowVisitors"
                        class="sr-only"
                      />
                      <div 
                        class="block h-6 w-10 rounded-full"
                        :class="settings.parking.allowVisitors ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                      ></div>
                      <div 
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                        :class="settings.parking.allowVisitors ? 'transform translate-x-4' : ''"
                      ></div>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                      <label for="auto-detect" class="block text-sm font-medium">Tự động nhận diện biển số</label>
                      <p class="text-sm text-gray-500">Sử dụng AI để tự động nhận diện biển số xe</p>
                    </div>
                    <div class="relative inline-flex items-center">
                      <input 
                        type="checkbox" 
                        id="auto-detect" 
                        v-model="settings.parking.autoDetect"
                        class="sr-only"
                      />
                      <div 
                        class="block h-6 w-10 rounded-full"
                        :class="settings.parking.autoDetect ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                      ></div>
                      <div 
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                        :class="settings.parking.autoDetect ? 'transform translate-x-4' : ''"
                      ></div>
                    </div>
                  </div>
                  <div class="flex items-center justify-between">
                    <div class="space-y-0.5">
                      <label for="auto-barrier" class="block text-sm font-medium">Tự động mở barrier</label>
                      <p class="text-sm text-gray-500">Tự động mở barrier khi nhận diện xe đã đăng ký</p>
                    </div>
                    <div class="relative inline-flex items-center">
                      <input 
                        type="checkbox" 
                        id="auto-barrier" 
                        v-model="settings.parking.autoBarrier"
                        class="sr-only"
                      />
                      <div 
                        class="block h-6 w-10 rounded-full"
                        :class="settings.parking.autoBarrier ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700'"
                      ></div>
                      <div 
                        class="dot absolute left-1 top-1 h-4 w-4 rounded-full bg-white transition"
                        :class="settings.parking.autoBarrier ? 'transform translate-x-4' : ''"
                      ></div>
                    </div>
                  </div>
                </div>
              </div>
  
              <div class="flex justify-end">
                <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                  <Save class="mr-2 h-4 w-4" />
                  Lưu thay đổi
                </button>
              </div>
            </div>
          </div>
  
          <!-- Placeholder for other settings tabs -->
          <div v-if="!['general', 'parking'].includes(activeTab)" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-lg font-medium">{{ getTabTitle() }}</h2>
              <p class="text-sm text-gray-500">{{ getTabDescription() }}</p>
            </div>
            <div class="p-6">
              <div class="flex items-center justify-center h-40 text-gray-500">
                <p>Nội dung đang được phát triển</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { Server, Car, Building, CreditCard, Bell, User, Shield, Database, Save } from 'lucide-vue-next'
  
  export default {
    name: 'Settings',
    components: {
      Server,
      Car,
      Building,
      CreditCard,
      Bell,
      User,
      Shield,
      Database,
      Save
    },
    setup() {
      const activeTab = ref('general')
  
      const tabs = [
        { value: 'general', label: 'Cài đặt chung', icon: Server },
        { value: 'parking', label: 'Bãi đỗ xe', icon: Car },
        { value: 'building', label: 'Chung cư', icon: Building },
        { value: 'payment', label: 'Thanh toán', icon: CreditCard },
        { value: 'notifications', label: 'Thông báo', icon: Bell },
        { value: 'users', label: 'Người dùng', icon: User },
        { value: 'security', label: 'Bảo mật', icon: Shield },
        { value: 'database', label: 'Cơ sở dữ liệu', icon: Database }
      ]
  
      const settings = ref({
        general: {
          systemName: 'Hệ thống Quản lý Bãi giữ xe Chung cư',
          adminEmail: 'admin@example.com',
          timezone: 'UTC+7',
          language: 'vi',
          darkMode: false,
          autoBackup: true,
          analytics: true
        },
        parking: {
          carSlots: 200,
          motorbikeSlots: 100,
          carFee: 500000,
          motorbikeFee: 300000,
          visitorCarFee: 20000,
          visitorMotorbikeFee: 10000,
          allowVisitors: true,
          autoDetect: true,
          autoBarrier: true
        }
      })
  
      const getTabTitle = () => {
        const tab = tabs.find(t => t.value === activeTab.value)
        return tab ? tab.label : ''
      }
  
      const getTabDescription = () => {
        switch (activeTab.value) {
          case 'building':
            return 'Quản lý thông tin chung cư'
          case 'payment':
            return 'Quản lý phương thức thanh toán và cấu hình'
          case 'notifications':
            return 'Quản lý cấu hình thông báo hệ thống'
          case 'users':
            return 'Quản lý người dùng và phân quyền'
          case 'security':
            return 'Cài đặt bảo mật hệ thống'
          case 'database':
            return 'Quản lý cơ sở dữ liệu và sao lưu'
          default:
            return ''
        }
      }
  
      return {
        activeTab,
        tabs,
        settings,
        getTabTitle,
        getTabDescription
      }
    }
  }
  </script>
  
  <style>
  .dot {
    transition: transform 0.3s ease;
  }
  </style>