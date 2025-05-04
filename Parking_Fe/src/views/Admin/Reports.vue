<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Báo cáo thống kê</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Filter class="mr-2 h-4 w-4" />
            Bộ lọc
          </button>
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Download class="mr-2 h-4 w-4" />
            Xuất báo cáo
          </button>
        </div>
      </div>
  
      <!-- Date Range Picker -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">Khoảng thời gian:</span>
            <select 
              v-model="selectedDateRange" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              @change="updateDateRange"
            >
              <option value="today">Hôm nay</option>
              <option value="yesterday">Hôm qua</option>
              <option value="thisWeek">Tuần này</option>
              <option value="lastWeek">Tuần trước</option>
              <option value="thisMonth">Tháng này</option>
              <option value="lastMonth">Tháng trước</option>
              <option value="custom">Tùy chỉnh</option>
            </select>
          </div>
          <div class="flex items-center space-x-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-auto">
              <Calendar class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                v-model="startDate" 
                type="date" 
                class="pl-9 w-full sm:w-[150px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                :disabled="selectedDateRange !== 'custom'"
              />
            </div>
            <span class="text-gray-500">đến</span>
            <div class="relative w-full sm:w-auto">
              <Calendar class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input 
                v-model="endDate" 
                type="date" 
                class="pl-9 w-full sm:w-[150px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                :disabled="selectedDateRange !== 'custom'"
              />
            </div>
            <button 
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="selectedDateRange !== 'custom'"
              @click="applyCustomDateRange"
            >
              Áp dụng
            </button>
          </div>
        </div>
      </div>
  
      <!-- Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng doanh thu</p>
              <h3 class="text-2xl font-bold">{{ formatCurrency(summaryData.totalRevenue) }}</h3>
              <p class="text-sm" :class="summaryData.revenueChange >= 0 ? 'text-green-600' : 'text-red-600'">
                <span v-if="summaryData.revenueChange >= 0">+</span>{{ summaryData.revenueChange }}% so với kỳ trước
              </p>
            </div>
            <div class="h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
              <CreditCard class="h-6 w-6 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Lượt xe ra vào</p>
              <h3 class="text-2xl font-bold">{{ summaryData.totalVehicles }}</h3>
              <p class="text-sm" :class="summaryData.vehiclesChange >= 0 ? 'text-green-600' : 'text-red-600'">
                <span v-if="summaryData.vehiclesChange >= 0">+</span>{{ summaryData.vehiclesChange }}% so với kỳ trước
              </p>
            </div>
            <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
              <Car class="h-6 w-6 text-green-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tỷ lệ lấp đầy</p>
              <h3 class="text-2xl font-bold">{{ summaryData.occupancyRate }}%</h3>
              <p class="text-sm" :class="summaryData.occupancyChange >= 0 ? 'text-green-600' : 'text-red-600'">
                <span v-if="summaryData.occupancyChange >= 0">+</span>{{ summaryData.occupancyChange }}% so với kỳ trước
              </p>
            </div>
            <div class="h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
              <BarChart class="h-6 w-6 text-purple-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Xe không đăng ký</p>
              <h3 class="text-2xl font-bold">{{ summaryData.unregisteredVehicles }}</h3>
              <p class="text-sm" :class="summaryData.unregisteredChange >= 0 ? 'text-green-600' : 'text-red-600'">
                <span v-if="summaryData.unregisteredChange < 0">-</span><span v-else>+</span>{{ Math.abs(summaryData.unregisteredChange) }}% so với kỳ trước
              </p>
            </div>
            <div class="h-12 w-12 rounded-full bg-orange-100 flex items-center justify-center">
              <AlertTriangle class="h-6 w-6 text-orange-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Revenue Chart -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Biểu đồ doanh thu</h2>
          <p class="text-sm text-gray-500">Doanh thu theo thời gian</p>
        </div>
        <div class="p-4">
          <div class="h-80">
            <canvas ref="revenueChart"></canvas>
          </div>
        </div>
      </div>
  
      <!-- Vehicle Types and Occupancy -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium">Phân loại phương tiện</h2>
            <p class="text-sm text-gray-500">Tỷ lệ các loại phương tiện</p>
          </div>
          <div class="p-4">
            <div class="h-64">
              <canvas ref="vehicleTypesChart"></canvas>
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-medium">Tỷ lệ lấp đầy theo thời gian</h2>
            <p class="text-sm text-gray-500">Phần trăm chỗ đỗ xe được sử dụng</p>
          </div>
          <div class="p-4">
            <div class="h-64">
              <canvas ref="occupancyChart"></canvas>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Top Residents Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Cư dân thường xuyên ra vào</h2>
          <p class="text-sm text-gray-500">Top 10 cư dân có lượt ra vào nhiều nhất</p>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cư dân</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Căn hộ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Lượt ra vào</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phí đã thanh toán</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="resident in topResidents" :key="resident.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <User class="h-5 w-5 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium">{{ resident.name }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">{{ resident.apartment }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">{{ resident.licensePlate }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">{{ resident.entries }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm">{{ formatCurrency(resident.paidFees) }}</div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  
      <!-- Report Types Tabs -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="border-b border-gray-200 dark:border-gray-700">
          <nav class="flex -mb-px">
            <button 
              v-for="tab in reportTabs" 
              :key="tab.id"
              @click="activeReportTab = tab.id"
              class="py-4 px-6 text-sm font-medium border-b-2 whitespace-nowrap"
              :class="activeReportTab === tab.id ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300'"
            >
              {{ tab.name }}
            </button>
          </nav>
        </div>
        <div class="p-4">
          <!-- Financial Report -->
          <div v-if="activeReportTab === 'financial'" class="space-y-4">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium">Báo cáo tài chính</h3>
              <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
                <Download class="mr-2 h-4 w-4" />
                Xuất Excel
              </button>
            </div>
            <div class="overflow-x-auto">
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
                  <tr v-for="(item, index) in financialReport" :key="index">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium">{{ item.type }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm">{{ item.quantity }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm">{{ formatCurrency(item.unitPrice) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium">{{ formatCurrency(item.total) }}</div>
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
  
          <!-- Occupancy Report -->
          <div v-if="activeReportTab === 'occupancy'" class="space-y-4">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium">Báo cáo tỷ lệ lấp đầy</h3>
              <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
                <Download class="mr-2 h-4 w-4" />
                Xuất Excel
              </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
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
              <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
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
            <div class="mt-4">
              <h4 class="text-md font-medium mb-2">Thời gian cao điểm</h4>
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                  <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tỷ lệ lấp đầy</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số lượng xe vào</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số lượng xe ra</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                    <tr v-for="(item, index) in peakHours" :key="index">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ item.time }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div class="w-24 bg-gray-200 rounded-full h-2.5 mr-2 dark:bg-gray-700">
                            <div
                              class="bg-blue-600 h-2.5 rounded-full"
                              :style="{ width: `${item.occupancyRate}%` }"
                            ></div>
                          </div>
                          <span class="text-sm">{{ item.occupancyRate }}%</span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ item.entriesCount }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ item.exitsCount }}</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
  
          <!-- Vehicle Report -->
          <div v-if="activeReportTab === 'vehicles'" class="space-y-4">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-medium">Báo cáo phương tiện</h3>
              <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
                <Download class="mr-2 h-4 w-4" />
                Xuất Excel
              </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                <h4 class="text-md font-medium mb-2">Tổng số phương tiện</h4>
                <div class="text-3xl font-bold">{{ vehicleReport.totalVehicles }}</div>
                <div class="mt-2 text-sm text-gray-500">Đã đăng ký</div>
              </div>
              <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                <h4 class="text-md font-medium mb-2">Ô tô</h4>
                <div class="text-3xl font-bold">{{ vehicleReport.cars }}</div>
                <div class="mt-2 text-sm text-gray-500">{{ Math.round(vehicleReport.cars / vehicleReport.totalVehicles * 100) }}% tổng số xe</div>
              </div>
              <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 text-center">
                <h4 class="text-md font-medium mb-2">Xe máy</h4>
                <div class="text-3xl font-bold">{{ vehicleReport.motorbikes }}</div>
                <div class="mt-2 text-sm text-gray-500">{{ Math.round(vehicleReport.motorbikes / vehicleReport.totalVehicles * 100) }}% tổng số xe</div>
              </div>
            </div>
            <div class="mt-4">
              <h4 class="text-md font-medium mb-2">Phương tiện thường xuyên ra vào</h4>
              <div class="overflow-x-auto">
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
                    <tr v-for="vehicle in frequentVehicles" :key="vehicle.licensePlate">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium">{{ vehicle.licensePlate }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ vehicle.type }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ vehicle.owner }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ vehicle.entries }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm">{{ vehicle.lastSeen }}</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, onMounted, computed } from 'vue'
  import { 
    Filter, 
    Download, 
    Calendar, 
    CreditCard, 
    Car, 
    BarChart, 
    AlertTriangle, 
    User 
  } from 'lucide-vue-next'
  import Chart from 'chart.js/auto'
  
  export default {
    name: 'Reports',
    components: {
      Filter,
      Download,
      Calendar,
      CreditCard,
      Car,
      BarChart,
      AlertTriangle,
      User
    },
    setup() {
      // Refs for charts
      const revenueChart = ref(null)
      const vehicleTypesChart = ref(null)
      const occupancyChart = ref(null)
      
      // Date range state
      const selectedDateRange = ref('thisMonth')
      const startDate = ref('')
      const endDate = ref('')
      
      // Active report tab
      const activeReportTab = ref('financial')
      
      // Report tabs
      const reportTabs = [
        { id: 'financial', name: 'Báo cáo tài chính' },
        { id: 'occupancy', name: 'Báo cáo tỷ lệ lấp đầy' },
        { id: 'vehicles', name: 'Báo cáo phương tiện' }
      ]
      
      // Summary data
      const summaryData = ref({
        totalRevenue: 25000000,
        revenueChange: 12.5,
        totalVehicles: 1250,
        vehiclesChange: 8.3,
        occupancyRate: 78,
        occupancyChange: 5.2,
        unregisteredVehicles: 45,
        unregisteredChange: -15.8
      })
      
      // Financial report data
      const financialReport = ref([
        { type: 'Phí gửi xe ô tô (cư dân)', quantity: 120, unitPrice: 500000, total: 60000000 },
        { type: 'Phí gửi xe máy (cư dân)', quantity: 200, unitPrice: 300000, total: 60000000 },
        { type: 'Phí gửi xe ô tô (khách vãng lai)', quantity: 350, unitPrice: 20000, total: 7000000 },
        { type: 'Phí gửi xe máy (khách vãng lai)', quantity: 580, unitPrice: 10000, total: 5800000 }
      ])
      
      // Calculate financial total
      const calculateFinancialTotal = () => {
        return financialReport.value.reduce((total, item) => total + item.total, 0)
      }
      
      // Occupancy report data
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
      
      // Peak hours data
      const peakHours = ref([
        { time: '07:00 - 09:00', occupancyRate: 92, entriesCount: 85, exitsCount: 25 },
        { time: '11:30 - 13:30', occupancyRate: 75, entriesCount: 45, exitsCount: 60 },
        { time: '17:00 - 19:00', occupancyRate: 95, entriesCount: 30, exitsCount: 110 },
        { time: '21:00 - 23:00', occupancyRate: 65, entriesCount: 15, exitsCount: 40 }
      ])
      
      // Vehicle report data
      const vehicleReport = ref({
        totalVehicles: 320,
        cars: 120,
        motorbikes: 200
      })
      
      // Frequent vehicles data
      const frequentVehicles = ref([
        { licensePlate: '30A-12345', type: 'Ô tô', owner: 'Nguyễn Văn A', entries: 45, lastSeen: '24/04/2023 - 18:30' },
        { licensePlate: '30F-54321', type: 'Ô tô', owner: 'Trần Thị B', entries: 38, lastSeen: '24/04/2023 - 19:15' },
        { licensePlate: '29P2-12345', type: 'Xe máy', owner: 'Lê Văn C', entries: 62, lastSeen: '24/04/2023 - 17:45' },
        { licensePlate: '30K1-65432', type: 'Xe máy', owner: 'Phạm Thị D', entries: 55, lastSeen: '24/04/2023 - 18:00' },
        { licensePlate: '30A-56789', type: 'Ô tô', owner: 'Hoàng Văn E', entries: 32, lastSeen: '24/04/2023 - 16:30' }
      ])
      
      // Top residents data
      const topResidents = ref([
        { id: 1, name: 'Nguyễn Văn A', apartment: 'A1201', licensePlate: '30A-12345', entries: 45, paidFees: 1500000 },
        { id: 2, name: 'Trần Thị B', apartment: 'B1502', licensePlate: '30F-54321', entries: 38, paidFees: 1500000 },
        { id: 3, name: 'Lê Văn C', apartment: 'C0901', licensePlate: '29P2-12345', entries: 62, paidFees: 900000 },
        { id: 4, name: 'Phạm Thị D', apartment: 'A0505', licensePlate: '30K1-65432', entries: 55, paidFees: 900000 },
        { id: 5, name: 'Hoàng Văn E', apartment: 'B1201', licensePlate: '30A-56789', entries: 32, paidFees: 1500000 },
        { id: 6, name: 'Vũ Thị F', apartment: 'C1105', licensePlate: '30H-98765', entries: 28, paidFees: 1500000 },
        { id: 7, name: 'Đặng Văn G', apartment: 'A0802', licensePlate: '29Y2-54321', entries: 25, paidFees: 900000 },
        { id: 8, name: 'Bùi Thị H', apartment: 'B0703', licensePlate: '30K5-13579', entries: 22, paidFees: 900000 },
        { id: 9, name: 'Ngô Văn I', apartment: 'C1502', licensePlate: '30A-24680', entries: 20, paidFees: 1500000 },
        { id: 10, name: 'Mai Thị K', apartment: 'A1105', licensePlate: '30F-97531', entries: 18, paidFees: 1500000 }
      ])
      
      // Format currency
      const formatCurrency = (value) => {
        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value)
      }
      
      // Update date range based on selection
      const updateDateRange = () => {
        const today = new Date()
        let start = new Date()
        let end = new Date()
        
        switch (selectedDateRange.value) {
          case 'today':
            // Start and end are both today
            break
          case 'yesterday':
            start.setDate(today.getDate() - 1)
            end.setDate(today.getDate() - 1)
            break
          case 'thisWeek':
            start.setDate(today.getDate() - today.getDay())
            break
          case 'lastWeek':
            start.setDate(today.getDate() - today.getDay() - 7)
            end.setDate(today.getDate() - today.getDay() - 1)
            break
          case 'thisMonth':
            start.setDate(1)
            break
          case 'lastMonth':
            start = new Date(today.getFullYear(), today.getMonth() - 1, 1)
            end = new Date(today.getFullYear(), today.getMonth(), 0)
            break
          case 'custom':
            // Don't change the dates, let user select
            return
        }
        
        startDate.value = start.toISOString().split('T')[0]
        endDate.value = end.toISOString().split('T')[0]
      }
      
      // Apply custom date range
      const applyCustomDateRange = () => {
        // In a real app, this would fetch data for the selected date range
        console.log('Applying custom date range:', startDate.value, 'to', endDate.value)
      }
      
      // Initialize charts
      const initCharts = () => {
        // Revenue Chart
        if (revenueChart.value) {
          const ctx = revenueChart.value.getContext('2d')
          new Chart(ctx, {
            type: 'line',
            data: {
              labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
              datasets: [
                {
                  label: 'Doanh thu (triệu VND)',
                  data: [120, 135, 125, 130, 142, 138, 145, 150, 160, 155, 165, 175],
                  borderColor: '#3b82f6',
                  backgroundColor: 'rgba(59, 130, 246, 0.1)',
                  tension: 0.3,
                  fill: true
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  position: 'top',
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return context.dataset.label + ': ' + context.raw + ' triệu VND'
                    }
                  }
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    callback: function(value) {
                      return value + ' tr'
                    }
                  }
                }
              }
            }
          })
        }
        
        // Vehicle Types Chart
        if (vehicleTypesChart.value) {
          const ctx = vehicleTypesChart.value.getContext('2d')
          new Chart(ctx, {
            type: 'doughnut',
            data: {
              labels: ['Ô tô cư dân', 'Xe máy cư dân', 'Ô tô khách', 'Xe máy khách'],
              datasets: [
                {
                  data: [120, 200, 35, 58],
                  backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b',
                    '#ef4444'
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
                }
              }
            }
          })
        }
        
        // Occupancy Chart
        if (occupancyChart.value) {
          const ctx = occupancyChart.value.getContext('2d')
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['00:00', '03:00', '06:00', '09:00', '12:00', '15:00', '18:00', '21:00'],
              datasets: [
                {
                  label: 'Tỷ lệ lấp đầy (%)',
                  data: [45, 30, 65, 92, 75, 70, 95, 65],
                  backgroundColor: '#3b82f6'
                }
              ]
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: {
                  position: 'top'
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
      }
      
      onMounted(() => {
        updateDateRange()
        initCharts()
      })
      
      return {
        revenueChart,
        vehicleTypesChart,
        occupancyChart,
        selectedDateRange,
        startDate,
        endDate,
        activeReportTab,
        reportTabs,
        summaryData,
        financialReport,
        occupancyReport,
        peakHours,
        vehicleReport,
        frequentVehicles,
        topResidents,
        formatCurrency,
        updateDateRange,
        applyCustomDateRange,
        calculateFinancialTotal
      }
    }
  }
  </script>
  
  <style>
  /* Custom styles if needed */
  </style>