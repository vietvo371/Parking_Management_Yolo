<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý giao dịch gửi xe</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Download class="mr-2 h-4 w-4" />
            Xuất dữ liệu
          </button>
        </div>
      </div>
  
      <!-- Transaction Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng số giao dịch</p>
              <h3 class="text-2xl font-bold">{{ transactionStats.total }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <Receipt class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng chi phí</p>
              <h3 class="text-2xl font-bold">{{ formatCurrency(transactionStats.totalAmount) }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <CreditCard class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Giao dịch tháng này</p>
              <h3 class="text-2xl font-bold">{{ transactionStats.thisMonth }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
              <Calendar class="h-5 w-5 text-purple-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Chi phí tháng này</p>
              <h3 class="text-2xl font-bold">{{ formatCurrency(transactionStats.thisMonthAmount) }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
              <TrendingUp class="h-5 w-5 text-orange-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Filters -->
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
              v-model="filters.type" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả loại</option>
              <option value="parking">Phí gửi xe</option>
              <option value="renewal">Gia hạn đăng ký</option>
              <option value="registration">Đăng ký mới</option>
            </select>
            <select 
              v-model="filters.status" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả trạng thái</option>
              <option value="completed">Đã hoàn thành</option>
              <option value="pending">Đang xử lý</option>
              <option value="failed">Thất bại</option>
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
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã giao dịch</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mô tả</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số tiền</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phương thức</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="transaction in paginatedTransactions" :key="transaction.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ transaction.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(transaction.date) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': transaction.type === 'parking',
                      'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300': transaction.type === 'renewal',
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': transaction.type === 'registration'
                    }">
                    {{ getTransactionTypeText(transaction.type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ transaction.licensePlate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ transaction.description }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ formatCurrency(transaction.amount) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <component :is="getPaymentMethodIcon(transaction.paymentMethod)" class="h-4 w-4 mr-2 text-gray-500" />
                    <span>{{ getPaymentMethodText(transaction.paymentMethod) }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': transaction.status === 'completed',
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': transaction.status === 'pending',
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': transaction.status === 'failed'
                    }">
                    {{ getStatusText(transaction.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="viewTransaction(transaction)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                      <Printer class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredTransactions.length === 0">
                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
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
  
      <!-- Transaction Detail Modal -->
      <a-modal v-model:open="showDetailModal" title="Chi tiết giao dịch" width="800px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="closeDetailModal">Đóng</a-button>
          </div>
        </template>
        <div v-if="selectedTransaction" class="space-y-6">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-xl font-bold">Giao dịch {{ selectedTransaction.id }}</h2>
              <p class="text-sm text-gray-500">{{ formatDateTime(selectedTransaction.date) }}</p>
            </div>
            <span 
              class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
              :class="{
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedTransaction.status === 'completed',
                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedTransaction.status === 'pending',
                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedTransaction.status === 'failed'
              }"
            >
              {{ getStatusText(selectedTransaction.status) }}
            </span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">Biển số xe</p>
              <p class="font-medium">{{ selectedTransaction.licensePlate }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại giao dịch</p>
              <p class="font-medium">{{ getTransactionTypeText(selectedTransaction.type) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Số tiền</p>
              <p class="font-medium">{{ formatCurrency(selectedTransaction.amount) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Phương thức thanh toán</p>
              <div class="flex items-center">
                <component :is="getPaymentMethodIcon(selectedTransaction.paymentMethod)" class="h-4 w-4 mr-2 text-gray-500" />
                <span class="font-medium">{{ getPaymentMethodText(selectedTransaction.paymentMethod) }}</span>
              </div>
            </div>
            <div>
              <p class="text-sm text-gray-500">Thời gian</p>
              <p class="font-medium">{{ formatDateTime(selectedTransaction.date) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Trạng thái</p>
              <p class="font-medium">{{ getStatusText(selectedTransaction.status) }}</p>
            </div>
          </div>
  
          <div v-if="selectedTransaction.details" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Chi tiết thanh toán</h4>
            <div class="space-y-2">
              <div v-for="(value, key) in selectedTransaction.details" :key="key" class="grid grid-cols-2 gap-4">
                <p class="text-sm text-gray-500">{{ key }}</p>
                <p class="text-sm">{{ value }}</p>
              </div>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Lịch sử giao dịch</h4>
            <div class="space-y-3">
              <div v-for="(history, index) in selectedTransaction.history" :key="index" class="flex items-start space-x-3">
                <div :class="`h-8 w-8 rounded-full flex items-center justify-center ${history.iconBg}`">
                  <component :is="history.icon" class="h-4 w-4" :class="history.iconColor" />
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium">{{ history.title }}</p>
                  <p class="text-xs text-gray-500">{{ history.time }}</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 flex justify-end space-x-2">
            <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">
              Tải hóa đơn PDF
            </button>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
              In hóa đơn
            </button>
          </div>
        </div>
      </a-modal>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
  import { 
    Download, 
    Search,
    Filter, 
    Calendar, 
    Receipt, 
    CreditCard, 
    TrendingUp, 
    Eye, 
    Printer, 
    ChevronLeft, 
    ChevronRight, 
    X, 
    CreditCard as CreditCardIcon, 
    Smartphone, 
    DollarSign, 
    CheckCircle, 
    Clock, 
    AlertTriangle 
  } from 'lucide-vue-next'
  import { Modal, Button } from 'ant-design-vue'
  
  export default {
    name: 'Transactions',
    components: {
      Download,
      Search,
      Filter,
      Calendar,
      Receipt,
      CreditCard,
      TrendingUp,
      Eye,
      Printer,
      ChevronLeft,
      ChevronRight,
      X,
      CreditCardIcon,
      Smartphone,
      DollarSign,
      CheckCircle,
      Clock,
      AlertTriangle,
      [Modal.name]: Modal,
      [Button.name]: Button
    },
    data() {
      return {
        transactions: [],
        filters: {
          search: '',
          type: '',
          status: '',
          dateFrom: '',
          dateTo: ''
        },
        pagination: {
          currentPage: 1,
          itemsPerPage: 10,
          totalPages: 1
        },
        showDetailModal: false,
        selectedTransaction: null,
        transactionStats: {
          total: 0,
          totalAmount: 0,
          thisMonth: 0,
          thisMonthAmount: 0
        }
      }
    },
    computed: {
      filteredTransactions() {
        let result = [...this.transactions]
        
        if (this.filters.search) {
          const searchLower = this.filters.search.toLowerCase()
          result = result.filter(transaction => 
            transaction.id.toLowerCase().includes(searchLower) || 
            transaction.licensePlate.toLowerCase().includes(searchLower)
          )
        }
        
        if (this.filters.type) {
          result = result.filter(transaction => transaction.type === this.filters.type)
        }
        
        if (this.filters.status) {
          result = result.filter(transaction => transaction.status === this.filters.status)
        }
        
        if (this.filters.dateFrom) {
          const fromDate = new Date(this.filters.dateFrom)
          result = result.filter(transaction => new Date(transaction.date) >= fromDate)
        }
        
        if (this.filters.dateTo) {
          const toDate = new Date(this.filters.dateTo)
          toDate.setHours(23, 59, 59)
          result = result.filter(transaction => new Date(transaction.date) <= toDate)
        }
        
        result.sort((a, b) => new Date(b.date) - new Date(a.date))
        
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
      this.fetchData()
    },
    methods: {
      async fetchData() {
        try {
          this.transactions = [
            {
              id: 'TRX-001',
              date: '2023-05-01T08:30:00',
              type: 'parking',
              licensePlate: '30A-12345',
              description: 'Phí gửi xe tháng 04/2023',
              amount: 500000,
              paymentMethod: 'bank',
              status: 'completed',
              paymentId: 'BNK123456789',
              details: {
                'Ngân hàng': 'Vietcombank',
                'Số tài khoản': '************1234',
                'Thời gian thanh toán': '24/04/2023 10:15',
                'Nội dung chuyển khoản': 'Phi gui xe 30A-12345 thang 04/2023'
              },
              history: [
                {
                  title: 'Giao dịch hoàn thành',
                  time: '24/04/2023 - 10:15',
                  icon: CheckCircle,
                  iconBg: 'bg-green-100',
                  iconColor: 'text-green-600'
                },
                {
                  title: 'Đang xử lý thanh toán',
                  time: '24/04/2023 - 10:14',
                  icon: Clock,
                  iconBg: 'bg-yellow-100',
                  iconColor: 'text-yellow-600'
                },
                {
                  title: 'Tạo giao dịch',
                  time: '24/04/2023 - 10:10',
                  icon: Receipt,
                  iconBg: 'bg-blue-100',
                  iconColor: 'text-blue-600'
                }
              ]
            }
          ]
          
          this.updateStats()
        } catch (error) {
          console.error('Lỗi khi lấy dữ liệu:', error)
        }
      },
      updateStats() {
        const today = new Date()
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1)
        
        this.transactionStats.total = this.transactions.length
        this.transactionStats.totalAmount = this.transactions.reduce((sum, t) => sum + t.amount, 0)
        this.transactionStats.thisMonth = this.transactions.filter(t => new Date(t.date) >= firstDayOfMonth).length
        this.transactionStats.thisMonthAmount = this.transactions
          .filter(t => new Date(t.date) >= firstDayOfMonth)
          .reduce((sum, t) => sum + t.amount, 0)
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
      getTransactionTypeText(type) {
        switch (type) {
          case 'parking':
            return 'Phí gửi xe'
          case 'renewal':
            return 'Gia hạn đăng ký'
          case 'registration':
            return 'Đăng ký mới'
          default:
            return type
        }
      },
      getPaymentMethodIcon(method) {
        switch (method) {
          case 'bank':
            return CreditCardIcon
          case 'momo':
            return Smartphone
          case 'vnpay':
            return CreditCardIcon
          case 'cash':
            return DollarSign
          default:
            return CreditCardIcon
        }
      },
      getPaymentMethodText(method) {
        switch (method) {
          case 'bank':
            return 'Chuyển khoản ngân hàng'
          case 'momo':
            return 'Ví MoMo'
          case 'vnpay':
            return 'VNPay'
          case 'cash':
            return 'Tiền mặt'
          default:
            return method
        }
      },
      getStatusText(status) {
        switch (status) { 
          case 'completed':
            return 'Đã hoàn thành'
          case 'pending':
            return 'Đang xử lý'
          case 'failed':
            return 'Thất bại' 
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
      viewTransaction(transaction) {
        this.selectedTransaction = { ...transaction }
        this.showDetailModal = true
      },
      closeDetailModal() {
        this.selectedTransaction = null
        this.showDetailModal = false
      }
    }
  }
  </script>
