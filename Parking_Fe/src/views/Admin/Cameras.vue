<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý camera</h1>
      <div class="flex items-center space-x-2">
        <router-link to="/admin/quan-ly-camera/them-camera">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <Plus class="mr-2 h-4 w-4" />
            Thêm camera
          </button>
        </router-link>
      </div>
    </div>

    <!-- Tab navigation -->
    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
      <ul class="flex flex-wrap -mb-px">
        <li class="mr-2">
          <button 
            class="inline-block py-2 px-4 border-b-2 font-medium text-sm" 
            :class="activeTab === 'cameras' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            @click="activeTab = 'cameras'"
          >
            Danh sách camera
          </button>
        </li>
        <li class="mr-2">
          <button 
            class="inline-block py-2 px-4 border-b-2 font-medium text-sm" 
            :class="activeTab === 'license-scan' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
            @click="activeTab = 'license-scan'"
          >
            Quét biển số
          </button>
        </li>
      </ul>
    </div>

    <!-- Tab content -->
    <div v-if="activeTab === 'cameras'">
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
              <option value="1">Đang hoạt động</option>
              <option value="0">Đang lỗi</option>
            </select>
            <select 
              v-model="filters.type" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả loại</option>
              <option value="Camera cổng vào">Camera cổng vào</option>
              <option value="Camera cổng ra">Camera cổng ra</option>
              <option value="Camera bãi đỗ">Camera bãi đỗ</option>
              <option value="Camera an ninh">Camera an ninh</option>
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
                      <div class="text-sm font-medium">{{ camera.vi_tri_dat }}</div>
                      <div class="text-sm text-gray-500">ID: {{ camera.id }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ camera.ten_bai }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ camera.vi_tri_dat }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="getStatusClass(camera.trang_thai)">
                    {{ getStatusText(camera.trang_thai) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(camera.updated_at) }}
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
                      @confirm="confirmDelete(camera)"
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
              <p class="text-sm font-medium">{{ selectedCamera.vi_tri_dat }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại camera</p>
              <p class="text-sm font-medium">{{ selectedCamera.ten_bai }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Vị trí</p>
              <p class="text-sm font-medium">{{ selectedCamera.vi_tri_dat }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Trạng thái</p>
              <p class="text-sm font-medium">{{ getStatusText(selectedCamera.trang_thai) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">IP Address</p>
              <p class="text-sm font-medium">{{ selectedCamera.ipAddress }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Lần cuối cập nhật</p>
              <p class="text-sm font-medium">{{ formatDateTime(selectedCamera.updated_at) }}</p>
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
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bãi xe *</label>
            <a-select
              v-model:value="editingCamera.id_bai_xe"
              show-search
              placeholder="Chọn bãi xe"
              style="width: 100%"
              :options="optionsBaiXe"
              :filter-option="filterOptionBaiXe()"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vị trí *</label>
            <a-select
              v-model:value="editingCamera.id_vi_tri"
              show-search
              placeholder="Chọn vị trí"
              style="width: 100%"
              :options="optionsViTri"
              :filter-option="filterOptionViTri()"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái *</label>
            <select 
              id="cameraStatus"
              v-model="editingCamera.trang_thai"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            >
              <option :value="1">Đang hoạt động</option>
              <option :value="0">Đang lỗi</option>
            </select>
          </div>
        </div>
      </a-modal>
    </div>

    <!-- License Plate Scanning Tab -->
    <div v-if="activeTab === 'license-scan'" class="space-y-6">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
        <h2 class="text-xl font-semibold mb-4">Quét biển số xe</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Upload area -->
          <div class="space-y-4">
            <div 
              class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
              @click="triggerFileInput"
              :class="{'border-blue-500': isDragging}"
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="isDragging = false"
              @drop.prevent="handleFileDrop"
            >
              <input
                type="file"
                ref="fileInput"
                class="hidden"
                accept="image/*"
                @change="handleFileSelection"
              />
              <div v-if="!previewImage" class="space-y-2">
                <Upload class="h-12 w-12 mx-auto text-gray-400" />
                <p class="text-sm text-gray-500">Kéo thả ảnh hoặc click để chọn ảnh</p>
                <p class="text-xs text-gray-400">Hỗ trợ: JPG, PNG, JPEG</p>
              </div>
              <div v-else class="relative">
                <img 
                  :src="previewImage" 
                  alt="Preview" 
                  class="max-h-64 mx-auto object-contain rounded"
                />
                <button 
                  @click.stop="clearImage" 
                  class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                >
                  <X class="h-4 w-4" />
                </button>
              </div>
            </div>
            
            <div class="flex space-x-2">
              <button 
                @click="scanLicensePlate" 
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex-1 flex items-center justify-center"
                :disabled="!previewImage || isScanning"
              >
                <span v-if="isScanning">Đang quét...</span>
                <span v-else>Quét biển số</span>
              </button>
            </div>
          </div>
          
          <!-- Results area -->
          <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
            <h3 class="text-lg font-medium mb-2">Kết quả</h3>
            
            <div v-if="scanResult" class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số đã quét</label>
                <div class="flex items-center space-x-2">
                  <input 
                    type="text" 
                    v-model="detectedPlate" 
                    class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md flex-1"
                    :readonly="!scanError"
                  />
                  <span class="text-xs font-medium" :class="scanError ? 'text-red-500' : 'text-green-500'">
                    {{ scanError ? '(Không chính xác)' : '(Đã xác nhận)' }}
                  </span>
                </div>
              </div>
              
              <div v-if="scanError">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nhập lại biển số</label>
                <input 
                  type="text" 
                  v-model="manualPlate" 
                  placeholder="Ví dụ: 30A-12345" 
                  class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md w-full"
                />
              </div>
              
              <div class="pt-2">
                <button 
                  @click="submitLicensePlate" 
                  class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                  :disabled="isSubmitting || (scanError && !manualPlate)"
                >
                  {{ isSubmitting ? 'Đang gửi...' : 'Cho phép vào bãi' }}
                </button>
              </div>
            </div>
            
            <div v-else-if="isScanning" class="flex items-center justify-center h-40">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
            
            <div v-else class="text-center text-gray-500 py-10">
              Tải lên ảnh và nhấn quét để xác định biển số xe
            </div>
          </div>
        </div>
      </div>
    </div>
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
  X,
  Upload
} from 'lucide-vue-next'
import { Modal, Button, Popconfirm } from 'ant-design-vue'
import baseRequest from '../../core/baseRequest'
import { useNotificationStore } from '../../stores/notication'

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
    Upload,
    [Modal.name]: Modal,
    [Button.name]: Button,
    [Popconfirm.name]: Popconfirm
  },
  data() {
    return {
      // Thống kê
      stats: {
        totalCameras: 0,
        activeCameras: 0,
        errorCameras: 0,
        todayEvents: 0
      },
      // Tab control
      activeTab: 'cameras',
      // License Plate Scanning
      previewImage: null,
      isDragging: false,
      isScanning: false,
      isSubmitting: false,
      scanResult: null,
      scanError: false,
      detectedPlate: '',
      manualPlate: '',
      // Bộ lọc
      filters: {
        search: '',
        status: '',
        type: ''
      },
      // Danh sách camera
      cameras: [],
      // Modal
      showViewModal: false,
      showEditModal: false,
      isEditing: false,
      selectedCamera: null,
      editingCamera: {
        id_vi_tri: '',
        id_bai_xe: '',
        trang_thai: 1,
        ten_bai: '',
        vi_tri_dat: ''
      },
      bai_xes: [],
      vi_tris: [],
    }
  },
  computed: {
    filteredCameras() {
      return this.cameras.filter(camera => {
        const matchesSearch = camera.vi_tri_dat.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                            camera.ten_bai.toLowerCase().includes(this.filters.search.toLowerCase())
        const matchesStatus = !this.filters.status || camera.trang_thai === parseInt(this.filters.status)
        return matchesSearch && matchesStatus
      })
    },
    optionsBaiXe() {
      return this.bai_xes.map(bai_xe => ({
        label: bai_xe.ten_bai,
        value: bai_xe.id,
      }));
    },
    optionsViTri() {
      return this.vi_tris.map(vi_tri => ({
        label: vi_tri.vi_tri_dat,
        value: vi_tri.id,
      }));
    }
  },
  mounted() {
    this.getCamera();
    this.getBaiXe();
    this.getViTri();
  },  
  methods: {
    getCamera() {
      baseRequest.get("admin/cam-giam-soat/lay-du-lieu").then((res) => {
        this.cameras = res.data.data;
        this.updateStats();
      });
    },
    updateStats() {
      this.stats.totalCameras = this.cameras.length;
      this.stats.activeCameras = this.cameras.filter(c => c.trang_thai === 1).length;
      this.stats.errorCameras = this.cameras.filter(c => c.trang_thai === 0).length;
    },
    getStatusText(status) {
      switch (status) {
        case 1:
          return 'Đang hoạt động'
        case 0:
          return 'Đang lỗi'
        default:
          return 'Không xác định'
      }
    },
    getStatusClass(status) {
      switch (status) {
        case 1:
          return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        case 0:
          return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
        default:
          return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
      }
    },
    resetFilters() {
      this.filters = {
        search: '',
        status: '',
        type: ''
      }
    },
    viewCamera(camera) {
      this.selectedCamera = camera
      this.showViewModal = true
    },
    editCamera(camera) {
      this.isEditing = true
      this.editingCamera = { 
        id: camera.id,
        id_vi_tri: camera.id_vi_tri,
        id_bai_xe: camera.id_bai_xe,
        trang_thai: camera.trang_thai,
        ten_bai: camera.ten_bai,
        vi_tri_dat: camera.vi_tri_dat
      }
      this.showEditModal = true
    },
    handleEditSubmit() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      
      if (this.isEditing) {
        baseRequest.post("admin/cam-giam-soat/thong-tin-cap-nhat", {
          id: this.editingCamera.id,
          id_bai_xe: this.editingCamera.id_bai_xe,
          id_vi_tri: this.editingCamera.id_vi_tri,
          trang_thai: this.editingCamera.trang_thai,
         
        })
          .then((response) => {
            if (response.data.status) {
              this.getCamera();
              this.showEditModal = false;
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Cập nhật camera thất bại');
            }
            this.isSubmitting = false;
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
            this.isSubmitting = false;
          });
      } else {
        baseRequest.post("admin/cam-giam-soat/them-du-lieu", this.editingCamera)
          .then((response) => {
            if (response.data.status) {
              this.getCamera();
              this.showEditModal = false;
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Thêm camera thất bại');
            }
            this.isSubmitting = false;
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
            this.isSubmitting = false;
          });
      }
    },
    confirmDelete(camera) {
      const notificationStore = useNotificationStore();
      baseRequest.delete("admin/cam-giam-soat/thong-tin-xoa/" + camera.id)
        .then((response) => {
          if (response.data.status) {
            this.getCamera();
            notificationStore.showSuccess(response.data.message);
          } else {
            notificationStore.showError('Xóa camera thất bại');
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
        });
    },
    formatDateTime(dateTime) {
      const date = new Date(dateTime);
      return date.toLocaleString('vi-VN');
    },
    getBaiXe() {
      baseRequest.get("admin/bai-xe/lay-du-lieu").then((response) => {
        this.bai_xes = response.data.data;
      });
    },
    getViTri() {
      baseRequest.get("admin/vi-tri-dat/lay-du-lieu").then((response) => {
        this.vi_tris = response.data.data;
      });
    },
    filterOptionBaiXe() {
      return (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
      };
    },
    filterOptionViTri() {
      return (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
      };
    },
    // License plate scanning methods
    triggerFileInput() {
      this.$refs.fileInput.click();
    },
    
    handleFileSelection(event) {
      const file = event.target.files[0];
      if (file) {
        this.processSelectedFile(file);
      }
    },
    
    handleFileDrop(event) {
      this.isDragging = false;
      const file = event.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        this.processSelectedFile(file);
      }
    },
    
    processSelectedFile(file) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.previewImage = e.target.result;
        this.scanResult = null;
        this.detectedPlate = '';
        this.manualPlate = '';
        this.scanError = false;
      };
      reader.readAsDataURL(file);
    },
    
    clearImage() {
      this.previewImage = null;
      this.scanResult = null;
      this.detectedPlate = '';
      this.manualPlate = '';
      this.scanError = false;
      this.$refs.fileInput.value = null;
    },
    
    scanLicensePlate() {
      if (!this.previewImage) return;
      
      this.isScanning = true;
      const notificationStore = useNotificationStore();
      
      // Create form data with the image
      const formData = new FormData();
      // Convert base64 to blob
      const blob = this.dataURLtoBlob(this.previewImage);
      formData.append('image', blob, 'license_plate.jpg');
      
      // Send to backend for scanning
      baseRequest.post("admin/cam-giam-soat/scan-bien-so", formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
        .then((response) => {
          this.isScanning = false;
          if (response.data.status) {
            this.scanResult = response.data;
            this.detectedPlate = response.data.plate_number || '';
            this.scanError = !response.data.is_con_han;
            if (!response.data.is_con_han) {
              notificationStore.showWarning('Biển số không được nhận diện chính xác, vui lòng nhập lại');
            }
          } else {
            this.scanError = true;
            notificationStore.showError(response.data.message || 'Không thể quét biển số');
          }
        })
        .catch((error) => {
          this.isScanning = false;
          console.error('Scan error:', error);
          notificationStore.showError('Đã xảy ra lỗi khi quét biển số');
          this.scanResult = { error: true };
          this.scanError = true;
        });
    },
    
    dataURLtoBlob(dataURL) {
      const arr = dataURL.split(',');
      const mime = arr[0].match(/:(.*?);/)[1];
      const bstr = atob(arr[1]);
      let n = bstr.length;
      const u8arr = new Uint8Array(n);
      
      while (n--) {
        u8arr[n] = bstr.charCodeAt(n);
      }
      
      return new Blob([u8arr], { type: mime });
    },
    
    submitLicensePlate() {
      const notificationStore = useNotificationStore();
      this.isSubmitting = true;
      
      const plateNumber = this.scanError ? this.manualPlate : this.detectedPlate;
      
      baseRequest.post("admin/cam-giam-soat/cho-phep-vao-bai", {
        plate_number: plateNumber
      })
        .then((response) => {
          if (response.data.status) {
            notificationStore.showSuccess('Xe đã được cho phép vào bãi');
            // Reset form
            this.clearImage();
          } else {
            notificationStore.showError(response.data.message || 'Không thể cho phép xe vào bãi');
          }
          this.isSubmitting = false;
        })
        .catch((error) => {
          console.error('Submission error:', error);
          notificationStore.showError('Đã xảy ra lỗi khi gửi yêu cầu');
          this.isSubmitting = false;
        });
    },
  }
}
</script>