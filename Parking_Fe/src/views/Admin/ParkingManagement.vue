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
  
      <!-- Parking Overview -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <h2 class="text-lg font-medium mb-4">Tổng quan bãi đỗ</h2>
          <div class="space-y-4">
            <div>
              <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium">Ô tô</span>
                <span v-if="parkingStatus && parkingStatus.car" class="text-sm">{{ parkingStatus.car.used }}/{{ parkingStatus.car.total }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div 
                  class="bg-blue-600 h-2.5 rounded-full" 
                  :style="{ width: `${(parkingStatus.car.used / parkingStatus.car.total) * 100}%` }"
                ></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium">Xe máy</span>
                <span class="text-sm">{{ parkingStatus.motorbike.used }}/{{ parkingStatus.motorbike.total }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div 
                  class="bg-green-600 h-2.5 rounded-full" 
                  :style="{ width: `${(parkingStatus.motorbike.used / parkingStatus.motorbike.total) * 100}%` }"
                ></div>
              </div>
            </div>
            <div>
              <div class="flex justify-between items-center mb-1">
                <span class="text-sm font-medium">Khách vãng lai</span>
                <span class="text-sm">{{ parkingStatus.visitor.used }}/{{ parkingStatus.visitor.total }}</span>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                <div 
                  class="bg-orange-600 h-2.5 rounded-full" 
                  :style="{ width: `${(parkingStatus.visitor.used / parkingStatus.visitor.total) * 100}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <h2 class="text-lg font-medium mb-4">Hoạt động gần đây</h2>
          <div class="space-y-3">
            <div v-for="(activity, index) in recentActivities.slice(0, 4)" :key="index" class="flex items-start space-x-3">
              <div :class="`h-8 w-8 rounded-full flex items-center justify-center ${activity.iconBg}`">
                <component :is="activity.icon" class="h-4 w-4" :class="activity.iconColor" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium">{{ activity.title }}</p>
                <p class="text-xs text-gray-500">{{ activity.time }}</p>
              </div>
            </div>
          </div>
          <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
            <button class="text-sm text-blue-600 hover:underline">Xem tất cả hoạt động</button>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <h2 class="text-lg font-medium mb-4">Thống kê hôm nay</h2>
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md">
              <p class="text-sm text-gray-500">Lượt vào</p>
              <p class="text-2xl font-bold">{{ todayStats.entries }}</p>
              <p class="text-xs text-green-600">+{{ todayStats.entriesChange }}% so với hôm qua</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md">
              <p class="text-sm text-gray-500">Lượt ra</p>
              <p class="text-2xl font-bold">{{ todayStats.exits }}</p>
              <p class="text-xs text-green-600">+{{ todayStats.exitsChange }}% so với hôm qua</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md">
              <p class="text-sm text-gray-500">Thời gian trung bình</p>
              <p class="text-2xl font-bold">{{ todayStats.avgTime }}</p>
              <p class="text-xs text-gray-500">phút/lượt</p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md">
              <p class="text-sm text-gray-500">Tỷ lệ lấp đầy</p>
              <p class="text-2xl font-bold">{{ todayStats.occupancyRate }}%</p>
              <p class="text-xs text-red-600">-{{ todayStats.occupancyChange }}% so với hôm qua</p>
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
              <option value="A">Khu vực A</option>
              <option value="B">Khu vực B</option>
              <option value="C">Khu vực C</option>
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
            <!-- Khu vực A -->
            <div v-if="selectedArea === 'all' || selectedArea === 'A'" class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <h3 class="text-md font-medium mb-3">Khu vực A - Ô tô</h3>
              <div class="grid grid-cols-5 gap-2">
                <div 
                  v-for="spot in parkingSpots.A?.car || []" 
                  :key="`A-car-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.id }}
                </div>
              </div>
              
              <h3 class="text-md font-medium mb-3 mt-4">Khu vực A - Xe máy</h3>
              <div class="grid grid-cols-8 gap-1">
                <div 
                  v-for="spot in parkingSpots.A?.motorbike || []" 
                  :key="`A-motorbike-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.id }}
                </div>
              </div>
            </div>
            
            <!-- Khu vực B -->
            <div v-if="selectedArea === 'all' || selectedArea === 'B'" class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <h3 class="text-md font-medium mb-3">Khu vực B - Ô tô</h3>
              <div class="grid grid-cols-5 gap-2">
                <div 
                  v-for="spot in parkingSpots.B?.car || []" 
                  :key="`B-car-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.id }}
                </div>
              </div>
              
              <h3 class="text-md font-medium mb-3 mt-4">Khu vực B - Xe máy</h3>
              <div class="grid grid-cols-8 gap-1">
                <div 
                  v-for="spot in parkingSpots.B?.motorbike || []" 
                  :key="`B-motorbike-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.id }}
                </div>
              </div>
            </div>
            
            <!-- Khu vực C -->
            <div v-if="selectedArea === 'all' || selectedArea === 'C'" class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
              <h3 class="text-md font-medium mb-3">Khu vực C - Ô tô</h3>
              <div class="grid grid-cols-5 gap-2">
                <div 
                  v-for="spot in parkingSpots.C?.car || []" 
                  :key="`C-car-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.id }}
                </div>
              </div>
              
              <h3 class="text-md font-medium mb-3 mt-4">Khu vực C - Xe máy</h3>
              <div class="grid grid-cols-8 gap-1">
                <div 
                  v-for="spot in parkingSpots.C?.motorbike || []" 
                  :key="`C-motorbike-${spot.id}`"
                  class="aspect-square rounded-md border flex items-center justify-center text-xs cursor-pointer hover:border-blue-500"
                  :class="getSpotClass(spot)"
                  @click="selectSpot(spot)"
                >
                  {{ spot.id }}
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
      <div v-if="selectedSpot" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết vị trí đỗ xe</h3>
            <button @click="selectedSpot = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Khu vực</p>
                  <p class="font-medium">{{ selectedSpot.area }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Vị trí</p>
                  <p class="font-medium">{{ selectedSpot.id }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Loại xe</p>
                  <p class="font-medium">{{ selectedSpot.type === 'car' ? 'Ô tô' : 'Xe máy' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Trạng thái</p>
                  <p class="font-medium" :class="{
                    'text-green-600': selectedSpot.status === 'available',
                    'text-blue-600': selectedSpot.status === 'occupied' && selectedSpot.isResident,
                    'text-orange-600': selectedSpot.status === 'occupied' && !selectedSpot.isResident,
                    'text-red-600': selectedSpot.status === 'unavailable',
                    'text-purple-600': selectedSpot.status === 'reserved'
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
                      <p class="font-medium">{{ selectedSpot.vehicle?.licensePlate || 'N/A' }}</p>
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
                
                <div class="flex space-x-2 mt-4">
                  <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Xem chi tiết
                  </button>
                  <button class="flex-1 px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                    Ghi nhận xe ra
                  </button>
                </div>
              </div>
              
              <div v-else-if="selectedSpot.status === 'available'">
                <div class="flex space-x-2 mt-4">
                  <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Ghi nhận xe vào
                  </button>
                  <button class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">
                    Đặt trước
                  </button>
                </div>
              </div>
              
              <div v-else-if="selectedSpot.status === 'reserved'">
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                  <h4 class="font-medium mb-2">Thông tin đặt trước</h4>
                  <div class="grid grid-cols-2 gap-4">
                    <div>
                      <p class="text-sm text-gray-500">Người đặt</p>
                      <p class="font-medium">{{ selectedSpot.reservation?.name || 'N/A' }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500">Thời gian đặt</p>
                      <p class="font-medium">{{ selectedSpot.reservation?.time || 'N/A' }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="flex space-x-2 mt-4">
                  <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Xem chi tiết
                  </button>
                  <button class="flex-1 px-3 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                    Hủy đặt trước
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
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
              <tr v-for="entry in recentEntries" :key="entry.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
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
  
      <!-- Modal chi tiết lịch sử gần đây -->
      <div v-if="showHistoryDetailModal && selectedHistoryEntry" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết lượt {{ selectedHistoryEntry.type === 'in' ? 'vào' : 'ra' }}</h3>
            <button @click="closeHistoryDetailModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4 space-y-4">
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
        </div>
      </div>
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
      Printer
    },
    data() {
      return {
        searchQuery: '',
        selectedArea: 'all',
        selectedVehicleType: 'all',
        selectedSpot: null,
        // Modal states
        showCheckInModal: false,
        showCheckOutModal: false,
        showReserveModal: false,
        showCancelReserveModal: false,
        showVehicleDetailModal: false,
        showHistoryDetailModal: false,
        selectedHistoryEntry: null,
        // Form data for modals
        checkInForm: {
          licensePlate: '',
          owner: '',
          model: '',
          entryTime: ''
        },
        checkOutForm: {
          licensePlate: '',
          exitTime: ''
        },
        reserveForm: {
          name: '',
          time: ''
        },
        // Parking status data
        parkingStatus: {
          car: { total: 0, used: 0 },
          motorbike: { total: 0, used: 0 },
          visitor: { total: 0, used: 0 }
        },
        // Today's statistics
        todayStats: {
          entries: 85,
          entriesChange: 12,
          exits: 65,
          exitsChange: 8,
          avgTime: 45,
          occupancyRate: 75,
          occupancyChange: 5
        },
        // Recent activities
        recentActivities: [
          { title: 'Xe vào: 30A-12345', time: '10:25', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' },
          { title: 'Xe ra: 30F-54321', time: '10:15', icon: LogOut, iconBg: 'bg-blue-100', iconColor: 'text-blue-600' },
          { title: 'Xe vào: 29P2-12345', time: '09:45', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' },
          { title: 'Xe ra: 30K1-65432', time: '09:15', icon: LogOut, iconBg: 'bg-blue-100', iconColor: 'text-blue-600' },
          { title: 'Xe vào: 30A-56789', time: '08:45', icon: LogIn, iconBg: 'bg-green-100', iconColor: 'text-green-600' }
        ],
        // Parking spots data
        parkingSpots: {},
        // Recent entries and exits
        recentEntries: [
          { id: 'ENT-001', time: '10:25', type: 'in', licensePlate: '30A-12345', spot: 'A-C05', duration: null, fee: null },
          { id: 'ENT-002', time: '10:15', type: 'out', licensePlate: '30F-54321', spot: 'B-C12', duration: '2h 30m', fee: 20000 },
          { id: 'ENT-003', time: '09:45', type: 'in', licensePlate: '29P2-12345', spot: 'C-M08', duration: null, fee: null },
          { id: 'ENT-004', time: '09:30', type: 'in', licensePlate: '30H-98765', spot: 'A-C18', duration: null, fee: null },
          { id: 'ENT-005', time: '09:15', type: 'out', licensePlate: '30K1-65432', spot: 'A-M22', duration: '1h 45m', fee: 10000 },
          { id: 'ENT-006', time: '08:45', type: 'in', licensePlate: '30A-56789', spot: 'B-C08', duration: null, fee: null },
          { id: 'ENT-007', time: '08:30', type: 'out', licensePlate: '30H-98765', spot: 'C-C15', duration: '12h 15m', fee: 50000 },
          { id: 'ENT-008', time: '08:15', type: 'in', licensePlate: '29Y2-54321', spot: 'A-M15', duration: null, fee: null }
        ]
      }
    },
    mounted() {
      this.generateAllParkingSpots()
    },
    methods: {
      generateAllParkingSpots() {
        this.parkingSpots = {
          A: {
            car: this.generateParkingSpots('A', 'car', 20),
            motorbike: this.generateParkingSpots('A', 'motorbike', 40)
          },
          B: {
            car: this.generateParkingSpots('B', 'car', 20),
            motorbike: this.generateParkingSpots('B', 'motorbike', 40)
          },
          C: {
            car: this.generateParkingSpots('C', 'car', 20),
            motorbike: this.generateParkingSpots('C', 'motorbike', 40)
          }
        }
      },
      generateParkingSpots(area, type, count) {
        const spots = []
        const prefix = type === 'car' ? 'C' : 'M'
        for (let i = 1; i <= count; i++) {
          const id = `${prefix}${i.toString().padStart(2, '0')}`
          const randomStatus = Math.random()
          let status, isResident, vehicle, reservation
          if (randomStatus < 0.6) {
            status = 'occupied'
            isResident = Math.random() > 0.3
            if (isResident) {
              vehicle = {
                licensePlate: `30${String.fromCharCode(65 + Math.floor(Math.random() * 26))}-${Math.floor(10000 + Math.random() * 90000)}`,
                model: type === 'car' ? 'Ô tô' : 'Xe máy',
                owner: `Nguyễn Văn ${String.fromCharCode(65 + Math.floor(Math.random() * 26))}`,
                entryTime: `${Math.floor(7 + Math.random() * 12)}:${Math.floor(Math.random() * 60).toString().padStart(2, '0')}`
              }
            } else {
              vehicle = {
                licensePlate: `30${String.fromCharCode(65 + Math.floor(Math.random() * 26))}-${Math.floor(10000 + Math.random() * 90000)}`,
                model: type === 'car' ? 'Ô tô' : 'Xe máy',
                owner: 'Khách vãng lai',
                entryTime: `${Math.floor(7 + Math.random() * 12)}:${Math.floor(Math.random() * 60).toString().padStart(2, '0')}`
              }
            }
          } else if (randomStatus < 0.7) {
            status = 'reserved'
            reservation = {
              name: `Trần Thị ${String.fromCharCode(65 + Math.floor(Math.random() * 26))}`,
              time: `${Math.floor(13 + Math.random() * 8)}:${Math.floor(Math.random() * 60).toString().padStart(2, '0')}`
            }
          } else if (randomStatus < 0.8) {
            status = 'unavailable'
          } else {
            status = 'available'
          }
          spots.push({
            id,
            area,
            type,
            status,
            isResident,
            vehicle,
            reservation
          })
        }
        return spots
      },
      getSpotClass(spot) {
        if (spot.status === 'available') {
          return 'bg-gray-100 border-gray-300 text-gray-800 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300'
        } else if (spot.status === 'occupied') {
          if (spot.isResident) {
            return 'bg-blue-100 border-blue-300 text-blue-800 dark:bg-blue-900 dark:border-blue-700 dark:text-blue-300'
          } else {
            return 'bg-orange-100 border-orange-300 text-orange-800 dark:bg-orange-900 dark:border-orange-700 dark:text-orange-300'
          }
        } else if (spot.status === 'unavailable') {
          return 'bg-red-100 border-red-300 text-red-800 dark:bg-red-900 dark:border-red-700 dark:text-red-300'
        } else if (spot.status === 'reserved') {
          return 'bg-purple-100 border-purple-300 text-purple-800 dark:bg-purple-900 dark:border-purple-700 dark:text-purple-300'
        }
      },
      getStatusText(status) {
        switch (status) {
          case 'available': return 'Trống'
          case 'occupied': return 'Đã đỗ'
          case 'unavailable': return 'Không khả dụng'
          case 'reserved': return 'Đã đặt trước'
          default: return status
        }
      },
      selectSpot(spot) {
        this.selectedSpot = { ...spot }
      },
      // Modal open/close handlers
      openCheckInModal() {
        this.checkInForm = { licensePlate: '', owner: '', model: '', entryTime: '' }
        this.showCheckInModal = true
      },
      openCheckOutModal() {
        this.checkOutForm = { licensePlate: this.selectedSpot?.vehicle?.licensePlate || '', exitTime: '' }
        this.showCheckOutModal = true
      },
      openReserveModal() {
        this.reserveForm = { name: '', time: '' }
        this.showReserveModal = true
      },
      openCancelReserveModal() {
        this.showCancelReserveModal = true
      },
      openVehicleDetailModal() {
        this.showVehicleDetailModal = true
      },
      closeAllModals() {
        this.showCheckInModal = false
        this.showCheckOutModal = false
        this.showReserveModal = false
        this.showCancelReserveModal = false
        this.showVehicleDetailModal = false
      },
      formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
      },
      openHistoryDetailModal(entry) {
        this.selectedHistoryEntry = entry
        this.showHistoryDetailModal = true
      },
      closeHistoryDetailModal() {
        this.showHistoryDetailModal = false
        this.selectedHistoryEntry = null
      }
    }
  }
  </script>