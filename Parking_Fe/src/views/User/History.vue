<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Lịch sử ra vào bãi</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
            <Download class="mr-2 h-4 w-4" />
            Xuất dữ liệu
          </button>
        </div>
      </div>

      <!-- History Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng lượt ra vào</p>
              <h3 class="text-2xl font-bold">{{ historyStats.total }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <Activity class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Lượt vào hôm nay</p>
              <h3 class="text-2xl font-bold">{{ historyStats.todayIn }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <LogIn class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Lượt ra hôm nay</p>
              <h3 class="text-2xl font-bold">{{ historyStats.todayOut }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
              <LogOut class="h-5 w-5 text-purple-600" />
            </div>
          </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Xe đang trong bãi</p>
              <h3 class="text-2xl font-bold">{{ historyStats.currentParked }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
              <Car class="h-5 w-5 text-orange-600" />
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
              <option value="in">Xe vào</option>
              <option value="out">Xe ra</option>
            </select>
            <select 
              v-model="filters.vehicleType" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả xe</option>
              <option value="car">Ô tô</option>
              <option value="motorbike">Xe máy</option>
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

      <!-- History Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vị trí đỗ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian đỗ</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="entry in paginatedHistory" :key="entry.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ entry.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(entry.time) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': entry.type === 'in',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': entry.type === 'out'
                    }">
                    {{ entry.type === 'in' ? 'Vào' : 'Ra' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ entry.licensePlate }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ entry.vehicleType === 'car' ? 'Ô tô' : 'Xe máy' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ entry.parkingSpot }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ entry.duration || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="viewEntryDetails(entry)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                      <MapPin class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredHistory.length === 0">
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                  Không tìm thấy dữ liệu lịch sử
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
                Hiển thị <span class="font-medium">{{ paginationStart }}</span> đến <span class="font-medium">{{ paginationEnd }}</span> của <span class="font-medium">{{ filteredHistory.length }}</span> kết quả
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

      <!-- Statistics -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <h3 class="text-lg font-medium mb-4">Thống kê theo thời gian</h3>
          <div class="h-64">
            <canvas ref="timeChart"></canvas>
          </div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <h3 class="text-lg font-medium mb-4">Thống kê theo xe</h3>
          <div class="h-64">
            <canvas ref="vehicleChart"></canvas>
          </div>
        </div>
      </div>

      <!-- Entry Detail Modal -->
      <a-modal v-model:open="showDetailModal" title="Chi tiết lượt ra vào" width="800px">
        <template #footer>
          <div class="flex justify-end space-x-2">
            <a-button @click="closeDetailModal">Đóng</a-button>
          </div>
        </template>
        <div v-if="selectedEntry" class="space-y-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <component :is="selectedEntry.type === 'in' ? LogIn : LogOut" class="h-5 w-5 mr-2" :class="selectedEntry.type === 'in' ? 'text-green-600' : 'text-blue-600'" />
              <h2 class="text-xl font-bold">{{ selectedEntry.type === 'in' ? 'Lượt vào' : 'Lượt ra' }}</h2>
            </div>
            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
              :class="{
                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedEntry.type === 'in',
                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': selectedEntry.type === 'out'
              }">
              {{ selectedEntry.type === 'in' ? 'Vào' : 'Ra' }}
            </span>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-500">ID</p>
              <p class="font-medium">{{ selectedEntry.id }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Thời gian</p>
              <p class="font-medium">{{ formatDateTime(selectedEntry.time) }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Biển số xe</p>
              <p class="font-medium">{{ selectedEntry.licensePlate }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Loại xe</p>
              <p class="font-medium">{{ selectedEntry.vehicleType === 'car' ? 'Ô tô' : 'Xe máy' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Vị trí đỗ</p>
              <p class="font-medium">{{ selectedEntry.parkingSpot }}</p>
            </div>
            <div v-if="selectedEntry.type === 'out'">
              <p class="text-sm text-gray-500">Thời gian đỗ</p>
              <p class="font-medium">{{ selectedEntry.duration }}</p>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Hình ảnh</h4>
            <div class="grid grid-cols-2 gap-2">
              <div class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                <img :src="selectedEntry.images?.vehicle || '/placeholder.svg?height=200&width=300&text=Hình xe'" alt="Vehicle image" class="w-full h-full object-cover" />
              </div>
              <div class="aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                <img :src="selectedEntry.images?.licensePlate || '/placeholder.svg?height=200&width=300&text=Biển số'" alt="License plate image" class="w-full h-full object-cover" />
              </div>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h4 class="font-medium mb-2">Vị trí trên bản đồ</h4>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-md">
              <!-- Map would be rendered here in a real app -->
              <div class="h-full flex items-center justify-center">
                <MapPin class="h-8 w-8 text-blue-600" />
              </div>
            </div>
          </div>
          
          <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 flex justify-end">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
              Xem chi tiết vị trí
            </button>
          </div>
        </div>
      </a-modal>
    </div>
  </template>

  <script>
  import { ref, computed, onMounted } from 'vue'
  import { 
    Download, 
    Search,
    Filter, 
    Calendar, 
    Eye, 
    MapPin, 
    ChevronLeft, 
    ChevronRight, 
    X, 
    LogIn, 
    LogOut,
    Activity,
    Car
  } from 'lucide-vue-next'
  import { Modal, Button } from 'ant-design-vue'
  import Chart from 'chart.js/auto'

  export default {
    name: 'EntryHistory',
    components: {
      Download,
      Search,
      Filter,
      Calendar,
      Eye,
      MapPin,
      ChevronLeft,
      ChevronRight,
      X,
      LogIn,
      LogOut,
      Activity,
      Car,
      [Modal.name]: Modal,
      [Button.name]: Button
    },
    data() {
      return {
        filters: {
          search: '',
          type: '',
          vehicleType: '',
          dateFrom: '',
          dateTo: ''
        },
        pagination: {
          currentPage: 1,
          itemsPerPage: 10,
          totalPages: 1
        },
        showDetailModal: false,
        selectedEntry: null,
        
        // Chart refs
        timeChart: null,
        vehicleChart: null,
        
        // History stats
        historyStats: {
          total: 0,
          todayIn: 0,
          todayOut: 0,
          currentParked: 0
        },
        
        // Sample history data
        historyEntries: [
          {
            id: 'ENT-001',
            time: '2024-02-20T10:25:00',
            type: 'in',
            licensePlate: '30A-12345',
            vehicleType: 'car',
            parkingSpot: 'A-C05',
            duration: null,
            images: {
              vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
              licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
            }
          },
          {
            id: 'ENT-002',
            time: '2024-02-20T10:15:00',
            type: 'out',
            licensePlate: '30F-54321',
            vehicleType: 'car',
            parkingSpot: 'B-C12',
            duration: '2h 30m',
            images: {
              vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
              licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
            }
          }
        ]
      }
    },
    computed: {
      filteredHistory() {
        let result = [...this.historyEntries]
        
        if (this.filters.search) {
          const searchLower = this.filters.search.toLowerCase()
          result = result.filter(entry => 
            entry.id.toLowerCase().includes(searchLower) || 
            entry.licensePlate.toLowerCase().includes(searchLower)
          )
        }
        
        if (this.filters.type) {
          result = result.filter(entry => entry.type === this.filters.type)
        }
        
        if (this.filters.vehicleType) {
          result = result.filter(entry => entry.vehicleType === this.filters.vehicleType)
        }
        
        if (this.filters.dateFrom) {
          const fromDate = new Date(this.filters.dateFrom)
          result = result.filter(entry => new Date(entry.time) >= fromDate)
        }
        
        if (this.filters.dateTo) {
          const toDate = new Date(this.filters.dateTo)
          toDate.setHours(23, 59, 59)
          result = result.filter(entry => new Date(entry.time) <= toDate)
        }
        
        result.sort((a, b) => new Date(b.time) - new Date(a.time))
        
        this.pagination.totalPages = Math.ceil(result.length / this.pagination.itemsPerPage)
        
        return result
      },
      paginatedHistory() {
        const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage
        const end = start + this.pagination.itemsPerPage
        return this.filteredHistory.slice(start, end)
      },
      paginationStart() {
        return (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1
      },
      paginationEnd() {
        return Math.min(this.pagination.currentPage * this.pagination.itemsPerPage, this.filteredHistory.length)
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
      this.updateStats()
      this.initCharts()
    },
    methods: {
      // View entry details
      viewEntryDetails(entry) {
        this.selectedEntry = { ...entry }
        this.showDetailModal = true
      },
      
      closeDetailModal() {
        this.selectedEntry = null
        this.showDetailModal = false
      },
      
      resetFilters() {
        this.filters = {
          search: '',
          type: '',
          vehicleType: '',
          dateFrom: '',
          dateTo: ''
        }
        this.pagination.currentPage = 1
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
      
      updateStats() {
        const today = new Date()
        today.setHours(0, 0, 0, 0)
        
        this.historyStats.total = this.historyEntries.length
        this.historyStats.todayIn = this.historyEntries.filter(entry => 
          entry.type === 'in' && new Date(entry.time) >= today
        ).length
        this.historyStats.todayOut = this.historyEntries.filter(entry => 
          entry.type === 'out' && new Date(entry.time) >= today
        ).length
        this.historyStats.currentParked = this.historyEntries.filter(entry => 
          entry.type === 'in' && !this.historyEntries.some(e => 
            e.type === 'out' && e.licensePlate === entry.licensePlate && new Date(e.time) > new Date(entry.time)
          )
        ).length
      },
      
      // Initialize charts
      initCharts() {
        // Time Chart
        if (this.$refs.timeChart) {
          const ctx = this.$refs.timeChart.getContext('2d')
          new Chart(ctx, {
            type: 'line',
            data: {
              labels: ['18/04', '19/04', '20/04', '21/04', '22/04', '23/04', '24/04'],
              datasets: [
                {
                  label: 'Lượt vào',
                  data: [3, 2, 4, 1, 1, 1, 1],
                  borderColor: '#10b981',
                  backgroundColor: 'rgba(16, 185, 129, 0.1)',
                  tension: 0.3,
                  fill: true
                },
                {
                  label: 'Lượt ra',
                  data: [2, 3, 2, 1, 1, 1, 1],
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
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1
                  }
                }
              }
            }
          })
        }
        
        // Vehicle Chart
        if (this.$refs.vehicleChart) {
          const ctx = this.$refs.vehicleChart.getContext('2d')
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['30A-12345', '30F-54321', '29P2-12345'],
              datasets: [
                {
                  label: 'Lượt vào/ra',
                  data: [4, 3, 2],
                  backgroundColor: [
                    '#3b82f6',
                    '#10b981',
                    '#f59e0b'
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
                  position: 'top'
                }
              },
              scales: {
                y: {
                  beginAtZero: true,
                  ticks: {
                    stepSize: 1
                  }
                }
              }
            }
          })
        }
      }
    }
  }
  </script>