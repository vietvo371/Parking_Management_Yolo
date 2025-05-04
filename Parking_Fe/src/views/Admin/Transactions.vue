<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý giao dịch</h1>
      <div class="flex items-center space-x-2">
        <div class="relative">
          <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
          <input 
            type="text" 
            placeholder="Tìm kiếm giao dịch..." 
            class="pl-9 h-10 w-full sm:w-[300px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            v-model="searchQuery"
          />
        </div>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
          <Plus class="mr-2 h-4 w-4" />
          Thêm giao dịch
        </button>
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
            <option value="Phí gửi xe tháng">Phí gửi xe tháng</option>
            <option value="Phí gửi xe ngày">Phí gửi xe ngày</option>
            <option value="Phí gửi xe giờ">Phí gửi xe giờ</option>
          </select>
          <select 
            v-model="filterStatus" 
            class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
          >
            <option value="">Tất cả trạng thái</option>
            <option value="Thành công">Thành công</option>
            <option value="Đang xử lý">Đang xử lý</option>
            <option value="Thất bại">Thất bại</option>
          </select>
        </div>
        <div class="flex items-center space-x-2">
          <div class="relative">
            <Calendar class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="date" 
              class="pl-9 h-10 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="startDate"
            />
          </div>
          <span class="text-gray-500">đến</span>
          <div class="relative">
            <Calendar class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="date" 
              class="pl-9 h-10 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="endDate"
            />
          </div>
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Filter class="mr-2 h-4 w-4" />
            Lọc
          </button>
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Download class="mr-2 h-4 w-4" />
            Xuất
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
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                <div class="flex items-center">
                  <input type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600" v-model="selectAll" @change="toggleSelectAll" />
                </div>
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại giao dịch</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số tiền</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phương thức</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="transaction in filteredTransactions" :key="transaction.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <input type="checkbox" class="h-4 w-4 rounded border-gray-300 dark:border-gray-600" v-model="transaction.selected" />
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ transaction.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ transaction.licensePlate }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ transaction.type }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ formatCurrency(transaction.amount) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                <div class="flex items-center">
                  <component :is="getPaymentIcon(transaction.paymentMethod)" class="h-4 w-4 mr-2" />
                  {{ transaction.paymentMethod }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ transaction.time }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="{
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': transaction.status === 'Thành công',
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': transaction.status === 'Đang xử lý',
                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': transaction.status === 'Thất bại'
                  }">
                  {{ transaction.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                    <Eye class="h-4 w-4" />
                  </button>
                  <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                    <Printer class="h-4 w-4" />
                  </button>
                  <button class="text-gray-600 hover:text-gray-900 dark:hover:text-gray-400">
                    <MoreVertical class="h-4 w-4" />
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
              Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">10</span> của <span class="font-medium">{{ transactions.length }}</span> kết quả
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
              <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300">
                ...
              </span>
              <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                10
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
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { 
  Search, 
  Plus, 
  Filter, 
  Download, 
  Calendar, 
  Eye, 
  Printer, 
  MoreVertical,
  ChevronLeft,
  ChevronRight,
  CreditCard,
  Wallet,
  Smartphone
} from 'lucide-vue-next'

export default {
  name: 'Transactions',
  components: {
    Search,
    Plus,
    Filter,
    Download,
    Calendar,
    Eye,
    Printer,
    MoreVertical,
    ChevronLeft,
    ChevronRight,
    CreditCard,
    Wallet,
    Smartphone
  },
  setup() {
    const searchQuery = ref('')
    const filterType = ref('')
    const filterStatus = ref('')
    const startDate = ref('')
    const endDate = ref('')
    const selectAll = ref(false)
    
    // Sample transactions data
    const transactions = ref([
      {
        id: 'TRX-001',
        licensePlate: '30A-12345',
        type: 'Phí gửi xe tháng',
        amount: 500000,
        paymentMethod: 'Thẻ tín dụng',
        time: '24/04/2023 - 10:25',
        status: 'Thành công',
        selected: false
      },
      {
        id: 'TRX-002',
        licensePlate: '30F-54321',
        type: 'Phí gửi xe tháng',
        amount: 500000,
        paymentMethod: 'Tiền mặt',
        time: '24/04/2023 - 09:45',
        status: 'Thành công',
        selected: false
      },
      {
        id: 'TRX-003',
        licensePlate: '29P2-12345',
        type: 'Phí gửi xe ngày',
        amount: 20000,
        paymentMethod: 'Ví điện tử',
        time: '24/04/2023 - 09:30',
        status: 'Đang xử lý',
        selected: false
      },
      {
        id: 'TRX-004',
        licensePlate: '30K1-65432',
        type: 'Phí gửi xe tháng',
        amount: 300000,
        paymentMethod: 'Thẻ tín dụng',
        time: '24/04/2023 - 09:15',
        status: 'Thành công',
        selected: false
      },
      {
        id: 'TRX-005',
        licensePlate: '30A-56789',
        type: 'Phí gửi xe ngày',
        amount: 20000,
        paymentMethod: 'Ví điện tử',
        time: '24/04/2023 - 08:45',
        status: 'Thất bại',
        selected: false
      },
      {
        id: 'TRX-006',
        licensePlate: '30H-98765',
        type: 'Phí gửi xe tháng',
        amount: 500000,
        paymentMethod: 'Tiền mặt',
        time: '23/04/2023 - 17:30',
        status: 'Thành công',
        selected: false
      },
      {
        id: 'TRX-007',
        licensePlate: '29Y2-54321',
        type: 'Phí gửi xe giờ',
        amount: 10000,
        paymentMethod: 'Tiền mặt',
        time: '23/04/2023 - 16:45',
        status: 'Thành công',
        selected: false
      },
      {
        id: 'TRX-008',
        licensePlate: '30K5-13579',
        type: 'Phí gửi xe tháng',
        amount: 300000,
        paymentMethod: 'Thẻ tín dụng',
        time: '23/04/2023 - 15:20',
        status: 'Thành công',
        selected: false
      },
      {
        id: 'TRX-009',
        licensePlate: '30A-24680',
        type: 'Phí gửi xe ngày',
        amount: 20000,
        paymentMethod: 'Ví điện tử',
        time: '23/04/2023 - 14:10',
        status: 'Đang xử lý',
        selected: false
      },
      {
        id: 'TRX-010',
        licensePlate: '30F-97531',
        type: 'Phí gửi xe tháng',
        amount: 500000,
        paymentMethod: 'Tiền mặt',
        time: '23/04/2023 - 11:30',
        status: 'Thành công',
        selected: false
      }
    ])
    
    // Filter transactions based on search query and filters
    const filteredTransactions = computed(() => {
      return transactions.value.filter(transaction => {
        const matchesSearch = 
          transaction.id.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
          transaction.licensePlate.toLowerCase().includes(searchQuery.value.toLowerCase())
        
        const matchesType = filterType.value ? transaction.type === filterType.value : true
        const matchesStatus = filterStatus.value ? transaction.status === filterStatus.value : true
        
        // Date filtering would be implemented here in a real app
        
        return matchesSearch && matchesType && matchesStatus
      })
    })
    
    // Toggle select all
    const toggleSelectAll = () => {
      transactions.value.forEach(transaction => {
        transaction.selected = selectAll.value
      })
    }
    
    // Format currency
    const formatCurrency = (value) => {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
    }
    
    // Get payment method icon
    const getPaymentIcon = (method) => {
      switch (method) {
        case 'Thẻ tín dụng':
          return CreditCard
        case 'Tiền mặt':
          return Wallet
        case 'Ví điện tử':
          return Smartphone
        default:
          return CreditCard
      }
    }
    
    return {
      searchQuery,
      filterType,
      filterStatus,
      startDate,
      endDate,
      selectAll,
      transactions,
      filteredTransactions,
      toggleSelectAll,
      formatCurrency,
      getPaymentIcon
    }
  }
}
</script>