<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý giao dịch</h1>
      <div class="flex items-center space-x-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showAddModal = true">
          <Plus class="mr-2 h-4 w-4" />
          Thêm giao dịch
        </button>
      </div>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Tổng giao dịch</p>
            <h3 class="text-2xl font-bold">{{ stats.total }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
            <CreditCard class="h-5 w-5 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Giao dịch thành công</p>
            <h3 class="text-2xl font-bold">{{ stats.successful }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
            <CheckCircle class="h-5 w-5 text-green-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Đang xử lý</p>
            <h3 class="text-2xl font-bold">{{ stats.processing }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
            <Clock class="h-5 w-5 text-yellow-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Doanh thu hôm nay</p>
            <h3 class="text-2xl font-bold">{{ formatCurrency(stats.todayRevenue) }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
            <DollarSign class="h-5 w-5 text-purple-600" />
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
              placeholder="Tìm kiếm theo ID, biển số xe..." 
              class="pl-9 h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="filters.search"
            />
          </div>
          <select 
            v-model="filters.status" 
            class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="1">Thành công</option>
            <option value="2">Đang xử lý</option>
            <option value="3">Thất bại</option>
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

    <!-- Transactions Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã giao dịch</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số tiền</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phương thức</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="transaction in paginatedTransactions" :key="transaction.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ transaction.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ transaction.bien_so_xe }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ transaction.ma_giao_dich }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ formatCurrency(transaction.so_tien_giao_dich) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex items-center">
                  <component :is="getPaymentIcon(transaction.phuong_thuc_thanh_toan)" class="h-4 w-4 mr-2" />
                  {{ transaction.phuong_thuc_thanh_toan == 1 ? 'Chuyển khoản' : 'Tiền mặt' }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDateTime(transaction.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClass(transaction.trang_thai_giao_dich) + ' px-2 inline-flex text-xs leading-5 font-semibold rounded-full'">
                  {{ transaction.trang_thai_giao_dich == 1 ? 'Thành công' : transaction.trang_thai_giao_dich == 0 ? 'Chưa thanh toán' : 'Thất bại' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <a-button 
                    type="primary"
                    size="small"
                    @click="viewTransactionDetails(transaction)"
                  >
                    <Eye class="h-4 w-4" />
                  </a-button>
                  <a-button 
                    type="primary"
                    size="small"
                    @click="printTransaction(transaction)"
                  >
                    <Printer class="h-4 w-4" />
                  </a-button>
                  <!-- <a-popconfirm
                    title="Bạn có chắc chắn muốn xóa giao dịch này không?"
                    ok-text="Có"
                    cancel-text="Không"
                    @confirm="deleteTransaction(transaction)"
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
            <tr v-if="filteredTransactions.length === 0">
              <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                Không tìm thấy giao dịch nào
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
              Hiển thị <span class="font-medium">{{ paginationStart }}</span> đến <span class="font-medium">{{ paginationEnd }}</span> của <span class="font-medium">{{ filteredTransactions.length }}</span> kết quả
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

    <!-- Modal thêm giao dịch -->
    <a-modal v-model:open="showAddModal" title="Thêm giao dịch" @ok="addTransaction">
      <template #footer>
        <a-button key="back" @click="closeAddModal">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="addTransaction">
          {{ isSubmitting ? "Đang lưu..." : "Thêm" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số xe *</label>
          <a-select
            v-model:value="newTransaction.id_xe"
            show-search
            placeholder="Chọn biển số xe"
            style="width: 100%"
            :options="xeCuDan.map(xe => ({ value: xe.id, label: xe.bien_so_xe + ' - ' + xe.ten_loai_xe + ' - ' + xe.ho_va_ten }))"
            :filter-option="filterOption"
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số tiền *</label>
          <input 
            type="number" 
            v-model="newTransaction.so_tien_giao_dich"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập số tiền"
          />
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức thanh toán *</label>
          <a-select
            v-model:value="newTransaction.phuong_thuc_thanh_toan"
            placeholder="Chọn phương thức thanh toán"
            style="width: 100%"
          >
            <a-select-option value="1">Chuyển khoản</a-select-option>
            <a-select-option value="2">Tiền mặt</a-select-option>
          </a-select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thanh toán</label> 
          <a-checkbox v-model:checked="newTransaction.is_thanh_toan">Thanh toán</a-checkbox>
        </div>
      </div>
    </a-modal>

    <!-- Modal chi tiết giao dịch -->
    <a-modal v-model:open="showDetailModal" title="Chi tiết giao dịch" width="800px">
      <template #footer>
        <div class="flex justify-end space-x-2">
          <a-button @click="closeDetailModal">Đóng</a-button>
          <a-button type="primary" @click="printTransaction(selectedTransaction)">
            <Printer class="h-4 w-4 mr-2" />
           
          </a-button>
        </div>
      </template>
      <div v-if="selectedTransaction" class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold">Giao dịch #{{ selectedTransaction.ma_giao_dich }}</h2>
            <p class="text-sm text-gray-500">{{ formatDateTime(selectedTransaction.ngay_het_han) }}</p>
          </div>
          <span 
            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
            :class="getStatusClass(selectedTransaction.trang_thai_giao_dich)"
          >
            {{ selectedTransaction.trang_thai_giao_dich == 1 ? 'Thành công' : 
               selectedTransaction.trang_thai_giao_dich == 2 ? 'Đang xử lý' : 'Thất bại' }}
          </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Biển số xe</p>
            <p class="font-medium">{{ selectedTransaction.bien_so_xe }}</p>
          </div>
          
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Số tiền</p>
            <p class="font-medium text-lg text-blue-600 dark:text-blue-400">
              {{ formatCurrency(selectedTransaction.so_tien_giao_dich) }}
            </p>
          </div>
          
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Phương thức thanh toán</p>
            <div class="flex items-center">
              <component 
                :is="getPaymentIcon(selectedTransaction.phuong_thuc_thanh_toan)" 
                class="h-4 w-4 mr-2" 
              />
              <p class="font-medium">
                {{ selectedTransaction.phuong_thuc_thanh_toan == 1 ? 'Chuyển khoản' : 'Tiền mặt' }}
              </p>
            </div>
          </div>
          
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Thời gian giao dịch</p>
            <p class="font-medium">{{ formatDateTime(selectedTransaction.ngay_het_han) }}</p>
          </div>
          
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Trạng thái</p>
            <p class="font-medium">
              {{ selectedTransaction.trang_thai_giao_dich == 1 ? 'Thành công' : 
                 selectedTransaction.trang_thai_giao_dich == 2 ? 'Đang xử lý' : 'Thất bại' }}
            </p>
          </div>
          
          <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
            <p class="text-sm text-gray-500 mb-1">Phương thức thanh toán</p>
            <p class="font-medium">{{ selectedTransaction.phuong_thuc_thanh_toan == 1 ? 'Chuyển khoản' : 'Tiền mặt' }}</p>
          </div>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script>
import { 
  Plus, 
  Search, 
  CreditCard, 
  CheckCircle, 
  Clock, 
  DollarSign, 
  Eye, 
  Printer, 
  Trash2, 
  ChevronLeft, 
  ChevronRight,
  Wallet,
  Smartphone
} from 'lucide-vue-next'
import { Modal, Button, Popconfirm, Select,Checkbox } from 'ant-design-vue'
import baseRequest from '../../core/baseRequest'
import { useNotificationStore } from '../../stores/notication'

export default {
  name: 'Transactions',
  components: {
    Plus,
    Search,
    CreditCard,
    CheckCircle,
    Clock,
    DollarSign,
    Eye,
    Printer,
    Trash2,
    ChevronLeft,
    ChevronRight,
    Wallet,
    Smartphone,
    [Modal.name]: Modal,
    [Button.name]: Button,
    [Popconfirm.name]: Popconfirm,
    [Select.name]: Select
  },
  data() {
    return {
      // Thống kê
      stats: {
        total: 0,
        successful: 0,
        processing: 0,
        todayRevenue: 0
      },
      // Danh sách giao dịch
      transactions: [],
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
      showDetailModal: false,
      selectedTransaction: null,
      // Giao dịch mới
      newTransaction: {
      
      },
      isSubmitting: false,
      xeCuDan: []
    }
  },
  computed: {
    filteredTransactions() {
      let result = [...this.transactions]
      
      // Lọc theo tìm kiếm
      if (this.filters.search) {
        const searchLower = this.filters.search.toLowerCase()
        result = result.filter(transaction => 
          transaction.id.toString().toLowerCase().includes(searchLower) || 
          (transaction.bien_so_xe && transaction.bien_so_xe.toLowerCase().includes(searchLower))
        )
      }
      
      // Lọc theo trạng thái
      if (this.filters.status) {
        result = result.filter(transaction => transaction.trang_thai_giao_dich == this.filters.status)
      }
      
      // Lọc theo loại
      if (this.filters.type) {
        result = result.filter(transaction => transaction.type === this.filters.type)
      }
      
      // Lọc theo ngày
      if (this.filters.dateFrom) {
        const fromDate = new Date(this.filters.dateFrom)
        result = result.filter(transaction => new Date(transaction.ngay_het_han) >= fromDate)
      }
      
      if (this.filters.dateTo) {
        const toDate = new Date(this.filters.dateTo)
        toDate.setHours(23, 59, 59)
        result = result.filter(transaction => new Date(transaction.ngay_het_han) <= toDate)
      }
      
      // Sắp xếp theo thời gian mới nhất
      result.sort((a, b) => new Date(b.ngay_het_han) - new Date(a.ngay_het_han))
      
      // Cập nhật tổng số trang
      this.pagination.totalPages = Math.ceil(result.length / this.pagination.itemsPerPage)
      
      return result
    },
    paginatedTransactions() {
      const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage
      const end = start + this.pagination.itemsPerPage
      return this.filteredTransactions.slice(start, end)
    },
    paginationStart() {
      return (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1
    },
    paginationEnd() {
      return Math.min(this.pagination.currentPage * this.pagination.itemsPerPage, this.filteredTransactions.length)
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
    this.fetchData();
    this.getXe();
  },
  methods: {
    getXe() {
      baseRequest.get("admin/xe/lay-du-lieu")
        .then((res) => {
          this.xeCuDan = res.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    async fetchData() {
        // const notificationStore = useNotificationStore();
        const res = await baseRequest.get("admin/giao-dich/lay-du-lieu");
        this.transactions = res.data.data;
        this.updateStats();
     
    },
    updateStats() {
      const today = new Date().toISOString().split('T')[0]
      
      this.stats.total = this.transactions.length
      this.stats.successful = this.transactions.filter(t => t.trang_thai_giao_dich == 1).length
      this.stats.processing = this.transactions.filter(t => t.trang_thai_giao_dich == 2).length
      this.stats.todayRevenue = this.transactions
        .filter(t => {
          const transactionDate = new Date(t.ngay_het_han).toISOString().split('T')[0]
          return transactionDate === today && t.trang_thai_giao_dich == 1
        })
        .reduce((sum, t) => sum + t.so_tien_giao_dich, 0)
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
    formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(amount || 0)
    },
    getStatusClass(status) {
      switch (status) {
        case 1:
          return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        case 0:
          return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300'
        case 3:
          return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
        default:
          return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
      }
    },
    getPaymentIcon(method) {
      switch (method) {
        case 1:
          return CreditCard
        case 2:
          return Wallet
        default:
          return CreditCard
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
    filterOption(input, option) {
      return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
    },
    closeAddModal() {
      this.showAddModal = false;
      this.resetNewTransaction();
    },
    resetNewTransaction() {
      this.newTransaction = {
        bien_so_xe: '',
        so_tien_giao_dich: 0,
        phuong_thuc_thanh_toan: '',
        trang_thai_giao_dich: 1,
        ngay_het_han: new Date().toISOString().slice(0, 16),
        ma_giao_dich: ''
      }
    },
    addTransaction() {
      this.isSubmitting = true;
      const notificationStore = useNotificationStore();
      
      // Tự động tạo mã giao dịch
      
      baseRequest.post("admin/giao-dich/them-du-lieu", this.newTransaction)
        .then((response) => {
          if (response.data.status) {
            this.fetchData();
            this.closeAddModal();
            notificationStore.showSuccess(response.data.message);
          } else {
            notificationStore.showError('Thêm giao dịch thất bại');
          }
          this.isSubmitting = false;
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false;
        });
    },
    viewTransactionDetails(transaction) {
      this.selectedTransaction = { ...transaction }
      this.showDetailModal = true
    },
    closeDetailModal() {
      this.selectedTransaction = null
      this.showDetailModal = false
    },
    printTransaction(transaction) {
      // Trong thực tế, bạn sẽ thực hiện in giao dịch
      console.log('In giao dịch:', transaction)
    },
    deleteTransaction(transaction) {
      // Trong thực tế, bạn sẽ gọi API để xóa giao dịch
      this.transactions = this.transactions.filter(t => t.id !== transaction.id)
      this.updateStats()
    }
  }
}
</script>