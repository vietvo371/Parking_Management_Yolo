<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Thống kê & Báo cáo</h1>
      <div class="flex items-center space-x-2">
        <button 
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center"
          @click="exportReport"
        >
          <Download class="mr-2 h-4 w-4" />
          Xuất báo cáo
        </button>
        <button 
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
          @click="refreshData"
        >
          <RefreshCw class="mr-2 h-4 w-4" />
          Làm mới
        </button>
      </div>
    </div>

    <!-- Bộ lọc thời gian -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
      <div class="flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Khoảng thời gian</label>
            <select 
              v-model="filters.timeRange" 
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              @change="handleTimeRangeChange"
            >
              <option value="today">Hôm nay</option>
              <option value="yesterday">Hôm qua</option>
              <option value="last7days">7 ngày qua</option>
              <option value="last30days">30 ngày qua</option>
              <option value="thisMonth">Tháng này</option>
              <option value="lastMonth">Tháng trước</option>
              <option value="thisYear">Năm nay</option>
              <option value="custom">Tùy chỉnh</option>
            </select>
          </div>
          
          <div v-if="filters.timeRange === 'custom'">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Từ ngày</label>
            <input 
              type="date" 
              v-model="filters.startDate"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            />
          </div>
          
          <div v-if="filters.timeRange === 'custom'">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đến ngày</label>
            <input 
              type="date" 
              v-model="filters.endDate"
              class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            />
          </div>
        </div>
        
        <div class="flex-shrink-0">
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center h-10"
            @click="applyFilters"
          >
            <Filter class="mr-2 h-4 w-4" />
            Áp dụng
          </button>
        </div>
      </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center mr-4">
            <DollarSign class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng doanh thu</p>
            <p class="text-2xl font-bold">{{ formatCurrency(statistics.totalRevenue) }}</p>
            <div class="flex items-center mt-1">
              <span 
                class="text-xs px-1 rounded flex items-center"
                :class="statistics.revenueChange >= 0 ? 'text-green-600 bg-green-100 dark:bg-green-900/20' : 'text-red-600 bg-red-100 dark:bg-red-900/20'"
              >
                <component :is="statistics.revenueChange >= 0 ? TrendingUp : TrendingDown" class="h-3 w-3 mr-1" />
                {{ Math.abs(statistics.revenueChange) }}%
              </span>
              <span class="text-xs text-gray-500 ml-1">so với kỳ trước</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-4">
            <Car class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số xe</p>
            <p class="text-2xl font-bold">{{ statistics.totalVehicles }}</p>
            <div class="flex items-center mt-1">
              <div class="flex items-center">
                <Car class="h-3 w-3 text-gray-500 mr-1" />
                <span class="text-xs text-gray-500">{{ statistics.carCount }} ô tô</span>
              </div>
              <div class="flex items-center ml-2">
                <Bike class="h-3 w-3 text-gray-500 mr-1" />
                <span class="text-xs text-gray-500">{{ statistics.motorbikeCount }} xe máy</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center mr-4">
            <Users class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Khách hàng</p>
            <p class="text-2xl font-bold">{{ statistics.totalCustomers }}</p>
            <div class="flex items-center mt-1">
              <div class="flex items-center">
                <Home class="h-3 w-3 text-gray-500 mr-1" />
                <span class="text-xs text-gray-500">{{ statistics.residentCount }} cư dân</span>
              </div>
              <div class="flex items-center ml-2">
                <UserPlus class="h-3 w-3 text-gray-500 mr-1" />
                <span class="text-xs text-gray-500">{{ statistics.guestCount }} khách</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 flex items-center justify-center mr-4">
            <PercentIcon class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tỷ lệ sử dụng</p>
            <p class="text-2xl font-bold">{{ statistics.occupancyRate }}%</p>
            <div class="flex items-center mt-1">
              <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div 
                  class="bg-purple-600 h-2 rounded-full" 
                  :style="{ width: `${statistics.occupancyRate}%` }"
                ></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Biểu đồ doanh thu -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4 lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Doanh thu theo thời gian</h2>
          <div>
            <select 
              v-model="revenueChartType" 
              class="px-3 py-1 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm"
              @change="updateRevenueChart"
            >
              <option value="daily">Theo ngày</option>
              <option value="weekly">Theo tuần</option>
              <option value="monthly">Theo tháng</option>
            </select>
          </div>
        </div>
        <div class="h-80">
          <canvas ref="revenueChart"></canvas>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Doanh thu theo loại</h2>
        </div>
        <div class="h-80">
          <canvas ref="revenueByTypeChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Biểu đồ xe và khách hàng -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Phân bố loại xe</h2>
        </div>
        <div class="h-80">
          <canvas ref="vehicleDistributionChart"></canvas>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Doanh thu theo gói đăng ký</h2>
        </div>
        <div class="h-80">
          <canvas ref="packageRevenueChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Thống kê chi tiết -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Thống kê theo giờ</h2>
        </div>
        <div class="h-80">
          <canvas ref="hourlyStatsChart"></canvas>
        </div>
      </div>
      
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between mb-4">
          <h2 class="text-lg font-medium">Tỷ lệ sử dụng bãi đỗ xe</h2>
        </div>
        <div class="h-80">
          <canvas ref="occupancyChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Bảng thống kê chi tiết -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-lg font-medium">Chi tiết doanh thu</h2>
        <div class="flex items-center space-x-2">
          <div class="relative">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="text" 
              placeholder="Tìm kiếm..." 
              class="pl-9 h-10 w-full sm:w-[250px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="revenueSearch"
            />
          </div>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số lượng</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doanh thu</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tỷ lệ</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="(item, index) in filteredRevenueDetails" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatDate(item.date) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <component :is="getTypeIcon(item.type)" class="h-4 w-4 mr-2" :class="getTypeColor(item.type)" />
                  <span class="text-sm">{{ getTypeName(item.type) }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ item.count }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">{{ formatCurrency(item.revenue) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-16 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-2">
                    <div 
                      class="rounded-full h-2" 
                      :class="getTypeBackgroundColor(item.type)"
                      :style="{ width: `${item.percentage}%` }"
                    ></div>
                  </div>
                  <span class="text-sm">{{ item.percentage.toFixed(1) }}%</span>
                </div>
              </td>
            </tr>
            <tr v-if="filteredRevenueDetails.length === 0">
              <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                Không có dữ liệu
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
import { ref, computed, onMounted, watch } from 'vue'
import { 
  Download, 
  RefreshCw, 
  Filter, 
  Search, 
  DollarSign, 
  Car, 
  Bike, 
  Users, 
  PercentIcon, 
  TrendingUp, 
  TrendingDown, 
  Home, 
  UserPlus, 
  CheckCircle, 
  AlertCircle, 
  X, 
  ChevronLeft, 
  ChevronRight, 
  ChevronsLeft, 
  ChevronsRight,
  Calendar,
  Clock,
  CreditCard
} from 'lucide-vue-next'
import Chart from 'chart.js/auto'

