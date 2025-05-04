<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Gửi báo cáo / sự cố</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showCreateReportModal = true">
            <Plus class="mr-2 h-4 w-4" />
            Tạo báo cáo mới
          </button>
        </div>
      </div>
  
      <!-- Reports Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng số báo cáo</p>
              <h3 class="text-2xl font-bold">{{ reportStats.total }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <ClipboardList class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đang xử lý</p>
              <h3 class="text-2xl font-bold">{{ reportStats.processing }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
              <Clock class="h-5 w-5 text-yellow-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đã giải quyết</p>
              <h3 class="text-2xl font-bold">{{ reportStats.resolved }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <CheckCircle class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đã đóng</p>
              <h3 class="text-2xl font-bold">{{ reportStats.closed }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
              <Archive class="h-5 w-5 text-gray-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">Lọc theo:</span>
            <select 
              v-model="filterType" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả loại</option>
              <option value="incident">Sự cố</option>
              <option value="complaint">Khiếu nại</option>
              <option value="suggestion">Đề xuất</option>
              <option value="visitor">Khách vãng lai</option>
            </select>
            <select 
              v-model="filterStatus" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả trạng thái</option>
              <option value="pending">Chờ xử lý</option>
              <option value="processing">Đang xử lý</option>
              <option value="resolved">Đã giải quyết</option>
              <option value="closed">Đã đóng</option>
            </select>
          </div>
          <div class="relative">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="text" 
              placeholder="Tìm kiếm báo cáo..." 
              class="pl-9 h-10 w-full sm:w-[300px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="searchQuery"
            />
          </div>
        </div>
      </div>
  
      <!-- Reports List -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiêu đề</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người xử lý</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="report in filteredReports" :key="report.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ report.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <component :is="getReportIcon(report.type)" class="h-4 w-4 mr-2" :class="getReportIconColor(report.type)" />
                    <span>{{ report.title }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ getReportTypeText(report.type) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ report.date }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': report.status === 'pending',
                      'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': report.status === 'processing',
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': report.status === 'resolved',
                      'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300': report.status === 'closed'
                    }">
                    {{ getStatusText(report.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ report.assignee || 'Chưa phân công' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="viewReport(report)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button v-if="report.status !== 'closed'" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="editReport(report)">
                      <Edit class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredReports.length === 0">
                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                  Không tìm thấy báo cáo nào
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
              Trước
            </button>
            <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
              Sau
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">10</span> của <span class="font-medium">{{ reports.length }}</span> kết quả
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <span class="sr-only">Trang trước</span>
                  <ChevronLeft class="h-5 w-5" />
                </button>
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                  1
                </button>
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700">
                  2
                </button>
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                  3
                </button>
                <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                  <span class="sr-only">Trang sau</span>
                  <ChevronRight class="h-5 w-5" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Create Report Modal -->
      <div v-if="showCreateReportModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Tạo báo cáo mới</h3>
            <button @click="showCreateReportModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <form @submit.prevent="createReport">
              <div class="space-y-4">
                <div>
                  <label for="reportTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề *</label>
                  <input 
                    id="reportTitle" 
                    type="text" 
                    v-model="newReport.title"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập tiêu đề báo cáo"
                  />
                </div>
                
                <div>
                  <label for="reportType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại báo cáo *</label>
                  <select 
                    id="reportType" 
                    v-model="newReport.type"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="incident">Sự cố</option>
                    <option value="complaint">Khiếu nại</option>
                    <option value="suggestion">Đề xuất</option>
                    <option value="visitor">Khách vãng lai</option>
                  </select>
                </div>
                
                <div v-if="newReport.type === 'incident' || newReport.type === 'complaint'">
                  <label for="relatedVehicle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xe liên quan</label>
                  <select 
                    id="relatedVehicle" 
                    v-model="newReport.relatedVehicle"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  >
                    <option value="">Không liên quan đến xe</option>
                    <option v-for="vehicle in vehicles" :key="vehicle.id" :value="vehicle.licensePlate">
                      {{ vehicle.licensePlate }}
                    </option>
                  </select>
                </div>
                
                <div>
                  <label for="reportDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả chi tiết *</label>
                  <textarea 
                    id="reportDescription" 
                    v-model="newReport.description"
                    required
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Mô tả chi tiết về sự cố/vấn đề"
                    rows="4"
                  ></textarea>
                </div>
                
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hình ảnh đính kèm</label>
                  <div class="border border-dashed border-gray-300 dark:border-gray-600 rounded-md p-4">
                    <div class="flex flex-col items-center justify-center">
                      <Upload class="h-8 w-8 text-gray-400 mb-2" />
                      <p class="text-sm text-gray-500">Kéo và thả hoặc nhấp để tải lên</p>
                      <p class="text-xs text-gray-500 mt-1">Hỗ trợ JPG, PNG (tối đa 5MB/ảnh)</p>
                      <input type="file" class="hidden" ref="fileInput" multiple @change="handleFileUpload" accept="image/*" />
                      <button 
                        type="button" 
                        class="mt-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 text-sm"
                        @click="$refs.fileInput.click()"
                      >
                        Chọn ảnh
                      </button>
                    </div>
                    <div v-if="newReport.images.length > 0" class="mt-4 grid grid-cols-3 gap-2">
                      <div v-for="(image, index) in newReport.images" :key="index" class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                        <img :src="image.preview" alt="Preview" class="w-full h-full object-cover" />
                        <button 
                          type="button" 
                          class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                          @click="removeImage(index)"
                        >
                          <X class="h-3 w-3" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div v-if="newReport.type === 'visitor'">
                  <label for="visitorInfo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thông tin khách vãng lai</label>
                  <div class="space-y-2">
                    <input 
                      type="text" 
                      v-model="newReport.visitorInfo.name"
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Tên khách"
                    />
                    <input 
                      type="text" 
                      v-model="newReport.visitorInfo.phone"
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Số điện thoại"
                    />
                    <input 
                      type="text" 
                      v-model="newReport.visitorInfo.licensePlate"
                      class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      placeholder="Biển số xe (nếu có)"
                    />
                  </div>
                </div>
                
                <div class="pt-4 flex justify-end space-x-2">
                  <button 
                    type="button" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                    @click="showCreateReportModal = false"
                  >
                    Hủy
                  </button>
                  <button 
                    type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  >
                    Gửi báo cáo
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
  
      <!-- View Report Modal -->
      <div v-if="selectedReport" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết báo cáo</h3>
            <button @click="selectedReport = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div>
                  <h2 class="text-xl font-bold">{{ selectedReport.title }}</h2>
                  <p class="text-sm text-gray-500">{{ selectedReport.id }} - {{ selectedReport.date }}</p>
                </div>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="{
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedReport.status === 'pending',
                    'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': selectedReport.status === 'processing',
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedReport.status === 'resolved',
                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300': selectedReport.status === 'closed'
                  }">
                  {{ getStatusText(selectedReport.status) }}
                </span>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Loại báo cáo</p>
                  <div class="flex items-center">
                    <component :is="getReportIcon(selectedReport.type)" class="h-4 w-4 mr-2" :class="getReportIconColor(selectedReport.type)" />
                    <p class="font-medium">{{ getReportTypeText(selectedReport.type) }}</p>
                  </div>
                </div>
                <div v-if="selectedReport.relatedVehicle">
                  <p class="text-sm text-gray-500">Xe liên quan</p>
                  <p class="font-medium">{{ selectedReport.relatedVehicle }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Người xử lý</p>
                  <p class="font-medium">{{ selectedReport.assignee || 'Chưa phân công' }}</p>
                </div>
                <div v-if="selectedReport.resolvedDate">
                  <p class="text-sm text-gray-500">Ngày giải quyết</p>
                  <p class="font-medium">{{ selectedReport.resolvedDate }}</p>
                </div>
              </div>
              
              <div>
                <p class="text-sm text-gray-500">Mô tả</p>
                <p class="mt-1 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ selectedReport.description }}</p>
              </div>
              
              <div v-if="selectedReport.type === 'visitor' && selectedReport.visitorInfo">
                <p class="text-sm text-gray-500 font-medium">Thông tin khách vãng lai</p>
                <div class="mt-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                      <p class="text-xs text-gray-500">Tên khách</p>
                      <p class="text-sm">{{ selectedReport.visitorInfo.name || 'Không có' }}</p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500">Số điện thoại</p>
                      <p class="text-sm">{{ selectedReport.visitorInfo.phone || 'Không có' }}</p>
                    </div>
                    <div>
                      <p class="text-xs text-gray-500">Biển số xe</p>
                      <p class="text-sm">{{ selectedReport.visitorInfo.licensePlate || 'Không có' }}</p>
                    </div>
                  </div>
                </div>
              </div>
              
              <div v-if="selectedReport.images && selectedReport.images.length > 0">
                <p class="text-sm text-gray-500 font-medium">Hình ảnh đính kèm</p>
                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-2">
                  <div v-for="(image, index) in selectedReport.images" :key="index" class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                    <img :src="image.preview || image.url" alt="Report image" class="w-full h-full object-cover" />
                  </div>
                </div>
              </div>
              
              <div v-if="selectedReport.responses && selectedReport.responses.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <p class="text-sm text-gray-500 font-medium">Phản hồi</p>
                <div class="mt-2 space-y-4">
                  <div v-for="(response, index) in selectedReport.responses" :key="index" class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
                    <div class="flex justify-between items-start">
                      <div>
                        <p class="text-sm font-medium">{{ response.from }}</p>
                        <p class="text-xs text-gray-500">{{ response.date }}</p>
                      </div>
                      <span v-if="response.isOfficial" class="px-2 py-0.5 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 text-xs rounded-full">
                        Phản hồi chính thức
                      </span>
                    </div>
                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ response.message }}</p>
                  </div>
                </div>
              </div>
              
              <div v-if="selectedReport.status !== 'closed'" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <p class="text-sm text-gray-500 font-medium">Thêm phản hồi</p>
                <div class="mt-2">
                  <textarea 
                    v-model="newResponse"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                    placeholder="Nhập phản hồi của bạn..."
                    rows="3"
                  ></textarea>
                  <div class="mt-2 flex justify-end">
                    <button 
                      class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                      @click="addResponse(selectedReport)"
                    >
                      Gửi phản hồi
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Plus, ClipboardList, Clock, CheckCircle, Archive, Search, Eye, Edit, X, Upload, ChevronLeft, ChevronRight } from 'lucide-vue-next'

// Data
const showCreateReportModal = ref(false)
const selectedReport = ref(null)
const newResponse = ref('')
const filterType = ref('')
const filterStatus = ref('')
const searchQuery = ref('')

// Mock data for demonstration
const reportStats = ref({
  total: 15,
  processing: 5,
  resolved: 7,
  closed: 3
})

const reports = ref([
  {
    id: 'RPT-001',
    title: 'Sự cố hỏng cổng ra vào',
    type: 'incident',
    date: '2024-03-15',
    status: 'processing',
    assignee: 'Nguyễn Văn A',
    description: 'Cổng ra vào không hoạt động, cần kiểm tra và sửa chữa',
    relatedVehicle: '51A-12345',
    images: []
  },
  // Add more mock reports as needed
])

const vehicles = ref([
  { id: 1, licensePlate: '51A-12345' },
  { id: 2, licensePlate: '51B-67890' }
])

const newReport = ref({
  title: '',
  type: '',
  description: '',
  relatedVehicle: '',
  images: [],
  visitorInfo: {
    name: '',
    phone: '',
    licensePlate: ''
  }
})

// Computed
const filteredReports = computed(() => {
  return reports.value.filter(report => {
    const matchesType = !filterType.value || report.type === filterType.value
    const matchesStatus = !filterStatus.value || report.status === filterStatus.value
    const matchesSearch = !searchQuery.value || 
      report.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      report.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    return matchesType && matchesStatus && matchesSearch
  })
})

// Methods
const getReportIcon = (type) => {
  const icons = {
    incident: 'AlertTriangle',
    complaint: 'AlertCircle',
    suggestion: 'Lightbulb',
    visitor: 'User'
  }
  return icons[type] || 'FileText'
}

const getReportIconColor = (type) => {
  const colors = {
    incident: 'text-red-500',
    complaint: 'text-orange-500',
    suggestion: 'text-blue-500',
    visitor: 'text-green-500'
  }
  return colors[type] || 'text-gray-500'
}

const getReportTypeText = (type) => {
  const types = {
    incident: 'Sự cố',
    complaint: 'Khiếu nại',
    suggestion: 'Đề xuất',
    visitor: 'Khách vãng lai'
  }
  return types[type] || 'Khác'
}

const getStatusText = (status) => {
  const statuses = {
    pending: 'Chờ xử lý',
    processing: 'Đang xử lý',
    resolved: 'Đã giải quyết',
    closed: 'Đã đóng'
  }
  return statuses[status] || 'Không xác định'
}

const viewReport = (report) => {
  selectedReport.value = report
}

const editReport = (report) => {
  // Implement edit functionality
}

const createReport = () => {
  // Implement create functionality
  showCreateReportModal.value = false
}

const handleFileUpload = (event) => {
  const files = event.target.files
  for (let i = 0; i < files.length; i++) {
    const file = files[i]
    const reader = new FileReader()
    reader.onload = (e) => {
      newReport.value.images.push({
        file,
        preview: e.target.result
      })
    }
    reader.readAsDataURL(file)
  }
}

const removeImage = (index) => {
  newReport.value.images.splice(index, 1)
}

const addResponse = (report) => {
  // Implement add response functionality
}
</script>

<style scoped>
/* Add any custom styles here */
</style>