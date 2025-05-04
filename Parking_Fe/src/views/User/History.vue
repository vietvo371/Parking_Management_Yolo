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
  
      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">Lọc theo:</span>
            <select 
              v-model="filterVehicle" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả xe</option>
              <option v-for="vehicle in vehicles" :key="vehicle.id" :value="vehicle.licensePlate">
                {{ vehicle.licensePlate }}
              </option>
            </select>
            <select 
              v-model="filterType" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả loại</option>
              <option value="in">Xe vào</option>
              <option value="out">Xe ra</option>
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
              <tr v-for="entry in filteredHistory" :key="entry.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ entry.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ entry.time }}
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
                Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">10</span> của <span class="font-medium">{{ historyEntries.length }}</span> kết quả
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
                <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
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
      <div v-if="selectedEntry" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-2xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết lượt ra vào</h3>
            <button @click="selectedEntry = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-6">
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
                  <p class="font-medium">{{ selectedEntry.time }}</p>
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
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed, onMounted } from 'vue'
  import { 
    Download, 
    Filter, 
    Calendar, 
    Eye, 
    MapPin, 
    ChevronLeft, 
    ChevronRight, 
    X, 
    LogIn, 
    LogOut 
  } from 'lucide-vue-next'
  import Chart from 'chart.js/auto'
  
  export default {
    name: 'EntryHistory',
    components: {
      Download,
      Filter,
      Calendar,
      Eye,
      MapPin,
      ChevronLeft,
      ChevronRight,
      X,
      LogIn,
      LogOut
    },
    setup() {
      const filterVehicle = ref('')
      const filterType = ref('')
      const startDate = ref('')
      const endDate = ref('')
      const selectedEntry = ref(null)
      
      // Chart refs
      const timeChart = ref(null)
      const vehicleChart = ref(null)
      
      // Sample vehicles data
      const vehicles = ref([
        {
          id: 'VEH-001',
          licensePlate: '30A-12345',
          type: 'car'
        },
        {
          id: 'VEH-002',
          licensePlate: '30F-54321',
          type: 'car'
        },
        {
          id: 'VEH-003',
          licensePlate: '29P2-12345',
          type: 'motorbike'
        }
      ])
      
      // Sample history data
      const historyEntries = ref([
        {
          id: 'ENT-001',
          time: '24/04/2023 - 10:25',
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
          time: '24/04/2023 - 10:15',
          type: 'out',
          licensePlate: '30F-54321',
          vehicleType: 'car',
          parkingSpot: 'B-C12',
          duration: '2h 30m',
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        },
        {
          id: 'ENT-003',
          time: '24/04/2023 - 09:45',
          type: 'in',
          licensePlate: '29P2-12345',
          vehicleType: 'motorbike',
          parkingSpot: 'C-M08',
          duration: null,
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        },
        {
          id: 'ENT-004',
          time: '23/04/2023 - 18:30',
          type: 'out',
          licensePlate: '29P2-12345',
          vehicleType: 'motorbike',
          parkingSpot: 'C-M08',
          duration: '8h 45m',
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        },
        {
          id: 'ENT-005',
          time: '23/04/2023 - 09:45',
          type: 'in',
          licensePlate: '29P2-12345',
          vehicleType: 'motorbike',
          parkingSpot: 'C-M08',
          duration: null,
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        },
        {
          id: 'ENT-006',
          time: '22/04/2023 - 19:15',
          type: 'out',
          licensePlate: '30A-12345',
          vehicleType: 'car',
          parkingSpot: 'A-C05',
          duration: '10h 30m',
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        },
        {
          id: 'ENT-007',
          time: '22/04/2023 - 08:45',
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
          id: 'ENT-008',
          time: '21/04/2023 - 18:30',
          type: 'out',
          licensePlate: '30F-54321',
          vehicleType: 'car',
          parkingSpot: 'B-C12',
          duration: '9h 15m',
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        },
        {
          id: 'ENT-009',
          time: '21/04/2023 - 09:15',
          type: 'in',
          licensePlate: '30F-54321',
          vehicleType: 'car',
          parkingSpot: 'B-C12',
          duration: null,
          images: {
            vehicle: '/placeholder.svg?height=200&width=300&text=Hình xe',
            licensePlate: '/placeholder.svg?height=200&width=300&text=Biển số'
          }
        }
      ])
      
      // Filter history entries based on filters
      const filteredHistory = computed(() => {
        return historyEntries.value.filter(entry => {
          const matchesVehicle = filterVehicle.value ? entry.licensePlate === filterVehicle.value : true
          const matchesType = filterType.value ? entry.type === filterType.value : true
          
          // Date filtering would be implemented here in a real app
          
          return matchesVehicle && matchesType
        })
      })
      
      // View entry details
      function viewEntryDetails(entry) {
        selectedEntry.value = { ...entry }
      }
      
      // Initialize charts
      function initCharts() {
        // Time Chart
        if (timeChart.value) {
          const ctx = timeChart.value.getContext('2d')
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
        if (vehicleChart.value) {
          const ctx = vehicleChart.value.getContext('2d')
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
      
      onMounted(() => {
        initCharts()
      })
      
      return {
        filterVehicle,
        filterType,
        startDate,
        endDate,
        selectedEntry,
        vehicles,
        historyEntries,
        filteredHistory,
        timeChart,
        vehicleChart,
        viewEntryDetails
      }
    }
  }
  </script>