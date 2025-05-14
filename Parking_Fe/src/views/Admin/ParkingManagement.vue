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
  
      <!-- Check Out Modal -->
      <a-modal 
        v-model:open="showCheckOutModal" 
        title="Xác nhận ghi nhận xe ra" 
        centered 
        width="400px"
        @ok="handleCheckOutSubmit"
      >
        <template #footer>
          <a-button key="back" @click="showCheckOutModal = false">Hủy</a-button>
          <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleCheckOutSubmit">
            {{ isSubmitting ? 'Đang xử lý...' : 'Xác nhận' }}
          </a-button>
        </template>
        <div class="text-center">
          <p class="text-lg">Bạn có chắc chắn muốn ghi nhận xe ra khỏi vị trí này?</p>
          <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <p><strong>Vị trí:</strong> {{ selectedSpot?.area }} - {{ selectedSpot?.thu_tu }}</p>
            <p><strong>Biển số:</strong> {{ selectedSpot?.license_plate || 'N/A' }}</p>
          </div>
        </div>
      </a-modal>
  
      <!-- Reserve Modal -->
      <a-modal 
        v-model:open="showReserveModal" 
        title="Đặt trước vị trí" 
        centered 
        width="500px"
        @ok="handleReserveSubmit"
      >
        <template #footer>
          <a-button key="back" @click="showReserveModal = false">Hủy</a-button>
          <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleReserveSubmit">
            {{ isSubmitting ? 'Đang xử lý...' : 'Xác nhận' }}
          </a-button>
        </template>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên người đặt *</label>
            <input 
              type="text" 
              v-model="reserveForm.name"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập tên người đặt"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời gian đặt *</label>
            <input 
              type="datetime-local" 
              v-model="reserveForm.time"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            />
          </div>
        </div>
      </a-modal>
  
      <!-- Recent Entries and Exits -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <div>
            <h2 class="text-lg font-medium">Lịch sử ra vào gần đây</h2>
            <p class="text-sm text-gray-500">Các lượt xe ra vào mới nhất</p>
          </div>
          <router-link to="/history" class="text-sm text-blue-600 hover:underline">
            Xem tất cả
          </router-link>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vị trí</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian đỗ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phí</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="entry in filteredRecentEntries" :key="entry.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ entry.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ entry.time }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': entry.type === 'in',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': entry.type === 'out'
                    }">
                    {{ entry.type === 'in' ? 'Vào' : 'Ra' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ entry.licensePlate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ entry.spot }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ entry.duration || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ entry.fee ? formatCurrency(entry.fee) : '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="openHistoryDetailModal(entry)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                      <Printer class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- History Detail Modal -->
      <a-modal 
        v-model:open="showHistoryDetailModal" 
        title="Chi tiết lượt ra/vào" 
        centered 
        width="500px"
        @cancel="closeHistoryDetailModal"
      >
        <div v-if="selectedHistoryEntry" class="space-y-4">
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Biển số xe</p>
              <p class="font-medium">{{ selectedHistoryEntry.licensePlate }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại</p>
              <p class="font-medium">{{ selectedHistoryEntry.type === 'in' ? 'Vào' : 'Ra' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Vị trí</p>
              <p class="font-medium">{{ selectedHistoryEntry.spot }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Thời gian</p>
              <p class="font-medium">{{ selectedHistoryEntry.time }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Thời gian đỗ</p>
              <p class="font-medium">{{ selectedHistoryEntry.duration || '-' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Phí</p>
              <p class="font-medium">{{ selectedHistoryEntry.fee ? formatCurrency(selectedHistoryEntry.fee) : '-' }}</p>
            </div>
          </div>
        </div>
        <template #footer>
          <a-button @click="closeHistoryDetailModal">Đóng</a-button>
        </template>
      </a-modal>
    </div>
  </template>
  
  <script>
  import { 
    Search, 
    RefreshCcw, 
    LogIn, 
    LogOut, 
    Car, 
    X, 
    Eye, 
    Printer 
  } from 'lucide-vue-next'
  import { Modal, Button } from 'ant-design-vue'
  import baseRequest from '../../core/baseRequest'
  import { useNotificationStore } from '../../stores/notication'
  export default {
    name: 'ParkingManagement',
    components: {
      Search,
      RefreshCcw,
      LogIn,
      LogOut,
      Car,
      X,
      Eye,
      Printer,
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
        recentActivities: [
          { title: 'Xe vào: 30A-12345', time: '10:25', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' },
          { title: 'Xe ra: 30F-54321', time: '10:15', icon: LogOut, iconBg: 'bg-blue-100', iconColor: 'text-blue-600' },
          { title: 'Xe vào: 29P2-12345', time: '09:45', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' },
          { title: 'Xe ra: 30K1-65432', time: '09:15', icon: LogOut, iconBg: 'bg-blue-100', iconColor: 'text-blue-600' },
          { title: 'Xe vào: 30A-56789', time: '08:45', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' }
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
        chitietBaiXe: []
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
        baseRequest.post("admin/ra-vao-bai/ghi-nhan-xe-vao", payload)
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
        this.selectedSpot = { ...spot }
        this.showSpotDetailModal = true
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
            'ho_va_ten': this.checkInForm.owner,
            'so_dien_thoai': this.checkInForm.phone,
            'id_vi_tri_trong_bai': this.checkInForm.id_trong_bai
          }
          const res = await baseRequest.post('admin/ra-vao-bai/ghi-nhan-xe-vao', payload)
          if (res.data.status) {
            await this.getChitietBaiXe()
            this.showCheckInModal = false
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
            bien_so_xe: this.selectedSpot.license_plate
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
          var errors = Object.values(err.response.data.errors)
          notificationStore.showError(errors[0])
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
      }
    }
  }
  </script>