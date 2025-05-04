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
      <div v-if="showReserveModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Đặt chỗ đỗ xe</h3>
            <button @click="showReserveModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="reserveSpot">
              <div class="space-y-4">
                <div>
                  <label for="reserveFloor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tầng *</label>
                  <select 
                    id="reserveFloor" 
                    v-model="reserveData.floorId"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option v-for="floor in floors" :key="floor.id" :value="floor.id">
                      {{ floor.name }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="reserveVehicleType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe *</label>
                  <select 
                    id="reserveVehicleType" 
                    v-model="reserveData.vehicleType"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="car">Ô tô</option>
                    <option value="motorbike">Xe máy</option>
                  </select>
                </div>
                
                <div>
                  <label for="reserveVehicle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xe *</label>
                  <select 
                    id="reserveVehicle" 
                    v-model="reserveData.vehicleId"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="">Chọn xe</option>
                    <option v-for="vehicle in filteredVehicles" :key="vehicle.id" :value="vehicle.id">
                      {{ vehicle.licensePlate }} - {{ vehicle.brand }} {{ vehicle.model }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="reserveDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày đặt *</label>
                  <input 
                    id="reserveDate" 
                    type="date" 
                    v-model="reserveData.date"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label for="reserveStartTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giờ bắt đầu *</label>
                    <input 
                      id="reserveStartTime" 
                      type="time" 
                      v-model="reserveData.startTime"
                      required
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    />
                  </div>
                  <div>
                    <label for="reserveEndTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giờ kết thúc *</label>
                    <input 
                      id="reserveEndTime" 
                      type="time" 
                      v-model="reserveData.endTime"
                      required
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    />
                  </div>
                </div>
                
                <div>
                  <label for="reserveNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ghi chú</label>
                  <textarea 
                    id="reserveNote" 
                    v-model="reserveData.note"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập ghi chú (nếu có)"
                    rows="2"
                  ></textarea>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showReserveModal = false"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Đặt chỗ
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- Spot Details Modal -->
      <div v-if="selectedSpot" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết vị trí đỗ xe</h3>
            <button @click="selectedSpot = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <MapPin class="h-5 w-5 mr-2 text-blue-600" />
                  <h2 class="text-xl font-bold">{{ selectedSpot.code }}</h2>
                </div>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedSpot.status === 'available',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedSpot.status === 'occupied',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedSpot.status === 'reserved'
                  }">
                  {{ getStatusText(selectedSpot.status) }}
                </span>
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
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 flex justify-end space-x-2">
                <button v-if="isExpiringSoon(selectedSpot.expiryDate)" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm" @click="showRenewModal(selectedSpot)">
                  Gia hạn
                </button>
                <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" @click="showAssignVehicleModal(selectedSpot)">
                  Đăng ký xe
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Assign Vehicle Modal -->
      <div v-if="showAssignModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Đăng ký xe cho vị trí {{ assignData.spotCode }}</h3>
            <button @click="showAssignModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="assignVehicle">
              <div class="space-y-4">
                <div>
                  <label for="assignVehicle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Chọn xe *</label>
                  <select 
                    id="assignVehicle" 
                    v-model="assignData.vehicleId"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="">Chọn xe</option>
                    <option v-for="vehicle in filteredVehiclesForSpot" :key="vehicle.id" :value="vehicle.id">
                      {{ vehicle.licensePlate }} - {{ vehicle.brand }} {{ vehicle.model }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="assignDefault" class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    <input 
                      id="assignDefault" 
                      type="checkbox" 
                      v-model="assignData.isDefault"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600"
                    />
                    <span>Đặt làm xe mặc định cho vị trí này</span>
                  </label>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showAssignModal = false"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Đăng ký
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- Renew Spot Modal -->
      <div v-if="showRenewSpotModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Gia hạn vị trí đỗ xe</h3>
            <button @click="showRenewSpotModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="renewSpot">
              <div class="space-y-4">
                <div>
                  <p class="text-sm text-gray-500">Vị trí đỗ xe</p>
                  <p class="font-medium">{{ renewData.spotCode }}</p>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500">Hạn sử dụng hiện tại</p>
                  <p class="font-medium text-red-600">{{ renewData.currentExpiryDate }}</p>
                </div>
                
                <div>
                  <label for="renewPeriod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời hạn gia hạn *</label>
                  <select 
                    id="renewPeriod" 
                    v-model="renewData.period"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="1">1 tháng</option>
                    <option value="3">3 tháng</option>
                    <option value="6">6 tháng</option>
                    <option value="12">12 tháng</option>
                  </select>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500">Phí gia hạn</p>
                  <p class="font-medium text-lg">{{ formatCurrency(calculateRenewalFee(renewData.period, renewData.vehicleType)) }}</p>
                </div>
                
                <div>
                  <label for="renewPaymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức thanh toán *</label>
                  <select 
                    id="renewPaymentMethod" 
                    v-model="renewData.paymentMethod"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="bank">Chuyển khoản ngân hàng</option>
                    <option value="momo">Ví MoMo</option>
                    <option value="vnpay">VNPay</option>
                    <option value="cash">Tiền mặt</option>
                  </select>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showRenewSpotModal = false"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Thanh toán và gia hạn
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
  import { 
    Calendar, 
    MapPin, 
    CheckCircle, 
    Clock, 
    LayoutGrid, 
    Eye, 
    Car, 
    X, 
    User 
  } from 'lucide-vue-next'
  
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
      User
    },
    setup() {
      const selectedFloor = ref('floor-1')
      const showReserveModal = ref(false)
      const showAssignModal = ref(false)
      const showRenewSpotModal = ref(false)
      const selectedSpot = ref(null)
      
      // Parking stats
      const parkingStats = ref({
        mySpots: 2,
        available: 15,
        reserved: 3,
        total: 50
      })
      
      // Floors data
      const floors = ref([
        { id: 'floor-1', name: 'Tầng 1' },
        { id: 'floor-2', name: 'Tầng 2' },
        { id: 'floor-3', name: 'Tầng 3' }
      ])
      
      // Sample vehicles data
      const vehicles = ref([
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
      ])
      
      // Sample parking spots data
      const parkingSpots = ref([
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
      ])
      
      // My parking spots
      const mySpots = computed(() => {
        return parkingSpots.value.filter(spot => spot.isOwner)
      })
      
      // Reserve data
      const reserveData = ref({
        floorId: 'floor-1',
        vehicleType: 'car',
        vehicleId: '',
        date: '',
        startTime: '',
        endTime: '',
        note: ''
      })
      
      // Assign vehicle data
      const assignData = ref({
        spotId: '',
        spotCode: '',
        vehicleId: '',
        isDefault: false
      })
      
      // Renew spot data
      const renewData = ref({
        spotId: '',
        spotCode: '',
        vehicleType: '',
        currentExpiryDate: '',
        period: '3',
        paymentMethod: 'bank'
      })
      
      // Filtered vehicles based on vehicle type
      const filteredVehicles = computed(() => {
        return vehicles.value.filter(vehicle => vehicle.type === reserveData.value.vehicleType)
      })
      
      // Filtered vehicles for spot
      const filteredVehiclesForSpot = computed(() => {
        if (!assignData.value.spotId) return []
        
        const spot = parkingSpots.value.find(s => s.id === assignData.value.spotId)
        if (!spot) return []
        
        return vehicles.value.filter(vehicle => vehicle.type === spot.vehicleType)
      })
      
      // Get floor spots
      function getFloorSpots() {
        return parkingSpots.value.filter(spot => spot.floorId === selectedFloor.value)
      }
      
      // Get floor name
      function getFloorName(floorId) {
        const floor = floors.value.find(f => f.id === floorId)
        return floor ? floor.name : floorId
      }
      
      // Get status text
      function getStatusText(status) {
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
      }
      
      // Check if expiry date is soon (within 30 days)
      function isExpiringSoon(expiryDate) {
        const today = new Date()
        const expiry = new Date(expiryDate.split('/').reverse().join('-'))
        const diffTime = expiry - today
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
        
        return diffDays <= 30 && diffDays > 0
      }
      
      // Handle spot click
      function handleSpotClick(spot) {
        if (spot.isOwner) {
          showSpotDetails(spot)
        } else if (spot.status === 'available') {
          // In a real app, you would implement the reservation functionality
          console.log('Reserve spot:', spot)
        }
      }
      
      // Show spot details
      function showSpotDetails(spot) {
        selectedSpot.value = { ...spot }
      }
      
      // Show assign vehicle modal
      function showAssignVehicleModal(spot) {
        assignData.value = {
          spotId: spot.id,
          spotCode: spot.code,
          vehicleId: '',
          isDefault: false
        }
        
        showAssignModal.value = true
        if (selectedSpot.value) selectedSpot.value = null
      }
      
      // Show renew modal
      function showRenewModal(spot) {
        renewData.value = {
          spotId: spot.id,
          spotCode: spot.code,
          vehicleType: spot.vehicleType,
          currentExpiryDate: spot.expiryDate,
          period: '3',
          paymentMethod: 'bank'
        }
        
        showRenewSpotModal.value = true
        if (selectedSpot.value) selectedSpot.value = null
      }
      
      // Calculate renewal fee
      function calculateRenewalFee(period, vehicleType) {
        const baseRate = vehicleType === 'car' ? 1000000 : 300000 // VND per month
        return baseRate * parseInt(period)
      }
      
      // Reserve spot
      function reserveSpot() {
        // In a real app, you would send the data to the server here
        console.log('Reserve spot:', reserveData.value)
        
        // Reset form and close modal
        reserveData.value = {
          floorId: 'floor-1',
          vehicleType: 'car',
          vehicleId: '',
          date: '',
          startTime: '',
          endTime: '',
          note: ''
        }
        
        showReserveModal.value = false
      }
      
      // Assign vehicle
      function assignVehicle() {
        // In a real app, you would send the data to the server here
        console.log('Assign vehicle:', assignData.value)
        
        // Update spot in local data
        const spotIndex = parkingSpots.value.findIndex(s => s.id === assignData.value.spotId)
        if (spotIndex !== -1) {
          const vehicle = vehicles.value.find(v => v.id === assignData.value.vehicleId)
          if (vehicle) {
            parkingSpots.value[spotIndex].licensePlate = vehicle.licensePlate
            parkingSpots.value[spotIndex].status = 'occupied'
          }
        }
        
        // Reset form and close modal
        assignData.value = {
          spotId: '',
          spotCode: '',
          vehicleId: '',
          isDefault: false
        }
        
        showAssignModal.value = false
      }
      
      // Renew spot
      function renewSpot() {
        // In a real app, you would send the data to the server here
        console.log('Renew spot:', renewData.value)
        
        // Parse current expiry date
        const parts = renewData.value.currentExpiryDate.split('/')
        const expiryDate = new Date(parts[2], parts[1] - 1, parts[0])
        
        // Add renewal period
        expiryDate.setMonth(expiryDate.getMonth() + parseInt(renewData.value.period))
        
        // Format new expiry date
        const newExpiryDate = `${expiryDate.getDate().toString().padStart(2, '0')}/${(expiryDate.getMonth() + 1).toString().padStart(2, '0')}/${expiryDate.getFullYear()}`
        
        // Update spot in local data
        const spotIndex = parkingSpots.value.findIndex(s => s.id === renewData.value.spotId)
        if (spotIndex !== -1) {
          parkingSpots.value[spotIndex].expiryDate = newExpiryDate
        }
        
        // Reset form and close modal
        renewData.value = {
          spotId: '',
          spotCode: '',
          vehicleType: '',
          currentExpiryDate: '',
          period: '3',
          paymentMethod: 'bank'
        }
        
        showRenewSpotModal.value = false
      }
      
      // Format currency
      function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
      }
      
      return {
        selectedFloor,
        showReserveModal,
        showAssignModal,
        showRenewSpotModal,
        selectedSpot,
        parkingStats,
        floors,
        vehicles,
        parkingSpots,
        mySpots,
        reserveData,
        assignData,
        renewData,
        filteredVehicles,
        filteredVehiclesForSpot,
        getFloorSpots,
        getFloorName,
        getStatusText,
        isExpiringSoon,
        handleSpotClick,
        showSpotDetails,
        showAssignVehicleModal,
        showRenewModal,
        calculateRenewalFee,
        reserveSpot,
        assignVehicle,
        renewSpot,
        formatCurrency
      }
    }
  }
  </script>