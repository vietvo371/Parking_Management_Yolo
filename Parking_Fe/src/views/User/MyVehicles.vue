<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý xe cá nhân</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showAddVehicleModal = true">
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
              <h3 class="text-2xl font-bold">{{ vehicleStats.total }}</h3>
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
              <h3 class="text-2xl font-bold">{{ vehicleStats.approved }}</h3>
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
              <h3 class="text-2xl font-bold">{{ vehicleStats.pending }}</h3>
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
            <input 
              type="text" 
              placeholder="Tìm kiếm biển số..." 
              class="pl-9 h-10 w-full sm:w-[250px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="searchQuery"
            />
          </div>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Màu sắc</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày đăng ký</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hạn đăng ký</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="vehicle in filteredVehicles" :key="vehicle.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ vehicle.licensePlate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <component :is="getVehicleIcon(vehicle.type)" class="h-4 w-4 mr-2 text-gray-500" />
                    <span>{{ getVehicleTypeText(vehicle.type) }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <div class="h-4 w-4 rounded-full mr-2" :style="{ backgroundColor: vehicle.color }"></div>
                    <span>{{ vehicle.colorName }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ vehicle.registrationDate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': vehicle.status === 'approved',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': vehicle.status === 'pending',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': vehicle.status === 'rejected'
                    }">
                    {{ getStatusText(vehicle.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div>
                    <span :class="{
                      'text-red-600': isExpiringSoon(vehicle.expiryDate),
                      'text-green-600': !isExpiringSoon(vehicle.expiryDate)
                    }">{{ vehicle.expiryDate }}</span>
                    <button v-if="isExpiringSoon(vehicle.expiryDate)" class="ml-2 text-xs text-blue-600 hover:underline" @click="showRenewModal(vehicle)">
                      Gia hạn
                    </button>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="viewVehicle(vehicle)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="editVehicle(vehicle)">
                      <Edit class="h-4 w-4" />
                    </button>
                    <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400" @click="confirmDeleteVehicle(vehicle)">
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
      <div v-if="showAddVehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Đăng ký xe mới</h3>
            <button @click="showAddVehicleModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="addVehicle">
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <label for="licensePlate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số xe *</label>
                    <input 
                      id="licensePlate" 
                      type="text" 
                      v-model="newVehicle.licensePlate"
                      required
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Nhập biển số xe"
                    />
                  </div>
                  
                  <div>
                    <label for="vehicleType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe *</label>
                    <select 
                      id="vehicleType" 
                      v-model="newVehicle.type"
                      required
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    >
                      <option value="car">Ô tô</option>
                      <option value="motorbike">Xe máy</option>
                    </select>
                  </div>
                  
                  <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hãng xe</label>
                    <input 
                      id="brand" 
                      type="text" 
                      v-model="newVehicle.brand"
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Nhập hãng xe"
                    />
                  </div>
                  
                  <div>
                    <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mẫu xe</label>
                    <input 
                      id="model" 
                      type="text" 
                      v-model="newVehicle.model"
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Nhập mẫu xe"
                    />
                  </div>
                  
                  <div>
                    <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Màu sắc</label>
                    <div class="flex items-center space-x-2">
                      <input 
                        id="color" 
                        type="color" 
                        v-model="newVehicle.color"
                        class="h-10 w-10 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      />
                      <input 
                        type="text" 
                        v-model="newVehicle.colorName"
                        class="flex-1 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                        placeholder="Tên màu (vd: Đen, Trắng)"
                      />
                    </div>
                  </div>
                  
                  <div>
                    <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Năm sản xuất</label>
                    <input 
                      id="year" 
                      type="number" 
                      v-model="newVehicle.year"
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Nhập năm sản xuất"
                    />
                  </div>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giấy tờ xe *</label>
                  <div class="border border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4">
                    <div class="flex flex-col items-center justify-center">
                      <Upload class="h-8 w-8 text-gray-400 mb-2" />
                      <p class="text-sm text-gray-500">Kéo và thả hoặc nhấp để tải lên</p>
                      <p class="text-xs text-gray-500 mt-1">Hỗ trợ JPG, PNG, PDF (tối đa 5MB)</p>
                      <input type="file" class="hidden" ref="fileInput" multiple @change="handleFileUpload" />
                      <button 
                        type="button" 
                        class="mt-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 text-sm"
                        @click="$refs.fileInput.click()"
                      >
                        Chọn tệp
                      </button>
                    </div>
                    <div v-if="newVehicle.documents.length > 0" class="mt-4 space-y-2">
                      <div v-for="(doc, index) in newVehicle.documents" :key="index" class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                        <div class="flex items-center">
                          <FileText class="h-4 w-4 text-gray-500 mr-2" />
                          <span class="text-sm truncate">{{ doc.name }}</span>
                        </div>
                        <button type="button" class="text-red-500 hover:text-red-700" @click="removeDocument(index)">
                          <X class="h-4 w-4" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div>
                  <label for="note" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ghi chú</label>
                  <textarea 
                    id="note" 
                    v-model="newVehicle.note"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập ghi chú (nếu có)"
                    rows="2"
                  ></textarea>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showAddVehicleModal = false"
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
  
      <!-- View Vehicle Modal -->
      <div v-if="selectedVehicle" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết xe</h3>
            <button @click="selectedVehicle = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <component :is="getVehicleIcon(selectedVehicle.type)" class="h-5 w-5 mr-2 text-blue-600" />
                  <h2 class="text-xl font-bold">{{ selectedVehicle.licensePlate }}</h2>
                </div>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedVehicle.status === 'approved',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedVehicle.status === 'pending',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedVehicle.status === 'rejected'
                  }">
                  {{ getStatusText(selectedVehicle.status) }}
                </span>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Loại xe</p>
                  <p class="font-medium">{{ getVehicleTypeText(selectedVehicle.type) }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Hãng xe</p>
                  <p class="font-medium">{{ selectedVehicle.brand }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Mẫu xe</p>
                  <p class="font-medium">{{ selectedVehicle.model }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Màu sắc</p>
                  <div class="flex items-center">
                    <div class="h-4 w-4 rounded-full mr-2" :style="{ backgroundColor: selectedVehicle.color }"></div>
                    <span class="font-medium">{{ selectedVehicle.colorName }}</span>
                  </div>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Năm sản xuất</p>
                  <p class="font-medium">{{ selectedVehicle.year }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Ngày đăng ký</p>
                  <p class="font-medium">{{ selectedVehicle.registrationDate }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Hạn đăng ký</p>
                  <p class="font-medium" :class="{
                    'text-red-600': isExpiringSoon(selectedVehicle.expiryDate),
                    'text-green-600': !isExpiringSoon(selectedVehicle.expiryDate)
                  }">{{ selectedVehicle.expiryDate }}</p>
                </div>
              </div>
              
              <div v-if="selectedVehicle.note" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2">Ghi chú</h4>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ selectedVehicle.note }}</p>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2">Giấy tờ xe</h4>
                <div class="grid grid-cols-2 gap-2">
                  <div v-for="(doc, index) in selectedVehicle.documents" :key="index" class="bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                    <div class="flex items-center">
                      <FileText class="h-4 w-4 text-gray-500 mr-2" />
                      <span class="text-sm truncate">{{ doc.name }}</span>
                    </div>
                    <button class="mt-1 text-xs text-blue-600 hover:underline">Xem</button>
                  </div>
                </div>
              </div>
              
              <div v-if="selectedVehicle.status === 'rejected'" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2 text-red-600">Lý do từ chối</h4>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ selectedVehicle.rejectionReason }}</p>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 flex justify-between">
                <div>
                  <button v-if="isExpiringSoon(selectedVehicle.expiryDate)" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm" @click="showRenewModal(selectedVehicle)">
                    Gia hạn đăng ký
                  </button>
                </div>
                <div class="flex space-x-2">
                  <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 text-sm" @click="editVehicle(selectedVehicle)">
                    Chỉnh sửa
                  </button>
                  <button class="px-4 py-2 border border-red-300 dark:border-red-600 text-red-600 rounded-md hover:bg-red-50 dark:hover:bg-red-900 text-sm" @click="confirmDeleteVehicle(selectedVehicle)">
                    Xóa
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Renew Registration Modal -->
      <div v-if="showRenewVehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Gia hạn đăng ký xe</h3>
            <button @click="showRenewVehicleModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="renewVehicle">
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
                  <label for="renewalPeriod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời hạn gia hạn *</label>
                  <select 
                    id="renewalPeriod" 
                    v-model="renewVehicleData.period"
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
                  <p class="font-medium text-lg">{{ formatCurrency(calculateRenewalFee(renewVehicleData.period, renewVehicleData.type)) }}</p>
                </div>
                
                <div>
                  <label for="paymentMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức thanh toán *</label>
                  <select 
                    id="paymentMethod" 
                    v-model="renewVehicleData.paymentMethod"
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
                    @click="showRenewVehicleModal = false"
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
  
      <!-- Delete Confirmation Modal -->
      <div v-if="showDeleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Xác nhận xóa</h3>
            <button @click="showDeleteConfirmModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-4">
              <div class="flex items-center space-x-2 text-red-600">
                <AlertTriangle class="h-5 w-5" />
                <p class="font-medium">Bạn có chắc chắn muốn xóa xe này?</p>
              </div>
              <p class="text-sm text-gray-500">Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến xe này sẽ bị xóa vĩnh viễn.</p>
              
              <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                <p class="text-sm font-medium">{{ deleteVehicleData.licensePlate }}</p>
                <p class="text-xs text-gray-500">{{ getVehicleTypeText(deleteVehicleData.type) }}</p>
              </div>
              
              <div class="pt-4 flex justify-end space-x-2">
                <button 
                  type="button" 
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                  @click="showDeleteConfirmModal = false"
                >
                  Hủy
                </button>
                <button 
                  type="button" 
                  class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700"
                  @click="deleteVehicle"
                >
                  Xóa
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
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
    setup() {
      const searchQuery = ref('')
      const showAddVehicleModal = ref(false)
      const showRenewVehicleModal = ref(false)
      const showDeleteConfirmModal = ref(false)
      const selectedVehicle = ref(null)
      
      // Vehicle stats
      const vehicleStats = ref({
        total: 3,
        approved: 2,
        pending: 1
      })
      
      // New vehicle data
      const newVehicle = ref({
        licensePlate: '',
        type: 'car',
        brand: '',
        model: '',
        color: '#000000',
        colorName: 'Đen',
        year: new Date().getFullYear(),
        documents: [],
        note: ''
      })
      
      // Renew vehicle data
      const renewVehicleData = ref({
        id: '',
        licensePlate: '',
        type: '',
        currentExpiryDate: '',
        period: '3',
        paymentMethod: 'bank'
      })
      
      // Delete vehicle data
      const deleteVehicleData = ref({
        id: '',
        licensePlate: '',
        type: ''
      })
      
      // Sample vehicles data
      const vehicles = ref([
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
      ])
      
      // Filter vehicles based on search query
      const filteredVehicles = computed(() => {
        if (!searchQuery.value) return vehicles.value
        
        return vehicles.value.filter(vehicle => 
          vehicle.licensePlate.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          vehicle.brand.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          vehicle.model.toLowerCase().includes(searchQuery.value.toLowerCase())
        )
      })
      
      // Get vehicle icon
      function getVehicleIcon(type) {
        return type === 'car' ? Car : Bike
      }
      
      // Get vehicle type text
      function getVehicleTypeText(type) {
        return type === 'car' ? 'Ô tô' : 'Xe máy'
      }
      
      // Get status text
      function getStatusText(status) {
        switch (status) {
          case 'pending':
            return 'Chờ phê duyệt'
          case 'approved':
            return 'Đã phê duyệt'
          case 'rejected':
            return 'Bị từ chối'
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
      
      // Handle file upload
      function handleFileUpload(event) {
        const files = event.target.files
        if (!files.length) return
        
        for (let i = 0; i < files.length; i++) {
          newVehicle.value.documents.push({
            name: files[i].name
          })
        }
      }
      
      // Remove document
      function removeDocument(index) {
        newVehicle.value.documents.splice(index, 1)
      }
      
      // Add vehicle
      function addVehicle() {
        // In a real app, you would send the data to the server here
        // For demo purposes, we'll just add it to the local array
        const id = `VEH-${(vehicles.value.length + 1).toString().padStart(3, '0')}`
        const today = new Date()
        const registrationDate = `${today.getDate().toString().padStart(2, '0')}/${(today.getMonth() + 1).toString().padStart(2, '0')}/${today.getFullYear()}`
        
        // Set expiry date to 3 months from now
        const expiryDate = new Date(today)
        expiryDate.setMonth(today.getMonth() + 3)
        const expiryDateStr = `${expiryDate.getDate().toString().padStart(2, '0')}/${(expiryDate.getMonth() + 1).toString().padStart(2, '0')}/${expiryDate.getFullYear()}`
        
        vehicles.value.push({
          id,
          ...newVehicle.value,
          registrationDate,
          expiryDate: expiryDateStr,
          status: 'pending'
        })
        
        // Update stats
        vehicleStats.value.total++
        vehicleStats.value.pending++
        
        // Reset form and close modal
        newVehicle.value = {
          licensePlate: '',
          type: 'car',
          brand: '',
          model: '',
          color: '#000000',
          colorName: 'Đen',
          year: new Date().getFullYear(),
          documents: [],
          note: ''
        }
        
        showAddVehicleModal.value = false
      }
      
      // View vehicle details
      function viewVehicle(vehicle) {
        selectedVehicle.value = { ...vehicle }
      }
      
      // Edit vehicle
      function editVehicle(vehicle) {
        // In a real app, you would implement the edit functionality
        console.log('Edit vehicle:', vehicle)
        // For now, just view the vehicle
        viewVehicle(vehicle)
      }
      
      // Show renew modal
      function showRenewModal(vehicle) {
        renewVehicleData.value = {
          id: vehicle.id,
          licensePlate: vehicle.licensePlate,
          type: vehicle.type,
          currentExpiryDate: vehicle.expiryDate,
          period: '3',
          paymentMethod: 'bank'
        }
        
        showRenewVehicleModal.value = true
        if (selectedVehicle.value) selectedVehicle.value = null
      }
      
      // Calculate renewal fee
      function calculateRenewalFee(period, type) {
        const baseRate = type === 'car' ? 500000 : 200000 // VND per month
        return baseRate * parseInt(period)
      }
      
      // Renew vehicle
      function renewVehicle() {
        // In a real app, you would send the data to the server here
        // For demo purposes, we'll just update the local data
        
        const vehicleIndex = vehicles.value.findIndex(v => v.id === renewVehicleData.value.id)
        if (vehicleIndex === -1) return
        
        // Parse current expiry date
        const parts = renewVehicleData.value.currentExpiryDate.split('/')
        const expiryDate = new Date(parts[2], parts[1] - 1, parts[0])
        
        // Add renewal period
        expiryDate.setMonth(expiryDate.getMonth() + parseInt(renewVehicleData.value.period))
        
        // Format new expiry date
        const newExpiryDate = `${expiryDate.getDate().toString().padStart(2, '0')}/${(expiryDate.getMonth() + 1).toString().padStart(2, '0')}/${expiryDate.getFullYear()}`
        
        // Update vehicle
        vehicles.value[vehicleIndex].expiryDate = newExpiryDate
        
        // Close modal
        showRenewVehicleModal.value = false
      }
      
      // Confirm delete vehicle
      function confirmDeleteVehicle(vehicle) {
        deleteVehicleData.value = {
          id: vehicle.id,
          licensePlate: vehicle.licensePlate,
          type: vehicle.type
        }
        
        showDeleteConfirmModal.value = true
        if (selectedVehicle.value) selectedVehicle.value = null
      }
      
      // Delete vehicle
      function deleteVehicle() {
        // In a real app, you would send the data to the server here
        // For demo purposes, we'll just remove it from the local array
        
        const vehicleIndex = vehicles.value.findIndex(v => v.id === deleteVehicleData.value.id)
        if (vehicleIndex === -1) return
        
        // Update stats
        vehicleStats.value.total--
        if (vehicles.value[vehicleIndex].status === 'approved') {
          vehicleStats.value.approved--
        } else if (vehicles.value[vehicleIndex].status === 'pending') {
          vehicleStats.value.pending--
        }
        
        // Remove vehicle
        vehicles.value.splice(vehicleIndex, 1)
        
        // Close modal
        showDeleteConfirmModal.value = false
      }
      
      // Format currency
      function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
      }
      
      return {
        searchQuery,
        showAddVehicleModal,
        showRenewVehicleModal,
        showDeleteConfirmModal,
        selectedVehicle,
        vehicleStats,
        newVehicle,
        renewVehicleData,
        deleteVehicleData,
        vehicles,
        filteredVehicles,
        getVehicleIcon,
        getVehicleTypeText,
        getStatusText,
        isExpiringSoon,
        handleFileUpload,
        removeDocument,
        addVehicle,
        viewVehicle,
        editVehicle,
        showRenewModal,
        calculateRenewalFee,
        renewVehicle,
        confirmDeleteVehicle,
        deleteVehicle,
        formatCurrency
      }
    }
  }
  </script>