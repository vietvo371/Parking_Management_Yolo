<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Camera & AI</h1>
        <router-link to="/admin/quan-ly-camera/them-camera">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
          <Plus class="mr-2 h-4 w-4" />
            Thêm camera mới
          </button>
        </router-link>
      </div>
  
      <div class="flex space-x-2 overflow-x-auto">
        <button 
          v-for="tab in tabs" 
          :key="tab.value"
          @click="activeTab = tab.value"
          class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap"
          :class="activeTab === tab.value ? 'bg-gray-100 dark:bg-gray-700' : ''"
        >
          {{ tab.label }}
        </button>
      </div>
  
      <!-- Tab: Cameras -->
      <div v-if="activeTab === 'cameras'" class="grid gap-4 md:grid-cols-2 lg:grid-cols-2">
        <div v-for="camera in cameras" :key="camera.id" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center">
              <h2 class="text-lg font-medium">Camera {{ camera.ten_bai }} - {{ camera.vi_tri_dat }}</h2>
              <div :class="`h-3 w-3 rounded-full ${camera.trang_thai === 1 ? 'bg-green-500' : 'bg-red-500'}`"></div>
            </div>
            <p class="text-sm text-gray-500">{{ camera.location }}</p>
          </div>
          <div class="p-4">
            <div class="relative aspect-video rounded-md overflow-hidden border border-gray-200 dark:border-gray-700">
              <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                Camera {{ camera.id }}
              </div>
              <div class="absolute bottom-2 left-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                {{ camera.status === 'online' ? 'Trực tiếp' : 'Ngoại tuyến' }}
              </div>
              <div class="absolute bottom-2 right-2 bg-black bg-opacity-70 text-white text-xs px-2 py-1 rounded">
                Phát hiện gần nhất: {{ camera.lastDetection }}
              </div>
            </div>
            <div class="flex justify-between mt-4">
              <button class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
                <RefreshCw class="mr-2 h-4 w-4" />
                Làm mới
              </button>
              <button class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
                <Settings class="mr-2 h-4 w-4" />
                Cài đặt
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Tab: AI Detection -->
      <div v-if="activeTab === 'ai'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Nhận diện biển số AI</h2>
          <p class="text-sm text-gray-500">Hệ thống nhận diện biển số xe tự động bằng AI</p>
        </div>
        <div class="p-6">
          <div class="flex space-x-2 overflow-x-auto mb-4">
            <button 
              @click="aiTab = 'upload'"
              class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap"
              :class="aiTab === 'upload' ? 'bg-gray-100 dark:bg-gray-700' : ''"
            >
              Tải ảnh lên
            </button>
            <button 
              @click="aiTab = 'camera'"
              class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap"
              :class="aiTab === 'camera' ? 'bg-gray-100 dark:bg-gray-700' : ''"
            >
              Chụp ảnh
            </button>
          </div>
  
          <div v-if="aiTab === 'upload'" class="space-y-4">
            <div class="grid w-full max-w-sm items-center gap-1.5">
              <label for="picture" class="text-sm font-medium">Tải lên ảnh biển số xe</label>
              <input id="picture" type="file" accept="image/*" class="w-full" />
            </div>
          </div>
  
          <div v-if="aiTab === 'camera'" class="space-y-4">
            <div class="text-center">
              <button 
                @click="handleCameraCapture"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center mx-auto"
              >
                <Camera class="mr-2 h-4 w-4" />
                Chụp ảnh từ camera
              </button>
            </div>
          </div>
  
          <div v-if="selectedImage" class="space-y-4 mt-6">
            <div class="relative aspect-video max-w-md mx-auto rounded-md overflow-hidden border border-gray-200 dark:border-gray-700">
              <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                {{ selectedImage }}
              </div>
            </div>
  
            <div class="flex justify-center">
              <button 
                @click="processImage" 
                :disabled="isProcessing" 
                class="w-full max-w-sm px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
              >
                {{ isProcessing ? "Đang xử lý..." : "Nhận diện biển số" }}
              </button>
            </div>
          </div>
  
          <div v-if="result" class="mt-6 p-4 max-w-md mx-auto border rounded-lg">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Kết quả nhận diện</h3>
                <div class="flex items-center" :class="result.status === 'registered' ? 'text-green-600' : 'text-amber-600'">
                  <component :is="result.status === 'registered' ? Check : AlertTriangle" class="mr-1 h-4 w-4" />
                  <span class="text-sm">{{ result.status === 'registered' ? 'Đã đăng ký' : 'Chưa đăng ký' }}</span>
                </div>
              </div>
  
              <div class="space-y-2">
                <div class="flex justify-between">
                  <span class="text-sm text-gray-500">Biển số:</span>
                  <span class="font-medium">{{ result.licensePlate }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="text-sm text-gray-500">Độ chính xác:</span>
                  <span class="font-medium">{{ result.confidence.toFixed(1) }}%</span>
                </div>
  
                <template v-if="result.status === 'registered'">
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Chủ xe:</span>
                    <span class="font-medium">{{ result.owner }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-sm text-gray-500">Căn hộ:</span>
                    <span class="font-medium">{{ result.apartment }}</span>
                  </div>
                </template>
              </div>
  
              <button v-if="result.status === 'unregistered'" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Đăng ký xe mới
              </button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Tab: AI Settings -->
      <div v-if="activeTab === 'settings'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Cài đặt AI</h2>
          <p class="text-sm text-gray-500">Điều chỉnh các thông số cho hệ thống nhận diện AI</p>
        </div>
        <div class="p-6">
          <div class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <h3 class="text-sm font-medium">Độ chính xác nhận diện</h3>
                <div class="flex items-center">
                  <input 
                    type="range" 
                    min="0" 
                    max="100" 
                    v-model="aiSettings.accuracy" 
                    class="w-full" 
                  />
                  <span class="ml-2 text-sm">{{ aiSettings.accuracy }}%</span>
                </div>
              </div>
              <div class="space-y-2">
                <h3 class="text-sm font-medium">Tốc độ xử lý</h3>
                <div class="flex items-center">
                  <input 
                    type="range" 
                    min="0" 
                    max="100" 
                    v-model="aiSettings.speed" 
                    class="w-full" 
                  />
                  <span class="ml-2 text-sm">{{ aiSettings.speed }}%</span>
                </div>
              </div>
            </div>
  
            <div class="grid gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <h3 class="text-sm font-medium">Lưu trữ hình ảnh</h3>
                <select 
                  v-model="aiSettings.storage"
                  class="w-full p-2 border rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                >
                  <option value="7">7 ngày</option>
                  <option value="14">14 ngày</option>
                  <option value="30">30 ngày</option>
                  <option value="90">90 ngày</option>
                </select>
              </div>
              <div class="space-y-2">
                <h3 class="text-sm font-medium">Thông báo</h3>
                <select 
                  v-model="aiSettings.notifications"
                  class="w-full p-2 border rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
                >
                  <option value="all">Tất cả các xe</option>
                  <option value="unknown">Chỉ xe không xác định</option>
                  <option value="none">Tắt thông báo</option>
                </select>
              </div>
            </div>
  
            <div class="space-y-2">
              <h3 class="text-sm font-medium">Mô hình AI</h3>
              <select 
                v-model="aiSettings.model"
                class="w-full p-2 border rounded-md bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600"
              >
                <option value="standard">Mô hình tiêu chuẩn</option>
                <option value="advanced">Mô hình nâng cao</option>
                <option value="custom">Mô hình tùy chỉnh</option>
              </select>
            </div>
  
            <div class="pt-4 flex justify-end space-x-2">
              <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
                Đặt lại mặc định
              </button>
              <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Lưu cài đặt
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
import { ref } from 'vue'
import { useNotificationStore } from '../stores/notication'
import baseRequest from '../core/baseRequest'
import { Popconfirm } from 'ant-design-vue';
import { Plus, Settings, RefreshCw, Camera, AlertTriangle } from 'lucide-vue-next'
export default {
  name: 'Cameras',
  components: {
      Plus,
      Settings,
      RefreshCw,
      Camera,
      AlertTriangle
    },
    data() {
      return {
        activeTab: 'cameras',
        aiTab: 'upload',
        isProcessing: false,
        selectedImage: null,
        result: null,
        aiSettings: {
          accuracy: 80,
          speed: 50,
          storage: 7,
          notifications: 'all',
          model: 'standard'
        },
        cameras: [],
        tabs: [
          { label: 'Camera', value: 'cameras' },
          { label: 'AI Detection', value: 'ai' },
          { label: 'AI Settings', value: 'settings' }
        ]
        
      }
    },
    mounted() {
      this.getCamera()
    },
    methods: {
      getCamera() {
        baseRequest.get("admin/cam-giam-soat/lay-du-lieu")
        .then((response) => {
          this.cameras = response.data.data
        })
    },
    }
  }
  </script>
  
  <style>
  </style>