export default {
  name: 'ThongKe',
  components: {
    Download, 
    RefreshCw, 
    Filter, 
    Search, 
    DollarSign, 
    Car, 
    Bike, 
    Users, 
    PercentIcon, 
    TrendingUp, 
    TrendingDown, 
    Home, 
    UserPlus, 
    CheckCircle, 
    AlertCircle, 
    X, 
    ChevronLeft, 
    ChevronRight, 
    ChevronsLeft, 
    ChevronsRight,
    Calendar,
    Clock,
    CreditCard
  },
  setup() {
    // Biểu đồ
    const revenueChart = ref(null)
    const revenueByTypeChart = ref(null)
    const vehicleDistributionChart = ref(null)
    const packageRevenueChart = ref(null)
    const hourlyStatsChart = ref(null)
    const occupancyChart = ref(null)
    
    // Đối tượng Chart.js
    const charts = ref({
      revenue: null,
      revenueByType: null,
      vehicleDistribution: null,
      packageRevenue: null,
      hourlyStats: null,
      occupancy: null
    })
    
    // Loại biểu đồ doanh thu
    const revenueChartType = ref('daily')
    
    // Bộ lọc
    const filters = ref({
      timeRange: 'last30days',
      startDate: '',
      endDate: ''
    })
    
    // Thống kê
    const statistics = ref({
      totalRevenue: 0,
      revenueChange: 0,
      totalVehicles: 0,
      carCount: 0,
      motorbikeCount: 0,
      totalCustomers: 0,
      residentCount: 0,
      guestCount: 0,
      occupancyRate: 0
    })
    
    // Dữ liệu chi tiết doanh thu
    const revenueDetails = ref([])
    const revenueSearch = ref('')
    
    // Phân trang
    const pagination = ref({
      currentPage: 1,
      itemsPerPage: 10,
      totalItems: 0,
      totalPages: 1,
      startItem: 1,
      endItem: 10
    })
    
    // Toast
    const showToast = ref(false)
    const toastType = ref('success')
    const toastTitle = ref('')
    const toastMessage = ref('')
    
    // Khởi tạo khi component được tạo
    onMounted(() => {
      // Thiết lập ngày mặc định cho bộ lọc
      setDefaultDateRange()
      
      // Lấy dữ liệu
      fetchData()
    })
    
    // Thiết lập ngày mặc định cho bộ lọc
    const setDefaultDateRange = () => {
      const today = new Date()
      const thirtyDaysAgo = new Date(today)
      thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30)
      
      filters.value.startDate = formatDateForInput(thirtyDaysAgo)
      filters.value.endDate = formatDateForInput(today)
    }
    
    // Định dạng ngày cho input
    const formatDateForInput = (date) => {
      return date.toISOString().split('T')[0]
    }
    
    // Xử lý thay đổi khoảng thời gian
    const handleTimeRangeChange = () => {
      const today = new Date()
      let startDate = new Date(today)
      
      switch (filters.value.timeRange) {
        case 'today':
          startDate = new Date(today)
          break
        case 'yesterday':
          startDate = new Date(today)
          startDate.setDate(startDate.getDate() - 1)
          break
        case 'last7days':
          startDate = new Date(today)
          startDate.setDate(startDate.getDate() - 7)
          break
        case 'last30days':
          startDate = new Date(today)
          startDate.setDate(startDate.getDate() - 30)
          break
        case 'thisMonth':
          startDate = new Date(today.getFullYear(), today.getMonth(), 1)
          break
        case 'lastMonth':
          startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1)
          const endOfLastMonth = new Date(today.getFullYear(), today.getMonth(), 0)
          filters.value.endDate = formatDateForInput(endOfLastMonth)
          break
        case 'thisYear':
          startDate = new Date(today.getFullYear(), 0, 1)
          break
        case 'custom':
          // Không làm gì, để người dùng chọn
          return
      }
      
      filters.value.startDate = formatDateForInput(startDate)
      if (filters.value.timeRange !== 'lastMonth') {
        filters.value.endDate = formatDateForInput(today)
      }
    }
    
    // Áp dụng bộ lọc
    const applyFilters = () => {
      fetchData()
    }
    
    // Làm mới dữ liệu
    const refreshData = () => {
      fetchData()
      showToastMessage('success', 'Làm mới dữ liệu', 'Dữ liệu đã được cập nhật thành công')
    }
    
    // Lấy dữ liệu từ server
    const fetchData = async () => {
      try {
        // Trong thực tế, bạn sẽ gọi API để lấy dữ liệu
        // Ở đây chúng ta sẽ sử dụng dữ liệu mẫu
        
        // Lấy thống kê tổng quan
        statistics.value = generateMockStatistics()
        
        // Lấy dữ liệu chi tiết doanh thu
        revenueDetails.value = generateMockRevenueDetails()
        
        // Cập nhật phân trang
        pagination.value.totalItems = revenueDetails.value.length
        pagination.value.totalPages = Math.ceil(revenueDetails.value.length / pagination.value.itemsPerPage)
        updatePaginationInfo()
        
        // Khởi tạo biểu đồ
        initCharts()
      } catch (error) {
        console.error('Lỗi khi lấy dữ liệu:', error)
        showToastMessage('error', 'Lỗi', 'Không thể tải dữ liệu. Vui lòng thử lại sau.')
      }
    }
    
    // Tạo dữ liệu thống kê mẫu
    const generateMockStatistics = () => {
      return {
        totalRevenue: Math.floor(Math.random() * 500000000) + 500000000,
        revenueChange: Math.floor(Math.random() * 30) - 10,
        totalVehicles: Math.floor(Math.random() * 500) + 500,
        carCount: Math.floor(Math.random() * 200) + 200,
        motorbikeCount: Math.floor(Math.random() * 300) + 300,
        totalCustomers: Math.floor(Math.random() * 300) + 300,
        residentCount: Math.floor(Math.random() * 200) + 200,
        guestCount: Math.floor(Math.random() * 100) + 100,
        occupancyRate: Math.floor(Math.random() * 30) + 70
      }
    }
    
    // Tạo dữ liệu chi tiết doanh thu mẫu
    const generateMockRevenueDetails = () => {
      const details = []
      const today = new Date()
      const startDate = new Date(filters.value.startDate)
      const endDate = new Date(filters.value.endDate)
      
      // Tính số ngày trong khoảng
      const diffTime = Math.abs(endDate - startDate)
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1
      
      const types = ['resident_car', 'resident_motorbike', 'guest_car', 'guest_motorbike']
      
      // Tạo dữ liệu cho mỗi ngày
      for (let i = 0; i < diffDays; i++) {
        const currentDate = new Date(startDate)
        currentDate.setDate(currentDate.getDate() + i)
        
        // Tạo dữ liệu cho mỗi loại
        for (const type of types) {
          const count = Math.floor(Math.random() * 50) + 10
          const revenue = type.includes('car') 
            ? count * (Math.floor(Math.random() * 50000) + 50000) 
            : count * (Math.floor(Math.random() * 20000) + 20000)
          
          details.push({
            date: currentDate,
            type: type,
            count: count,
            revenue: revenue,
            percentage: 0 // Sẽ được tính toán sau
          })
        }
      }
      
      // Tính phần trăm
      const totalRevenue = details.reduce((sum, item) => sum + item.revenue, 0)
      details.forEach(item => {
        item.percentage = (item.revenue / totalRevenue) * 100
      })
      
      return details
    }
    
    // Khởi tạo biểu đồ
    const initCharts = () => {
      // Hủy biểu đồ cũ nếu có
      Object.values(charts.value).forEach(chart => {
        if (chart) {
          chart.destroy()
        }
      })
      
      // Khởi tạo biểu đồ mới
      initRevenueChart()
      initRevenueByTypeChart()
      initVehicleDistributionChart()
      initPackageRevenueChart()
      initHourlyStatsChart()
      initOccupancyChart()
    }
    
    // Khởi tạo biểu đồ doanh thu
    const initRevenueChart = () => {
      const ctx = revenueChart.value.getContext('2d')
      
      // Chuẩn bị dữ liệu
      const data = prepareRevenueChartData()
      
      charts.value.revenue = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [
            {
              label: 'Doanh thu',
              data: data.values,
              backgroundColor: 'rgba(59, 130, 246, 0.2)',
              borderColor: 'rgba(59, 130, 246, 1)',
              borderWidth: 2,
              tension: 0.4,
              fill: true
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  return formatCurrency(context.parsed.y)
                }
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  return formatCurrencyShort(value)
                }
              }
            }
          }
        }
      })
    }
    
    // Chuẩn bị dữ liệu cho biểu đồ doanh thu
    const prepareRevenueChartData = () => {
      const labels = []
      const values = []
      
      // Nhóm dữ liệu theo ngày/tuần/tháng
      const groupedData = {}
      
      revenueDetails.value.forEach(item => {
        let key
        const date = new Date(item.date)
        
        if (revenueChartType.value === 'daily') {
          key = formatDate(date)
        } else if (revenueChartType.value === 'weekly') {
          // Lấy ngày đầu tuần (thứ 2)
          const dayOfWeek = date.getDay() || 7 // 0 = CN, 1-6 = T2-T7
          const mondayDate = new Date(date)
          mondayDate.setDate(date.getDate() - dayOfWeek + 1)
          key = `Tuần ${Math.ceil(date.getDate() / 7)} - ${formatMonthYear(date)}`
        } else if (revenueChartType.value === 'monthly') {
          key = formatMonthYear(date)
        }
        
        if (!groupedData[key]) {
          groupedData[key] = 0
        }
        
        groupedData[key] += item.revenue
      })
      
      // Sắp xếp theo thời gian
      const sortedKeys = Object.keys(groupedData).sort((a, b) => {
        if (revenueChartType.value === 'daily') {
          return new Date(a) - new Date(b)
        } else {
          return a.localeCompare(b)
        }
      })
      
      sortedKeys.forEach(key => {
        labels.push(key)
        values.push(groupedData[key])
      })
      
      return { labels, values }
    }
    
    // Cập nhật biểu đồ doanh thu khi thay đổi loại
    const updateRevenueChart = () => {
      const data = prepareRevenueChartData()
      
      charts.value.revenue.data.labels = data.labels
      charts.value.revenue.data.datasets[0].data = data.values
      charts.value.revenue.update()
    }
    
    // Khởi tạo biểu đồ doanh thu theo loại
    const initRevenueByTypeChart = () => {
      const ctx = revenueByTypeChart.value.getContext('2d')
      
      // Chuẩn bị dữ liệu
      const data = prepareRevenueByTypeData()
      
      charts.value.revenueByType = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: data.labels,
          datasets: [
            {
              data: data.values,
              backgroundColor: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(245, 158, 11, 0.7)',
                'rgba(239, 68, 68, 0.7)'
              ],
              borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(245, 158, 11, 1)',
                'rgba(239, 68, 68, 1)'
              ],
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || ''
                  const value = formatCurrency(context.parsed)
                  const percentage = Math.round(context.parsed * 100 / context.dataset.data.reduce((a, b) => a + b, 0)) + '%'
                  return `${label}: ${value} (${percentage})`
                }
              }
            }
          }
        }
      })
    }
    
    // Chuẩn bị dữ liệu cho biểu đồ doanh thu theo loại
    const prepareRevenueByTypeData = () => {
      const typeMap = {
        'resident_car': 'Cư dân - Ô tô',
        'resident_motorbike': 'Cư dân - Xe máy',
        'guest_car': 'Khách - Ô tô',
        'guest_motorbike': 'Khách - Xe máy'
      }
      
      const groupedData = {}
      
      revenueDetails.value.forEach(item => {
        const type = typeMap[item.type]
        
        if (!groupedData[type]) {
          groupedData[type] = 0
        }
        
        groupedData[type] += item.revenue
      })
      
      const labels = Object.keys(groupedData)
      const values = Object.values(groupedData)
      
      return { labels, values }
    }
    
    // Khởi tạo biểu đồ phân bố loại xe
    const initVehicleDistributionChart = () => {
      const ctx = vehicleDistributionChart.value.getContext('2d')
      
      charts.value.vehicleDistribution = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Cư dân', 'Khách'],
          datasets: [
            {
              label: 'Ô tô',
              data: [statistics.value.carCount * 0.7, statistics.value.carCount * 0.3],
              backgroundColor: 'rgba(59, 130, 246, 0.7)',
              borderColor: 'rgba(59, 130, 246, 1)',
              borderWidth: 1
            },
            {
              label: 'Xe máy',
              data: [statistics.value.motorbikeCount * 0.6, statistics.value.motorbikeCount * 0.4],
              backgroundColor: 'rgba(16, 185, 129, 0.7)',
              borderColor: 'rgba(16, 185, 129, 1)',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            x: {
              stacked: false
            },
            y: {
              stacked: false,
              beginAtZero: true
            }
          }
        }
      })
    }
    
    // Khởi tạo biểu đồ doanh thu theo gói đăng ký
    const initPackageRevenueChart = () => {
      const ctx = packageRevenueChart.value.getContext('2d')
      
      charts.value.packageRevenue = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Gói 1 tháng', 'Gói 3 tháng', 'Gói 6 tháng', 'Gói 12 tháng'],
          datasets: [
            {
              data: [
                Math.floor(Math.random() * 100000000) + 50000000,
                Math.floor(Math.random() * 150000000) + 100000000,
                Math.floor(Math.random() * 200000000) + 150000000,
                Math.floor(Math.random() * 250000000) + 200000000
              ],
              backgroundColor: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(245, 158, 11, 0.7)',
                'rgba(239, 68, 68, 0.7)'
              ],
              borderColor: [
                'rgba(59, 130, 246, 1)',
                'rgba(16, 185, 129, 1)',
                'rgba(245, 158, 11, 1)',
                'rgba(239, 68, 68, 1)'
              ],
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            },
            tooltip: {
              callbacks: {
                label: function(context) {
                  const label = context.label || ''
                  const value = formatCurrency(context.parsed)
                  const percentage = Math.round(context.parsed * 100 / context.dataset.data.reduce((a, b) => a + b, 0)) + '%'
                  return `${label}: ${value} (${percentage})`
                }
              }
            }
          }
        }
      })
    }
    
    // Khởi tạo biểu đồ thống kê theo giờ
    const initHourlyStatsChart = () => {
      const ctx = hourlyStatsChart.value.getContext('2d')
      
      const hours = Array.from({ length: 24 }, (_, i) => `${i}:00`)
      const carData = Array.from({ length: 24 }, () => Math.floor(Math.random() * 50))
      const motorbikeData = Array.from({ length: 24 }, () => Math.floor(Math.random() * 80))
      
      charts.value.hourlyStats = new Chart(ctx, {
        type: 'line',
        data: {
          labels: hours,
          datasets: [
            {
              label: 'Ô tô',
              data: carData,
              backgroundColor: 'rgba(59, 130, 246, 0.2)',
              borderColor: 'rgba(59, 130, 246, 1)',
              borderWidth: 2,
              tension: 0.4
            },
            {
              label: 'Xe máy',
              data: motorbikeData,
              backgroundColor: 'rgba(16, 185, 129, 0.2)',
              borderColor: 'rgba(16, 185, 129, 1)',
              borderWidth: 2,
              tension: 0.4
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            y: {
              beginAtZero: true
            }
          }
        }
      })
    }
    
    // Khởi tạo biểu đồ tỷ lệ sử dụng
    const initOccupancyChart = () => {
      const ctx = occupancyChart.value.getContext('2d')
      
      const days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật']
      const carData = days.map(() => Math.floor(Math.random() * 30) + 70)
      const motorbikeData = days.map(() => Math.floor(Math.random() * 20) + 80)
      
      charts.value.occupancy = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: days,
          datasets: [
            {
              label: 'Ô tô (%)',
              data: carData,
              backgroundColor: 'rgba(59, 130, 246, 0.7)',
              borderColor: 'rgba(59, 130, 246, 1)',
              borderWidth: 1
            },
            {
              label: 'Xe máy (%)',
              data: motorbikeData,
              backgroundColor: 'rgba(16, 185, 129, 0.7)',
              borderColor: 'rgba(16, 185, 129, 1)',
              borderWidth: 1
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'bottom'
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              max: 100,
              ticks: {
                callback: function(value) {
                  return value + '%'
                }
              }
            }
          }
        }
      })
    }
    
    // Lọc dữ liệu chi tiết doanh thu
    const filteredRevenueDetails = computed(() => {
      let result = [...revenueDetails.value]
      
      // Lọc theo tìm kiếm
      if (revenueSearch.value) {
        const searchLower = revenueSearch.value.toLowerCase()
        result = result.filter(item => 
          getTypeName(item.type).toLowerCase().includes(searchLower) ||
          formatDate(item.date).includes(searchLower)
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
    
    // Thay đổi trang
    const changePage = (page) => {
      pagination.value.currentPage = page
      updatePaginationInfo()
    }
    
    // Xuất báo cáo
    const exportReport = () => {
      // Trong thực tế, bạn sẽ gọi API để xuất báo cáo
      showToastMessage('success', 'Xuất báo cáo thành công', 'Báo cáo đã được xuất ra file Excel')
    }
    
    // Lấy tên loại
    const getTypeName = (type) => {
      switch (type) {
        case 'resident_car':
          return 'Cư dân - Ô tô'
        case 'resident_motorbike':
          return 'Cư dân - Xe máy'
        case 'guest_car':
          return 'Khách - Ô tô'
        case 'guest_motorbike':
          return 'Khách - Xe máy'
        default:
          return 'Không xác định'
      }
    }
    
    // Lấy icon cho loại
    const getTypeIcon = (type) => {
      if (type.includes('car')) {
        return Car
      } else if (type.includes('motorbike')) {
        return Bike
      } else {
        return DollarSign
      }
    }
    
    // Lấy màu cho loại
    const getTypeColor = (type) => {
      if (type === 'resident_car') {
        return 'text-blue-600'
      } else if (type === 'resident_motorbike') {
        return 'text-green-600'
      } else if (type === 'guest_car') {
        return 'text-amber-600'
      } else if (type === 'guest_motorbike') {
        return 'text-red-600'
      } else {
        return 'text-gray-600'
      }
    }
    
    // Lấy màu nền cho loại
    const getTypeBackgroundColor = (type) => {
      if (type === 'resident_car') {
        return 'bg-blue-600'
      } else if (type === 'resident_motorbike') {
        return 'bg-green-600'
      } else if (type === 'guest_car') {
        return 'bg-amber-600'
      } else if (type === 'guest_motorbike') {
        return 'bg-red-600'
      } else {
        return 'bg-gray-600'
      }
    }
    
    // Định dạng ngày
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      })
    }
    
    // Định dạng tháng năm
    const formatMonthYear = (date) => {
      return new Date(date).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: 'long'
      })
    }
    
    // Định dạng tiền tệ
    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(amount || 0)
    }
    
    // Định dạng tiền tệ ngắn gọn
    const formatCurrencyShort = (amount) => {
      if (amount >= 1000000000) {
        return (amount / 1000000000).toFixed(1) + ' tỷ'
      } else if (amount >= 1000000) {
        return (amount / 1000000).toFixed(1) + ' tr'
      } else if (amount >= 1000) {
        return (amount / 1000).toFixed(1) + ' k'
      } else {
        return amount
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
    
    // Theo dõi thay đổi của revenueSearch
    watch(revenueSearch, () => {
      pagination.value.currentPage = 1
      updatePaginationInfo()
    })
    
    return {
      revenueChart,
      revenueByTypeChart,
      vehicleDistributionChart,
      packageRevenueChart,
      hourlyStatsChart,
      occupancyChart,
      revenueChartType,
      filters,
      statistics,
      revenueDetails,
      revenueSearch,
      pagination,
      showToast,
      toastType,
      toastTitle,
      toastMessage,
      filteredRevenueDetails,
      displayedPages,
      handleTimeRangeChange,
      applyFilters,
      refreshData,
      changePage,
      exportReport,
      updateRevenueChart,
      getTypeName,
      getTypeIcon,
      getTypeColor,
      getTypeBackgroundColor,
      formatDate,
      formatCurrency,
      TrendingUp,
      TrendingDown
    }
  }
}
</script>