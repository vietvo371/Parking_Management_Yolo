<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý camera</h1>
      <div class="flex items-center space-x-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="addCamera">
          <Plus class="mr-2 h-4 w-4" />
          Thêm camera
        </button>
      </div>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Tổng số camera</p>
            <h3 class="text-2xl font-bold">{{ stats.totalCameras }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
            <Video class="h-5 w-5 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Camera hoạt động</p>
            <h3 class="text-2xl font-bold">{{ stats.activeCameras }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
            <CheckCircle class="h-5 w-5 text-green-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Camera lỗi</p>
            <h3 class="text-2xl font-bold">{{ stats.errorCameras }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
            <AlertCircle class="h-5 w-5 text-red-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Sự kiện hôm nay</p>
            <h3 class="text-2xl font-bold">{{ stats.todayEvents }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
            <Activity class="h-5 w-5 text-purple-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
      <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="flex flex-1 items-center space-x-2">
          <input 
            type="text" 
            placeholder="Tìm kiếm camera..." 
            class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md flex-1"
            v-model="filters.search"
          />
          <select 
            v-model="filters.status" 
            class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="active">Đang hoạt động</option>
            <option value="error">Đang lỗi</option>
            <option value="offline">Ngoại tuyến</option>
          </select>
          <select 
            v-model="filters.type" 
            class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
          >
            <option value="">Tất cả loại</option>
            <option value="entrance">Camera cổng vào</option>
            <option value="exit">Camera cổng ra</option>
            <option value="parking">Camera bãi đỗ</option>
            <option value="security">Camera an ninh</option>
          </select>
        </div>
        <button 
          class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
          @click="resetFilters"
        >
          Đặt lại
        </button>
      </div>
    </div>

    <!-- Danh sách camera -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camera</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vị trí</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lần cuối cập nhật</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="camera in filteredCameras" :key="camera.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <Video class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium">{{ camera.name }}</div>
                    <div class="text-sm text-gray-500">ID: {{ camera.id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ camera.type }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ camera.location }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': camera.status === 'active',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': camera.status === 'error',
                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300': camera.status === 'offline'
                  }">
                  {{ getStatusText(camera.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ camera.lastUpdate }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <a-button 
                    type="primary"
                    size="small"
                    @click="viewCamera(camera)"
                  >
                    <Eye class="h-4 w-4" />
                  </a-button>
                  <a-button 
                    type="primary"
                    size="small"
                    @click="editCamera(camera)"
                  >
                    <Settings class="h-4 w-4" />
                  </a-button>
                  <a-popconfirm
                    title="Bạn có chắc chắn muốn xóa camera này không?"
                    ok-text="Có"
                    cancel-text="Không"
                    @confirm="confirmDelete"
                  >
                    <a-button 
                      type="primary"
                      danger
                      size="small"
                    >
                      <Trash2 class="h-4 w-4" />
                    </a-button>
                  </a-popconfirm>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal xem camera -->
    <a-modal v-model:open="showViewModal" title="Xem camera" width="800px">
      <template #footer>
        <div class="flex justify-end space-x-2">
          <a-button @click="showViewModal = false">Đóng</a-button>
        </div>
      </template>
      <div v-if="selectedCamera" class="space-y-6">
        <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden mb-4">
          <img :src="selectedCamera.image" alt="Camera Feed" class="w-full h-full object-cover" />
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">Tên camera</p>
            <p class="text-sm font-medium">{{ selectedCamera.name }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Loại camera</p>
            <p class="text-sm font-medium">{{ selectedCamera.type }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Vị trí</p>
            <p class="text-sm font-medium">{{ selectedCamera.location }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Trạng thái</p>
            <p class="text-sm font-medium">{{ getStatusText(selectedCamera.status) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">IP Address</p>
            <p class="text-sm font-medium">{{ selectedCamera.ipAddress }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Lần cuối cập nhật</p>
            <p class="text-sm font-medium">{{ selectedCamera.lastUpdate }}</p>
          </div>
        </div>
      </div>
    </a-modal>

    <!-- Modal thêm/sửa camera -->
    <a-modal v-model:open="showEditModal" :title="isEditing ? 'Chỉnh sửa camera' : 'Thêm camera mới'" @ok="handleEditSubmit">
      <template #footer>
        <a-button key="back" @click="showEditModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleEditSubmit">
          {{ isSubmitting ? "Đang lưu..." : (isEditing ? "Cập nhật" : "Thêm") }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div>
          <label for="cameraName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên camera *</label>
          <input 
            id="cameraName" 
            type="text" 
            v-model="editingCamera.name"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập tên camera"
          />
        </div>
        
        <div>
          <label for="cameraType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại camera *</label>
          <select 
            id="cameraType"
            v-model="editingCamera.type"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
          >
            <option value="Camera cổng vào">Camera cổng vào</option>
            <option value="Camera cổng ra">Camera cổng ra</option>
            <option value="Camera bãi đỗ">Camera bãi đỗ</option>
            <option value="Camera an ninh">Camera an ninh</option>
          </select>
        </div>
        
        <div>
          <label for="cameraLocation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vị trí *</label>
          <input 
            id="cameraLocation" 
            type="text" 
            v-model="editingCamera.location"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập vị trí camera"
          />
        </div>
        
        <div>
          <label for="cameraIp" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">IP Address *</label>
          <input 
            id="cameraIp" 
            type="text" 
            v-model="editingCamera.ipAddress"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập IP address"
          />
        </div>
        
        <div>
          <label for="cameraStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái *</label>
          <select 
            id="cameraStatus"
            v-model="editingCamera.status"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
          >
            <option value="active">Đang hoạt động</option>
            <option value="error">Đang lỗi</option>
            <option value="offline">Ngoại tuyến</option>
          </select>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script>
import { 
  Plus, 
  Video, 
  CheckCircle, 
  AlertCircle, 
  Activity,
  Eye,
  Settings,
  Trash2,
  X
} from 'lucide-vue-next'
import { Modal, Button, Popconfirm } from 'ant-design-vue'

export default {
  name: 'Cameras',
  components: {
    Plus,
    Video,
    CheckCircle,
    AlertCircle,
    Activity,
    Eye,
    Settings,
    Trash2,
    X,
    [Modal.name]: Modal,
    [Button.name]: Button,
    [Popconfirm.name]: Popconfirm
  },
  data() {
    return {
      // Thống kê
      stats: {
        totalCameras: 12,
        activeCameras: 10,
        errorCameras: 1,
        todayEvents: 156
      },
      // Bộ lọc
      filters: {
        search: '',
        status: '',
        type: ''
      },
      // Danh sách camera
      cameras: [
        { 
          id: 'CAM001',
          name: 'Camera cổng vào',
          type: 'Camera cổng vào',
          location: 'Cổng chính',
          status: 'active',
          lastUpdate: '24/04/2023 - 18:30',
          ipAddress: '192.168.1.101',
          image: '../assets/images/camera.png'
        },
        { 
          id: 'CAM002',
          name: 'Camera cổng ra',
          type: 'Camera cổng ra',
          location: 'Cổng phụ',
          status: 'active',
          lastUpdate: '24/04/2023 - 18:25',
          ipAddress: '192.168.1.102',
          image: '../assets/images/camera.png'
        },
        { 
          id: 'CAM003',
          name: 'Camera bãi đỗ A',
          type: 'Camera bãi đỗ',
          location: 'Tầng hầm A',
          status: 'error',
          lastUpdate: '24/04/2023 - 17:45',
          ipAddress: '192.168.1.103',
          image: '../assets/images/camera.png'
        },
        { 
          id: 'CAM004',
          name: 'Camera bãi đỗ B',
          type: 'Camera bãi đỗ',
          location: 'Tầng hầm B',
          status: 'active',
          lastUpdate: '24/04/2023 - 18:15',
          ipAddress: '192.168.1.104',
          image: '../assets/images/camera.png'
        },
        { 
          id: 'CAM005',
          name: 'Camera an ninh 1',
          type: 'Camera an ninh',
          location: 'Sảnh chính',
          status: 'active',
          lastUpdate: '24/04/2023 - 18:00',
          ipAddress: '192.168.1.105',
          image: '../assets/images/camera.png'
        }
      ],
      // Modal
      showViewModal: false,
      showEditModal: false,
      isEditing: false,
      isSubmitting: false,
      selectedCamera: null,
      editingCamera: {
        name: '',
        type: '',
        location: '',
        status: 'active',
        ipAddress: ''
      }
    }
  },
  computed: {
    filteredCameras() {
      return this.cameras.filter(camera => {
        const matchesSearch = camera.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            camera.id.toLowerCase().includes(this.filters.search.toLowerCase())
        const matchesStatus = !this.filters.status || camera.status === this.filters.status
        const matchesType = !this.filters.type || camera.type.toLowerCase().includes(this.filters.type.toLowerCase())
        return matchesSearch && matchesStatus && matchesType
      })
    }
  },
  methods: {
    getStatusText(status) {
      switch (status) {
        case 'active':
          return 'Đang hoạt động'
        case 'error':
          return 'Đang lỗi'
        case 'offline':
          return 'Ngoại tuyến'
        default:
          return 'Không xác định'
      }
    },
    resetFilters() {
      this.filters = {
        search: '',
        status: '',
        type: ''
      }
    },
    addCamera() {
      this.isEditing = false
      this.editingCamera = {
        name: '',
        type: '',
        location: '',
        status: 'active',
        ipAddress: ''
      }
      this.showEditModal = true
    },
    viewCamera(camera) {
      this.selectedCamera = camera
      this.showViewModal = true
    },
    editCamera(camera) {
      this.isEditing = true
      this.editingCamera = { ...camera }
      this.showEditModal = true
    },
    handleEditSubmit() {
      this.isSubmitting = true
      
      // Trong thực tế, bạn sẽ gọi API để thêm/sửa camera
      setTimeout(() => {
        if (this.isEditing) {
          const index = this.cameras.findIndex(c => c.id === this.editingCamera.id)
          if (index !== -1) {
            this.cameras[index] = { ...this.editingCamera }
          }
        } else {
          const newId = `CAM${String(this.cameras.length + 1).padStart(3, '0')}`
          this.cameras.push({
            id: newId,
            ...this.editingCamera,
            lastUpdate: new Date().toLocaleString('vi-VN')
          })
        }
        
        this.isSubmitting = false
        this.showEditModal = false
      }, 1000)
    },
    confirmDelete() {
      // Trong thực tế, bạn sẽ gọi API để xóa camera
      this.cameras = this.cameras.filter(c => c.id !== this.selectedCamera.id)
      this.selectedCamera = null
    }
  }
}
</script>