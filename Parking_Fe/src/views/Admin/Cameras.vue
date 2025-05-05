<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý camera</h1>
      <div class="flex items-center space-x-2">
        <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
          <RefreshCcw class="mr-2 h-4 w-4" />
          Làm mới
        </button>
        <router-link to="/admin/quan-ly-camera/them-camera">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <Plus class="mr-2 h-4 w-4" />
            Thêm camera
          </button>
        </router-link>
      </div>
    </div>

    <!-- Camera Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Main Camera -->
      <div class="md:col-span-2 lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <div>
            <h2 class="text-lg font-medium">Camera chính - Cổng vào</h2>
            <p class="text-sm text-gray-500">Trực tiếp</p>
          </div>
          <div class="flex items-center space-x-2">
            <button class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <Maximize2 class="h-5 w-5" />
            </button>
            <button class="p-1 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">
              <Settings class="h-5 w-5" />
            </button>
          </div>
        </div>
        <div class="p-4">
          <div class="relative aspect-video bg-gray-900 rounded-md overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
              <img src="../assets/images/camera.png" alt="Camera Feed" class="w-full h-full object-cover" />
              <div class="absolute top-2 right-2 bg-red-600 h-2 w-2 rounded-full animate-pulse"></div>
              <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                LIVE
              </div>
              <div class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                {{ currentTime }}
              </div>
            </div>
          </div>
          
          <!-- License Plate Recognition -->
          <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-md">
              <h3 class="text-sm font-medium mb-2">Nhận diện biển số</h3>
              <div class="flex items-center space-x-4">
                <div class="h-16 w-24 bg-gray-200 dark:bg-gray-800 rounded-md overflow-hidden">
                  <img src="../assets/images/license-plate.png" alt="License Plate" class="w-full h-full object-cover" />
                </div>
                <div>
                  <p class="text-lg font-bold">30A-12345</p>
                  <p class="text-xs text-gray-500">Độ tin cậy: 98%</p>
                </div>
              </div>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-md">
              <h3 class="text-sm font-medium mb-2">Thông tin xe</h3>
              <p class="text-sm"><span class="font-medium">Chủ xe:</span> Nguyễn Văn A</p>
              <p class="text-sm"><span class="font-medium">Căn hộ:</span> A1201</p>
              <p class="text-sm"><span class="font-medium">Loại xe:</span> Ô tô</p>
              <p class="text-sm"><span class="font-medium">Trạng thái:</span> <span class="text-green-600">Đã đăng ký</span></p>
            </div>
            
            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-md">
              <h3 class="text-sm font-medium mb-2">Hành động</h3>
              <div class="space-y-2">
                <button class="w-full px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                  Cho phép vào
                </button>
                <button class="w-full px-3 py-1 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                  Từ chối
                </button>
                <button class="w-full px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                  Xem lịch sử
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Camera List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Danh sách camera</h2>
          <p class="text-sm text-gray-500">Nhấp vào camera để xem</p>
        </div>
        <div class="p-4">
          <div class="space-y-4">
            <div v-for="(camera, index) in cameras" :key="index" 
              class="relative aspect-video bg-gray-900 rounded-md overflow-hidden cursor-pointer hover:ring-2 hover:ring-blue-500"
              :class="{ 'ring-2 ring-blue-500': index === 0 }"
            >
              <img :src="`../assets/images/camera.png`" alt="Camera Feed" class="w-full h-full object-cover" />
              <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
              <div class="absolute bottom-2 left-2 text-white text-xs">
                {{ camera.name }}
              </div>
              <div class="absolute top-2 right-2 bg-red-600 h-2 w-2 rounded-full animate-pulse"></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Camera Settings -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Cài đặt camera</h2>
          <p class="text-sm text-gray-500">Camera cổng vào</p>
        </div>
        <div class="p-4">
          <div class="space-y-4">
            <div>
              <label class="text-sm font-medium">Độ phân giải</label>
              <select class="mt-1 w-full h-10 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                <option>1080p (Full HD)</option>
                <option>720p (HD)</option>
                <option>480p (SD)</option>
              </select>
            </div>
            
            <div>
              <label class="text-sm font-medium">Tốc độ khung hình</label>
              <select class="mt-1 w-full h-10 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                <option>30 fps</option>
                <option>25 fps</option>
                <option>20 fps</option>
                <option>15 fps</option>
              </select>
            </div>
            
            <div>
              <label class="text-sm font-medium">Chế độ ban đêm</label>
              <div class="mt-1 flex items-center">
                <label class="inline-flex items-center">
                  <input type="radio" name="nightMode" class="h-4 w-4 text-blue-600" checked />
                  <span class="ml-2 text-sm">Bật</span>
                </label>
                <label class="inline-flex items-center ml-6">
                  <input type="radio" name="nightMode" class="h-4 w-4 text-blue-600" />
                  <span class="ml-2 text-sm">Tắt</span>
                </label>
                <label class="inline-flex items-center ml-6">
                  <input type="radio" name="nightMode" class="h-4 w-4 text-blue-600" />
                  <span class="ml-2 text-sm">Tự động</span>
                </label>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Nhận diện biển số</label>
              <div class="mt-1 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Lưu trữ video</label>
              <div class="mt-1 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Thời gian lưu trữ</label>
              <select class="mt-1 w-full h-10 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                <option>7 ngày</option>
                <option>14 ngày</option>
                <option>30 ngày</option>
                <option>60 ngày</option>
                <option>90 ngày</option>
              </select>
            </div>
            
            <div class="pt-2">
              <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Lưu cài đặt
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Events -->
      <div class="md:col-span-2 lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <div>
            <h2 class="text-lg font-medium">Sự kiện gần đây</h2>
            <p class="text-sm text-gray-500">Các sự kiện được ghi nhận bởi camera</p>
          </div>
          <button class="text-sm text-blue-600 hover:underline">
            Xem tất cả
          </button>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camera</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Sự kiện</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="(event, index) in recentEvents" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ event.time }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ event.camera }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <component :is="getEventIcon(event.type)" class="h-4 w-4 mr-2" :class="getEventIconColor(event.type)" />
                    {{ event.type }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ event.licensePlate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': event.status === 'Cho phép',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': event.status === 'Từ chối',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': event.status === 'Cảnh báo'
                    }">
                    {{ event.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                      <Save class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- AI Settings -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Cài đặt AI</h2>
          <p class="text-sm text-gray-500">Tùy chỉnh nhận diện thông minh</p>
        </div>
        <div class="p-4">
          <div class="space-y-4">
            <div>
              <label class="text-sm font-medium">Độ chính xác nhận diện biển số</label>
              <div class="mt-2 flex items-center">
                <input type="range" min="0" max="100" value="80" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" />
                <span class="ml-2 text-sm">80%</span>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Nhận diện loại xe</label>
              <div class="mt-1 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Nhận diện khuôn mặt</label>
              <div class="mt-1 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Phát hiện đối tượng</label>
              <div class="mt-1 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            
            <div>
              <label class="text-sm font-medium">Cảnh báo tự động</label>
              <div class="mt-1 flex items-center">
                <label class="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" class="sr-only peer" checked />
                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
                </label>
              </div>
            </div>
            
            <div class="pt-2">
              <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                Lưu cài đặt AI
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { 
  RefreshCcw, 
  Plus, 
  Maximize2, 
  Settings, 
  Eye, 
  Save,
  LogIn,
  LogOut,
  AlertTriangle
} from 'lucide-vue-next'

export default {
  name: 'Cameras',
  components: {
    RefreshCcw,
    Plus,
    Maximize2,
    Settings,
    Eye,
    Save,
    LogIn,
    LogOut,
    AlertTriangle
  },
  data() {
    return {
      cameras: [
      { name: 'Camera cổng vào', status: 'online' },
      { name: 'Camera cổng ra', status: 'online' },
      { name: 'Camera bãi đỗ A', status: 'online' },
      { name: 'Camera bãi đỗ B', status: 'online' },
      { name: 'Camera hầm xe', status: 'online' },
      { name: 'Camera an ninh', status: 'online' }
      ],
      recentEvents: [
      { time: '10:25', camera: 'Camera cổng vào', type: 'Xe vào', licensePlate: '30A-12345', status: 'Cho phép' },
      { time: '10:15', camera: 'Camera cổng ra', type: 'Xe ra', licensePlate: '30F-54321', status: 'Cho phép' },
      { time: '09:45', camera: 'Camera cổng vào', type: 'Xe vào', licensePlate: '29P2-12345', status: 'Cho phép' },
      { time: '09:30', camera: 'Camera cổng vào', type: 'Cảnh báo', licensePlate: '30H-98765', status: 'Cảnh báo' },
      { time: '09:15', camera: 'Camera cổng ra', type: 'Xe ra', licensePlate: '30K1-65432', status: 'Cho phép' },
      { time: '08:45', camera: 'Camera cổng vào', type: 'Xe vào', licensePlate: '30A-56789', status: 'Từ chối' }
      ],
      currentTime: ''
    }
  },
  methods: {
    getEventIcon(type) {
      switch (type) {
        case 'Xe vào':
          return LogIn
        case 'Xe ra':
          return LogOut
        case 'Cảnh báo':
          return AlertTriangle
        default:
          return Eye
      }
    },
    getEventIconColor(type) {
      switch (type) {
        case 'Xe vào':
          return 'text-green-600'
        case 'Xe ra':
          return 'text-blue-600'  
        case 'Cảnh báo':
          return 'text-orange-600'
        default:
          return 'text-gray-600'
      }
    },
    updateCurrentTime() {
      const now = new Date()
      const hours = now.getHours().toString().padStart(2, '0')
      const minutes = now.getMinutes().toString().padStart(2, '0')
      const seconds = now.getSeconds().toString().padStart(2, '0')
      this.currentTime = `${hours}:${minutes}:${seconds}`
    }
  },
  mounted() {
    this.updateCurrentTime()
    setInterval(this.updateCurrentTime, 1000)
  }
}
</script>