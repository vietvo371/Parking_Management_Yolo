<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý báo cáo / sự cố</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showAddModal = true">
            <Plus class="mr-2 h-4 w-4" />
            Tạo báo cáo
          </button>
        </div>
      </div>
  
      <!-- Thống kê -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng số báo cáo</p>
              <h3 class="text-2xl font-bold">{{ stats.total }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <ClipboardList class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Chờ xử lý</p>
              <h3 class="text-2xl font-bold">{{ stats.pending }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
              <Clock class="h-5 w-5 text-yellow-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đang xử lý</p>
              <h3 class="text-2xl font-bold">{{ stats.processing }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
              <Loader2 class="h-5 w-5 text-orange-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đã giải quyết</p>
              <h3 class="text-2xl font-bold">{{ stats.resolved }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <CheckCircle class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Bộ lọc -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <div class="flex flex-1 items-center space-x-2">
            <div class="relative flex-1">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                type="text" 
                placeholder="Tìm kiếm báo cáo..." 
                class="pl-9 h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="filters.search"
              />
            </div>
            <select 
              v-model="filters.type" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả loại</option>
              <option value="incident">Sự cố</option>
              <option value="visitor">Khách vãng lai</option>
              <option value="maintenance">Bảo trì</option>
              <option value="security">An ninh</option>
            </select>
            <select 
              v-model="filters.status" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả trạng thái</option>
              <option value="pending">Chờ xử lý</option>
              <option value="processing">Đang xử lý</option>
              <option value="resolved">Đã giải quyết</option>
              <option value="closed">Đã đóng</option>
            </select>
          </div>
          <div class="flex items-center space-x-2">
            <div class="flex items-center space-x-2">
              <label class="text-sm">Từ:</label>
              <input 
                type="date" 
                class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                v-model="filters.dateFrom"
              />
            </div>
            <div class="flex items-center space-x-2">
              <label class="text-sm">Đến:</label>
              <input 
                type="date" 
                class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                v-model="filters.dateTo"
              />
            </div>
            <button 
              class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
              @click="resetFilters"
            >
              Đặt lại
            </button>
          </div>
        </div>
      </div>
  
      <!-- Danh sách báo cáo -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiêu đề</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mức độ</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="report in paginatedReports" :key="report.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
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
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <User class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium">{{ report.reporter }}</div>
                      <div class="text-xs text-gray-500">{{ report.reporterType }}</div>
                    </div>
                  </div>
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
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': report.priority === 'high',
                      'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': report.priority === 'medium',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': report.priority === 'low'
                    }">
                    {{ getPriorityText(report.priority) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <a-button 
                      type="primary"
                      size="small"
                      @click="viewReport(report)"
                    >
                      <Eye class="h-4 w-4" />
                    </a-button>
                    <a-button 
                      type="primary"
                      size="small"
                      @click="editReport(report)"
                    >
                      <Edit class="h-4 w-4" />
                    </a-button>
                    <a-popconfirm
                      title="Bạn có chắc chắn muốn xóa báo cáo này không?"
                      ok-text="Có"
                      cancel-text="Không"
                      @confirm="deleteReport(report)"
                    >
                      <a-button 
                        type="primary"
                        danger
                        size="small"
                      >
                        <Trash2 class="h-4 w-4" />
                      </a-button>
                    </a-popconfirm>
                  </div>
                </td>
              </tr>
              <tr v-if="paginatedReports.length === 0">
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                  Không tìm thấy báo cáo nào
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        
        <!-- Phân trang -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <button 
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
              :disabled="pagination.currentPage === 1"
              @click="pagination.currentPage--"
            >
              Trước
            </button>
            <button 
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
              :disabled="pagination.currentPage === pagination.totalPages"
              @click="pagination.currentPage++"
            >
              Sau
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700 dark:text-gray-300">
                Hiển thị <span class="font-medium">{{ paginationStart }}</span> đến <span class="font-medium">{{ paginationEnd }}</span> của <span class="font-medium">{{ paginatedReports.length }}</span> kết quả
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button 
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                  :disabled="pagination.currentPage === 1"
                  @click="pagination.currentPage--"
                >
                  <span class="sr-only">Trang trước</span>
                  <ChevronLeft class="h-5 w-5" />
                </button>
                
                <button 
                  v-for="page in paginationPages" 
                  :key="page"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium"
                  :class="page === pagination.currentPage ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'"
                  @click="pagination.currentPage = page"
                >
                  {{ page }}
                </button>
                
                <button 
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700"
                  :disabled="pagination.currentPage === pagination.totalPages"
                  @click="pagination.currentPage++"
                >
                  <span class="sr-only">Trang sau</span>
                  <ChevronRight class="h-5 w-5" />
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Modal tạo báo cáo mới -->
      <a-modal v-model:open="showAddModal" title="Tạo báo cáo mới" centered width="600px" @ok="handleAddSubmit">
        <template #footer>
          <a-button key="back" @click="showAddModal = false">Hủy</a-button>
          <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleAddSubmit">
            {{ isSubmitting ? "Đang lưu..." : "Thêm" }}
          </a-button>
        </template>
        <div class="space-y-4">
          <div>
            <label for="addReportTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề *</label>
            <input 
              id="addReportTitle" 
              type="text" 
              v-model="newReport.title"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập tiêu đề báo cáo"
            />
          </div>
          <div>
            <label for="addReportType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại báo cáo *</label>
            <select 
              id="addReportType"
              v-model="newReport.type"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            >
              <option value="incident">Sự cố</option>
              <option value="visitor">Khách vãng lai</option>
              <option value="maintenance">Bảo trì</option>
              <option value="security">An ninh</option>
            </select>
          </div>
          <div>
            <label for="addReportPriority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mức độ ưu tiên *</label>
            <select 
              id="addReportPriority"
              v-model="newReport.priority"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            >
              <option value="high">Cao</option>
              <option value="medium">Trung bình</option>
              <option value="low">Thấp</option>
            </select>
          </div>
          <div>
            <label for="addReportDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả *</label>
            <textarea 
              id="addReportDescription" 
              v-model="newReport.description"
              required
              rows="4"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập mô tả chi tiết"
            ></textarea>
          </div>
        </div>
      </a-modal>
  
      <!-- Modal xem chi tiết báo cáo -->
      <a-modal v-model:open="showViewModal" title="Chi tiết báo cáo" width="800px" centered :bodyStyle="{ maxHeight: '80vh', overflowY: 'auto' }">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button v-if="selectedReport && selectedReport.status === 'pending'" type="primary" @click="updateStatus('processing')">
              Bắt đầu xử lý
            </a-button>
            <a-button v-if="selectedReport && selectedReport.status === 'processing'" type="primary" @click="updateStatus('resolved')">
              Đánh dấu đã giải quyết
            </a-button>
            <a-button v-if="selectedReport && selectedReport.status === 'resolved'" type="primary" @click="updateStatus('closed')">
              Đóng báo cáo
            </a-button>
            <a-button @click="showViewModal = false">Đóng</a-button>
          </div>
        </template>
        <div v-if="selectedReport" class="space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <component :is="getReportIcon(selectedReport.type)" class="h-5 w-5 mr-2" :class="getReportIconColor(selectedReport.type)" />
              <h2 class="text-xl font-bold">{{ selectedReport.title }}</h2>
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
              <p class="text-sm text-gray-500">ID báo cáo</p>
              <p class="font-medium">{{ selectedReport.id }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại báo cáo</p>
              <p class="font-medium">{{ getReportTypeText(selectedReport.type) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Người báo cáo</p>
              <p class="font-medium">{{ selectedReport.reporter }} ({{ selectedReport.reporterType }})</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Ngày báo cáo</p>
              <p class="font-medium">{{ selectedReport.date }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Mức độ ưu tiên</p>
              <p class="font-medium" :class="{
                'text-red-600': selectedReport.priority === 'high',
                'text-orange-600': selectedReport.priority === 'medium',
                'text-blue-600': selectedReport.priority === 'low'
              }">{{ getPriorityText(selectedReport.priority) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Người xử lý</p>
              <p class="font-medium">{{ selectedReport.assignee || 'Chưa phân công' }}</p>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Mô tả</h4>
            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ selectedReport.description }}</p>
          </div>
          
          <div v-if="selectedReport.images && selectedReport.images.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Hình ảnh</h4>
            <div class="grid grid-cols-3 gap-2">
              <div v-for="(image, index) in selectedReport.images" :key="index" class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                <img :src="image" alt="Report image" class="w-full h-full object-cover" />
              </div>
            </div>
          </div>
          
          <div v-if="selectedReport.comments && selectedReport.comments.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Bình luận</h4>
            <div class="space-y-4">
              <div v-for="(comment, index) in selectedReport.comments" :key="index" class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md">
                <div class="flex justify-between items-start">
                  <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <User class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium">{{ comment.user }}</div>
                      <div class="text-xs text-gray-500">{{ comment.time }}</div>
                    </div>
                  </div>
                </div>
                <p class="text-sm mt-2">{{ comment.text }}</p>
              </div>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Thêm bình luận</h4>
            <textarea 
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              rows="3"
              placeholder="Nhập bình luận của bạn..."
              v-model="newComment"
            ></textarea>
            <div class="mt-2 flex justify-end">
              <a-button type="primary" @click="addComment">
                Gửi bình luận
              </a-button>
            </div>
          </div>
        </div>
      </a-modal>
  
      <!-- Modal chỉnh sửa báo cáo -->
      <a-modal v-model:open="showEditModal" title="Chỉnh sửa báo cáo" centered width="600px" @ok="handleEditSubmit">
        <template #footer>
          <a-button key="back" @click="showEditModal = false">Hủy</a-button>
          <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleEditSubmit">
            {{ isSubmitting ? "Đang lưu..." : "Cập nhật" }}
          </a-button>
        </template>
        <div class="space-y-4">
          <div>
            <label for="editReportTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề *</label>
            <input 
              id="editReportTitle" 
              type="text" 
              v-model="editingReport.title"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập tiêu đề báo cáo"
            />
          </div>
          <div>
            <label for="editReportType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại báo cáo *</label>
            <select 
              id="editReportType"
              v-model="editingReport.type"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            >
              <option value="incident">Sự cố</option>
              <option value="visitor">Khách vãng lai</option>
              <option value="maintenance">Bảo trì</option>
              <option value="security">An ninh</option>
            </select>
          </div>
          <div>
            <label for="editReportPriority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mức độ ưu tiên *</label>
            <select 
              id="editReportPriority"
              v-model="editingReport.priority"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            >
              <option value="high">Cao</option>
              <option value="medium">Trung bình</option>
              <option value="low">Thấp</option>
            </select>
          </div>
          <div>
            <label for="editReportDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mô tả *</label>
            <textarea 
              id="editReportDescription" 
              v-model="editingReport.description"
              required
              rows="4"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập mô tả chi tiết"
            ></textarea>
          </div>
        </div>
      </a-modal>
    </div>
  </template>
  
  <script>
  import { 
    Plus, 
    Search, 
    ClipboardList, 
    Clock, 
    Loader2, 
    CheckCircle, 
    User, 
    Eye, 
    Edit, 
    Trash2,
    ChevronLeft,
    ChevronRight,
    X,
    AlertTriangle,
    Car,
    Shield,
    Upload
  } from 'lucide-vue-next'
  import { Modal, Button, Popconfirm } from 'ant-design-vue'
  
  export default {
    name: 'IncidentReports',
    components: {
      Plus,
      Search,
      ClipboardList,
      Clock,
      Loader2,
      CheckCircle,
      User,
      Eye,
      Edit,
      Trash2,
      ChevronLeft,
      ChevronRight,
      X,
      AlertTriangle,
      Car,
      Shield,
      Upload,
      [Modal.name]: Modal,
      [Button.name]: Button,
      [Popconfirm.name]: Popconfirm
    },
    data() {
      return {
        // Thống kê
        stats: {
          total: 45,
          pending: 12,
          processing: 18,
          resolved: 15
        },
        // Bộ lọc
        filters: {
          search: '',
          type: '',
          status: '',
          dateFrom: '',
          dateTo: ''
        },
        // Phân trang
        pagination: {
          currentPage: 1,
          itemsPerPage: 10,
          totalPages: 1
        },
        // Modal
        showAddModal: false,
        showEditModal: false,
        showViewModal: false,
        isSubmitting: false,
        // Dữ liệu tạo mới
        newReport: {
          title: '',
          type: '',
          priority: 'medium',
          description: ''
        },
        // Dữ liệu chỉnh sửa
        editingReport: {
          id: '',
          title: '',
          type: '',
          priority: 'medium',
          description: ''
        },
        // Dữ liệu xem chi tiết
        selectedReport: null,
        newComment: '',
        // Danh sách báo cáo
        reports: [
          {
            id: 'REP-001',
            title: 'Xe đỗ sai vị trí',
            type: 'incident',
            reporter: 'Nguyễn Văn A',
            reporterType: 'Cư dân',
            date: '24/04/2023',
            status: 'pending',
            priority: 'medium',
            description: 'Có một chiếc xe ô tô biển số 30A-12345 đỗ sai vị trí tại khu vực A, gây cản trở lối đi.',
            assignee: null,
            images: [
              '/placeholder.svg?height=200&width=300&text=Image 1',
              '/placeholder.svg?height=200&width=300&text=Image 2'
            ],
            comments: [
              {
                user: 'Nguyễn Văn A',
                time: '24/04/2023 - 10:25',
                text: 'Tôi đã báo cáo sự việc này. Xe vẫn đang đỗ sai vị trí.'
              }
            ]
          },
          // ... other reports data ...
        ]
      }
    },
    computed: {
      filteredReports() {
        let result = [...this.reports]
        // Lọc theo tìm kiếm
        if (this.filters.search) {
          const searchLower = this.filters.search.toLowerCase()
          result = result.filter(report => 
            report.id.toLowerCase().includes(searchLower) ||
            report.title.toLowerCase().includes(searchLower) ||
            report.reporter.toLowerCase().includes(searchLower)
          )
        }
        // Lọc theo loại
        if (this.filters.type) {
          result = result.filter(report => report.type === this.filters.type)
        }
        // Lọc theo trạng thái
        if (this.filters.status) {
          result = result.filter(report => report.status === this.filters.status)
        }
        // Lọc theo ngày
        if (this.filters.dateFrom) {
          const fromDate = new Date(this.filters.dateFrom)
          result = result.filter(report => new Date(report.date) >= fromDate)
        }
        if (this.filters.dateTo) {
          const toDate = new Date(this.filters.dateTo)
          toDate.setHours(23, 59, 59)
          result = result.filter(report => new Date(report.date) <= toDate)
        }
        // Sắp xếp theo ngày báo cáo mới nhất
        result.sort((a, b) => new Date(b.date) - new Date(a.date))
        // Cập nhật tổng số trang
        this.pagination.totalPages = Math.ceil(result.length / this.pagination.itemsPerPage)
        return result
      },
      paginatedReports() {
        const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage
        const end = start + this.pagination.itemsPerPage
        return this.filteredReports.slice(start, end)
      },
      paginationStart() {
        return (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1
      },
      paginationEnd() {
        return Math.min(this.pagination.currentPage * this.pagination.itemsPerPage, this.filteredReports.length)
      },
      paginationPages() {
        const pages = []
        for (let i = 1; i <= this.pagination.totalPages; i++) {
          pages.push(i)
        }
        return pages
      }
    },
    methods: {
      getReportIcon(type) {
        switch (type) {
          case 'incident':
            return AlertTriangle
          case 'visitor':
            return Car
          case 'security':
            return Shield
          default:
            return ClipboardList
        }
      },
      getReportIconColor(type) {
        switch (type) {
          case 'incident':
            return 'text-orange-600'
          case 'visitor':
            return 'text-blue-600'
          case 'maintenance':
            return 'text-yellow-600'
          case 'security':
            return 'text-red-600'
          default:
            return 'text-gray-600'
        }
      },
      getReportTypeText(type) {
        switch (type) {
          case 'incident':
            return 'Sự cố'
          case 'visitor':
            return 'Khách vãng lai'
          case 'maintenance':
            return 'Bảo trì'
          case 'security':
            return 'An ninh'
          default:
            return type
        }
      },
      getStatusText(status) {
        switch (status) {
          case 'pending':
            return 'Chờ xử lý'
          case 'processing':
            return 'Đang xử lý'
          case 'resolved':
            return 'Đã giải quyết'
          case 'closed':
            return 'Đã đóng'
          default:
            return status
        }
      },
      getPriorityText(priority) {
        switch (priority) {
          case 'high':
            return 'Cao'
          case 'medium':
            return 'Trung bình'
          case 'low':
            return 'Thấp'
          default:
            return priority
        }
      },
      resetFilters() {
        this.filters = {
          search: '',
          type: '',
          status: '',
          dateFrom: '',
          dateTo: ''
        }
        this.pagination.currentPage = 1
      },
      viewReport(report) {
        this.selectedReport = { ...report }
        this.showViewModal = true
      },
      editReport(report) {
        this.editingReport = { ...report }
        this.showEditModal = true
      },
      handleEditSubmit() {
        this.isSubmitting = true
        setTimeout(() => {
          const index = this.reports.findIndex(r => r.id === this.editingReport.id)
          if (index !== -1) {
            this.reports[index] = {
              ...this.reports[index],
              ...this.editingReport
            }
          }
          this.isSubmitting = false
          this.showEditModal = false
          this.updateStats()
        }, 1000)
      },
      handleAddSubmit() {
        this.isSubmitting = true
        setTimeout(() => {
          const newId = `REP-${String(this.reports.length + 1).padStart(3, '0')}`
          this.reports.push({
            id: newId,
            ...this.newReport,
            date: new Date().toLocaleDateString('vi-VN'),
            status: 'pending',
            comments: [],
            images: [],
            reporter: 'Người dùng hiện tại',
            reporterType: 'Cư dân',
            assignee: null
          })
          this.isSubmitting = false
          this.showAddModal = false
          this.newReport = {
            title: '',
            type: '',
            priority: 'medium',
            description: ''
          }
          this.updateStats()
        }, 1000)
      },
      deleteReport(report) {
        this.reports = this.reports.filter(r => r.id !== report.id)
        this.updateStats()
      },
      updateStatus(status) {
        if (this.selectedReport) {
          const index = this.reports.findIndex(r => r.id === this.selectedReport.id)
          if (index !== -1) {
            this.reports[index].status = status
            this.selectedReport.status = status
          }
          this.updateStats()
        }
      },
      addComment() {
        if (this.newComment.trim() && this.selectedReport) {
          const comment = {
            user: 'Người dùng hiện tại',
            time: new Date().toLocaleString('vi-VN'),
            text: this.newComment.trim()
          }
          const index = this.reports.findIndex(r => r.id === this.selectedReport.id)
          if (index !== -1) {
            this.reports[index].comments.push(comment)
            this.selectedReport.comments.push(comment)
          }
          this.newComment = ''
        }
      },
      updateStats() {
        this.stats.total = this.reports.length
        this.stats.pending = this.reports.filter(r => r.status === 'pending').length
        this.stats.processing = this.reports.filter(r => r.status === 'processing').length
        this.stats.resolved = this.reports.filter(r => r.status === 'resolved').length
      }
    },
    mounted() {
      this.updateStats()
    }
  }
  </script>