<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý thông báo gia hạn</h1>
        <button 
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
          @click="showCreateNotificationModal = true"
        >
          <Plus class="mr-2 h-4 w-4" />
          Tạo thông báo mới
        </button>
      </div>
  
      <!-- Thống kê -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-4">
              <Users class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số cư dân</p>
              <p class="text-2xl font-bold">{{ statistics.totalResidents }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center mr-4">
              <Clock class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Sắp hết hạn (30 ngày)</p>
              <p class="text-2xl font-bold">{{ statistics.expiringIn30Days }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center mr-4">
              <AlertTriangle class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Sắp hết hạn (7 ngày)</p>
              <p class="text-2xl font-bold">{{ statistics.expiringIn7Days }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center">
            <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center mr-4">
              <CheckCircle class="h-6 w-6" />
            </div>
            <div>
              <p class="text-sm text-gray-500 dark:text-gray-400">Đã gửi thông báo</p>
              <p class="text-2xl font-bold">{{ statistics.notificationsSent }}</p>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Bộ lọc -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col md:flex-row md:items-center gap-4">
          <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời gian hết hạn</label>
              <select 
                v-model="filters.expiryRange" 
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              >
                <option value="all">Tất cả</option>
                <option value="7">Sắp hết hạn trong 7 ngày</option>
                <option value="15">Sắp hết hạn trong 15 ngày</option>
                <option value="30">Sắp hết hạn trong 30 ngày</option>
                <option value="expired">Đã hết hạn</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái thông báo</label>
              <select 
                v-model="filters.notificationStatus" 
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              >
                <option value="all">Tất cả</option>
                <option value="not_sent">Chưa gửi thông báo</option>
                <option value="sent">Đã gửi thông báo</option>
                <option value="read">Đã đọc thông báo</option>
                <option value="renewed">Đã gia hạn</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe</label>
              <select 
                v-model="filters.vehicleType" 
                class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              >
                <option value="all">Tất cả</option>
                <option value="car">Ô tô</option>
                <option value="motorbike">Xe máy</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tìm kiếm</label>
              <div class="relative">
                <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
                <input 
                  type="text" 
                  placeholder="Tên, biển số, căn hộ..." 
                  class="pl-9 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  v-model="filters.search"
                />
              </div>
            </div>
          </div>
          
          <div class="flex-shrink-0 flex space-x-2">
            <button 
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center"
              @click="resetFilters"
            >
              <RefreshCw class="mr-2 h-4 w-4" />
              Đặt lại
            </button>
            <button 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
              @click="applyFilters"
            >
              <Filter class="mr-2 h-4 w-4" />
              Lọc
            </button>
          </div>
        </div>
      </div>
  
      <!-- Danh sách cư dân sắp hết hạn -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
          <h2 class="text-lg font-medium">Danh sách cư dân sắp hết hạn</h2>
          <div class="flex items-center space-x-2">
            <button 
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center"
              @click="exportData"
            >
              <Download class="mr-2 h-4 w-4" />
              Xuất Excel
            </button>
            <button 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
              @click="sendBulkNotifications"
              :disabled="selectedResidents.length === 0"
              :class="{'opacity-50 cursor-not-allowed': selectedResidents.length === 0}"
            >
              <Send class="mr-2 h-4 w-4" />
              Gửi thông báo ({{ selectedResidents.length }})
            </button>
          </div>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      v-model="selectAll"
                      @change="toggleSelectAll"
                    />
                  </div>
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cư dân</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thông tin xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Gói đăng ký</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày hết hạn</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="resident in filteredResidents" :key="resident.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <input 
                      type="checkbox" 
                      :value="resident.id" 
                      v-model="selectedResidents"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                    />
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                        {{ getInitials(resident.name) }}
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium">{{ resident.name }}</div>
                      <div class="text-sm text-gray-500">
                        <div class="flex items-center">
                          <Phone class="h-3 w-3 mr-1" />
                          {{ resident.phone }}
                        </div>
                        <div class="flex items-center">
                          <Mail class="h-3 w-3 mr-1" />
                          {{ resident.email }}
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <component 
                      :is="resident.vehicle.type === 'car' ? Car : Bike" 
                      class="h-5 w-5 mr-2 text-gray-500"
                    />
                    <div>
                      <div class="text-sm font-medium">{{ resident.vehicle.licensePlate }}</div>
                      <div class="text-sm text-gray-500">{{ resident.vehicle.brand }} {{ resident.vehicle.model }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">{{ resident.package.name }}</div>
                  <div class="text-sm text-gray-500">{{ formatCurrency(resident.package.price) }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">{{ formatDate(resident.expiryDate) }}</div>
                  <div 
                    class="text-xs px-2 py-1 rounded-full mt-1 inline-flex items-center"
                    :class="getExpiryStatusClass(resident.expiryDate)"
                  >
                    {{ getExpiryStatus(resident.expiryDate) }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="getNotificationStatusClass(resident.notificationStatus)"
                  >
                    {{ getNotificationStatusText(resident.notificationStatus) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button 
                      class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 p-1 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20"
                      @click="viewNotificationHistory(resident)"
                      title="Lịch sử thông báo"
                    >
                      <History class="h-4 w-4" />
                    </button>
                    <button 
                      class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 p-1 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20"
                      @click="sendNotification(resident)"
                      title="Gửi thông báo"
                    >
                      <Send class="h-4 w-4" />
                    </button>
                    <button 
                      class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 p-1 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/20"
                      @click="markAsRenewed(resident)"
                      title="Đánh dấu đã gia hạn"
                    >
                      <CheckCircle class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredResidents.length === 0">
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                  Không tìm thấy cư dân nào sắp hết hạn
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Phân trang -->
        <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
          <div class="flex-1 flex justify-between sm:hidden">
            <button 
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
              :disabled="pagination.currentPage === 1"
              :class="{'opacity-50 cursor-not-allowed': pagination.currentPage === 1}"
              @click="changePage(pagination.currentPage - 1)"
            >
              Trước
            </button>
            <button 
              class="ml-3 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
              :disabled="pagination.currentPage === pagination.totalPages"
              :class="{'opacity-50 cursor-not-allowed': pagination.currentPage === pagination.totalPages}"
              @click="changePage(pagination.currentPage + 1)"
            >
              Sau
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Hiển thị <span class="font-medium">{{ pagination.startItem }}</span> đến <span class="font-medium">{{ pagination.endItem }}</span> của <span class="font-medium">{{ pagination.totalItems }}</span> kết quả
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                  :disabled="pagination.currentPage === 1"
                  :class="{'opacity-50 cursor-not-allowed': pagination.currentPage === 1}"
                  @click="changePage(1)"
                >
                  <span class="sr-only">Trang đầu</span>
                  <ChevronsLeft class="h-5 w-5" />
                </button>
                <button
                  class="relative inline-flex items-center px-2 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                  :disabled="pagination.currentPage === 1"
                  :class="{'opacity-50 cursor-not-allowed': pagination.currentPage === 1}"
                  @click="changePage(pagination.currentPage - 1)"
                >
                  <span class="sr-only">Trang trước</span>
                  <ChevronLeft class="h-5 w-5" />
                </button>
                
                <template v-for="page in displayedPages" :key="page">
                  <button
                    v-if="page !== '...'"
                    class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                    :class="[
                      page === pagination.currentPage
                        ? 'z-10 bg-blue-50 dark:bg-blue-900/20 border-blue-500 text-blue-600 dark:text-blue-400'
                        : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'
                    ]"
                    @click="changePage(page)"
                  >
                    {{ page }}
                  </button>
                  <span
                    v-else
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300"
                  >
                    ...
                  </span>
                </template>
                
                <button
                  class="relative inline-flex items-center px-2 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                  :disabled="pagination.currentPage === pagination.totalPages"
                  :class="{'opacity-50 cursor-not-allowed': pagination.currentPage === pagination.totalPages}"
                  @click="changePage(pagination.currentPage + 1)"
                >
                  <span class="sr-only">Trang sau</span>
                  <ChevronRight class="h-5 w-5" />
                </button>
                <button
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                  :disabled="pagination.currentPage === pagination.totalPages"
                  :class="{'opacity-50 cursor-not-allowed': pagination.currentPage === pagination.totalPages}"
                  @click="changePage(pagination.totalPages)"
                >
                  <span class="sr-only">Trang cuối</span>
                  <ChevronsRight class="h-5 w-5" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal tạo thông báo mới -->
      <div v-if="showCreateNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Tạo thông báo mới</h3>
            <button @click="showCreateNotificationModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="createNotification">
              <div class="space-y-4">
                <div>
                  <label for="notificationTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề thông báo *</label>
                  <input 
                    id="notificationTitle" 
                    type="text" 
                    v-model="notificationForm.title"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập tiêu đề thông báo"
                  />
                </div>
                
                <div>
                  <label for="notificationContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung thông báo *</label>
                  <textarea 
                    id="notificationContent" 
                    v-model="notificationForm.content"
                    required
                    rows="4"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập nội dung thông báo"
                  ></textarea>
                  <p class="text-xs text-gray-500 mt-1">
                    Bạn có thể sử dụng các biến sau: {TEN_CU_DAN}, {BIEN_SO_XE}, {GOI_DANG_KY}, {NGAY_HET_HAN}, {SO_NGAY_CON_LAI}
                  </p>
                </div>
                
                <div>
                  <label for="notificationTarget" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đối tượng nhận thông báo *</label>
                  <select 
                    id="notificationTarget" 
                    v-model="notificationForm.target"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="all">Tất cả cư dân sắp hết hạn</option>
                    <option value="7days">Cư dân hết hạn trong 7 ngày</option>
                    <option value="15days">Cư dân hết hạn trong 15 ngày</option>
                    <option value="30days">Cư dân hết hạn trong 30 ngày</option>
                    <option value="expired">Cư dân đã hết hạn</option>
                  </select>
                </div>
                
                <div>
                  <label for="notificationMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức gửi *</label>
                  <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                      <input 
                        type="checkbox" 
                        v-model="notificationForm.methods.email"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>Email</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input 
                        type="checkbox" 
                        v-model="notificationForm.methods.sms"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>SMS</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input 
                        type="checkbox" 
                        v-model="notificationForm.methods.app"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>Thông báo ứng dụng</span>
                    </label>
                  </div>
                </div>
                
                <div>
                  <label for="notificationSchedule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lịch gửi thông báo</label>
                  <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                      <input 
                        type="radio" 
                        v-model="notificationForm.scheduleType"
                        value="now"
                        class="rounded-full border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>Gửi ngay</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input 
                        type="radio" 
                        v-model="notificationForm.scheduleType"
                        value="scheduled"
                        class="rounded-full border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>Đặt lịch gửi</span>
                    </label>
                  </div>
                </div>
                
                <div v-if="notificationForm.scheduleType === 'scheduled'" class="space-y-4">
                  <div>
                    <label for="notificationDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày gửi *</label>
                    <input 
                      id="notificationDate" 
                      type="date" 
                      v-model="notificationForm.scheduleDate"
                      required
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    />
                  </div>
                  
                  <div>
                    <label for="notificationTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giờ gửi *</label>
                    <input 
                      id="notificationTime" 
                      type="time" 
                      v-model="notificationForm.scheduleTime"
                      required
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    />
                  </div>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showCreateNotificationModal = false"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    {{ notificationForm.scheduleType === 'now' ? 'Gửi ngay' : 'Đặt lịch' }}
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- Modal lịch sử thông báo -->
      <div v-if="showHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Lịch sử thông báo - {{ selectedResident?.name }}</h3>
            <button @click="showHistoryModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4 max-h-[60vh] overflow-y-auto">
            <div v-if="notificationHistory.length === 0" class="text-center py-8">
              <Bell class="h-12 w-12 mx-auto text-gray-400 mb-4" />
              <p class="text-gray-500">Chưa có thông báo nào được gửi</p>
            </div>
            
            <div v-else class="space-y-4">
              <div 
                v-for="(notification, index) in notificationHistory" 
                :key="index"
                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4"
              >
                <div class="flex items-start">
                  <div 
                    class="w-10 h-10 rounded-full flex items-center justify-center mr-4 flex-shrink-0"
                    :class="getNotificationTypeClass(notification.type)"
                  >
                    <component :is="getNotificationTypeIcon(notification.type)" class="h-5 w-5" />
                  </div>
                  <div class="flex-1">
                    <div class="flex items-center justify-between">
                      <h4 class="font-medium">{{ notification.title }}</h4>
                      <span class="text-xs text-gray-500">{{ formatDateTime(notification.sentAt) }}</span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ notification.content }}</p>
                    <div class="mt-2 flex items-center justify-between">
                      <div class="flex items-center space-x-2">
                        <span 
                          class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="getNotificationStatusClass(notification.status)"
                        >
                          {{ getNotificationStatusText(notification.status) }}
                        </span>
                        <span 
                          v-if="notification.readAt"
                          class="text-xs text-gray-500"
                        >
                          Đã đọc: {{ formatDateTime(notification.readAt) }}
                        </span>
                      </div>
                      <div class="text-xs text-gray-500">
                        Phương thức: {{ getNotificationMethodText(notification.method) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button 
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
              @click="showHistoryModal = false"
            >
              Đóng
            </button>
          </div>
        </div>
      </div>
  
      <!-- Modal gửi thông báo -->
      <div v-if="showSendNotificationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Gửi thông báo</h3>
            <button @click="showSendNotificationModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="mb-4">
              <h4 class="font-medium mb-2">Danh sách người nhận ({{ selectedResidentsForNotification.length }})</h4>
              <div class="max-h-32 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-2">
                <div v-for="resident in selectedResidentsForNotification" :key="resident.id" class="flex items-center justify-between py-1">
                  <div class="flex items-center">
                    <div class="h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-600 dark:text-gray-300 mr-2">
                      {{ getInitials(resident.name) }}
                    </div>
                    <span class="text-sm">{{ resident.name }}</span>
                  </div>
                  <div class="text-xs text-gray-500">{{ resident.vehicle.licensePlate }}</div>
                </div>
              </div>
            </div>
            
            <form @submit.prevent="sendNotificationToSelected">
              <div class="space-y-4">
                <div>
                  <label for="bulkNotificationTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề thông báo *</label>
                  <input 
                    id="bulkNotificationTitle" 
                    type="text" 
                    v-model="sendNotificationForm.title"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập tiêu đề thông báo"
                  />
                </div>
                
                <div>
                  <label for="bulkNotificationContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung thông báo *</label>
                  <textarea 
                    id="bulkNotificationContent" 
                    v-model="sendNotificationForm.content"
                    required
                    rows="4"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập nội dung thông báo"
                  ></textarea>
                  <p class="text-xs text-gray-500 mt-1">
                    Bạn có thể sử dụng các biến sau: {TEN_CU_DAN}, {BIEN_SO_XE}, {GOI_DANG_KY}, {NGAY_HET_HAN}, {SO_NGAY_CON_LAI}
                  </p>
                </div>
                
                <div>
                  <label for="bulkNotificationMethod" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức gửi *</label>
                  <div class="space-y-2">
                    <label class="flex items-center space-x-2">
                      <input 
                        type="checkbox" 
                        v-model="sendNotificationForm.methods.email"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>Email</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input 
                        type="checkbox" 
                        v-model="sendNotificationForm.methods.sms"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>SMS</span>
                    </label>
                    <label class="flex items-center space-x-2">
                      <input 
                        type="checkbox" 
                        v-model="sendNotificationForm.methods.app"
                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      />
                      <span>Thông báo ứng dụng</span>
                    </label>
                  </div>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showSendNotificationModal = false"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Gửi thông báo
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- Toast thông báo -->
      <div 
        v-if="showToast" 
        class="fixed bottom-4 right-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 flex items-center max-w-sm transition-all duration-300"
        :class="[
          toastType === 'success' ? 'border-l-4 border-l-green-500' : 'border-l-4 border-l-red-500'
        ]"
      >
        <div 
          class="w-8 h-8 rounded-full flex items-center justify-center mr-3"
          :class="[
            toastType === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'
          ]"
        >
          <component :is="toastType === 'success' ? CheckCircle : AlertCircle" class="h-5 w-5" />
        </div>
        <div class="flex-1">
          <p class="font-medium">{{ toastTitle }}</p>
          <p class="text-sm text-gray-500">{{ toastMessage }}</p>
        </div>
        <button @click="showToast = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 ml-4">
          <X class="h-5 w-5" />
        </button>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed, onMounted } from 'vue'
  import { 
    Plus, 
    Search, 
    Filter, 
    RefreshCw, 
    Download, 
    Send, 
    CheckCircle, 
    AlertCircle, 
    X, 
    Users, 
    Clock, 
    AlertTriangle, 
    Car, 
    Bike, 
    Phone, 
    Mail, 
    History, 
    Bell, 
    ChevronLeft, 
    ChevronRight, 
    ChevronsLeft, 
    ChevronsRight 
  } from 'lucide-vue-next'
  
  export default {
    name: 'QuanLyThongBao',
    components: {
      Plus, 
      Search, 
      Filter, 
      RefreshCw, 
      Download, 
      Send, 
      CheckCircle, 
      AlertCircle, 
      X, 
      Users, 
      Clock, 
      AlertTriangle, 
      Car, 
      Bike, 
      Phone, 
      Mail, 
      History, 
      Bell, 
      ChevronLeft, 
      ChevronRight, 
      ChevronsLeft, 
      ChevronsRight
    },
    setup() {
      // Thống kê
      const statistics = ref({
        totalResidents: 120,
        expiringIn30Days: 28,
        expiringIn7Days: 12,
        notificationsSent: 45
      })
  
      // Bộ lọc
      const filters = ref({
        expiryRange: 'all',
        notificationStatus: 'all',
        vehicleType: 'all',
        search: ''
      })
  
      // Danh sách cư dân
      const residents = ref([])
      const selectedResidents = ref([])
      const selectAll = ref(false)
  
      // Phân trang
      const pagination = ref({
        currentPage: 1,
        itemsPerPage: 10,
        totalItems: 0,
        totalPages: 1,
        startItem: 1,
        endItem: 10
      })
  
      // Modals
      const showCreateNotificationModal = ref(false)
      const showHistoryModal = ref(false)
      const showSendNotificationModal = ref(false)
      const selectedResident = ref(null)
      const notificationHistory = ref([])
  
      // Toast
      const showToast = ref(false)
      const toastType = ref('success')
      const toastTitle = ref('')
      const toastMessage = ref('')
  
      // Forms
      const notificationForm = ref({
        title: '',
        content: '',
        target: 'all',
        methods: {
          email: true,
          sms: true,
          app: true
        },
        scheduleType: 'now',
        scheduleDate: '',
        scheduleTime: ''
      })
  
      const sendNotificationForm = ref({
        title: 'Thông báo gia hạn gói gửi xe',
        content: 'Kính gửi {TEN_CU_DAN},\n\nGói gửi xe {GOI_DANG_KY} của xe {BIEN_SO_XE} sẽ hết hạn vào ngày {NGAY_HET_HAN} (còn {SO_NGAY_CON_LAI} ngày).\n\nVui lòng gia hạn để tiếp tục sử dụng dịch vụ.\n\nTrân trọng,\nBan quản lý',
        methods: {
          email: true,
          sms: true,
          app: true
        }
      })
  
      // Lấy dữ liệu khi component được tạo
      onMounted(() => {
        fetchData()
        
        // Thiết lập ngày mặc định cho lịch gửi thông báo
        const today = new Date()
        const formattedDate = today.toISOString().split('T')[0]
        const formattedTime = `${today.getHours().toString().padStart(2, '0')}:${today.getMinutes().toString().padStart(2, '0')}`
        
        notificationForm.value.scheduleDate = formattedDate
        notificationForm.value.scheduleTime = formattedTime
      })
  
      // Lấy dữ liệu từ server
      const fetchData = async () => {
        try {
          // Trong thực tế, bạn sẽ gọi API để lấy dữ liệu
          // Ở đây chúng ta sẽ sử dụng dữ liệu mẫu
          const mockData = generateMockData()
          residents.value = mockData
          
          // Cập nhật phân trang
          pagination.value.totalItems = mockData.length
          pagination.value.totalPages = Math.ceil(mockData.length / pagination.value.itemsPerPage)
          updatePaginationInfo()
        } catch (error) {
          console.error('Lỗi khi lấy dữ liệu:', error)
          showToastMessage('error', 'Lỗi', 'Không thể tải dữ liệu. Vui lòng thử lại sau.')
        }
      }
  
      // Tạo dữ liệu mẫu
      const generateMockData = () => {
        const data = []
        const today = new Date()
        
        const packages = [
          { id: 1, name: 'Gói 1 tháng', price: 150000, duration: 30 },
          { id: 2, name: 'Gói 3 tháng', price: 400000, duration: 90 },
          { id: 3, name: 'Gói 6 tháng', price: 750000, duration: 180 },
          { id: 4, name: 'Gói 12 tháng', price: 1400000, duration: 365 }
        ]
        
        const names = [
          'Nguyễn Văn An', 'Trần Thị Bình', 'Lê Văn Cường', 'Phạm Thị Dung', 
          'Hoàng Văn Em', 'Ngô Thị Phương', 'Đỗ Văn Giáp', 'Vũ Thị Hoa', 
          'Đinh Văn Inh', 'Bùi Thị Khanh', 'Lý Văn Lâm', 'Mai Thị Ngọc',
          'Trương Văn Oanh', 'Dương Thị Phúc', 'Hồ Văn Quang', 'Đặng Thị Rạng'
        ]
        
        const vehicleBrands = {
          car: [
            { brand: 'Toyota', models: ['Camry', 'Corolla', 'Vios', 'Fortuner'] },
            { brand: 'Honda', models: ['Civic', 'Accord', 'City', 'CR-V'] },
            { brand: 'Ford', models: ['Ranger', 'Everest', 'EcoSport', 'Focus'] },
            { brand: 'Mazda', models: ['Mazda3', 'Mazda6', 'CX-5', 'CX-8'] }
          ],
          motorbike: [
            { brand: 'Honda', models: ['SH', 'Air Blade', 'Vision', 'Wave'] },
            { brand: 'Yamaha', models: ['Exciter', 'NVX', 'Janus', 'Sirius'] },
            { brand: 'Suzuki', models: ['Raider', 'Impulse', 'Address', 'GD110'] },
            { brand: 'Piaggio', models: ['Vespa', 'Liberty', 'Medley', 'Zip'] }
          ]
        }
        
        const notificationStatuses = ['not_sent', 'sent', 'read', 'renewed']
        
        for (let i = 1; i <= 50; i++) {
          const nameIndex = Math.floor(Math.random() * names.length)
          const name = names[nameIndex]
          
          const vehicleType = Math.random() > 0.5 ? 'car' : 'motorbike'
          const brandIndex = Math.floor(Math.random() * vehicleBrands[vehicleType].length)
          const brand = vehicleBrands[vehicleType][brandIndex]
          const modelIndex = Math.floor(Math.random() * brand.models.length)
          const model = brand.models[modelIndex]
          
          const packageIndex = Math.floor(Math.random() * packages.length)
          const pkg = packages[packageIndex]
          
          // Tạo ngày hết hạn ngẫu nhiên trong khoảng -10 đến +60 ngày
          const daysOffset = Math.floor(Math.random() * 70) - 10
          const expiryDate = new Date(today)
          expiryDate.setDate(expiryDate.getDate() + daysOffset)
          
          // Trạng thái thông báo dựa trên ngày hết hạn
          let notificationStatus
          if (daysOffset < 0) {
            // Đã hết hạn
            notificationStatus = Math.random() > 0.5 ? 'sent' : 'read'
          } else if (daysOffset < 7) {
            // Sắp hết hạn trong 7 ngày
            notificationStatus = notificationStatuses[Math.floor(Math.random() * 3)]
          } else if (daysOffset < 30) {
            // Sắp hết hạn trong 30 ngày
            notificationStatus = Math.random() > 0.7 ? 'not_sent' : 'sent'
          } else {
            // Còn lâu mới hết hạn
            notificationStatus = 'not_sent'
          }
          
          // Nếu đã gia hạn, thì ngày hết hạn sẽ là trong tương lai xa
          if (notificationStatus === 'renewed') {
            expiryDate.setDate(today.getDate() + pkg.duration)
          }
          
          data.push({
            id: i,
            name: name,
            phone: `09${Math.floor(10000000 + Math.random() * 90000000)}`,
            email: `${name.toLowerCase().replace(/\s+/g, '.')}@gmail.com`,
            vehicle: {
              type: vehicleType,
              licensePlate: vehicleType === 'car' 
                ? `5${String.fromCharCode(65 + Math.floor(Math.random() * 26))}-${Math.floor(10000 + Math.random() * 90000)}`
                : `5${String.fromCharCode(65 + Math.floor(Math.random() * 26))}1-${Math.floor(10000 + Math.random() * 90000)}`,
              brand: brand.brand,
              model: model
            },
            package: pkg,
            expiryDate: expiryDate,
            notificationStatus: notificationStatus
          })
        }
        
        return data
      }
  
      // Lọc cư dân
      const filteredResidents = computed(() => {
        let result = [...residents.value]
        const today = new Date()
        
        // Lọc theo thời gian hết hạn
        if (filters.value.expiryRange !== 'all') {
          if (filters.value.expiryRange === 'expired') {
            // Đã hết hạn
            result = result.filter(resident => new Date(resident.expiryDate) < today)
          } else {
            // Sắp hết hạn trong X ngày
            const days = parseInt(filters.value.expiryRange)
            const futureDate = new Date(today)
            futureDate.setDate(futureDate.getDate() + days)
            
            result = result.filter(resident => {
              const expiryDate = new Date(resident.expiryDate)
              return expiryDate >= today && expiryDate <= futureDate
            })
          }
        }
        
        // Lọc theo trạng thái thông báo
        if (filters.value.notificationStatus !== 'all') {
          result = result.filter(resident => resident.notificationStatus === filters.value.notificationStatus)
        }
        
        // Lọc theo loại xe
        if (filters.value.vehicleType !== 'all') {
          result = result.filter(resident => resident.vehicle.type === filters.value.vehicleType)
        }
        
        // Lọc theo tìm kiếm
        if (filters.value.search) {
          const searchLower = filters.value.search.toLowerCase()
          result = result.filter(resident => 
            resident.name.toLowerCase().includes(searchLower) ||
            resident.vehicle.licensePlate.toLowerCase().includes(searchLower) ||
            resident.phone.includes(searchLower) ||
            resident.email.toLowerCase().includes(searchLower)
          )
        }
        
        // Cập nhật phân trang
        pagination.value.totalItems = result.length
        pagination.value.totalPages = Math.ceil(result.length / pagination.value.itemsPerPage)
        
        // Phân trang
        const startIndex = (pagination.value.currentPage - 1) * pagination.value.itemsPerPage
        const endIndex = startIndex + pagination.value.itemsPerPage
        
        return result.slice(startIndex, endIndex)
      })
  
      // Cập nhật thông tin phân trang
      const updatePaginationInfo = () => {
        const startItem = (pagination.value.currentPage - 1) * pagination.value.itemsPerPage + 1
        const endItem = Math.min(startItem + pagination.value.itemsPerPage - 1, pagination.value.totalItems)
        
        pagination.value.startItem = startItem
        pagination.value.endItem = endItem
      }
  
      // Tính toán các trang hiển thị
      const displayedPages = computed(() => {
        const totalPages = pagination.value.totalPages
        const currentPage = pagination.value.currentPage
        
        if (totalPages <= 7) {
          return Array.from({ length: totalPages }, (_, i) => i + 1)
        }
        
        if (currentPage <= 3) {
          return [1, 2, 3, 4, 5, '...', totalPages]
        }
        
        if (currentPage >= totalPages - 2) {
          return [1, '...', totalPages - 4, totalPages - 3, totalPages - 2, totalPages - 1, totalPages]
        }
        
        return [1, '...', currentPage - 1, currentPage, currentPage + 1, '...', totalPages]
      })
  
      // Chọn tất cả
      const toggleSelectAll = () => {
        if (selectAll.value) {
          selectedResidents.value = filteredResidents.value.map(resident => resident.id)
        } else {
          selectedResidents.value = []
        }
      }
  
      // Đặt lại bộ lọc
      const resetFilters = () => {
        filters.value = {
          expiryRange: 'all',
          notificationStatus: 'all',
          vehicleType: 'all',
          search: ''
        }
        applyFilters()
      }
  
      // Áp dụng bộ lọc
      const applyFilters = () => {
        pagination.value.currentPage = 1
        updatePaginationInfo()
      }
  
      // Thay đổi trang
      const changePage = (page) => {
        pagination.value.currentPage = page
        updatePaginationInfo()
      }
  
      // Xuất dữ liệu
      const exportData = () => {
        // Trong thực tế, bạn sẽ gọi API để xuất dữ liệu
        showToastMessage('success', 'Xuất dữ liệu thành công', 'Dữ liệu đã được xuất ra file Excel')
      }
  
      // Gửi thông báo hàng loạt
      const sendBulkNotifications = () => {
        if (selectedResidents.value.length === 0) {
          showToastMessage('error', 'Lỗi', 'Vui lòng chọn ít nhất một cư dân để gửi thông báo')
          return
        }
        
        showSendNotificationModal.value = true
      }
  
      // Danh sách cư dân đã chọn để gửi thông báo
      const selectedResidentsForNotification = computed(() => {
        return residents.value.filter(resident => selectedResidents.value.includes(resident.id))
      })
  
      // Gửi thông báo đến các cư dân đã chọn
      const sendNotificationToSelected = () => {
        // Kiểm tra phương thức gửi
        if (!sendNotificationForm.value.methods.email && !sendNotificationForm.value.methods.sms && !sendNotificationForm.value.methods.app) {
          showToastMessage('error', 'Lỗi', 'Vui lòng chọn ít nhất một phương thức gửi thông báo')
          return
        }
        
        // Trong thực tế, bạn sẽ gọi API để gửi thông báo
        
        // Cập nhật trạng thái thông báo
        selectedResidentsForNotification.value.forEach(resident => {
          const index = residents.value.findIndex(r => r.id === resident.id)
          if (index !== -1) {
            residents.value[index].notificationStatus = 'sent'
          }
        })
        
        showSendNotificationModal.value = false
        showToastMessage('success', 'Gửi thông báo thành công', `Đã gửi thông báo đến ${selectedResidentsForNotification.value.length} cư dân`)
        
        // Cập nhật thống kê
        statistics.value.notificationsSent += selectedResidentsForNotification.value.length
        
        // Đặt lại danh sách đã chọn
        selectedResidents.value = []
        selectAll.value = false
      }
  
      // Xem lịch sử thông báo
      const viewNotificationHistory = (resident) => {
        selectedResident.value = resident
        
        // Trong thực tế, bạn sẽ gọi API để lấy lịch sử thông báo
        notificationHistory.value = generateMockNotificationHistory(resident)
        
        showHistoryModal.value = true
      }
  
      // Tạo lịch sử thông báo mẫu
      const generateMockNotificationHistory = (resident) => {
        const history = []
        const today = new Date()
        
        // Tạo 0-5 thông báo ngẫu nhiên
        const count = Math.floor(Math.random() * 6)
        
        for (let i = 0; i < count; i++) {
          const daysAgo = Math.floor(Math.random() * 30)
          const sentDate = new Date(today)
          sentDate.setDate(sentDate.getDate() - daysAgo)
          
          const status = Math.random() > 0.3 ? 'read' : 'sent'
          
          let readDate = null
          if (status === 'read') {
            readDate = new Date(sentDate)
            readDate.setHours(readDate.getHours() + Math.floor(Math.random() * 24))
          }
          
          const method = ['email', 'sms', 'app'][Math.floor(Math.random() * 3)]
          
          history.push({
            id: i + 1,
            title: 'Thông báo gia hạn gói gửi xe',
            content: `Kính gửi ${resident.name},\n\nGói gửi xe ${resident.package.name} của xe ${resident.vehicle.licensePlate} sẽ hết hạn vào ngày ${formatDate(resident.expiryDate)}.\n\nVui lòng gia hạn để tiếp tục sử dụng dịch vụ.\n\nTrân trọng,\nBan quản lý`,
            type: 'expiry',
            status: status,
            method: method,
            sentAt: sentDate,
            readAt: readDate
          })
        }
        
        // Sắp xếp theo thời gian gửi giảm dần
        return history.sort((a, b) => new Date(b.sentAt) - new Date(a.sentAt))
      }
  
      // Gửi thông báo cho một cư dân
      const sendNotification = (resident) => {
        selectedResidents.value = [resident.id]
        sendBulkNotifications()
      }
  
      // Đánh dấu đã gia hạn
      const markAsRenewed = (resident) => {
        const index = residents.value.findIndex(r => r.id === resident.id)
        if (index !== -1) {
          // Cập nhật trạng thái
          residents.value[index].notificationStatus = 'renewed'
          
          // Cập nhật ngày hết hạn
          const newExpiryDate = new Date(resident.expiryDate)
          newExpiryDate.setDate(newExpiryDate.getDate() + resident.package.duration)
          residents.value[index].expiryDate = newExpiryDate
          
          showToastMessage('success', 'Cập nhật thành công', `Đã đánh dấu ${resident.name} đã gia hạn gói gửi xe`)
        }
      }
  
      // Tạo thông báo mới
      const createNotification = () => {
        // Kiểm tra phương thức gửi
        if (!notificationForm.value.methods.email && !notificationForm.value.methods.sms && !notificationForm.value.methods.app) {
          showToastMessage('error', 'Lỗi', 'Vui lòng chọn ít nhất một phương thức gửi thông báo')
          return
        }
        
        // Trong thực tế, bạn sẽ gọi API để tạo thông báo
        
        let message = ''
        if (notificationForm.value.scheduleType === 'now') {
          message = 'Đã gửi thông báo thành công'
          
          // Cập nhật thống kê
          statistics.value.notificationsSent += getTargetResidentsCount()
        } else {
          const scheduleDateTime = `${notificationForm.value.scheduleDate} ${notificationForm.value.scheduleTime}`
          message = `Đã đặt lịch gửi thông báo vào ${scheduleDateTime}`
        }
        
        showCreateNotificationModal.value = false
        showToastMessage('success', 'Thành công', message)
        
        // Đặt lại form
        resetNotificationForm()
      }
  
      // Đặt lại form thông báo
      const resetNotificationForm = () => {
        const today = new Date()
        const formattedDate = today.toISOString().split('T')[0]
        const formattedTime = `${today.getHours().toString().padStart(2, '0')}:${today.getMinutes().toString().padStart(2, '0')}`
        
        notificationForm.value = {
          title: '',
          content: '',
          target: 'all',
          methods: {
            email: true,
            sms: true,
            app: true
          },
          scheduleType: 'now',
          scheduleDate: formattedDate,
          scheduleTime: formattedTime
        }
      }
  
      // Lấy số lượng cư dân mục tiêu
      const getTargetResidentsCount = () => {
        const today = new Date()
        
        switch (notificationForm.value.target) {
          case '7days':
            const sevenDaysLater = new Date(today)
            sevenDaysLater.setDate(sevenDaysLater.getDate() + 7)
            return residents.value.filter(resident => {
              const expiryDate = new Date(resident.expiryDate)
              return expiryDate >= today && expiryDate <= sevenDaysLater
            }).length
          case '15days':
            const fifteenDaysLater = new Date(today)
            fifteenDaysLater.setDate(fifteenDaysLater.getDate() + 15)
            return residents.value.filter(resident => {
              const expiryDate = new Date(resident.expiryDate)
              return expiryDate >= today && expiryDate <= fifteenDaysLater
            }).length
          case '30days':
            const thirtyDaysLater = new Date(today)
            thirtyDaysLater.setDate(thirtyDaysLater.getDate() + 30)
            return residents.value.filter(resident => {
              const expiryDate = new Date(resident.expiryDate)
              return expiryDate >= today && expiryDate <= thirtyDaysLater
            }).length
          case 'expired':
            return residents.value.filter(resident => new Date(resident.expiryDate) < today).length
          default:
            return residents.value.length
        }
      }
  
      // Lấy chữ cái đầu của tên
      const getInitials = (name) => {
        return name
          .split(' ')
          .map(word => word.charAt(0))
          .join('')
          .toUpperCase()
          .substring(0, 2)
      }
  
      // Định dạng ngày
      const formatDate = (date) => {
        return new Date(date).toLocaleDateString('vi-VN', {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit'
        })
      }
  
      // Định dạng ngày giờ
      const formatDateTime = (date) => {
        return new Date(date).toLocaleString('vi-VN', {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit'
        })
      }
  
      // Định dạng tiền tệ
      const formatCurrency = (amount) => {
        return new Intl.NumberFormat('vi-VN', {
          style: 'currency',
          currency: 'VND'
        }).format(amount || 0)
      }
  
      // Lấy trạng thái hết hạn
      const getExpiryStatus = (date) => {
        const today = new Date()
        const expiryDate = new Date(date)
        const diffTime = expiryDate - today
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
        
        if (diffDays < 0) {
          return 'Đã hết hạn'
        } else if (diffDays === 0) {
          return 'Hết hạn hôm nay'
        } else if (diffDays <= 7) {
          return `Còn ${diffDays} ngày`
        } else if (diffDays <= 30) {
          return `Còn ${diffDays} ngày`
        } else {
          return `Còn ${diffDays} ngày`
        }
      }
  
      // Lấy class cho trạng thái hết hạn
      const getExpiryStatusClass = (date) => {
        const today = new Date()
        const expiryDate = new Date(date)
        const diffTime = expiryDate - today
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
        
        if (diffDays < 0) {
          return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
        } else if (diffDays <= 7) {
          return 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300'
        } else if (diffDays <= 30) {
          return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
        } else {
          return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        }
      }
  
      // Lấy text cho trạng thái thông báo
      const getNotificationStatusText = (status) => {
        switch (status) {
          case 'not_sent':
            return 'Chưa gửi thông báo'
          case 'sent':
            return 'Đã gửi thông báo'
          case 'read':
            return 'Đã đọc thông báo'
          case 'renewed':
            return 'Đã gia hạn'
          default:
            return 'Không xác định'
        }
      }
  
      // Lấy class cho trạng thái thông báo
      const getNotificationStatusClass = (status) => {
        switch (status) {
          case 'not_sent':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
          case 'sent':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
          case 'read':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300'
          case 'renewed':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
          default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        }
      }
  
      // Lấy text cho phương thức thông báo
      const getNotificationMethodText = (method) => {
        switch (method) {
          case 'email':
            return 'Email'
          case 'sms':
            return 'SMS'
          case 'app':
            return 'Ứng dụng'
          default:
            return 'Không xác định'
        }
      }
  
      // Lấy class cho loại thông báo
      const getNotificationTypeClass = (type) => {
        switch (type) {
          case 'expiry':
            return 'bg-amber-100 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400'
          case 'payment':
            return 'bg-green-100 text-green-600 dark:bg-green-900/20 dark:text-green-400'
          case 'system':
            return 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400'
          default:
            return 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400'
        }
      }
  
      // Lấy icon cho loại thông báo
      const getNotificationTypeIcon = (type) => {
        switch (type) {
          case 'expiry':
            return Clock
          case 'payment':
            return CheckCircle
          case 'system':
            return Bell
          default:
            return Bell
        }
      }
  
      // Hiển thị thông báo
      const showToastMessage = (type, title, message) => {
        toastType.value = type
        toastTitle.value = title
        toastMessage.value = message
        showToast.value = true
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
          showToast.value = false
        }, 3000)
      }
  
      return {
        statistics,
        filters,
        residents,
        selectedResidents,
        selectAll,
        pagination,
        showCreateNotificationModal,
        showHistoryModal,
        showSendNotificationModal,
        selectedResident,
        notificationHistory,
        showToast,
        toastType,
        toastTitle,
        toastMessage,
        notificationForm,
        sendNotificationForm,
        filteredResidents,
        displayedPages,
        selectedResidentsForNotification,
        toggleSelectAll,
        resetFilters,
        applyFilters,
        changePage,
        exportData,
        sendBulkNotifications,
        sendNotificationToSelected,
        viewNotificationHistory,
        sendNotification,
        markAsRenewed,
        createNotification,
        getInitials,
        formatDate,
        formatDateTime,
        formatCurrency,
        getExpiryStatus,
        getExpiryStatusClass,
        getNotificationStatusText,
        getNotificationStatusClass,
        getNotificationMethodText,
        getNotificationTypeClass,
        getNotificationTypeIcon
      }
    }
  }
  </script>