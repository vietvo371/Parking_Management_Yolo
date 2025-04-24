<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý cư dân</h1>
        <router-link to="/residents/add">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <Plus class="mr-2 h-4 w-4" />
            Thêm cư dân mới
          </button>
        </router-link>
      </div>
  
      <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
          <span class="px-3 py-1 rounded-lg border border-gray-300 dark:border-gray-600 text-sm">
            Tất cả: {{ residents.length }}
          </span>
          <span class="px-3 py-1 rounded-lg bg-green-100 text-green-800 border border-green-200 text-sm">
            Hoạt động: {{ residents.filter(r => r.status === 'active').length }}
          </span>
          <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-800 border border-gray-200 text-sm">
            Không hoạt động: {{ residents.filter(r => r.status === 'inactive').length }}
          </span>
        </div>
        <div class="flex w-full sm:w-auto items-center gap-2">
          <div class="relative w-full sm:w-auto">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              v-model="searchQuery" 
              type="search" 
              placeholder="Tìm kiếm cư dân..." 
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
          <h2 class="text-lg font-medium">Danh sách cư dân</h2>
          <p class="text-sm text-gray-500">Quản lý thông tin cư dân trong chung cư</p>
        </div>
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cư dân</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Căn hộ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Liên hệ</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số xe</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày đăng ký</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="resident in filteredResidents" :key="resident.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-3">
                    <div class="relative h-10 w-10 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <span class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ resident.name.charAt(0) }}</span>
                    </div>
                    <span class="font-medium">{{ resident.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ resident.apartment }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex flex-col">
                    <div class="flex items-center">
                      <Phone class="mr-2 h-3 w-3 text-gray-500" />
                      <span class="text-sm">{{ resident.phone }}</span>
                    </div>
                    <div class="flex items-center mt-1">
                      <Mail class="mr-2 h-3 w-3 text-gray-500" />
                      <span class="text-sm">{{ resident.email }}</span>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                    {{ resident.vehicles }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    class="px-2 py-1 text-xs font-medium rounded-full"
                    :class="resident.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                  >
                    {{ resident.status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ resident.joinDate }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex space-x-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">Chi tiết</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">Chỉnh sửa</button>
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
  import { Plus, Search, Filter, Phone, Mail } from 'lucide-vue-next'
  import { useResidentsStore } from '@/stores/residents'
  
  export default {
    name: 'Residents',
    components: {
      Plus,
      Search,
      Filter,
      Phone,
      Mail
    },
    setup() {
      const residentsStore = useResidentsStore()
      const searchQuery = ref('')
  
      const residents = computed(() => residentsStore.residents)
  
      const filteredResidents = computed(() => {
        if (!searchQuery.value) {
          return residents.value
        }
  
        const query = searchQuery.value.toLowerCase()
        return residents.value.filter(
          (r) =>
            r.name.toLowerCase().includes(query) ||
            r.apartment.toLowerCase().includes(query) ||
            r.phone.includes(query) ||
            r.email.toLowerCase().includes(query)
        )
      })
  
      return {
        residents,
        searchQuery,
        filteredResidents
      }
    }
  }
  </script>
  
  <style>
  /* Custom styles can be added here if needed */
  </style>