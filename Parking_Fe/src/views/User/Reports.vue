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
  
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <div class="flex flex-1 items-center space-x-2">
            <div class="relative flex-1">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                type="text" 
                placeholder="Tìm kiếm theo ID, nội dung..." 
                class="pl-9 h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="filters.search"
              />
            </div>
            <select 
              v-model="filters.status" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả trạng thái</option>
              <option value="0">Chưa xử lý</option>
              <option value="1">Đã xử lý</option>
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
  
      <!-- Reports Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiêu đề</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người xử lý</th>
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
                    <span>{{ report.noi_dung_bao_cao }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(report.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': report.trang_thai_xu_ly === 0,
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-white-300': report.trang_thai_xu_ly === 1,
                    }">
                    {{ getStatusText(report.trang_thai_xu_ly) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ report.ten_admin_xu_ly || 'Chưa phân công' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="viewReport(report)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button v-if="report.trang_thai_xu_ly !== 3" class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="editReport(report)">
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
                Hiển thị <span class="font-medium">{{ paginationStart }}</span> đến <span class="font-medium">{{ paginationEnd }}</span> của <span class="font-medium">{{ filteredReports.length }}</span> kết quả
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
  
      <!-- Create Report Modal -->
      <a-modal v-model:open="showCreateReportModal" title="Tạo báo cáo mới" width="800px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="showCreateReportModal = false">Hủy</a-button>
            <a-button type="primary" @click="createReport">Tạo báo cáo</a-button>
          </div>
        </template>
        <div class="space-y-4">
          <div>
            <label for="reportContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung báo cáo *</label>
            <textarea 
              id="reportContent" 
              v-model="newReport.noi_dung_bao_cao"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập nội dung báo cáo sự cố"
              rows="4"
            ></textarea>
          </div>
        </div>
      </a-modal>
  
      <!-- View Report Modal -->
      <a-modal v-model:open="showDetailModal" title="Chi tiết báo cáo" width="800px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="closeDetailModal">Đóng</a-button>
          </div>
        </template>
        <div v-if="selectedReport" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-xl font-bold">{{ selectedReport.noi_dung_bao_cao }}</h2>
              <p class="text-sm text-gray-500">{{ selectedReport.id }} - {{ formatDateTime(selectedReport.created_at) }}</p>
            </div>
            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
              :class="{
                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedReport.trang_thai_xu_ly === 0,
                'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': selectedReport.trang_thai_xu_ly === 1,
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedReport.trang_thai_xu_ly === 2,
                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300': selectedReport.trang_thai_xu_ly === 3
              }">
              {{ getStatusText(selectedReport.trang_thai_xu_ly) }}
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
              <p class="font-medium">{{ selectedReport.ten_admin_xu_ly || 'Chưa phân công' }}</p>
            </div>
            <div v-if="selectedReport.resolvedDate">
              <p class="text-sm text-gray-500">Ngày giải quyết</p>
              <p class="font-medium">{{ formatDateTime(selectedReport.resolvedDate) }}</p>
            </div>
          </div>
        </div>
      </a-modal>

      <!-- Edit Report Modal -->
      <a-modal v-model:open="showEditModal" title="Cập nhật báo cáo" width="800px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="showEditModal = false">Hủy</a-button>
            <a-button type="primary" @click="updateReport">Cập nhật</a-button>
          </div>
        </template>
        <div class="space-y-4">
          <div>
            <label for="editReportContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung báo cáo *</label>
            <textarea 
              id="editReportContent" 
              v-model="editingReport.noi_dung_bao_cao"
              required
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập nội dung báo cáo sự cố"
              rows="4"
            ></textarea>
          </div>
          <div>
            <label for="editReportStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái</label>
            <select 
              id="editReportStatus" 
              v-model="editingReport.trang_thai_xu_ly"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            >
              <option :value="0">Chưa xử lý</option>
              <option :value="1">Đã xử lý</option>
            </select>
          </div>
        </div>
      </a-modal>
    </div>
  </template>

  <script>
  import { 
    Plus, 
    ClipboardList, 
    Clock, 
    CheckCircle, 
    Archive, 
    Search, 
    Eye, 
    Edit, 
    X, 
    Upload, 
    ChevronLeft, 
    ChevronRight,
    AlertTriangle,
    AlertCircle,
    Lightbulb,
    User
  } from 'lucide-vue-next'
  import { Modal, Button } from 'ant-design-vue'
  import baseRequestUser from '../../core/baseRequestUser'
  import { useNotificationStore } from '../../stores/notication'
  export default {
    name: 'Reports',
    components: {
      Plus,
      ClipboardList,
      Clock,
      CheckCircle,
      Archive,
      Search,
      Eye,
      Edit,
      X,
      Upload,
      ChevronLeft,
      ChevronRight,
      AlertTriangle,
      AlertCircle,
      Lightbulb,
      User,
      [Modal.name]: Modal,
      [Button.name]: Button
    },
    data() {
      return {
        filters: {
          search: '',
          status: '',
          dateFrom: '',
          dateTo: ''
        },
        pagination: {
          currentPage: 1,
          itemsPerPage: 10,
          totalPages: 1
        },
        showCreateReportModal: false,
        showDetailModal: false,
        showEditModal: false,
        selectedReport: null,
        editingReport: null,
        newResponse: '',
        reportStats: {
          total: 0,
          pending: 0,
          processed: 0
        },
        reports: [],
        isSubmitting: false,
        newReport: {
          noi_dung_bao_cao: '',
          trang_thai_xu_ly: 0
        }
      }
    },
    computed: {
      filteredReports() {
        let result = [...this.reports]
        
        if (this.filters.search) {
          const searchLower = this.filters.search.toLowerCase()
          result = result.filter(report => 
            report.id.toString().includes(searchLower) || 
            report.noi_dung_bao_cao.toLowerCase().includes(searchLower)
          )
        }
        
        if (this.filters.status !== '') {
          result = result.filter(report => report.trang_thai_xu_ly === parseInt(this.filters.status))
        }
        
        if (this.filters.dateFrom) {
          const fromDate = new Date(this.filters.dateFrom)
          result = result.filter(report => new Date(report.created_at) >= fromDate)
        }
        
        if (this.filters.dateTo) {
          const toDate = new Date(this.filters.dateTo)
          toDate.setHours(23, 59, 59)
          result = result.filter(report => new Date(report.created_at) <= toDate)
        }
        
        result.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
        
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
    mounted() {
      this.getReports()
    },
    methods: {
      getReportIcon(type) {
        const icons = {
          incident: AlertTriangle,
          complaint: AlertCircle,
          suggestion: Lightbulb,
          visitor: User
        }
        return icons[type] || 'FileText'
      },
      getReportIconColor(type) {
        const colors = {
          incident: 'text-red-500',
          complaint: 'text-orange-500',
          suggestion: 'text-blue-500',
          visitor: 'text-green-500'
        }
        return colors[type] || 'text-gray-500'
      },
      getReportTypeText(type) {
        const types = {
          incident: 'Sự cố',
          complaint: 'Khiếu nại',
          suggestion: 'Đề xuất',
          visitor: 'Khách vãng lai'
        }
        return types[type] || 'Khác'
      },
      getStatusText(status) {
        const statuses = {
          0: 'Chưa xử lý',
          1: 'Đã xử lý'
        }
        return statuses[status] || 'Không xác định'
      },
      formatDateTime(dateTimeStr) {
        if (!dateTimeStr) return ''
        
        const date = new Date(dateTimeStr)
        return date.toLocaleString('vi-VN', {
          year: 'numeric',
          month: '2-digit',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit'
        })
      },
      viewReport(report) {
        this.selectedReport = { ...report }
        this.showDetailModal = true
      },
      editReport(report) {
        this.editingReport = { ...report }
        this.showEditModal = true
      },
      async createReport() {
        const notificationStore = useNotificationStore();
        this.isSubmitting = true;
        try {
          const res = await baseRequestUser.post('user/bao-cao-su-co', this.newReport)
          if (res.data.status) {
            notificationStore.showSuccess(res.data.message);
            this.reports.unshift(res.data.data)
            this.updateStats()
            this.showCreateReportModal = false
            this.newReport = {
              noi_dung_bao_cao: '',
              trang_thai_xu_ly: 0
            }
            this.isSubmitting = false;
          }
          else {
            notificationStore.showError(res.data.message);
          }
          this.isSubmitting = false;
        } catch (error) {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        }
      },
      handleFileUpload(event) {
        const files = event.target.files
        for (let i = 0; i < files.length; i++) {
          const file = files[i]
          const reader = new FileReader()
          reader.onload = (e) => {
            this.newReport.images.push({
              file,
              preview: e.target.result
            })
          }
          reader.readAsDataURL(file)
        }
      },
      removeImage(index) {
        this.newReport.images.splice(index, 1)
      },
      handleEditFileUpload(event) {
        const files = event.target.files
        for (let i = 0; i < files.length; i++) {
          const file = files[i]
          const reader = new FileReader()
          reader.onload = (e) => {
            this.editingReport.images.push({
              file,
              preview: e.target.result
            })
          }
          reader.readAsDataURL(file)
        }
      },
      removeEditImage(index) {
        this.editingReport.images.splice(index, 1)
      },
      addResponse(report) {
        // Implement add response functionality
      },
      resetFilters() {
        this.filters = {
          search: '',
          status: '',
          dateFrom: '',
          dateTo: ''
        }
        this.pagination.currentPage = 1
      },
      closeDetailModal() {
        this.selectedReport = null
        this.showDetailModal = false
      },
      async updateReport() {
        const notificationStore = useNotificationStore();
        this.isSubmitting = true;
        try {
          const res = await baseRequestUser.post(`user/cap-nhat-bao-cao-su-co/`, {
            'id': this.editingReport.id,
            'noi_dung_bao_cao': this.editingReport.noi_dung_bao_cao,
          })
          if (res.data.status) {
            notificationStore.showSuccess(res.data.message);
            const index = this.reports.findIndex(r => r.id === this.editingReport.id)
            if (index !== -1) {
              this.reports[index] = { ...this.editingReport }
              this.updateStats()
            }
            this.showEditModal = false
            this.isSubmitting = false;
          }
          else {
            notificationStore.showError(res.data.message);
          }
          this.isSubmitting = false;
        } catch (error) {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        }
      },
      updateStats() {
        this.reportStats.total = this.reports.length
        this.reportStats.pending = this.reports.filter(r => r.trang_thai_xu_ly === 0).length
        this.reportStats.processed = this.reports.filter(r => r.trang_thai_xu_ly === 1).length
      },
      async getReports() {
        try {
          const res = await baseRequestUser.get('user/lay-du-lieu-bao-cao-su-co')
          this.reports = res.data.data
          this.updateStats()
        } catch (error) {
          console.error('Error fetching reports:', error)
        }
      },
      async getVehicles() {
        try {
          const res = await baseRequestUser.get('user/lay-du-lieu-xe')
          this.vehicles = res.data.data
        } catch (error) {
          console.error('Error fetching vehicles:', error)
        }
      }
    }
  }
  </script>

<style scoped>
/* Add any custom styles here */
</style>