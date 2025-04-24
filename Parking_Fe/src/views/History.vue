<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Lịch sử ra vào</h1>
        <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
          <Download class="mr-2 h-4 w-4" />
          Xuất báo cáo
        </button>
      </div>
  
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-600 text-sm">
            Tất cả: {{ historyEntries.length }}
          </span>
          <span class="px-3 py-1 rounded-lg bg-green-100 text-green-800 text-sm">
            Vào bãi: {{ historyEntries.filter(h => h.type === 'Vào bãi').length }}
          </span>
          <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-800 text-sm">
            Ra khỏi bãi: {{ historyEntries.filter(h => h.type === 'Ra khỏi bãi').length }}
          </span>
          <span class="px-3 py-1 rounded-lg bg-red-100 text-red-800 text-sm">
            Xe không đăng ký: {{ historyEntries.filter(h => h.type === 'Xe không đăng ký').length }}
          </span>
        </div>
        <div class="flex w-full sm:w-auto items-center gap-2">
          <div class="relative w-full sm:w-auto">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              v-model="searchQuery" 
              type="search" 
              placeholder="Tìm kiếm biển số..." 
              class="pl-9 w-full sm:w-[250px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            />
          </div>
          <button class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
            <Filter class="h-4 w-4" />
          </button>
        </div>
      </div>
  
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Lịch sử ra vào bãi đỗ xe</h2>
          <p class="text-sm text-gray-500">Ghi nhận tất cả các lượt xe ra vào bãi đỗ xe</p>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cư dân</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Camera</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Độ chính xác</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="entry in filteredHistory" :key="entry.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-3">
                    <div class="relative h-10 w-16 overflow-hidden rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <span class="text-xs text-gray-500">{{ entry.licensePlate }}</span>
                    </div>
                    <span class="font-medium">{{ entry.licensePlate }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <span>{{ entry.resident }}</span>
                    <span class="text-xs text-gray-500">{{ entry.apartment }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="{
                      'bg-green-100 text-green-800': entry.type === 'Vào bãi',
                      'bg-blue-100 text-blue-800': entry.type === 'Ra khỏi bãi',
                      'bg-red-100 text-red-800': entry.type === 'Xe không đăng ký'
                    }"
                  >
                    {{ entry.type }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ entry.time }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ entry.camera }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2 dark:bg-gray-700">
                      <div
                        class="bg-green-600 h-2.5 rounded-full"
                        :style="{ width: `${entry.confidence}%` }"
                      ></div>
                    </div>
                    <span class="text-xs">{{ entry.confidence }}%</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">
                    Chi tiết
                  </button>
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
  import { Search, Filter, Download } from 'lucide-vue-next'
  import { useHistoryStore } from '@/stores/history'
  
  export default {
    name: 'History',
    components: {
      Search,
      Filter,
      Download
    },
    setup() {
      const historyStore = useHistoryStore()
      const searchQuery = ref('')
      const startDate = ref('')
      const endDate = ref('')
  
      const historyEntries = computed(() => historyStore.historyEntries)
  
      const filteredHistory = computed(() => {
        let result = [...historyEntries.value]
  
        // Lọc theo tìm kiếm
        if (searchQuery.value) {
          const query = searchQuery.value.toLowerCase()
          result = result.filter(
            h => 
              h.licensePlate.toLowerCase().includes(query) ||
              h.resident.toLowerCase().includes(query) ||
              h.apartment.toLowerCase().includes(query)
          )
        }
  
        // Lọc theo khoảng thời gian
        if (startDate.value && endDate.value) {
          // Trong thực tế sẽ cần xử lý chuyển đổi định dạng ngày tháng
          // Đây chỉ là mô phỏng đơn giản
          result = result.filter(h => {
            const historyDate = new Date(h.time.split(' - ')[1].split('/').reverse().join('-'))
            const start = new Date(startDate.value)
            const end = new Date(endDate.value)
            return historyDate >= start && historyDate <= end
          })
        }
  
        return result
      })
  
      return {
        historyEntries,
        searchQuery,
        startDate,
        endDate,
        filteredHistory
      }
    }
  }
  </script>
  
  <style>
  </style>