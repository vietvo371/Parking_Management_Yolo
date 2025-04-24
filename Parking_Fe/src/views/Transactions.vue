<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý giao dịch</h1>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
          <CreditCard class="mr-2 h-4 w-4" />
          Tạo giao dịch mới
        </button>
      </div>
  
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex space-x-2 overflow-x-auto">
          <button 
            v-for="tab in tabs" 
            :key="tab.value"
            @click="activeTab = tab.value"
            class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap"
            :class="activeTab === tab.value ? 'bg-gray-100 dark:bg-gray-700' : ''"
          >
            {{ tab.label }}
          </button>
        </div>
        <div class="flex w-full sm:w-auto items-center gap-2">
          <div class="relative w-full sm:w-auto">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              v-model="searchQuery" 
              type="search" 
              placeholder="Tìm kiếm giao dịch..." 
              class="pl-9 w-full sm:w-[250px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            />
          </div>
          <button class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
            <Filter class="h-4 w-4" />
          </button>
        </div>
      </div>
  
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
          <div>
            <h2 class="text-lg font-medium">{{ getActiveTabTitle() }}</h2>
            <p class="text-sm text-gray-500">{{ getActiveTabDescription() }}</p>
          </div>
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Download class="mr-2 h-4 w-4" />
            Xuất báo cáo
          </button>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mã giao dịch</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cư dân</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại phí</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số tiền</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày giao dịch</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phương thức</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="transaction in filteredTransactions" :key="transaction.id">
                <td class="px-6 py-4 whitespace-nowrap font-medium">{{ transaction.id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span>{{ transaction.resident }}</span>
                    <span class="text-xs text-gray-500">{{ transaction.apartment }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ transaction.licensePlate }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ transaction.type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ transaction.amount.toLocaleString() }} VND</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ transaction.date }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ transaction.paymentMethod }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="transaction.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'"
                  >
                    {{ transaction.status === 'completed' ? 'Đã thanh toán' : 'Chờ thanh toán' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">Chi tiết</button>
                    <button 
                      v-if="transaction.status === 'pending'"
                      class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md flex items-center text-green-600"
                    >
                      <CheckCircle class="mr-1 h-4 w-4" />
                      Xác nhận
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
  import { Search, Filter, Download, CreditCard, CheckCircle } from 'lucide-vue-next'
  import { useTransactionsStore } from '@/stores/transactions'
  
  export default {
    name: 'Transactions',
    components: {
      Search,
      Filter,
      Download,
      CreditCard,
      CheckCircle
    },
    setup() {
      const transactionsStore = useTransactionsStore()
      const activeTab = ref('all')
      const searchQuery = ref('')
      const startDate = ref('')
      const endDate = ref('')
  
      const tabs = [
        { value: 'all', label: 'Tất cả' },
        { value: 'completed', label: 'Đã thanh toán' },
        { value: 'pending', label: 'Chờ thanh toán' }
      ]
  
      const filteredTransactions = computed(() => {
        let result = [...transactionsStore.transactions]
  
        // Lọc theo tab
        if (activeTab.value === 'completed') {
          result = result.filter(t => t.status === 'completed')
        } else if (activeTab.value === 'pending') {
          result = result.filter(t => t.status === 'pending')
        }
  
        // Lọc theo tìm kiếm
        if (searchQuery.value) {
          const query = searchQuery.value.toLowerCase()
          result = result.filter(
            t => 
              t.id.toLowerCase().includes(query) ||
              t.resident.toLowerCase().includes(query) ||
              t.licensePlate.toLowerCase().includes(query)
          )
        }
  
        // Lọc theo khoảng thời gian
        if (startDate.value && endDate.value) {
          // Trong thực tế sẽ cần xử lý chuyển đổi định dạng ngày tháng
          // Đây chỉ là mô phỏng đơn giản
          result = result.filter(t => {
            const transactionDate = new Date(t.date.split('/').reverse().join('-'))
            const start = new Date(startDate.value)
            const end = new Date(endDate.value)
            return transactionDate >= start && transactionDate <= end
          })
        }
  
        return result
      })
  
      const getActiveTabTitle = () => {
        switch (activeTab.value) {
          case 'completed':
            return 'Giao dịch đã thanh toán'
          case 'pending':
            return 'Giao dịch chờ thanh toán'
          default:
            return 'Danh sách giao dịch'
        }
      }
  
      const getActiveTabDescription = () => {
        switch (activeTab.value) {
          case 'completed':
            return 'Danh sách các giao dịch đã hoàn thành'
          case 'pending':
            return 'Danh sách các giao dịch đang chờ xử lý'
          default:
            return 'Quản lý tất cả các giao dịch trong hệ thống'
        }
      }
  
      const confirmTransaction = (id) => {
        transactionsStore.updateTransaction(id, {
          status: 'completed',
          paymentMethod: 'Tiền mặt'
        })
      }
  
      return {
        activeTab,
        searchQuery,
        startDate,
        endDate,
        tabs,
        filteredTransactions,
        getActiveTabTitle,
        getActiveTabDescription,
        confirmTransaction
      }
    }
  }
  </script>
  
  <style>
  </style>