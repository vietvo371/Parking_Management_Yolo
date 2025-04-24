<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý xe</h1>
      <router-link to="/vehicles/add">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
          <Plus class="mr-2 h-4 w-4" />
          Thêm xe mới
        </button>
      </router-link>
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
        <h2 class="text-lg font-medium">{{ getActiveTabTitle() }}</h2>
        <p class="text-sm text-gray-500">{{ getActiveTabDescription() }}</p>
      </div>
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chủ xe</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Căn hộ</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại xe</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày đăng ký</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="vehicle in filteredVehicles" :key="vehicle.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center space-x-3">
                  <div class="relative h-10 w-16 overflow-hidden rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-xs text-gray-500">{{ vehicle.licensePlate }}</span>
                  </div>
                  <span class="font-medium">{{ vehicle.licensePlate }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ vehicle.owner }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ vehicle.apartment }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <Car class="mr-2 h-4 w-4" />
                  {{ vehicle.type }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="vehicle.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
                >
                  {{ vehicle.status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ vehicle.registeredDate }}</td>
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
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { Plus, Search, Filter, Car } from "lucide-vue-next";
import { useVehiclesStore } from "@/stores/vehicles";

export default {
  name: "Vehicles",
  components: {
    Plus,
    Search,
    Filter,
    Car,
  },
  setup() {
    const router = useRouter();
    const vehiclesStore = useVehiclesStore();

    const activeTab = ref("all");
    const searchQuery = ref("");

    const tabs = [
      { value: "all", label: "Tất cả xe" },
      { value: "car", label: "Ô tô" },
      { value: "motorbike", label: "Xe máy" },
      { value: "inactive", label: "Không hoạt động" },
    ];

    const filteredVehicles = computed(() => {
      let result = [...vehiclesStore.vehicles];

      // Lọc theo tab
      if (activeTab.value === "car") {
        result = result.filter((v) => v.type === "Ô tô");
      } else if (activeTab.value === "motorbike") {
        result = result.filter((v) => v.type === "Xe máy");
      } else if (activeTab.value === "inactive") {
        result = result.filter((v) => v.status === "inactive");
      }

      // Lọc theo tìm kiếm
      if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        result = result.filter(
          (v) =>
            v.licensePlate.toLowerCase().includes(query) ||
            v.owner.toLowerCase().includes(query) ||
            v.apartment.toLowerCase().includes(query)
        );
      }

      return result;
    });

    const getActiveTabTitle = () => {
      switch (activeTab.value) {
        case "car":
          return "Danh sách ô tô";
        case "motorbike":
          return "Danh sách xe máy";
        case "inactive":
          return "Danh sách xe không hoạt động";
        default:
          return "Danh sách xe";
      }
    };

    const getActiveTabDescription = () => {
      switch (activeTab.value) {
        case "car":
          return "Quản lý các ô tô đã đăng ký trong hệ thống";
        case "motorbike":
          return "Quản lý các xe máy đã đăng ký trong hệ thống";
        case "inactive":
          return "Quản lý các xe không hoạt động trong hệ thống";
        default:
          return "Quản lý tất cả các xe đã đăng ký trong hệ thống";
      }
    };

    return {
      activeTab,
      searchQuery,
      tabs,
      filteredVehicles,
      getActiveTabTitle,
      getActiveTabDescription,
    };
  },
};
</script>

<style>
</style>
