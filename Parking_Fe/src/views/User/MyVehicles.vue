<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý xe cá nhân</h1>
      <div class="flex items-center space-x-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
          @click="showAddVehicleModal = true">
          <Plus class="mr-2 h-4 w-4" />
          Đăng ký xe mới
        </button>
      </div>
    </div>

    <!-- Vehicles Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Tổng số xe</p>
            <h3 class="text-2xl font-bold">{{ vehicles.length }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
            <Car class="h-5 w-5 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Xe đã duyệt</p>
            <h3 class="text-2xl font-bold">{{ vehicles.filter(vehicle => vehicle.trang_thai_duyet === 1).length }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
            <CheckCircle class="h-5 w-5 text-green-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Xe chờ duyệt</p>
            <h3 class="text-2xl font-bold">{{ vehicles.filter(vehicle => vehicle.trang_thai_duyet === 0).length }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
            <Clock class="h-5 w-5 text-yellow-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Vehicles List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h3 class="text-lg font-medium">Danh sách xe đã đăng ký</h3>
        <div class="relative">
          <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
          <input type="text" placeholder="Tìm kiếm biển số..."
            class="pl-9 h-10 w-full sm:w-[250px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            v-model="searchQuery" />
        </div>
      </div>
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Biển số xe</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Loại xe</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Màu sắc</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Ngày đăng ký</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Trạng thái</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Hạn đăng ký</th>
              <th scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Thao tác</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="vehicle in filteredVehicles" :key="vehicle.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ vehicle.bien_so_xe }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span>{{ vehicle.ten_loai_xe }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span>{{ vehicle.ten_toa_nha }} - {{ vehicle.so_can_ho }}</span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(vehicle.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': vehicle.trang_thai_duyet === 1,
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': vehicle.trang_thai_duyet === 0,
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': vehicle.trang_thai_duyet === 2
                  }">
                  {{ getStatusText(vehicle.trang_thai_duyet) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <span :class="{
                  'text-green-600': vehicle.is_con_han === 1,
                  'text-red-600': vehicle.is_con_han === 0
                }">
                  {{ vehicle.is_con_han === 1 ? 'Còn hạn' : 'Hết hạn' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                    @click="viewVehicle(vehicle)">
                    <Eye class="h-4 w-4" />
                  </button>
                  <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                    @click="editVehicle(vehicle)">
                    <Edit class="h-4 w-4" />
                  </button>
                  <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400"
                    @click="showDeleteConfirm(vehicle)">
                    <Trash2 class="h-4 w-4" />
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredVehicles.length === 0">
              <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                Không tìm thấy xe nào
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add Vehicle Modal -->
    <a-modal v-model:open="showAddVehicleModal" title="Đăng ký xe mới" @ok="addVehicle">
      <template #footer>
        <a-button key="back" @click="showAddVehicleModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="addVehicle">
          {{ isSubmitting ? "Đang lưu..." : "Đăng ký" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="licensePlate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số xe
              *</label>
            <input id="licensePlate" type="text" v-model="newVehicle.bien_so_xe" required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập biển số xe" />
          </div>

          <div>
            <label for="vehicleType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe
              *</label>
            <select id="vehicleType" v-model="newVehicle.id_loai_xe" required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
              <option value="">Chọn loại xe</option>
              <option v-for="item in loai_xe" :key="item.id" :value="item.id">{{ item.ten_loai_xe }}</option>
            </select>
          </div>

        </div>


        <div>
          <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ghi chú</label>
          <textarea id="note" v-model="newVehicle.note"
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập ghi chú (nếu có)" rows="2"></textarea>
        </div>
      </div>
    </a-modal>

    <!-- Delete Confirmation Modal -->
    <a-modal v-model:open="showDeleteConfirmModal" title="Xác nhận xóa xe" @ok="deleteVehicle">
      <template #footer>
        <div class="flex justify-end space-x-2">
          <a-button @click="showDeleteConfirmModal = false">Hủy</a-button>
          <a-button type="primary" danger :loading="isSubmitting" @click="deleteVehicle">
            {{ isSubmitting ? "Đang xóa..." : "Xóa" }}
          </a-button>
        </div>
      </template>
      <div class="space-y-4">
        <div class="flex items-center space-x-3">
          <AlertTriangle class="h-6 w-6 text-red-500" />
          <p class="text-base">Bạn có chắc chắn muốn xóa xe này không?</p>
        </div>
        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-md">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Biển số xe</p>
              <p class="font-medium">{{ deleteVehicleData.bien_so_xe }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại xe</p>
              <p class="font-medium">{{ deleteVehicleData.ten_loai_xe }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Tòa nhà - Căn hộ</p>
              <p class="font-medium">{{ deleteVehicleData.ten_toa_nha }} - {{ deleteVehicleData.so_can_ho }}</p>
            </div>
          </div>
        </div>
        <p class="text-sm text-red-500">Lưu ý: Hành động này không thể hoàn tác.</p>
      </div>
    </a-modal>

    <!-- View Vehicle Modal -->
    <a-modal v-model:open="showDetailModal" title="Chi tiết xe" width="800px">
      <template #footer>
        <div class="flex justify-end space-x-2">
          <a-button @click="closeDetailModal">Đóng</a-button>
        </div>
      </template>
      <div v-if="selectedVehicle" class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold">{{ selectedVehicle.bien_so_xe }}</h2>
            <p class="text-sm text-gray-500">ID: {{ selectedVehicle.id }} - Ngày đăng ký: {{ formatDate(selectedVehicle.created_at) }}</p>
          </div>
          <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
            :class="{
              'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedVehicle.trang_thai_duyet === 1,
              'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedVehicle.trang_thai_duyet === 0,
              'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedVehicle.trang_thai_duyet === 2
            }">
            {{ getStatusText(selectedVehicle.trang_thai_duyet) }}
          </span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">Loại xe</p>
            <p class="font-medium">{{ selectedVehicle.ten_loai_xe }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Cư dân</p>
            <p class="font-medium">{{ selectedVehicle.ho_va_ten }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Tòa nhà - Căn hộ</p>
            <p class="font-medium">{{ selectedVehicle.ten_toa_nha }} - {{ selectedVehicle.so_can_ho }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Màu xe</p>
            <p class="font-medium">{{ selectedVehicle.mau_xe }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Trạng thái hạn</p>
            <span :class="{
              'text-green-600': selectedVehicle.is_con_han === 1,
              'text-red-600': selectedVehicle.is_con_han === 0
            }">
              {{ selectedVehicle.is_con_han === 1 ? 'Còn hạn' : 'Hết hạn' }}
            </span>
          </div>
        </div>
      </div>
    </a-modal>

    <!-- Edit Vehicle Modal -->
    <a-modal v-model:open="showEditVehicleModal" title="Chỉnh sửa thông tin xe" @ok="updateVehicle">
      <template #footer>
        <a-button key="back" @click="showEditVehicleModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="updateVehicle">
          {{ isSubmitting ? "Đang lưu..." : "Cập nhật" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium mb-1">Biển số xe *</label>
          <input v-model="editingVehicle.bien_so_xe" required class="w-full px-3 py-2 border rounded-md" placeholder="Nhập biển số xe" />
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Loại xe *</label>
          <select v-model="editingVehicle.id_loai_xe" required class="w-full px-3 py-2 border rounded-md">
            <option value="">Chọn loại xe</option>
            <option v-for="item in loai_xe" :key="item.id" :value="item.id">{{ item.ten_loai_xe }}</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Ghi chú</label>
          <textarea v-model="editingVehicle.note" class="w-full px-3 py-2 border rounded-md" rows="2"></textarea>
        </div>
      </div>
    </a-modal>

    <!-- Renew Registration Modal -->
    <a-modal v-model:open="showRenewVehicleModal" title="Gia hạn đăng ký xe" @ok="renewVehicle">
      <template #footer>
        <a-button key="back" @click="showRenewVehicleModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="renewVehicle">
          {{ isSubmitting ? "Đang xử lý..." : "Thanh toán và gia hạn" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div>
          <p class="text-sm text-gray-500">Biển số xe</p>
          <p class="font-medium">{{ renewVehicleData.licensePlate }}</p>
        </div>

        <div>
          <p class="text-sm text-gray-500">Hạn đăng ký hiện tại</p>
          <p class="font-medium text-red-600">{{ renewVehicleData.currentExpiryDate }}</p>
        </div>

        <div>
          <label for="renewalPeriod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời hạn
            gia
            hạn *</label>
          <select id="renewalPeriod" v-model="renewVehicleData.period" required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
            <option value="1">1 tháng</option>
            <option value="3">3 tháng</option>
            <option value="6">6 tháng</option>
            <option value="12">12 tháng</option>
          </select>
        </div>

        <div>
          <p class="text-sm text-gray-500">Phí gia hạn</p>
          <p class="font-medium text-lg">{{ formatCurrency(calculateRenewalFee(renewVehicleData.period,
            renewVehicleData.type)) }}</p>
        </div>

        <div>
          <label for="paymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức
            thanh toán *</label>
          <select id="paymentMethod" v-model="renewVehicleData.paymentMethod" required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
            <option value="bank">Chuyển khoản ngân hàng</option>
            <option value="momo">Ví MoMo</option>
            <option value="vnpay">VNPay</option>
            <option value="cash">Tiền mặt</option>
          </select>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useNotificationStore } from '../../stores/notication'
import {
  Plus,
  Search,
  Car,
  Bike,
  CheckCircle,
  Clock,
  Eye,
  Edit,
  Trash2,
  X,
  Upload,
  FileText,
  AlertTriangle
} from 'lucide-vue-next'
import baseRequestUser from '../../core/baseRequestUser'

export default {
  name: 'MyVehicles',
  components: {
    Plus,
    Search,
    Car,
    Bike,
    CheckCircle,
    Clock,
    Eye,
    Edit,
    Trash2,
    X,
    Upload,
    FileText,
    AlertTriangle
  },
  data() {
    return {
      searchQuery: '',
      showAddVehicleModal: false,
      showEditVehicleModal: false,
      showRenewVehicleModal: false,
      showDeleteConfirmModal: false,
      showDetailModal: false,
      selectedVehicle: null,
      editingVehicle: null,
      isSubmitting: false,

      // Vehicle stats
      vehicleStats: {
        total: 3,
        approved: 2,
        pending: 1
      },

      // New vehicle data
      newVehicle: {
        licensePlate: '',
        type: 'car',
        brand: '',
        model: '',
        color: '#000000',
        colorName: 'Đen',
        year: new Date().getFullYear(),
        documents: [],
        note: ''
      },

      // Renew vehicle data
      renewVehicleData: {
        id: '',
        licensePlate: '',
        type: '',
        currentExpiryDate: '',
        period: '3',
        paymentMethod: 'bank'
      },

      // Delete vehicle data
      deleteVehicleData: {},

      // Sample vehicles data
      vehicles: [
        {
          id: 'VEH-001',
          licensePlate: '30A-12345',
          type: 'car',
          brand: 'Toyota',
          model: 'Camry',
          color: '#000000',
          colorName: 'Đen',
          year: 2020,
          registrationDate: '15/01/2023',
          expiryDate: '15/01/2024',
          status: 'approved',
          documents: [
            { name: 'cavet_xe.jpg' },
            { name: 'dang_kiem.pdf' }
          ],
          note: 'Xe chính chủ'
        },
        {
          id: 'VEH-002',
          licensePlate: '30F-54321',
          type: 'car',
          brand: 'Honda',
          model: 'CR-V',
          color: '#FFFFFF',
          colorName: 'Trắng',
          year: 2021,
          registrationDate: '20/02/2023',
          expiryDate: '20/05/2023',
          status: 'approved',
          documents: [
            { name: 'cavet_xe.jpg' },
            { name: 'dang_kiem.pdf' }
          ],
          note: ''
        },
        {
          id: 'VEH-003',
          licensePlate: '29P2-12345',
          type: 'motorbike',
          brand: 'Honda',
          model: 'SH',
          color: '#FF0000',
          colorName: 'Đỏ',
          year: 2022,
          registrationDate: '10/04/2023',
          expiryDate: '10/07/2023',
          status: 'pending',
          documents: [
            { name: 'cavet_xe.jpg' }
          ],
          note: 'Xe mới mua'
        }
      ],
      loai_xe: []
    }
  },
  computed: {
    filteredVehicles() {
      if (!this.searchQuery) return this.vehicles
      return this.vehicles.filter(vehicle =>
        vehicle.bien_so_xe.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
        (vehicle.ten_loai_xe && vehicle.ten_loai_xe.toLowerCase().includes(this.searchQuery.toLowerCase())) ||
        (vehicle.ho_va_ten && vehicle.ho_va_ten.toLowerCase().includes(this.searchQuery.toLowerCase()))
      )
    }
  },
  mounted() {
    this.fetchLoaiXe()
    this.getXe()
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('vi-VN')
    },
    getXe() {
      baseRequestUser.get('user/lay-du-lieu-xe').then((res) => {
        this.vehicles = res.data.data
      })
    },
    async fetchLoaiXe() {
      try {
        const res = await baseRequestUser.get('user/lay-du-lieu-loai-xe')
        this.loai_xe = res.data.data
      } catch (e) {
        this.errorMsg = 'Không lấy được danh sách loại xe'
      }
    },
    // Get vehicle icon
    getVehicleIcon(type) {
      const iconMap = {
        'car': Car,
        'motorcycle': Bike,
        'bicycle': Bike
      }
      return iconMap[type] || Car
    },

    // Get vehicle type text
    getVehicleTypeText(type) {
      const typeMap = {
        'car': 'Ô tô',
        'motorcycle': 'Xe máy',
        'bicycle': 'Xe đạp'
      }
      return typeMap[type] || type
    },

    // Get status text
    getStatusText(status) {
      const statusMap = {
        1: 'Đã duyệt',
        0: 'Chờ duyệt',
        2: 'Từ chối'
      }
      return statusMap[status] || status
    },

    // Check if expiry date is soon (within 30 days)
    isExpiringSoon(expiryDate) {
      const today = new Date()
      const expiry = new Date(expiryDate.split('/').reverse().join('-'))
      const diffTime = expiry - today
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

      return diffDays <= 30 && diffDays > 0
    },

    // Handle file upload
    handleFileUpload(event) {
      const files = event.target.files
      if (!files.length) return

      for (let i = 0; i < files.length; i++) {
        this.newVehicle.documents.push({
          name: files[i].name
        })
      }
    },

    // Remove document
    removeDocument(index) {
      this.newVehicle.documents.splice(index, 1)
    },

    // Add vehicle
    async addVehicle() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequestUser.post("user/dang-ky-xe", this.newVehicle)
        .then((response) => {
          if (response.data.status) {
            this.getXe()
            this.showAddVehicleModal = false
            this.newVehicle = {}
            this.isSubmitting = false
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError(response.data.message)
          }
          this.isSubmitting = false
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },

    // View vehicle details
    viewVehicle(vehicle) {
      this.selectedVehicle = { ...vehicle }
      this.showDetailModal = true
    },

    // Edit vehicle
    editVehicle(vehicle) {
      this.editingVehicle = { ...vehicle }
      this.showEditVehicleModal = true
      if (this.selectedVehicle) this.selectedVehicle = null
    },

    handleEditFileUpload(event) {
      const files = event.target.files
      if (!files.length) return

      for (let i = 0; i < files.length; i++) {
        this.editingVehicle.documents.push({
          name: files[i].name
        })
      }
    },

    removeEditDocument(index) {
      this.editingVehicle.documents.splice(index, 1)
    },

    updateVehicle() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequestUser.post("user/cap-nhat-xe", this.editingVehicle)
        .then((response) => {
          if (response.data.status) {
            this.getXe()
            this.showEditVehicleModal = false
            this.editingVehicle = {}
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError(response.data.message)
          }
          this.isSubmitting = false
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },

    // Show renew modal
    showRenewModal(vehicle) {
      this.renewVehicleData = {
        id: vehicle.id,
        licensePlate: vehicle.licensePlate,
        type: vehicle.type,
        currentExpiryDate: vehicle.expiryDate,
        period: '3',
        paymentMethod: 'bank'
      }

      this.showRenewVehicleModal = true
      if (this.selectedVehicle) this.selectedVehicle = null
    },

    // Calculate renewal fee
    calculateRenewalFee(period, type) {
      const baseRate = type === 'car' ? 500000 : 200000 // VND per month
      return baseRate * parseInt(period)
    },

    // Renew vehicle
    async renewVehicle() {
      this.isSubmitting = true
      try {
        const res = await baseRequestUser.post('user/xe/gia-hạn', this.renewVehicleData)
        if (res.data.status) {
          this.successMsg = 'Gia hạn đăng ký xe thành công!'
          const vehicleIndex = this.vehicles.findIndex(v => v.id === this.renewVehicleData.id)
          if (vehicleIndex !== -1) {
            this.vehicles[vehicleIndex].expiryDate = res.data.data.newExpiryDate
          }
          this.renewVehicleData = {
            id: '',
            licensePlate: '',
            type: '',
            currentExpiryDate: '',
            period: '3',
            paymentMethod: 'bank'
          }
          this.showRenewVehicleModal = false
        } else {
          this.errorMsg = res.data.message || 'Có lỗi xảy ra khi gia hạn đăng ký xe'
        }
      } catch (e) {
        this.errorMsg = 'Có lỗi xảy ra khi gia hạn đăng ký xe'
      } finally {
        this.isSubmitting = false
      }
    },

    // Show delete confirmation
    showDeleteConfirm(vehicle) {
      this.deleteVehicleData = vehicle
      this.showDeleteConfirmModal = true
    },

    // Delete vehicle
    async deleteVehicle() {
      try {
        this.isSubmitting = true
        const res = await baseRequestUser.delete(`user/xe/${this.deleteVehicleData.id}`)
        if (res.data.status) {
          this.successMsg = 'Xóa xe thành công'
          const index = this.vehicles.findIndex(v => v.id === this.deleteVehicleData.id)
          if (index !== -1) {
            this.vehicles.splice(index, 1)
          }
          this.showDeleteConfirmModal = false
        } else {
          this.errorMsg = res.data.message || 'Có lỗi xảy ra khi xóa xe'
        }
      } catch (e) {
        this.errorMsg = 'Có lỗi xảy ra khi xóa xe'
      } finally {
        this.isSubmitting = false
      }
    },

    // Format currency
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
    },

    closeDetailModal() {
      this.showDetailModal = false
      this.selectedVehicle = null
    }
  }
}
</script>