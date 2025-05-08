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
                    <button class="text-red-600 hover:text-red-900 dark:hover:text-red-400" @click="showDeleteConfirm(vehicle)">
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
                <p class="font-medium">{{ deleteVehicleData.licensePlate }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Loại xe</p>
                <p class="font-medium">{{ getVehicleTypeText(deleteVehicleData.type) }}</p>
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
              <h2 class="text-xl font-bold">{{ selectedVehicle.licensePlate }}</h2>
              <p class="text-sm text-gray-500">{{ selectedVehicle.id }} - {{ selectedVehicle.registrationDate }}</p>
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
              <div class="flex items-center">
                <component :is="getVehicleIcon(selectedVehicle.type)" class="h-4 w-4 mr-2 text-gray-500" />
                <p class="font-medium">{{ getVehicleTypeText(selectedVehicle.type) }}</p>
              </div>
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
              <a-popconfirm
                title="Bạn có chắc chắn muốn xóa xe này không?"
                ok-text="Có"
                cancel-text="Không"
                @confirm="deleteVehicle(selectedVehicle)"
              >
                <a-button type="primary" danger>
                  Xóa
                </a-button>
              </a-popconfirm>
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
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label for="editLicensePlate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số xe *</label>
              <input 
                id="editLicensePlate" 
                type="text" 
                v-model="editingVehicle.licensePlate"
                required
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                placeholder="Nhập biển số xe"
              />
            </div>
            
            <div>
              <label for="editVehicleType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe *</label>
              <select 
                id="editVehicleType" 
                v-model="editingVehicle.type"
                required
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              >
                <option value="car">Ô tô</option>
                <option value="motorbike">Xe máy</option>
              </select>
            </div>
            
            <div>
              <label for="editBrand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hãng xe</label>
              <input 
                id="editBrand" 
                type="text" 
                v-model="editingVehicle.brand"
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                placeholder="Nhập hãng xe"
              />
            </div>
            
            <div>
              <label for="editModel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mẫu xe</label>
              <input 
                id="editModel" 
                type="text" 
                v-model="editingVehicle.model"
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                placeholder="Nhập mẫu xe"
              />
            </div>
            
            <div>
              <label for="editColor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Màu sắc</label>
              <div class="flex items-center space-x-2">
                <input 
                  id="editColor" 
                  type="color" 
                  v-model="editingVehicle.color"
                  class="h-10 w-10 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                />
                <input 
                  type="text" 
                  v-model="editingVehicle.colorName"
                  class="flex-1 px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  placeholder="Tên màu (vd: Đen, Trắng)"
                />
              </div>
            </div>
            
            <div>
              <label for="editYear" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Năm sản xuất</label>
              <input 
                id="editYear" 
                type="number" 
                v-model="editingVehicle.year"
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
                <input type="file" class="hidden" ref="editFileInput" multiple @change="handleEditFileUpload" />
                <button 
                  type="button" 
                  class="mt-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 text-sm"
                  @click="$refs.editFileInput.click()"
                >
                  Chọn tệp
                </button>
              </div>
              <div v-if="editingVehicle.documents.length > 0" class="mt-4 space-y-2">
                <div v-for="(doc, index) in editingVehicle.documents" :key="index" class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-2 rounded-md">
                  <div class="flex items-center">
                    <FileText class="h-4 w-4 text-gray-500 mr-2" />
                    <span class="text-sm truncate">{{ doc.name }}</span>
                  </div>
                  <button type="button" class="text-red-500 hover:text-red-700" @click="removeEditDocument(index)">
                    <X class="h-4 w-4" />
                  </button>
                </div>
              </div>
            </div>
          </div>
          
          <div>
            <label for="editNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ghi chú</label>
            <textarea 
              id="editNote" 
              v-model="editingVehicle.note"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập ghi chú (nếu có)"
              rows="2"
            ></textarea>
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
        </div>
      </a-modal>
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
        ]
      }
    },
    computed: {
      filteredVehicles() {
        if (!this.searchQuery) return this.vehicles
        
        return this.vehicles.filter(vehicle => 
          vehicle.licensePlate.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          vehicle.brand.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          vehicle.model.toLowerCase().includes(this.searchQuery.toLowerCase())
        )
      }
    },
    methods: {
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
          'approved': 'Đã duyệt',
          'pending': 'Đang chờ duyệt',
          'rejected': 'Từ chối'
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
      addVehicle() {
        const id = `VEH-${(this.vehicles.length + 1).toString().padStart(3, '0')}`
        const today = new Date()
        const registrationDate = `${today.getDate().toString().padStart(2, '0')}/${(today.getMonth() + 1).toString().padStart(2, '0')}/${today.getFullYear()}`
        
        // Set expiry date to 3 months from now
        const expiryDate = new Date(today)
        expiryDate.setMonth(today.getMonth() + 3)
        const expiryDateStr = `${expiryDate.getDate().toString().padStart(2, '0')}/${(expiryDate.getMonth() + 1).toString().padStart(2, '0')}/${expiryDate.getFullYear()}`
        
        this.vehicles.push({
          id,
          ...this.newVehicle,
          registrationDate,
          expiryDate: expiryDateStr,
          status: 'pending'
        })
        
        // Update stats
        this.vehicleStats.total++
        this.vehicleStats.pending++
        
        // Reset form and close modal
        this.newVehicle = {
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
        
        this.showAddVehicleModal = false
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
        const index = this.vehicles.findIndex(v => v.id === this.editingVehicle.id)
        if (index !== -1) {
          this.vehicles[index] = { ...this.editingVehicle }
        }
        this.showEditVehicleModal = false
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
      renewVehicle() {
        const vehicleIndex = this.vehicles.findIndex(v => v.id === this.renewVehicleData.id)
        if (vehicleIndex === -1) return
        
        // Parse current expiry date
        const parts = this.renewVehicleData.currentExpiryDate.split('/')
        const expiryDate = new Date(parts[2], parts[1] - 1, parts[0])
        
        // Add renewal period
        expiryDate.setMonth(expiryDate.getMonth() + parseInt(this.renewVehicleData.period))
        
        // Format new expiry date
        const newExpiryDate = `${expiryDate.getDate().toString().padStart(2, '0')}/${(expiryDate.getMonth() + 1).toString().padStart(2, '0')}/${expiryDate.getFullYear()}`
        
        // Update vehicle
        this.vehicles[vehicleIndex].expiryDate = newExpiryDate
        
        // Close modal
        this.showRenewVehicleModal = false
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
          // Here you would typically make an API call to delete the vehicle
          // For now, we'll just remove it from the local array
          const index = this.vehicles.findIndex(v => v.id === this.deleteVehicleData.id)
          if (index !== -1) {
            this.vehicles.splice(index, 1)
          }
          this.showDeleteConfirmModal = false
          this.$message.success('Xóa xe thành công')
        } catch (error) {
          this.$message.error('Có lỗi xảy ra khi xóa xe')
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