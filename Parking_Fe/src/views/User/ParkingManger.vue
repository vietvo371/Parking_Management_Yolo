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
                  @click="handleSpotClick(spot)"
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
                  @click="handleSpotClick(spot)"
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
              <span class="text-sm">Vị trí của tôi</span>
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
        parkingSpots: {
          'Khu A': {
            car: [
              { id: 1, thu_tu: 'A01', status: 'available', isResident: false, isOwner: false },
              { id: 2, thu_tu: 'A02', status: 'occupied', isResident: true, isOwner: false },
              { id: 3, thu_tu: 'A03', status: 'occupied', isResident: false, isOwner: false },
              { id: 4, thu_tu: 'A04', status: 'available', isResident: false, isOwner: true },
              { id: 5, thu_tu: 'A05', status: 'occupied', isResident: true, isOwner: false }
            ],
            motorbike: [
              { id: 6, thu_tu: 'AM01', status: 'available', isResident: false, isOwner: false },
              { id: 7, thu_tu: 'AM02', status: 'occupied', isResident: true, isOwner: false },
              { id: 8, thu_tu: 'AM03', status: 'occupied', isResident: false, isOwner: true },
              { id: 9, thu_tu: 'AM04', status: 'available', isResident: false, isOwner: false },
              { id: 10, thu_tu: 'AM05', status: 'occupied', isResident: true, isOwner: false }
            ]
          },
          'Khu B': {
            car: [
              { id: 11, thu_tu: 'B01', status: 'available', isResident: false, isOwner: false },
              { id: 12, thu_tu: 'B02', status: 'occupied', isResident: true, isOwner: false },
              { id: 13, thu_tu: 'B03', status: 'occupied', isResident: false, isOwner: false },
              { id: 14, thu_tu: 'B04', status: 'available', isResident: false, isOwner: false },
              { id: 15, thu_tu: 'B05', status: 'occupied', isResident: true, isOwner: false }
            ],
            motorbike: [
              { id: 16, thu_tu: 'BM01', status: 'available', isResident: false, isOwner: false },
              { id: 17, thu_tu: 'BM02', status: 'occupied', isResident: true, isOwner: false },
              { id: 18, thu_tu: 'BM03', status: 'occupied', isResident: false, isOwner: false },
              { id: 19, thu_tu: 'BM04', status: 'available', isResident: false, isOwner: false },
              { id: 20, thu_tu: 'BM05', status: 'occupied', isResident: true, isOwner: false }
            ]
          }
        },
        
        // Reserve data
        reserveData: {
          floorId: 'floor-1',
          vehicleType: 'car',
          vehicleId: '',
          date: '',
          startTime: '',
          endTime: '',
          note: '',
          spotId: null
        },
        
        // Assign vehicle data
        assignData: {
          spotId: '',
          spotCode: '',
          vehicleId: '',
          isDefault: false,
          vehicleType: ''
        },
        
        // Renew spot data
        renewData: {
          spotId: '',
          spotCode: '',
          vehicleType: '',
          currentExpiryDate: '',
          period: '3',
          paymentMethod: 'bank'
        },
        selectedArea: 'all',
        selectedVehicleType: 'all',
        parkingAreas: ['Khu A', 'Khu B', 'Khu C']
      }
    },
    computed: {
      mySpots() {
        const spots = [];
        Object.keys(this.parkingSpots).forEach(area => {
          if (this.parkingSpots[area].car) {
            spots.push(...this.parkingSpots[area].car.filter(spot => spot.isOwner));
          }
          if (this.parkingSpots[area].motorbike) {
            spots.push(...this.parkingSpots[area].motorbike.filter(spot => spot.isOwner));
          }
        });
        return spots;
      },
      filteredVehicles() {
        return this.vehicles.filter(vehicle => vehicle.type === this.reserveData.vehicleType);
      },
      filteredVehiclesForSpot() {
        if (!this.assignData.spotId) return [];
        
        let spot = null;
        Object.keys(this.parkingSpots).forEach(area => {
          const carSpot = this.parkingSpots[area].car?.find(s => s.id === this.assignData.spotId);
          const motoSpot = this.parkingSpots[area].motorbike?.find(s => s.id === this.assignData.spotId);
          if (carSpot) spot = carSpot;
          if (motoSpot) spot = motoSpot;
        });
        
        if (!spot) return [];
        return this.vehicles.filter(vehicle => vehicle.type === (spot.vehicleType === 'car' ? 'car' : 'motorbike'));
      }
    },
    methods: {
      getFloorSpots() {
        if (this.selectedArea === 'all') {
          const spots = [];
          Object.keys(this.parkingSpots).forEach(area => {
            if (this.selectedVehicleType === 'all' || this.selectedVehicleType === 'car') {
              spots.push(...(this.parkingSpots[area].car || []));
            }
            if (this.selectedVehicleType === 'all' || this.selectedVehicleType === 'motorbike') {
              spots.push(...(this.parkingSpots[area].motorbike || []));
            }
          });
          return spots;
        } else {
          const areaSpots = this.parkingSpots[this.selectedArea] || { car: [], motorbike: [] };
          if (this.selectedVehicleType === 'all') {
            return [...(areaSpots.car || []), ...(areaSpots.motorbike || [])];
          } else {
            return areaSpots[this.selectedVehicleType] || [];
          }
        }
      },
      getFloorName(floorId) {
        const floor = this.floors.find(f => f.id === floorId)
        return floor ? floor.name : floorId
      },
      getStatusText(status) {
        if (!status) return 'N/A';
        
        switch (status) {
          case 'available':
            return 'Trống';
          case 'occupied':
            return this.selectedSpot?.isResident ? 'Đang đỗ (Cư dân)' : 'Đang đỗ (Khách)';
          case 'reserved':
            return 'Đặt trước';
          default:
            return status;
        }
      },
      isExpiringSoon(expiryDate) {
        if (!expiryDate) return false;
        try {
          const today = new Date();
          const [day, month, year] = expiryDate.split('/').map(Number);
          const expiry = new Date(year, month - 1, day);
          const diffTime = expiry - today;
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
          
          return diffDays <= 30 && diffDays > 0;
        } catch (error) {
          console.error('Error parsing date:', error);
          return false;
        }
      },
      handleSpotClick(spot) {
        if (!spot) return;
        
        if (spot.isOwner) {
          this.showSpotDetails(spot);
        } else if (spot.status === 'available') {
          this.showReserveModal = true;
          this.reserveData = {
            ...this.reserveData,
            spotId: spot.id,
            vehicleType: spot.vehicleType
          };
        }
      },
      showSpotDetails(spot) {
        if (!spot) return;
        
        this.selectedSpot = {
          ...spot,
          expiryDate: spot.expiryDate || null,
          history: spot.history || []
        };
        this.showSpotDetailsModal = true;
      },
      showAssignVehicleModal(spot) {
        this.assignData = {
          spotId: spot.id,
          spotCode: spot.code,
          vehicleId: '',
          isDefault: false,
          vehicleType: spot.vehicleType
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
          note: '',
          spotId: null
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
          isDefault: false,
          vehicleType: ''
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
      },
      getSpotClass(spot) {
        if (!spot) return '';
        
        if (spot.status === 'available') {
          return 'bg-gray-100 border-gray-300 text-gray-800 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300';
        } else if (spot.status === 'occupied') {
          if (spot.isOwner) {
            return 'bg-purple-100 border-purple-300 text-purple-800 dark:bg-purple-900 dark:border-purple-700 dark:text-purple-300';
          } else if (spot.isResident) {
            return 'bg-blue-100 border-blue-300 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300';
          } else {
            return 'bg-orange-100 border-orange-300 text-orange-800 dark:bg-orange-900 dark:border-orange-700 dark:text-orange-300';
          }
        } else if (spot.status === 'reserved') {
          return 'bg-red-100 border-red-300 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-300';
        }
        return '';
      }
    }
  }
  </script>