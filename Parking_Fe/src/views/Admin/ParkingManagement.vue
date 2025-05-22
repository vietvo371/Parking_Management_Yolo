<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý ra vào bãi</h1>
        <div class="flex items-center space-x-2">
          <div class="relative">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="text" 
              placeholder="Tìm kiếm biển số..." 
              class="pl-9 h-10 w-full sm:w-[300px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="searchQuery"
            />
          </div>
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <RefreshCcw class="mr-2 h-4 w-4" />
            Làm mới
          </button>
        </div>
      </div>
  
  
      <!-- Parking Map -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <div>
            <h2 class="text-lg font-medium">Sơ đồ bãi đỗ xe</h2>
            <p class="text-sm text-gray-500">Nhấp vào vị trí để xem chi tiết</p>
          </div>
          <div class="flex items-center space-x-2">
            <select 
              v-model="selectedArea" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="all">Tất cả khu vực</option>
              <option v-for="area in parkingAreas" :key="area" :value="area">
                {{ area }}
              </option>
            </select>
            <select 
              v-model="selectedVehicleType" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="all">Tất cả loại xe</option>
              <option value="car">Ô tô</option>
              <option value="motorbike">Xe máy</option>
            </select>
          </div>
        </div>
        <div class="p-4">
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Dynamic Parking Areas -->
            <div v-for="area in parkingAreas" :key="area" 
                 v-if="selectedArea === 'all' || selectedArea === area" 
                 class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <h3 class="text-md font-medium mb-3">{{ area }} - Ô tô</h3>
              <div class="grid grid-cols-5 gap-2">
                <div 
                  v-for="spot in parkingSpots[area]?.car || []" 
                  :key="`${area}-car-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.thu_tu }}
                </div>
              </div>
              
              <h3 class="text-md font-medium mb-3 mt-4">{{ area }} - Xe máy</h3>
              <div class="grid grid-cols-8 gap-1">
                <div 
                  v-for="spot in parkingSpots[area]?.motorbike || []" 
                  :key="`${area}-motorbike-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.thu_tu }}
                </div>
              </div>
            </div>
          </div>
          
          <!-- Legend -->
          <div class="mt-6 flex flex-wrap gap-4 justify-center">
            <div class="flex items-center">
              <div class="w-4 h-4 bg-gray-100 border border-gray-300 dark:bg-gray-800 dark:border-gray-700 rounded-sm mr-2"></div>
              <span class="text-sm">Trống</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-blue-100 border border-blue-300 dark:bg-blue-900 dark:border-blue-700 rounded-sm mr-2"></div>
              <span class="text-sm">Đã đỗ (cư dân)</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-orange-100 border border-orange-300 dark:bg-orange-900 dark:border-orange-700 rounded-sm mr-2"></div>
              <span class="text-sm">Đã đỗ (khách)</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-red-100 border border-red-300 dark:bg-red-900 dark:border-red-700 rounded-sm mr-2"></div>
              <span class="text-sm">Không khả dụng</span>
            </div>
            <div class="flex items-center">
              <div class="w-4 h-4 bg-purple-100 border border-purple-300 dark:bg-purple-900 dark:border-purple-700 rounded-sm mr-2"></div>
              <span class="text-sm">Đã đặt trước</span>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Selected Spot Details Modal -->
      <a-modal 
        v-model:open="showSpotDetailModal" 
        title="Chi tiết vị trí đỗ xe" 
        centered 
        width="500px"
        @cancel="selectedSpot = null"
      >
        <div v-if="selectedSpot" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Khu vực</p>
              <p class="font-medium">{{ selectedSpot.area }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Vị trí</p>
              <p class="font-medium">{{ selectedSpot.thu_tu }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại xe</p>
              <p class="font-medium">{{ selectedSpot.type === 'car' ? 'Ô tô' : 'Xe máy' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Trạng thái</p>
              <p class="font-medium" :class="{
                'text-green-600': selectedSpot.status === 'available',
                'text-blue-600': selectedSpot.status === 'occupied',
                'text-orange-600': selectedSpot.status === 'reserved'
              }">
                {{ getStatusText(selectedSpot.status) }}
              </p>
            </div>
          </div>
          
          <div v-if="selectedSpot.status === 'occupied'">
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
              <h4 class="font-medium mb-2">Thông tin xe</h4>
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Biển số xe</p>
                  <p class="font-medium">{{ selectedSpot.license_plate || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Loại</p>
                  <p class="font-medium">{{ selectedSpot.vehicle?.model || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Chủ xe</p>
                  <p class="font-medium">{{ selectedSpot.vehicle?.owner || 'N/A' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Thời gian vào</p>
                  <p class="font-medium">{{ selectedSpot.vehicle?.entryTime || 'N/A' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button v-if="selectedSpot?.status === 'occupied'" type="primary" @click="openCheckOutModal">
              Ghi nhận xe ra
            </a-button>
            <a-button v-if="selectedSpot?.status === 'available'" type="primary" @click="openCheckInModal">
              Ghi nhận xe vào
            </a-button>
            <a-button v-if="selectedSpot?.status === 'available'" @click="openReserveModal">
              Đặt trước
            </a-button>
            <!-- <a-button @click="selectedSpot = null">Đóng</a-button> -->
          </div>
        </template>
      </a-modal>
  
      <!-- Check In Modal -->
      <a-modal 
        v-model:open="showCheckInModal" 
        title="Ghi nhận xe vào" 
        centered 
        width="500px"
        @ok="handleCheckInSubmit"
      >
        <template #footer>
          <a-button key="back" @click="showCheckInModal = false">Hủy</a-button>
          <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleCheckInSubmit">
            {{ isSubmitting ? 'Đang xử lý...' : 'Xác nhận' }}
          </a-button>
        </template>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ tên *</label>
            <input 
              type="text" 
              v-model="checkInForm.owner"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập họ tên"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại *</label>
            <input 
              type="text" 
              v-model="checkInForm.phone"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập số điện thoại"
            />
          </div>
        </div>
      </a-modal>
  
      <!-- Scan License Plate Modal for Check In -->
      <a-modal 
        v-model:open="showScanModal" 
        title="Quét biển số xe vào" 
        centered 
        width="800px"
        @cancel="closeScanModal"
      >
        <div class="space-y-6">
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

          <div v-if="selectedSpot" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h4 class="font-medium mb-2">Thông tin vị trí</h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Khu vực:</p>
                <p class="font-medium">{{ selectedSpot.area }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Vị trí:</p>
                <p class="font-medium">{{ selectedSpot.thu_tu }}</p>
              </div>
            </div>
          </div>
        </div>
      </a-modal>
  
      <!-- Check Out Modal -->
      <a-modal 
        v-model:open="showCheckOutModal" 
        title="Xác nhận xe ra" 
        centered 
        width="800px"
        @cancel="closeCheckOutModal"
      >
        <div class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Upload area -->
            <div class="space-y-4">
              <div 
                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                @click="triggerFileInputCheckout"
                :class="{'border-blue-500': isDraggingCheckout}"
                @dragover.prevent="isDraggingCheckout = true"
                @dragleave.prevent="isDraggingCheckout = false"
                @drop.prevent="handleFileDropCheckout"
              >
                <input
                  type="file"
                  ref="fileInputCheckout"
                  class="hidden"
                  accept="image/*"
                  @change="handleFileSelectionCheckout"
                />
                <div v-if="!previewImageCheckout" class="space-y-2">
                  <Upload class="h-12 w-12 mx-auto text-gray-400" />
                  <p class="text-sm text-gray-500">Kéo thả ảnh hoặc click để chọn ảnh</p>
                  <p class="text-xs text-gray-400">Hỗ trợ: JPG, PNG, JPEG</p>
                </div>
                <div v-else class="relative">
                  <img 
                    :src="previewImageCheckout" 
                    alt="Preview" 
                    class="max-h-64 mx-auto object-contain rounded"
                  />
                  <button 
                    @click.stop="clearImageCheckout" 
                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                  >
                    <X class="h-4 w-4" />
                  </button>
                </div>
              </div>
              
              <div class="flex space-x-2">
                <button 
                  @click="scanLicensePlateCheckout" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex-1 flex items-center justify-center"
                  :disabled="!previewImageCheckout || isScanningCheckout"
                >
                  <span v-if="isScanningCheckout">Đang quét...</span>
                  <span v-else>Quét biển số</span>
                </button>
              </div>
            </div>
            
            <!-- Results area -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 space-y-4">
              <h3 class="text-lg font-medium mb-2">Kết quả</h3>
              
              <div v-if="scanResultCheckout" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số đã quét</label>
                  <div class="flex items-center space-x-2">
                    <input 
                      type="text" 
                      v-model="detectedPlateCheckout" 
                      class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md flex-1"
                      :readonly="!scanErrorCheckout"
                    />
                    <span class="text-xs font-medium" :class="scanErrorCheckout ? 'text-red-500' : 'text-green-500'">
                      {{ scanErrorCheckout ? '(Không chính xác)' : '(Đã xác nhận)' }}
                    </span>
                  </div>
                </div>
                
                <div v-if="scanErrorCheckout">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nhập lại biển số</label>
                  <input 
                    type="text" 
                    v-model="manualPlateCheckout" 
                    placeholder="Ví dụ: 30A-12345" 
                    class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md w-full"
                  />
                </div>
                
                <div class="pt-2">
                  <button 
                    @click="submitCheckoutLicensePlate" 
                    class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    :disabled="isSubmitting || (scanErrorCheckout && !manualPlateCheckout)"
                  >
                    {{ isSubmitting ? 'Đang gửi...' : 'Cho phép ra bãi' }}
                  </button>
                </div>
              </div>
              
              <div v-else-if="isScanningCheckout" class="flex items-center justify-center h-40">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
              </div>
              
              <div v-else class="text-center text-gray-500 py-10">
                Tải lên ảnh và nhấn quét để xác định biển số xe
              </div>
            </div>
          </div>

          <div v-if="selectedSpot" class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h4 class="font-medium mb-2">Thông tin vị trí</h4>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Khu vực:</p>
                <p class="font-medium">{{ selectedSpot.area }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Vị trí:</p>
                <p class="font-medium">{{ selectedSpot.thu_tu }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Biển số xe hiện tại:</p>
                <p class="font-medium">{{ selectedSpot.bien_so_xe || 'N/A' }}</p>
              </div>
            </div>
          </div>
        </div>
      </a-modal>
    </div>
  </template>
  
  <script>
  import { 
    Search, 
    RefreshCcw,
    Eye,
    Printer,
    Upload,
    X
  } from 'lucide-vue-next'
  import { Modal, Button } from 'ant-design-vue'
  import baseRequest from '../../core/baseRequest'
  import { useNotificationStore } from '../../stores/notication'
  export default {
    name: 'ParkingManagement',
    components: {
      Search,
      RefreshCcw,
      Eye,
      Printer,
      Upload,
      X,
      [Modal.name]: Modal,
      [Button.name]: Button
    },
    data() {
      return {
        searchQuery: '',
        selectedArea: 'all',
        selectedVehicleType: 'all',
        selectedSpot: null,
        showSpotDetailModal: false,
        showCheckInModal: false,
        showCheckOutModal: false,
        showReserveModal: false,
        showHistoryDetailModal: false,
        showScanModal: false,
        isSubmitting: false,
        checkInForm: {
          owner: '',
          phone: '',
          id_trong_bai: ''
        },
        reserveForm: {
          name: '',
          time: ''
        },
        parkingStatus: {
          car: { total: 0, used: 0 },
          motorbike: { total: 0, used: 0 },
          visitor: { total: 0, used: 0 }
        },
        todayStats: {
          entries: 85,
          entriesChange: 12,
          exits: 65,
          exitsChange: 8,
          avgTime: 45,
          occupancyRate: 75,
          occupancyChange: 5
        },
        is_vao_bai: false,
        recentActivities: [
          // { title: 'Xe vào: 30A-12345', time: '10:25', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' },
          // { title: 'Xe ra: 30F-54321', time: '10:15', icon: LogOut, iconBg: 'bg-blue-100', iconColor: 'text-blue-600' },
          // { title: 'Xe vào: 29P2-12345', time: '09:45', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' },
          // { title: 'Xe ra: 30K1-65432', time: '09:15', icon: LogOut, iconBg: 'bg-blue-100', iconColor: 'text-blue-600' },
          // { title: 'Xe vào: 30A-56789', time: '08:45', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' }
        ],
        parkingSpots: {},
        recentEntries: [
          { id: 'ENT-001', time: '10:25', type: 'in', licensePlate: '30A-12345', spot: 'A-C05', duration: null, fee: null },
          { id: 'ENT-002', time: '10:15', type: 'out', licensePlate: '30F-54321', spot: 'B-C12', duration: '2h 30m', fee: 20000 },
          { id: 'ENT-003', time: '09:45', type: 'in', licensePlate: '29P2-12345', spot: 'C-M08', duration: null, fee: null },
          { id: 'ENT-004', time: '09:30', type: 'in', licensePlate: '30H-98765', spot: 'A-C18', duration: null, fee: null },
          { id: 'ENT-005', time: '09:15', type: 'out', licensePlate: '30K1-65432', spot: 'A-M22', duration: '1h 45m', fee: 10000 },
          { id: 'ENT-006', time: '08:45', type: 'in', licensePlate: '30A-56789', spot: 'B-C08', duration: null, fee: null },
          { id: 'ENT-007', time: '08:30', type: 'out', licensePlate: '30H-98765', spot: 'C-C15', duration: '12h 15m', fee: 50000 },
          { id: 'ENT-008', time: '08:15', type: 'in', licensePlate: '29Y2-54321', spot: 'A-M15', duration: null, fee: null }
        ],
        parkingAreas: [],
        chitietBaiXe: [],
        previewImage: null,
        isDragging: false,
        isScanning: false,
        scanResult: null,
        scanError: false,
        detectedPlate: '',
        manualPlate: '',
        // New data properties for checkout scanning
        previewImageCheckout: null,
        isDraggingCheckout: false,
        isScanningCheckout: false,
        scanResultCheckout: null,
        scanErrorCheckout: false,
        detectedPlateCheckout: '',
        manualPlateCheckout: '',
      }
    },
    mounted() {
      this.getParkingSpots();
      this.getChitietBaiXe();
    },
    computed: {
      filteredParkingSpots() {
        if (this.selectedArea === 'all') {
          return this.parkingSpots;
        }
        const filtered = {};
        if (this.parkingSpots[this.selectedArea]) {
          filtered[this.selectedArea] = this.parkingSpots[this.selectedArea];
        }
        return filtered;
      },
      filteredRecentEntries() {
        if (!this.searchQuery) {
          return this.recentEntries;
        }
        const query = this.searchQuery.toLowerCase();
        return this.recentEntries.filter(entry => 
          entry.licensePlate.toLowerCase().includes(query)
        );
      }
    },
    methods: {
      getChitietBaiXe() {
        const notificationStore = useNotificationStore();
        baseRequest.get('admin/chi-tiet-bai-xe/lay-du-lieu')
          .then((res) => {
            this.chitietBaiXe = res.data.data;
            this.mapChitietBaiXeToParkingSpots();
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          });
      },
      
      getParkingSpots() {
        const notificationStore = useNotificationStore();
        baseRequest.get('admin/bai-xe/lay-du-lieu')
          .then((res) => {
            const data = res.data.data;
            console.log(data);
            this.parkingSpots = {};
            this.parkingAreas = [];
            
            const grouped = {};
            data.forEach(spot => {
              const areaName = spot.ten_bai;
              const type = spot.loai_xe === 'Ô-Tô' ? 'car' : 'motorbike';
              if (!grouped[areaName]) grouped[areaName] = { car: [], motorbike: [] };
              grouped[areaName][type].push(spot);
            });
            
            Object.keys(grouped).forEach(areaName => {
              grouped[areaName].car = this.generateParkingSpots(grouped[areaName].car, areaName, 'car');
              grouped[areaName].motorbike = this.generateParkingSpots(grouped[areaName].motorbike, areaName, 'motorbike');
            });
            this.parkingSpots = grouped;
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          });
      },
      ghiNhanXeVao() {
        const notificationStore = useNotificationStore();
        var payload = {
          'ho_va_ten': this.checkInForm.owner,
          'so_dien_thoai': this.checkInForm.phone,
          'id_vi_tri_trong_bai': this.checkInForm.id_trong_bai
        }
        baseRequest.post("admin/ra-vao-bai/them-du-lieu", payload)
          .then((res) => {
            if (res.data.status) {
              this.checkInForm = {
                licensePlate: "",
                owner: "",
                phone: "",
              }
              this.getLoaiXe();
              notificationStore.showSuccess(res.data.message);
            }
            else {
              notificationStore.showError(res.data.message);
            }
            this.open = false;
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
            this.isSubmitting = false
          });
      },
      formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
      },
      generateParkingSpots(spots, areaName, type) {
        return spots.map(spot => ({
          id: spot.thu_tu,
          area: areaName,
          type: type,
          status: spot.trang_thai === 0 ? 'available' : (spot.trang_thai === 1 ? 'reserved' : 'occupied'),
          ...spot
        }));
      },
      getSpotClass(spot) {
        if (spot.status === 'available') {
          return 'bg-gray-100 border-gray-300 text-gray-800 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300'
        } else if (spot.status === 'occupied') {
          return 'bg-blue-100 border-blue-300 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300'
        } else if (spot.status === 'reserved') {
          return 'bg-orange-100 border-orange-300 text-orange-800 dark:bg-orange-900 dark:border-orange-700 dark:text-orange-300'
        }
      },
      getStatusText(status) {
        switch (status) {
          case 'available': return 'Trống'
          case 'occupied': return 'Đã có xe đậu'
          case 'reserved': return 'Đã đặt trước'
          default: return status
        }
      },
      selectSpot(spot) {
        if (spot.status === 'available') {
          this.selectedSpot = { ...spot }
          this.showScanModal = true
          this.clearImage()
        } else {
          this.selectedSpot = { ...spot }
          this.showSpotDetailModal = true
        }
      },
      openCheckInModal() {
        this.checkInForm = {
          owner: '',
          phone: '',
          id_trong_bai: this.selectedSpot.id
        }
        this.showCheckInModal = true
      },
      openCheckOutModal() {
        this.showCheckOutModal = true
      },
      openReserveModal() {
        this.reserveForm = { name: '', time: '' }
        this.showReserveModal = true
      },
      openHistoryDetailModal(entry) {
        this.selectedHistoryEntry = entry
        this.showHistoryDetailModal = true
      },
      closeHistoryDetailModal() {
        this.showHistoryDetailModal = false
        this.selectedHistoryEntry = null
      },
      async handleCheckInSubmit() {
        if (!this.checkInForm.owner || !this.checkInForm.phone) {
          const notificationStore = useNotificationStore()
          notificationStore.showError('Vui lòng điền đầy đủ thông tin')
          return
        }
        this.isSubmitting = true
        const notificationStore = useNotificationStore()
        try {
          var payload = {
            'bien_so_xe': this.detectedPlate || this.manualPlate,
            'ho_va_ten': this.checkInForm.owner,
            'so_dien_thoai': this.checkInForm.phone,
            'id_vi_tri_trong_bai': this.checkInForm.id_trong_bai
          }
          const res = await baseRequest.post('admin/ra-vao-bai/ghi-nhan-xe-vao', payload)
          if (res.data.status) {
            await this.getChitietBaiXe()
            this.showCheckInModal = false
            this.showSpotDetailModal = false
            this.checkInForm = {
              owner: '',
              phone: '',
              id_trong_bai: ''
            }
            notificationStore.showSuccess(res.data.message)
          } else {
            notificationStore.showError(res.data.message)
          }
        } catch (err) {
          var errors = Object.values(err.response.data.errors)
          notificationStore.showError(errors[0])
        }
        this.isSubmitting = false
      },
      async handleCheckOutSubmit() {
        if (!this.selectedSpot) return
        this.isSubmitting = true
        const notificationStore = useNotificationStore()
        try {
          const res = await baseRequest.post('admin/ra-vao-bai/ghi-nhan-xe-ra', {
            id_vi_tri_trong_bai: this.selectedSpot.id,
            bien_so_xe: this.selectedSpot.license_plate,
            id_khach_hang: this.selectedSpot.is_khach_hang,
            is_cu_dan: this.selectedSpot.is_cu_dan
          })
          if (res.data.status) {
            await this.getChitietBaiXe()
            this.showCheckOutModal = false
            this.showSpotDetailModal = false
            notificationStore.showSuccess(res.data.message)
          } else {
            notificationStore.showError(res.data.message)
          }
        } catch (err) {
            var errors = Object.values(err.response.data.errors);
            notificationStore.showError(errors[0]);
        }
        this.isSubmitting = false
      },
      async handleReserveSubmit() {
        if (!this.reserveForm.name || !this.reserveForm.time) return
        this.isSubmitting = true
        const notificationStore = useNotificationStore()
        try {
          const res = await baseRequest.post('admin/ra-vao-bai/dat-truoc', {
            name: this.reserveForm.name,
            time: this.reserveForm.time,
            spotId: this.selectedSpot.id
          })
          if (res.data.status) {
            this.getParkingSpots()
            this.showReserveModal = false
            notificationStore.showSuccess(res.data.message)
          } else {
            notificationStore.showError(res.data.message)
          }
        } catch (err) {
          var errors = Object.values(err.response.data.errors)
          notificationStore.showError(errors[0])
        }
        this.isSubmitting = false
      },
      mapChitietBaiXeToParkingSpots() {
        this.parkingSpots = {};
        this.parkingAreas = [];

        this.chitietBaiXe.forEach(spot => {
          const areaName = spot.ten_bai;
          const type = spot.loai_xe === 'O-To' ? 'car' : 'motorbike';

          if (!this.parkingSpots[areaName]) {
            this.parkingSpots[areaName] = { car: [], motorbike: [] };
            this.parkingAreas.push(areaName);
          }

          this.parkingSpots[areaName][type].push({
            id: spot.thu_tu,
            area: areaName,
            type: type,
            status: spot.trang_thai === 0 ? 'available' : (spot.trang_thai === 1 ? 'reserved' : 'occupied'),
            ...spot
          });
        });
      },
      triggerFileInput() {
        this.$refs.fileInput.click()
      },
      
      handleFileSelection(event) {
        const file = event.target.files[0]
        if (file) {
          this.processSelectedFile(file)
        }
      },
      
      handleFileDrop(event) {
        this.isDragging = false
        const file = event.dataTransfer.files[0]
        if (file && file.type.startsWith('image/')) {
          this.processSelectedFile(file)
        }
      },
      
      processSelectedFile(file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.previewImage = e.target.result
          this.scanResult = null
          this.detectedPlate = ''
          this.manualPlate = ''
          this.scanError = false
        }
        reader.readAsDataURL(file)
      },
      
      clearImage() {
        this.previewImage = null
        this.scanResult = null
        this.detectedPlate = ''
        this.manualPlate = ''
        this.scanError = false
        if (this.$refs.fileInput) {
          this.$refs.fileInput.value = null
        }
      },

      closeScanModal() {
        this.showScanModal = false
        this.clearImage()
        this.selectedSpot = null
      },
      
      scanLicensePlate() {
        if (!this.previewImage) return
        
        this.isScanning = true
        const notificationStore = useNotificationStore()
        
        // Create form data with the image
        const formData = new FormData()
        // Convert base64 to blob
        const blob = this.dataURLtoBlob(this.previewImage)
        formData.append('image', blob, 'license_plate.jpg')
        
        // Send to backend for scanning
        baseRequest.post("admin/cam-giam-soat/scan-bien-so", formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
          .then((response) => {
            this.isScanning = false
            if (response.data.status) {
              this.scanResult = response.data
              this.detectedPlate = response.data.plate_number || ''
              this.scanError = !response.data.is_con_han
              if (!response.data.is_con_han) {
                notificationStore.showWarning('Biển số không được nhận diện chính xác, vui lòng nhập lại')
              }
            } else {
              this.scanError = true
              notificationStore.showError(response.data.message || 'Không thể quét biển số')
            }
          })
          .catch((error) => {
            this.isScanning = false
            console.error('Scan error:', error)
            notificationStore.showError('Đã xảy ra lỗi khi quét biển số')
            this.scanResult = { error: true }
            this.scanError = true
          })
      },
      
      dataURLtoBlob(dataURL) {
        const arr = dataURL.split(',')
        const mime = arr[0].match(/:(.*?);/)[1]
        const bstr = atob(arr[1])
        let n = bstr.length
        const u8arr = new Uint8Array(n)
        
        while (n--) {
          u8arr[n] = bstr.charCodeAt(n)
        }
        
        return new Blob([u8arr], { type: mime })
      },
      
      submitLicensePlate() {
        const notificationStore = useNotificationStore()
        this.isSubmitting = true
        
        const plateNumber = this.scanError ? this.manualPlate : this.detectedPlate
        
        // Send the plate number and spot ID to backend
        baseRequest.post("admin/ra-vao-bai/ghi-nhan-xe-vao", {
          id_vi_tri_trong_bai: this.selectedSpot.id,
          bien_so_xe: plateNumber
        })
          .then((response) => {
            if (response.data.status) {
              notificationStore.showSuccess('Xe đã được ghi nhận vào bãi')
              this.closeScanModal()
              this.getChitietBaiXe() // Refresh parking data
            } else {
              notificationStore.showError(response.data.message || 'Không thể ghi nhận xe vào bãi')
            }
            this.isSubmitting = false
          })
          .catch((error) => {
            console.error('Submission error:', error)
            notificationStore.showError('Đã xảy ra lỗi khi gửi yêu cầu')
            this.isSubmitting = false
          })
      },

      // New methods for checkout license plate scanning
      triggerFileInputCheckout() {
        this.$refs.fileInputCheckout.click()
      },
      
      handleFileSelectionCheckout(event) {
        const file = event.target.files[0]
        if (file) {
          this.processSelectedFileCheckout(file)
        }
      },
      
      handleFileDropCheckout(event) {
        this.isDraggingCheckout = false
        const file = event.dataTransfer.files[0]
        if (file && file.type.startsWith('image/')) {
          this.processSelectedFileCheckout(file)
        }
      },
      
      processSelectedFileCheckout(file) {
        const reader = new FileReader()
        reader.onload = (e) => {
          this.previewImageCheckout = e.target.result
          this.scanResultCheckout = null
          this.detectedPlateCheckout = ''
          this.manualPlateCheckout = ''
          this.scanErrorCheckout = false
        }
        reader.readAsDataURL(file)
      },
      
      clearImageCheckout() {
        this.previewImageCheckout = null
        this.scanResultCheckout = null
        this.detectedPlateCheckout = ''
        this.manualPlateCheckout = ''
        this.scanErrorCheckout = false
        if (this.$refs.fileInputCheckout) {
          this.$refs.fileInputCheckout.value = null
        }
      },

      closeCheckOutModal() {
        this.showCheckOutModal = false
        this.clearImageCheckout()
        this.selectedSpot = null
      },
      
      scanLicensePlateCheckout() {
        if (!this.previewImageCheckout) return
        
        this.isScanningCheckout = true
        const notificationStore = useNotificationStore()
        
        // Create form data with the image
        const formData = new FormData()
        // Convert base64 to blob
        const blob = this.dataURLtoBlob(this.previewImageCheckout)
        formData.append('image', blob, 'license_plate_checkout.jpg')
        
        // Send to backend for scanning
        baseRequest.post("admin/cam-giam-soat/scan-bien-so", formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        })
          .then((response) => {
            this.isScanningCheckout = false
            if (response.data.status) {
              this.scanResultCheckout = response.data
              this.detectedPlateCheckout = response.data.plate_number || ''
              this.scanErrorCheckout = !response.data.is_con_han || !response.data.is_vao_bai
              
              // Check if the car is actually in the parking lot
              if (!response.data.is_vao_bai) {
                notificationStore.showError('Xe này chưa được ghi nhận vào bãi')
                return
              }
              
              if (!response.data.is_con_han) {
                notificationStore.showWarning('Biển số không được nhận diện chính xác, vui lòng nhập lại')
              }
            } else {
              this.scanErrorCheckout = true
              notificationStore.showError(response.data.message || 'Không thể quét biển số')
            }
          })
          .catch((error) => {
            this.isScanningCheckout = false
            console.error('Scan error:', error)
            notificationStore.showError('Đã xảy ra lỗi khi quét biển số')
            this.scanResultCheckout = { error: true }
            this.scanErrorCheckout = true
          })
      },
      
      submitCheckoutLicensePlate() {
        const notificationStore = useNotificationStore()
        this.isSubmitting = true
        
        const plateNumber = this.scanErrorCheckout ? this.manualPlateCheckout : this.detectedPlateCheckout
        
        // Send the plate number and spot ID to backend
        baseRequest.post("admin/ra-vao-bai/ghi-nhan-xe-ra", {
          id_vi_tri_trong_bai: this.selectedSpot.id,
          bien_so_xe: plateNumber
        })
          .then((response) => {
            if (response.data.status) {
              notificationStore.showSuccess('Xe đã được ghi nhận ra bãi')
              this.closeCheckOutModal()
              this.showSpotDetailModal = false
              this.getChitietBaiXe() // Refresh parking data
            } else {
              notificationStore.showError(response.data.message || 'Không thể ghi nhận xe ra bãi')
            }
            this.isSubmitting = false
          })
          .catch((error) => {
            console.error('Submission error:', error)
            notificationStore.showError('Đã xảy ra lỗi khi gửi yêu cầu')
            this.isSubmitting = false
          })
      },
    }
  }
  </script>