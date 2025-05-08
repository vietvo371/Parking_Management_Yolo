<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý vị trí đậu xe</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showReserveModal = true">
            <Calendar class="mr-2 h-4 w-4" />
            Đặt chỗ đỗ xe
          </button>
        </div>
      </div>
  
      <!-- Parking Stats -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Vị trí của tôi</p>
              <h3 class="text-2xl font-bold">{{ parkingStats.mySpots }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <MapPin class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Vị trí trống</p>
              <h3 class="text-2xl font-bold">{{ parkingStats.available }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <CheckCircle class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đặt trước</p>
              <h3 class="text-2xl font-bold">{{ parkingStats.reserved }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
              <Clock class="h-5 w-5 text-yellow-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng số vị trí</p>
              <h3 class="text-2xl font-bold">{{ parkingStats.total }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
              <LayoutGrid class="h-5 w-5 text-purple-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Parking Map -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-4">
          <h3 class="text-lg font-medium">Sơ đồ bãi đỗ xe</h3>
          <div class="flex items-center space-x-2">
            <select 
              v-model="selectedFloor" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option v-for="floor in floors" :key="floor.id" :value="floor.id">
                {{ floor.name }}
              </option>
            </select>
            <div class="flex items-center space-x-2">
              <div class="flex items-center">
                <div class="h-4 w-4 bg-green-500 rounded-sm mr-1"></div>
                <span class="text-xs">Trống</span>
              </div>
              <div class="flex items-center">
                <div class="h-4 w-4 bg-red-500 rounded-sm mr-1"></div>
                <span class="text-xs">Đã đỗ</span>
              </div>
              <div class="flex items-center">
                <div class="h-4 w-4 bg-yellow-500 rounded-sm mr-1"></div>
                <span class="text-xs">Đặt trước</span>
              </div>
              <div class="flex items-center">
                <div class="h-4 w-4 bg-blue-500 rounded-sm mr-1"></div>
                <span class="text-xs">Của tôi</span>
              </div>
            </div>
          </div>
        </div>
        
        <div class="relative h-[500px] border border-gray-200 dark:border-gray-700 rounded-md overflow-auto">
          <!-- Parking map would be rendered here in a real app -->
          <!-- For demo purposes, we'll create a simple grid -->
          <div class="p-4">
            <div class="flex justify-center mb-6">
              <div class="bg-gray-200 dark:bg-gray-700 p-2 rounded-md w-1/2 text-center">
                Lối vào/ra
              </div>
            </div>
            
            <div class="grid grid-cols-5 gap-2 mb-6">
              <div v-for="spot in getFloorSpots()" :key="spot.id" 
                class="aspect-[4/3] rounded-md flex items-center justify-center cursor-pointer relative"
                :class="{
                  'bg-green-500 hover:bg-green-600': spot.status === 'available',
                  'bg-red-500': spot.status === 'occupied' && !spot.isOwner,
                  'bg-yellow-500': spot.status === 'reserved' && !spot.isOwner,
                  'bg-blue-500': spot.isOwner
                }"
                @click="handleSpotClick(spot)"
              >
                <span class="text-white font-medium">{{ spot.code }}</span>
                <span v-if="spot.isOwner" class="absolute top-1 right-1 bg-white rounded-full p-0.5">
                  <User class="h-3 w-3 text-blue-600" />
                </span>
              </div>
            </div>
            
            <div class="flex justify-center">
              <div class="bg-gray-200 dark:bg-gray-700 p-2 rounded-md w-1/2 text-center">
                Thang máy & Cầu thang
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- My Parking Spots -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-medium">Vị trí đỗ xe của tôi</h3>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã vị trí</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tầng</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hạn sử dụng</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="spot in mySpots" :key="spot.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ spot.code }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ getFloorName(spot.floorId) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ spot.vehicleType === 'car' ? 'Ô tô' : 'Xe máy' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ spot.licensePlate || 'Chưa đăng ký' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': spot.status === 'available',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': spot.status === 'occupied',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': spot.status === 'reserved'
                    }">
                    {{ getStatusText(spot.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span :class="{
                    'text-red-600': isExpiringSoon(spot.expiryDate),
                    'text-green-600': !isExpiringSoon(spot.expiryDate)
                  }">{{ spot.expiryDate }}</span>
                  <button v-if="isExpiringSoon(spot.expiryDate)" class="ml-2 text-xs text-blue-600 hover:underline" @click="showRenewModal(spot)">
                    Gia hạn
                  </button>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="showSpotDetails(spot)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="showAssignVehicleModal(spot)">
                      <Car class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="mySpots.length === 0">
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                  Bạn chưa có vị trí đỗ xe nào
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- Reserve Spot Modal -->
      <a-modal v-model:open="showReserveModal" title="Đặt chỗ đỗ xe" width="520px" :bodyStyle="{padding: '28px 24px 12px 24px'}">
        <template #footer>
          <div class="flex justify-end gap-2">
            <a-button @click="showReserveModal = false">Hủy</a-button>
            <a-button type="primary" @click="reserveSpot">
              <template #icon><Calendar class="h-4 w-4 mr-1" /></template>
              Đặt chỗ
            </a-button>
          </div>
        </template>
        <a-form :model="reserveData" layout="vertical">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a-form-item label="Tầng" required class="mb-0">
              <a-select v-model:value="reserveData.floorId" size="large">
                <template #suffixIcon><LayoutGrid class="h-4 w-4 text-gray-500" /></template>
                <a-select-option v-for="floor in floors" :key="floor.id" :value="floor.id">
                  {{ floor.name }}
                </a-select-option>
              </a-select>
            </a-form-item>
            <a-form-item label="Loại xe" required class="mb-0">
              <a-select v-model:value="reserveData.vehicleType" size="large">
                <template #suffixIcon><Car class="h-4 w-4 text-gray-500" /></template>
                <a-select-option value="car">Ô tô</a-select-option>
                <a-select-option value="motorbike">Xe máy</a-select-option>
              </a-select>
            </a-form-item>
          </div>
          <a-form-item label="Xe" required class="mt-4">
            <a-select v-model:value="reserveData.vehicleId" size="large">
              <template #suffixIcon><Car class="h-4 w-4 text-gray-500" /></template>
              <a-select-option value="">Chọn xe</a-select-option>
              <a-select-option v-for="vehicle in filteredVehicles" :key="vehicle.id" :value="vehicle.id">
                <div class="flex items-center">
                  <Car class="h-4 w-4 mr-2 text-gray-500" />
                  <div>
                    <div class="font-medium">{{ vehicle.licensePlate }}</div>
                    <div class="text-xs text-gray-500">{{ vehicle.brand }} {{ vehicle.model }}</div>
                  </div>
                </div>
              </a-select-option>
            </a-select>
          </a-form-item>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a-form-item label="Ngày đặt" required class="mb-0">
              <a-date-picker v-model:value="reserveData.date" class="w-full" size="large">
                <template #suffixIcon><Calendar class="h-4 w-4 text-gray-500" /></template>
              </a-date-picker>
            </a-form-item>
            <div class="grid grid-cols-2 gap-2">
              <a-form-item label="Giờ bắt đầu" required class="mb-0">
                <a-time-picker v-model:value="reserveData.startTime" class="w-full" size="large">
                  <template #suffixIcon><Clock class="h-4 w-4 text-gray-500" /></template>
                </a-time-picker>
              </a-form-item>
              <a-form-item label="Giờ kết thúc" required class="mb-0">
                <a-time-picker v-model:value="reserveData.endTime" class="w-full" size="large">
                  <template #suffixIcon><Clock class="h-4 w-4 text-gray-500" /></template>
                </a-time-picker>
              </a-form-item>
            </div>
          </div>
          <a-form-item label="Ghi chú" class="mt-4">
            <a-textarea 
              v-model:value="reserveData.note" 
              placeholder="Nhập ghi chú (nếu có)" 
              :rows="2"
              class="resize-none"
              size="large"
            />
          </a-form-item>
          <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 px-4 py-3 mt-6 mb-2">
            <h4 class="font-semibold text-base mb-2">Thông tin đặt chỗ</h4>
            <div class="flex flex-col gap-1 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Phí đặt chỗ:</span>
                <span class="font-semibold">{{ formatCurrency(calculateReservationFee()) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Thời gian:</span>
                <span class="font-semibold">{{ calculateDuration() }}</span>
              </div>
            </div>
          </div>
        </a-form>
      </a-modal>
  
      <!-- Spot Details Modal -->
      <a-modal v-model:open="showSpotDetailsModal" title="Chi tiết vị trí đỗ xe" width="800px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="showSpotDetailsModal = false">Đóng</a-button>
            <a-button v-if="isExpiringSoon(selectedSpot?.expiryDate)" type="primary" @click="showRenewModal(selectedSpot)">
              Gia hạn
            </a-button>
            <a-button type="primary" @click="showAssignVehicleModal(selectedSpot)">
              Đăng ký xe
            </a-button>
          </div>
        </template>
        <div v-if="selectedSpot" class="space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <MapPin class="h-5 w-5 mr-2 text-blue-600" />
              <h2 class="text-xl font-bold">{{ selectedSpot.code }}</h2>
            </div>
            <a-tag :color="getStatusColor(selectedSpot.status)">
              {{ getStatusText(selectedSpot.status) }}
            </a-tag>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Tầng</p>
              <p class="font-medium">{{ getFloorName(selectedSpot.floorId) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại xe</p>
              <p class="font-medium">{{ selectedSpot.vehicleType === 'car' ? 'Ô tô' : 'Xe máy' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Kích thước</p>
              <p class="font-medium">{{ selectedSpot.size }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Hạn sử dụng</p>
              <p class="font-medium" :class="{
                'text-red-600': isExpiringSoon(selectedSpot.expiryDate),
                'text-green-600': !isExpiringSoon(selectedSpot.expiryDate)
              }">{{ selectedSpot.expiryDate }}</p>
            </div>
          </div>
          
          <div v-if="selectedSpot.licensePlate" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Thông tin xe đang đỗ</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Biển số xe</p>
                <p class="font-medium">{{ selectedSpot.licensePlate }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Thời gian vào</p>
                <p class="font-medium">{{ selectedSpot.entryTime || 'N/A' }}</p>
              </div>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Lịch sử đỗ xe gần đây</h4>
            <div class="space-y-2">
              <div v-for="(history, index) in selectedSpot.history" :key="index" class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                <div class="flex justify-between">
                  <div>
                    <p class="text-sm font-medium">{{ history.licensePlate }}</p>
                    <p class="text-xs text-gray-500">{{ history.entryTime }} - {{ history.exitTime }}</p>
                  </div>
                  <p class="text-sm">{{ history.duration }}</p>
                </div>
              </div>
              <div v-if="!selectedSpot.history || selectedSpot.history.length === 0" class="text-sm text-gray-500 text-center py-2">
                Không có lịch sử đỗ xe
              </div>
            </div>
          </div>
        </div>
      </a-modal>
  
      <!-- Assign Vehicle Modal -->
      <a-modal v-model:open="showAssignModal" title="Đăng ký xe cho vị trí" width="420px" :bodyStyle="{padding: '28px 24px 12px 24px'}">
        <template #footer>
          <div class="flex justify-end gap-2">
            <a-button @click="showAssignModal = false">Hủy</a-button>
            <a-button type="primary" @click="assignVehicle">
              <template #icon><Car class="h-4 w-4 mr-1" /></template>
              Đăng ký
            </a-button>
          </div>
        </template>
        <a-form :model="assignData" layout="vertical">
          <div class="rounded-lg border border-blue-200 dark:border-blue-700 bg-blue-50 dark:bg-blue-900/30 px-4 py-3 mb-4 flex items-center gap-3">
            <MapPin class="h-5 w-5 text-blue-600" />
            <div>
              <div class="font-semibold text-base">{{ assignData.spotCode }}</div>
              <div class="text-xs text-gray-500">{{ getFloorName(assignData.floorId) }}</div>
            </div>
          </div>
          <a-form-item label="Chọn xe" required>
            <a-select v-model:value="assignData.vehicleId" size="large">
              <template #suffixIcon><Car class="h-4 w-4 text-gray-500" /></template>
              <a-select-option value="">Chọn xe</a-select-option>
              <a-select-option v-for="vehicle in filteredVehiclesForSpot" :key="vehicle.id" :value="vehicle.id">
                <div class="flex items-center">
                  <Car class="h-4 w-4 mr-2 text-gray-500" />
                  <div>
                    <div class="font-medium">{{ vehicle.licensePlate }}</div>
                    <div class="text-xs text-gray-500">{{ vehicle.brand }} {{ vehicle.model }}</div>
                  </div>
                </div>
              </a-select-option>
            </a-select>
          </a-form-item>
          <a-form-item class="mt-2">
            <a-checkbox v-model:checked="assignData.isDefault">
              <span class="flex items-center"><Star class="h-4 w-4 mr-1 text-yellow-500" /> Đặt làm xe mặc định cho vị trí này</span>
            </a-checkbox>
          </a-form-item>
          <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/60 px-4 py-3 mt-6 mb-2">
            <h4 class="font-semibold text-base mb-2">Thông tin đăng ký</h4>
            <div class="flex flex-col gap-1 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Loại xe:</span>
                <span class="font-semibold">{{ getVehicleTypeText(assignData.vehicleType) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-500">Kích thước vị trí:</span>
                <span class="font-semibold">{{ getSpotSize(assignData.spotId) }}</span>
              </div>
            </div>
          </div>
        </a-form>
      </a-modal>
  
      <!-- Renew Spot Modal -->
      <a-modal v-model:open="showRenewSpotModal" title="Gia hạn vị trí đỗ xe" width="500px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="showRenewSpotModal = false">Hủy</a-button>
            <a-button type="primary" @click="renewSpot">Thanh toán và gia hạn</a-button>
          </div>
        </template>
        <a-form :model="renewData" layout="vertical">
          <a-form-item label="Vị trí đỗ xe">
            <p class="font-medium">{{ renewData.spotCode }}</p>
          </a-form-item>
          
          <a-form-item label="Hạn sử dụng hiện tại">
            <p class="font-medium text-red-600">{{ renewData.currentExpiryDate }}</p>
          </a-form-item>
          
          <a-form-item label="Thời hạn gia hạn" required>
            <a-select v-model:value="renewData.period">
              <a-select-option value="1">1 tháng</a-select-option>
              <a-select-option value="3">3 tháng</a-select-option>
              <a-select-option value="6">6 tháng</a-select-option>
              <a-select-option value="12">12 tháng</a-select-option>
            </a-select>
          </a-form-item>
          
          <a-form-item label="Phí gia hạn">
            <p class="font-medium text-lg">{{ formatCurrency(calculateRenewalFee(renewData.period, renewData.vehicleType)) }}</p>
          </a-form-item>
          
          <a-form-item label="Phương thức thanh toán" required>
            <a-select v-model:value="renewData.paymentMethod">
              <a-select-option value="bank">Chuyển khoản ngân hàng</a-select-option>
              <a-select-option value="momo">Ví MoMo</a-select-option>
              <a-select-option value="vnpay">VNPay</a-select-option>
              <a-select-option value="cash">Tiền mặt</a-select-option>
            </a-select>
          </a-form-item>
        </a-form>
      </a-modal>
    </div>
  </template>
  
  <script>
  import { 
    Calendar, 
    MapPin, 
    CheckCircle, 
    Clock, 
    LayoutGrid, 
    Eye, 
    Car, 
    X, 
    User, 
    Star 
  } from 'lucide-vue-next'
  import { Modal, Button, Form, Input, Select, DatePicker, TimePicker, Checkbox } from 'ant-design-vue'
  
  export default {
    name: 'ParkingSpots',
    components: {
      Calendar,
      MapPin,
      CheckCircle,
      Clock,
      LayoutGrid,
      Eye,
      Car,
      X,
      User,
      Star,
      [Modal.name]: Modal,
      [Button.name]: Button,
      [Form.name]: Form,
      [Input.name]: Input,
      [Select.name]: Select,
      [DatePicker.name]: DatePicker,
      [TimePicker.name]: TimePicker,
      [Checkbox.name]: Checkbox
    },
    data() {
      return {
        selectedFloor: 'floor-1',
        showReserveModal: false,
        showAssignModal: false,
        showRenewSpotModal: false,
        showSpotDetailsModal: false,
        selectedSpot: null,
        
        // Parking stats
        parkingStats: {
          mySpots: 2,
          available: 15,
          reserved: 3,
          total: 50
        },
        
        // Floors data
        floors: [
          { id: 'floor-1', name: 'Tầng 1' },
          { id: 'floor-2', name: 'Tầng 2' },
          { id: 'floor-3', name: 'Tầng 3' }
        ],
        
        // Sample vehicles data
        vehicles: [
          {
            id: 'VEH-001',
            licensePlate: '30A-12345',
            type: 'car',
            brand: 'Toyota',
            model: 'Camry'
          },
          {
            id: 'VEH-002',
            licensePlate: '30F-54321',
            type: 'car',
            brand: 'Honda',
            model: 'CR-V'
          },
          {
            id: 'VEH-003',
            licensePlate: '29P2-12345',
            type: 'motorbike',
            brand: 'Honda',
            model: 'SH'
          }
        ],
        
        // Sample parking spots data
        parkingSpots: [
          {
            id: 'spot-1',
            code: 'A-C01',
            floorId: 'floor-1',
            vehicleType: 'car',
            status: 'occupied',
            isOwner: true,
            licensePlate: '30A-12345',
            entryTime: '24/04/2023 - 08:30',
            expiryDate: '31/05/2023',
            size: '2.5m x 5.5m',
            history: [
              {
                licensePlate: '30A-12345',
                entryTime: '20/04/2023 - 08:30',
                exitTime: '20/04/2023 - 17:45',
                duration: '9h 15m'
              },
              {
                licensePlate: '30A-12345',
                entryTime: '21/04/2023 - 08:15',
                exitTime: '21/04/2023 - 18:00',
                duration: '9h 45m'
              }
            ]
          },
          {
            id: 'spot-2',
            code: 'B-C05',
            floorId: 'floor-1',
            vehicleType: 'car',
            status: 'available',
            isOwner: true,
            licensePlate: '',
            expiryDate: '15/05/2023',
            size: '2.5m x 5.5m',
            history: []
          },
          {
            id: 'spot-3',
            code: 'A-C02',
            floorId: 'floor-1',
            vehicleType: 'car',
            status: 'occupied',
            isOwner: false,
            licensePlate: '30B-12345',
            size: '2.5m x 5.5m'
          },
          {
            id: 'spot-4',
            code: 'A-C03',
            floorId: 'floor-1',
            vehicleType: 'car',
            status: 'reserved',
            isOwner: false,
            licensePlate: '',
            size: '2.5m x 5.5m'
          },
          {
            id: 'spot-5',
            code: 'A-C04',
            floorId: 'floor-1',
            vehicleType: 'car',
            status: 'available',
            isOwner: false,
            licensePlate: '',
            size: '2.5m x 5.5m'
          },
          {
            id: 'spot-6',
            code: 'A-M01',
            floorId: 'floor-1',
            vehicleType: 'motorbike',
            status: 'available',
            isOwner: false,
            licensePlate: '',
            size: '1.2m x 2.5m'
          },
          {
            id: 'spot-7',
            code: 'A-M02',
            floorId: 'floor-1',
            vehicleType: 'motorbike',
            status: 'occupied',
            isOwner: false,
            licensePlate: '29P1-12345',
            size: '1.2m x 2.5m'
          },
          {
            id: 'spot-8',
            code: 'A-C05',
            floorId: 'floor-2',
            vehicleType: 'car',
            status: 'available',
            isOwner: false,
            licensePlate: '',
            size: '2.5m x 5.5m'
          },
          {
            id: 'spot-9',
            code: 'A-C06',
            floorId: 'floor-2',
            vehicleType: 'car',
            status: 'available',
            isOwner: false,
            licensePlate: '',
            size: '2.5m x 5.5m'
          },
          {
            id: 'spot-10',
            code: 'A-C07',
            floorId: 'floor-2',
            vehicleType: 'car',
            status: 'available',
            isOwner: false,
            licensePlate: '',
            size: '2.5m x 5.5m'
          }
        ],
        
        // Reserve data
        reserveData: {
          floorId: 'floor-1',
          vehicleType: 'car',
          vehicleId: '',
          date: '',
          startTime: '',
          endTime: '',
          note: ''
        },
        
        // Assign vehicle data
        assignData: {
          spotId: '',
          spotCode: '',
          vehicleId: '',
          isDefault: false
        },
        
        // Renew spot data
        renewData: {
          spotId: '',
          spotCode: '',
          vehicleType: '',
          currentExpiryDate: '',
          period: '3',
          paymentMethod: 'bank'
        }
      }
    },
    computed: {
      mySpots() {
        return this.parkingSpots.filter(spot => spot.isOwner)
      },
      filteredVehicles() {
        return this.vehicles.filter(vehicle => vehicle.type === this.reserveData.vehicleType)
      },
      filteredVehiclesForSpot() {
        if (!this.assignData.spotId) return []
        
        const spot = this.parkingSpots.find(s => s.id === this.assignData.spotId)
        if (!spot) return []
        
        return this.vehicles.filter(vehicle => vehicle.type === spot.vehicleType)
      }
    },
    methods: {
      getFloorSpots() {
        return this.parkingSpots.filter(spot => spot.floorId === this.selectedFloor)
      },
      getFloorName(floorId) {
        const floor = this.floors.find(f => f.id === floorId)
        return floor ? floor.name : floorId
      },
      getStatusText(status) {
        switch (status) {
          case 'available':
            return 'Trống'
          case 'occupied':
            return 'Đang đỗ'
          case 'reserved':
            return 'Đặt trước'
          default:
            return status
        }
      },
      isExpiringSoon(expiryDate) {
        const today = new Date()
        const expiry = new Date(expiryDate.split('/').reverse().join('-'))
        const diffTime = expiry - today
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
        
        return diffDays <= 30 && diffDays > 0
      },
      handleSpotClick(spot) {
        if (spot.isOwner) {
          this.showSpotDetails(spot)
        } else if (spot.status === 'available') {
          console.log('Reserve spot:', spot)
        }
      },
      showSpotDetails(spot) {
        this.selectedSpot = spot
        this.showSpotDetailsModal = true
      },
      showAssignVehicleModal(spot) {
        this.assignData = {
          spotId: spot.id,
          spotCode: spot.code,
          vehicleId: '',
          isDefault: false
        }
        
        this.showAssignModal = true
        if (this.selectedSpot) this.selectedSpot = null
      },
      showRenewModal(spot) {
        this.renewData = {
          spotId: spot.id,
          spotCode: spot.code,
          vehicleType: spot.vehicleType,
          currentExpiryDate: spot.expiryDate,
          period: '3',
          paymentMethod: 'bank'
        }
        
        this.showRenewSpotModal = true
        if (this.selectedSpot) this.selectedSpot = null
      },
      calculateRenewalFee(period, vehicleType) {
        const baseRate = vehicleType === 'car' ? 1000000 : 300000 // VND per month
        return baseRate * parseInt(period)
      },
      reserveSpot() {
        console.log('Reserve spot:', this.reserveData)
        
        this.reserveData = {
          floorId: 'floor-1',
          vehicleType: 'car',
          vehicleId: '',
          date: '',
          startTime: '',
          endTime: '',
          note: ''
        }
        
        this.showReserveModal = false
      },
      assignVehicle() {
        console.log('Assign vehicle:', this.assignData)
        
        const spotIndex = this.parkingSpots.findIndex(s => s.id === this.assignData.spotId)
        if (spotIndex !== -1) {
          const vehicle = this.vehicles.find(v => v.id === this.assignData.vehicleId)
          if (vehicle) {
            this.parkingSpots[spotIndex].licensePlate = vehicle.licensePlate
            this.parkingSpots[spotIndex].status = 'occupied'
          }
        }
        
        this.assignData = {
          spotId: '',
          spotCode: '',
          vehicleId: '',
          isDefault: false
        }
        
        this.showAssignModal = false
      },
      renewSpot() {
        console.log('Renew spot:', this.renewData)
        
        const parts = this.renewData.currentExpiryDate.split('/')
        const expiryDate = new Date(parts[2], parts[1] - 1, parts[0])
        
        expiryDate.setMonth(expiryDate.getMonth() + parseInt(this.renewData.period))
        
        const newExpiryDate = `${expiryDate.getDate().toString().padStart(2, '0')}/${(expiryDate.getMonth() + 1).toString().padStart(2, '0')}/${expiryDate.getFullYear()}`
        
        const spotIndex = this.parkingSpots.findIndex(s => s.id === this.renewData.spotId)
        if (spotIndex !== -1) {
          this.parkingSpots[spotIndex].expiryDate = newExpiryDate
        }
        
        this.renewData = {
          spotId: '',
          spotCode: '',
          vehicleType: '',
          currentExpiryDate: '',
          period: '3',
          paymentMethod: 'bank'
        }
        
        this.showRenewSpotModal = false
      },
      formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
      },
      getStatusColor(status) {
        switch (status) {
          case 'available':
            return 'success'
          case 'occupied':
            return 'error'
          case 'reserved':
            return 'warning'
          default:
            return 'default'
        }
      },
      calculateReservationFee() {
        // Implement fee calculation logic
        return 50000
      },
      calculateDuration() {
        if (!this.reserveData.startTime || !this.reserveData.endTime) return '--:--'
        // Implement duration calculation logic
        return '2 giờ'
      },
      getVehicleTypeText(type) {
        return type === 'car' ? 'Ô tô' : 'Xe máy'
      },
      getSpotSize(spotId) {
        const spot = this.parkingSpots.find(s => s.id === spotId)
        return spot ? spot.size : '--'
      }
    }
  }
  </script>