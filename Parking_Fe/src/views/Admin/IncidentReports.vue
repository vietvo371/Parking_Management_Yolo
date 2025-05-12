<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý báo cáo / sự cố</h1>
        <div class="flex items-center space-x-2">
          <!-- <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showAddModal = true">
            <Plus class="mr-2 h-4 w-4" />
            Tạo báo cáo
          </button> -->
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
                placeholder="Tìm kiếm nội dung hoặc người báo cáo..." 
                class="pl-9 h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="filters.search"
              />
            </div>
            <select 
              v-model="filters.status" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả trạng thái</option>
              <option :value="0">Chưa xử lý</option>
              <option :value="1">Đã xử lý</option>
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
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nội dung báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày tạo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái xử lý</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="report in paginatedReports" :key="report.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ report.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span>{{ report.noi_dung_bao_cao }}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <User class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium">{{ report.ten_cu_dan || report.id_cu_dan_bao_cao }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ convertDate(report.ngay_tao) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': report.trang_thai_xu_ly === 0,
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': report.trang_thai_xu_ly === 1,
                    }">
                    {{ getStatusText(report.trang_thai_xu_ly) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <a-button 
                      v-if="report.trang_thai_xu_ly === 0"
                      type="primary"
                      size="small"
                      @click="openProcessModal(report)"
                    >
                      Xử lý
                    </a-button>
                    <a-button 
                      type="primary"
                      size="small"
                      @click="viewReport(report)"
                    >
                      <Eye class="h-4 w-4" />
                    </a-button>
                    <!-- <a-popconfirm
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
                    </a-popconfirm> -->
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
            <label for="addReportContent" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung báo cáo *</label>
            <textarea 
              id="addReportContent" 
              v-model="newReport.noi_dung_bao_cao"
              required
              rows="4"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập nội dung báo cáo"
            ></textarea>
          </div>
        </div>
      </a-modal>
  
      <!-- Modal xem chi tiết báo cáo -->
      <a-modal v-model:open="showViewModal" title="Chi tiết báo cáo" width="600px" centered :bodyStyle="{ maxHeight: '80vh', overflowY: 'auto' }">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button v-if="selectedReport && selectedReport.trang_thai_xu_ly === 0" type="primary" @click="updateStatus(1)">
              Đánh dấu đã xử lý
            </a-button>
            <a-button @click="showViewModal = false">Đóng</a-button>
          </div>
        </template>
        <div v-if="selectedReport" class="space-y-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">ID báo cáo</p>
              <p class="font-medium">{{ selectedReport.id }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Người báo cáo</p>
              <p class="font-medium">{{ selectedReport.ten_cu_dan || selectedReport.id_cu_dan_bao_cao }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Ngày tạo</p>
              <p class="font-medium">{{ convertDate(selectedReport.ngay_tao) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Trạng thái xử lý</p>
              <p class="font-medium">{{ getStatusText(selectedReport.trang_thai_xu_ly) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Nội dung báo cáo</p>
              <p class="font-medium">{{ selectedReport.noi_dung_bao_cao }}</p>
            </div>
            <div v-if="selectedReport.id_admin_xu_ly">
              <p class="text-sm text-gray-500">Admin xử lý</p>
              <p class="font-medium">{{ selectedReport.ten_admin_xu_ly }}</p>
            </div>
          </div>
        </div>
      </a-modal>
  
      <!-- Modal xử lý báo cáo -->
      <a-modal v-model:open="showProcessModal" title="Xử lý báo cáo" centered width="500px" @ok="handleProcessSubmit">
        <template #footer>
          <a-button key="back" @click="showProcessModal = false">Hủy</a-button>
          <a-button key="submit" type="primary" :loading="isSubmitting" @click="handleProcessSubmit">
            {{ isSubmitting ? 'Đang xử lý...' : 'Xác nhận xử lý' }}
          </a-button>
        </template>
        <div class="space-y-4">
          <div>
            <label for="processNote" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ghi chú xử lý</label>
            <textarea 
              id="processNote" 
              v-model="processNote"
              rows="4"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              placeholder="Nhập ghi chú xử lý (nếu có)"
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
  import baseRequest from '../../core/baseRequest'
  import { useNotificationStore } from '../../stores/notication'
  
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
          total: 0,
          pending: 0,
          processing: 0,
          resolved: 0
        },
        // Bộ lọc
        filters: {
          search: '',
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
        showProcessModal: false,
        isSubmitting: false,
        // Dữ liệu tạo mới
        newReport: {
          noi_dung_bao_cao: '',
          type: '',
          priority: 'medium',
        },
        // Dữ liệu chỉnh sửa
        editingReport: {
          id: '',
          noi_dung_bao_cao: '',
          type: '',
          priority: 'medium',
        },
        // Dữ liệu xem chi tiết
        selectedReport: null,
        newComment: '',
        processNote: '',
        processingReport: null,
        // Danh sách báo cáo
        reports: []
      }
    },
    computed: {
      filteredReports() {
        let result = [...this.reports];
        // Lọc theo tìm kiếm nội dung hoặc người báo cáo
        if (this.filters.search) {
          const searchLower = this.filters.search.toLowerCase();
          result = result.filter(report => 
            (report.noi_dung_bao_cao && report.noi_dung_bao_cao.toLowerCase().includes(searchLower)) ||
            (report.ten_cu_dan && report.ten_cu_dan.toLowerCase().includes(searchLower)) ||
            (report.id_cu_dan_bao_cao && String(report.id_cu_dan_bao_cao).toLowerCase().includes(searchLower))
          );
        }
        // Lọc theo trạng thái xử lý
        if (this.filters.status !== '' && this.filters.status !== null && this.filters.status !== undefined) {
          result = result.filter(report => String(report.trang_thai_xu_ly) === String(this.filters.status));
        }
        // Lọc theo ngày tạo
        if (this.filters.dateFrom) {
          const fromDate = new Date(this.filters.dateFrom);
          result = result.filter(report => new Date(report.ngay_tao) >= fromDate);
        }
        if (this.filters.dateTo) {
          const toDate = new Date(this.filters.dateTo);
          toDate.setHours(23, 59, 59);
          result = result.filter(report => new Date(report.ngay_tao) <= toDate);
        }
        // Sắp xếp theo ngày tạo mới nhất
        result.sort((a, b) => new Date(b.ngay_tao) - new Date(a.ngay_tao));
        // Cập nhật tổng số trang
        this.pagination.totalPages = Math.ceil(result.length / this.pagination.itemsPerPage);
        return result;
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
      convertDate(date) {
        if (!date) return '';
        return new Date(date).toLocaleDateString('vi-VN', { year: 'numeric', month: '2-digit', day: '2-digit' });
      },
      getIncidentReports() {
        const notificationStore = useNotificationStore();
        baseRequest.get('admin/bao-cao-su-co/lay-du-lieu')
          .then((res) => {
            this.reports = res.data.data;
            this.updateStats();
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          });
      },
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
          case 0:
            return 'Chưa xử lý';
          case 1:
            return 'Đã xử lý';
          default:
            return status;
        }
      },
      getPriorityText(priority) {
        switch (priority) {
          case 1:
            return 'Cao'
          case 2:
            return 'Trung bình'
          case 3:
            return 'Thấp'
          default:
            return priority
        }
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
      viewReport(report) {
        this.selectedReport = { ...report }
        this.showViewModal = true
      },
      editReport(report) {
        this.editingReport = { ...report }
        this.showEditModal = true
      },
      handleEditSubmit() {
        this.isSubmitting = true;
        const notificationStore = useNotificationStore();
        baseRequest.post('admin/bao-cao-su-co/cap-nhat-du-lieu', this.editingReport)
          .then((response) => {
            if (response.data.status) {
              this.getIncidentReports();
              this.showEditModal = false;
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Cập nhật báo cáo/sự cố thất bại');
            }
            this.isSubmitting = false;
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
            this.isSubmitting = false;
          });
      },
      handleAddSubmit() {
        this.isSubmitting = true;
        const notificationStore = useNotificationStore();
        baseRequest.post('admin/bao-cao-su-co/xu-ly-bao-cao', this.newReport)
          .then((response) => {
            if (response.data.status) {
              this.getIncidentReports();
              this.showAddModal = false;
              this.newReport = { noi_dung_bao_cao: '', type: '', priority: 'medium' };
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError(response.data.message);
            }
            this.isSubmitting = false;
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
            this.isSubmitting = false;
          });
      },
      deleteReport(report) {
        const notificationStore = useNotificationStore();
        baseRequest.post('admin/bao-cao-su-co/xoa-du-lieu', { id: report.id })
          .then((response) => {
            if (response.data.status) {
              this.getIncidentReports();
              notificationStore.showSuccess(response.data.message);
            } else {
              notificationStore.showError('Xóa báo cáo/sự cố thất bại');
            }
          })
          .catch((res) => {
            var errors = Object.values(res.response.data.errors);
            notificationStore.showError(errors[0]);
          });
      },
      updateStatus(status) {
        if (this.selectedReport) {
          const notificationStore = useNotificationStore();
          baseRequest.post('admin/bao-cao-su-co/cap-nhat-trang-thai', {
            id: this.selectedReport.id,
            trang_thai_xu_ly: status
          })
            .then((response) => {
              if (response.data.status) {
                this.getIncidentReports();
                this.selectedReport.trang_thai_xu_ly = status;
                notificationStore.showSuccess(response.data.message);
              } else {
                notificationStore.showError('Cập nhật trạng thái thất bại');
              }
            })
            .catch((res) => {
              var errors = Object.values(res.response.data.errors);
              notificationStore.showError(errors[0]);
            });
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
      },
      openProcessModal(report) {
        this.processingReport = report;
        this.processNote = '';
        this.showProcessModal = true;
      },
      async handleProcessSubmit() {
        if (!this.processingReport) return;
        this.isSubmitting = true;
        const notificationStore = useNotificationStore();
        try {
          const payload = {
            id: this.processingReport.id,
            trang_thai_xu_ly: 1,
            ghi_chu_xu_ly: this.processNote
          };
          const res = await baseRequest.post('admin/bao-cao-su-co/xu-ly-bao-cao', payload);
          if (res.data.status) {
            this.getIncidentReports();
            this.showProcessModal = false;
            notificationStore.showSuccess(res.data.message);
          } else {
            notificationStore.showError(res.data.message);
          }
        } catch (err) {
          var errors = Object.values(err.response.data.errors);
          notificationStore.showError(errors[0]);
        }
        this.isSubmitting = false;
      }
    },
    mounted() {
      this.getIncidentReports()
    }
  }
  </script>