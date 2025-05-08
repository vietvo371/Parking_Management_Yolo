<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Báo cáo thống kê</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="exportReport">
            <Download class="mr-2 h-4 w-4" />
            Xuất báo cáo
          </button>
        </div>
      </div>
  
      <!-- Thống kê -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng doanh thu</p>
              <h3 class="text-2xl font-bold">{{ formatCurrency(stats.totalRevenue) }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <CreditCard class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Lượt xe ra vào</p>
              <h3 class="text-2xl font-bold">{{ stats.totalVehicles }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <Car class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tỷ lệ lấp đầy</p>
              <h3 class="text-2xl font-bold">{{ stats.occupancyRate }}%</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
              <BarChart class="h-5 w-5 text-purple-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Xe không đăng ký</p>
              <h3 class="text-2xl font-bold">{{ stats.unregisteredVehicles }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
              <AlertTriangle class="h-5 w-5 text-orange-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Bộ lọc -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <div class="flex flex-1 items-center space-x-2">
            <select 
              v-model="filters.reportType" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="financial">Báo cáo tài chính</option>
              <option value="occupancy">Báo cáo tỷ lệ lấp đầy</option>
              <option value="vehicles">Báo cáo phương tiện</option>
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
  
      <!-- Báo cáo tài chính -->
      <div v-if="filters.reportType === 'financial'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số lượng</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Đơn giá</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thành tiền</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="(item, index) in financialReport" :key="index" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ item.type }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ item.quantity }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ formatCurrency(item.unitPrice) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ formatCurrency(item.total) }}
                </td>
              </tr>
              <tr class="bg-gray-50 dark:bg-gray-800">
                <td colspan="3" class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="text-sm font-bold">Tổng cộng:</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-bold">{{ formatCurrency(calculateFinancialTotal()) }}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- Báo cáo tỷ lệ lấp đầy -->
      <div v-if="filters.reportType === 'occupancy'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <h4 class="text-md font-medium mb-2">Ô tô</h4>
              <div class="flex items-center">
                <div class="w-full bg-gray-200 rounded-full h-4 mr-2 dark:bg-gray-700">
                  <div
                    class="bg-blue-600 h-4 rounded-full"
                    :style="{ width: `${occupancyReport.car.percentage}%` }"
                  ></div>
                </div>
                <span class="text-sm">{{ occupancyReport.car.percentage }}%</span>
              </div>
              <div class="mt-2 text-sm text-gray-500">
                {{ occupancyReport.car.used }} / {{ occupancyReport.car.total }} chỗ đỗ
              </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
              <h4 class="text-md font-medium mb-2">Xe máy</h4>
              <div class="flex items-center">
                <div class="w-full bg-gray-200 rounded-full h-4 mr-2 dark:bg-gray-700">
                  <div
                    class="bg-green-600 h-4 rounded-full"
                    :style="{ width: `${occupancyReport.motorbike.percentage}%` }"
                  ></div>
                </div>
                <span class="text-sm">{{ occupancyReport.motorbike.percentage }}%</span>
              </div>
              <div class="mt-2 text-sm text-gray-500">
                {{ occupancyReport.motorbike.used }} / {{ occupancyReport.motorbike.total }} chỗ đỗ
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Báo cáo phương tiện -->
      <div v-if="filters.reportType === 'vehicles'" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chủ xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lượt ra vào</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lần cuối</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="vehicle in frequentVehicles" :key="vehicle.licensePlate" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ vehicle.licensePlate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ vehicle.type }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ vehicle.owner }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ vehicle.entries }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ vehicle.lastSeen }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted, computed } from 'vue'
  import { 
    Download, 
    CreditCard, 
    Car, 
    BarChart, 
    AlertTriangle 
  } from 'lucide-vue-next'
  
  export default {
    name: 'Reports',
    components: {
      Download,
      CreditCard,
      Car,
      BarChart,
      AlertTriangle
    },
    setup() {
      // Thống kê
      const stats = ref({
        totalRevenue: 25000000,
        totalVehicles: 1250,
        occupancyRate: 78,
        unregisteredVehicles: 45
      })
      
      // Bộ lọc
      const filters = ref({
        reportType: 'financial',
        dateFrom: '',
        dateTo: ''
      })
      
      // Báo cáo tài chính
      const financialReport = ref([
        { type: 'Phí gửi xe ô tô (cư dân)', quantity: 120, unitPrice: 500000, total: 60000000 },
        { type: 'Phí gửi xe máy (cư dân)', quantity: 200, unitPrice: 300000, total: 60000000 },
        { type: 'Phí gửi xe ô tô (khách vãng lai)', quantity: 350, unitPrice: 20000, total: 7000000 },
        { type: 'Phí gửi xe máy (khách vãng lai)', quantity: 580, unitPrice: 10000, total: 5800000 }
      ])
      
      // Báo cáo tỷ lệ lấp đầy
      const occupancyReport = ref({
        car: {
          total: 200,
          used: 156,
          percentage: 78
        },
        motorbike: {
          total: 100,
          used: 85,
          percentage: 85
        }
      })
      
      // Báo cáo phương tiện
      const frequentVehicles = ref([
        { licensePlate: '30A-12345', type: 'Ô tô', owner: 'Nguyễn Văn A', entries: 45, lastSeen: '24/04/2023 - 18:30' },
        { licensePlate: '30F-54321', type: 'Ô tô', owner: 'Trần Thị B', entries: 38, lastSeen: '24/04/2023 - 19:15' },
        { licensePlate: '29P2-12345', type: 'Xe máy', owner: 'Lê Văn C', entries: 62, lastSeen: '24/04/2023 - 17:45' },
        { licensePlate: '30K1-65432', type: 'Xe máy', owner: 'Phạm Thị D', entries: 55, lastSeen: '24/04/2023 - 18:00' },
        { licensePlate: '30A-56789', type: 'Ô tô', owner: 'Hoàng Văn E', entries: 32, lastSeen: '24/04/2023 - 16:30' }
      ])
      
      // Format currency
      const formatCurrency = (amount) => {
        return new Intl.NumberFormat('vi-VN', {
          style: 'currency',
          currency: 'VND'
        }).format(amount)
      }
      
      // Calculate financial total
      const calculateFinancialTotal = () => {
        return financialReport.value.reduce((total, item) => total + item.total, 0)
      }
      
      // Reset filters
      const resetFilters = () => {
        filters.value = {
          reportType: 'financial',
          dateFrom: '',
          dateTo: ''
        }
      }
      
      // Export report
      const exportReport = () => {
        // Trong thực tế, bạn sẽ xuất báo cáo ra file
        console.log('Xuất báo cáo')
      }
      
      return {
        stats,
        filters,
        financialReport,
        occupancyReport,
        frequentVehicles,
        formatCurrency,
        calculateFinancialTotal,
        resetFilters,
        exportReport
      }
    }
  }
  </script>
  
  <style>
  /* Custom styles if needed */
  </style>