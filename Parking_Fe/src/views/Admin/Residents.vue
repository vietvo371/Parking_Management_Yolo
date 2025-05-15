<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý cư dân</h1>
      <router-link to="/admin/quan-ly-khach-hang/them-khach-hang">
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
          Hoạt động: {{residents.filter(r => r.trang_thai === 1).length}}
        </span>
        <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-800 border border-gray-200 text-sm">
          Không hoạt động: {{residents.filter(r => r.trang_thai === 0).length}}
        </span>
      </div>
      <div class="flex w-full sm:w-auto items-center gap-2">
        <div class="relative w-full sm:w-auto">
          <div class="flex items-center gap-2">
            <div class="relative flex-1">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input v-model="searchQuery" type="search" placeholder="Tìm theo tên..."
                class="pl-9 w-full sm:w-[200px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>
            <div class="relative flex-1">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input v-model="searchLicensePlate" type="search" placeholder="Tìm theo biển số..."
                class="pl-9 w-full sm:w-[200px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>
          </div>
        </div>
        <div class="relative">
          <button @click="toggleFilterMenu" class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
            <Filter class="h-4 w-4" />
          </button>
          <!-- Filter Dropdown -->
          <div v-if="showFilterMenu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-10 border border-gray-200 dark:border-gray-700">
            <div class="p-2">
              <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Trạng thái</label>
                <select v-model="statusFilter" class="w-full h-8 px-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                  <option value="all">Tất cả</option>
                  <option value="1">Hoạt động</option>
                  <option value="0">Không hoạt động</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Sắp xếp theo</label>
                <select v-model="sortBy" class="w-full h-8 px-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                  <option value="name">Tên</option>
                  <option value="apartment">Căn hộ</option>
                  <option value="date">Ngày đăng ký</option>
                  <option value="status">Trạng thái</option>
                </select>
              </div>
              <div class="mb-2">
                <label class="block text-sm font-medium mb-1">Thứ tự</label>
                <select v-model="sortOrder" class="w-full h-8 px-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                  <option value="asc">Tăng dần</option>
                  <option value="desc">Giảm dần</option>
                </select>
              </div>
            </div>
          </div>
        </div>
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
              <th scope="col" @click="sortByColumn('name')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                Cư dân
                <span v-if="sortBy === 'name'" class="ml-1">
                  {{ sortOrder === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th scope="col" @click="sortByColumn('apartment')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                Căn hộ
                <span v-if="sortBy === 'apartment'" class="ml-1">
                  {{ sortOrder === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Liên hệ</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Số xe</th>
              <th scope="col" @click="sortByColumn('status')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                Trạng thái
                <span v-if="sortBy === 'status'" class="ml-1">
                  {{ sortOrder === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th scope="col" @click="sortByColumn('date')"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700">
                Ngày đăng ký
                <span v-if="sortBy === 'date'" class="ml-1">
                  {{ sortOrder === 'asc' ? '↑' : '↓' }}
                </span>
              </th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="resident in filteredAndSortedResidents" :key="resident.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center space-x-3">
                  <div
                    class="relative h-10 w-10 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-lg font-medium text-gray-600 dark:text-gray-300">{{ resident.ho_va_ten.charAt(0)
                      }}</span>
                  </div>
                  <span class="font-medium">{{ resident.ho_va_ten }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">Toà {{ resident.id_can_ho }} - Tầng {{ resident.tang }} - Căn hộ
                {{ resident.so_can_ho }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-col">
                  <div class="flex items-center">
                    <Phone class="mr-2 h-3 w-3 text-gray-500" />
                    <span class="text-sm">{{ resident.so_dien_thoai }}</span>
                  </div>
                  <div class="flex items-center mt-1">
                    <Mail class="mr-2 h-3 w-3 text-gray-500" />
                    <span class="text-sm">{{ resident.email }}</span>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                  {{ resident.bien_so_xe }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 text-xs font-medium rounded-full"
                  :class="resident.trang_thai === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                  {{ resident.trang_thai === 1 ? 'Hoạt động' : 'Không hoạt động' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ convertDate(resident.created_at) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                  <button v-if="resident.phe_duyet === 0" @click="duyetCuDan(resident)"
                    class="px-3 py-1 text-sm text-white bg-green-600 border border-gray-300 dark:border-gray-600 rounded-md">Duyệt</button>
                  <button @click="openChiTietCuDan(resident)"
                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">Chi
                    tiết</button>
                  <button @click="openResidentModal(resident)"
                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">Chỉnh
                    sửa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- Cập nhật căn hộ -->
      <div v-if="showUpdateResidentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium">Chỉnh sửa cư dân</h3>
            <button @click="closeResidentModal" class="text-gray-500 hover:text-gray-700">
              <span class="text-xl">&times;</span>
            </button>
          </div>
          <form @submit.prevent="capNhatCuDan" class="space-y-6">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <label for="full-name" class="block text-sm font-medium">Họ và tên</label>
                <input v-model="update_cu_dan.ho_va_ten" id="full-name" placeholder="Nguyễn Văn A"
                  class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  required />
              </div>
              <div class="space-y-2">
                <label for="id-number" class="block text-sm font-medium">Số CMND/CCCD</label>
                <input v-model="update_cu_dan.so_cccd" id="id-number" placeholder="012345678901"
                  class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  required />
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div class="space-y-2">
                <label for="phone" class="block text-sm font-medium">Số điện thoại</label>
                <input v-model="update_cu_dan.so_dien_thoai" id="phone" placeholder="0901234567"
                  class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  required />
              </div>
              <div class="space-y-2">
                <label for="email" class="block text-sm font-medium">Email</label>
                <input v-model="update_cu_dan.email" id="email" placeholder="example@email.com" type="email"
                  class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
              </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-1">
              <div class="space-y-3">
                  <label for="brand" class="block text-sm font-medium ">Căn hộ</label>
                  <a-select class="w-100" v-model:value="update_cu_dan.id_can_ho" show-search placeholder="Chọn cư dân"
                    style="width: 100%" :options="options" :filter-option="filterOption()" />
                </div>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
              <button @click="closeResidentModal" type="button"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
                Hủy
              </button>
              <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                :disabled="isSubmitting">
                {{ isSubmitting ? "Đang lưu..." : "Lưu cư dân" }}
              </button>
            </div>

            <div v-if="isSuccess" class="bg-green-100 text-green-800 p-3 rounded-md flex items-center">
              <Check class="h-4 w-4 mr-2" />
              Cư dân đã được cập nhật thành công!
            </div>
          </form>

        </div>
      </div>
      <!-- Chi tiết cư dân -->
      <!-- Overlay -->
      <div v-if="showChiTietCuDanModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300">

        <!-- Modal -->
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-lg w-full max-w-lg p-6 relative animate-fade-in">
          <!-- Close Button -->
          <button @click="closeResidentModal"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
              stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- Header -->
          <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-4">Chi tiết cư dân</h2>

          <!-- Content -->
          <div class="space-y-4">
            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium">Họ và tên:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.ho_va_ten }}</span>
            </div>

            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium">Số CMND/CCCD:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.so_cccd }}</span>
            </div>

            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium">Số điện thoại:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.so_dien_thoai }}</span>
            </div>

            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium">Email:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.email }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium">Số xe:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.bien_so_xe }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium">Căn hộ:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.id_can_ho }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium"> Ngày đăng ký:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ convertDate(chi_tiet_cu_dan.created_at) }}</span>
              </div>
            <div class="flex justify-between">
              <span class="text-gray-500 dark:text-gray-300 font-medium"> Trạng thái:</span>
              <span class="text-gray-900 dark:text-white font-semibold">{{ chi_tiet_cu_dan.trang_thai === 1 ? 'Hoạt động' : 'Không hoạt động' }}</span>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { Plus, Search, Filter, Phone, Mail, Check } from 'lucide-vue-next'
import baseRequest from '../../core/baseRequest'
import { useNotificationStore } from '../../stores/notication'
export default {
  name: 'Residents',
  components: {
    Plus,
    Search,
    Filter,
    Phone,
    Mail,
    Check
  },
  data() {
    return {
      residents: [],
      searchQuery: '',
      searchLicensePlate: '',
      isSubmitting: false,
      isSuccess: false,
      apartments: [],
      showUpdateResidentModal: false,
      showChiTietCuDanModal: false,
      chi_tiet_cu_dan: {},
      update_cu_dan: {
        ho_va_ten: '',
        email: '',
        password: '',
        so_dien_thoai: '',
        bien_so_xe: '',
        trang_thai: '',
        created_at: ''
      },
      // New sorting and filtering states
      showFilterMenu: false,
      statusFilter: 'all',
      sortBy: 'name',
      sortOrder: 'asc'
    }
  },

  mounted() {
    this.getCuDan()
    this.getCanHo()
    // Close filter menu when clicking outside
    document.addEventListener('click', this.handleClickOutside)
  },

  beforeUnmount() {
    document.removeEventListener('click', this.handleClickOutside)
  },

  computed: {
    filteredAndSortedResidents() {
      let result = [...this.residents]

      // Apply name search filter
      if (this.searchQuery) {
        const query = this.searchQuery.toLowerCase()
        result = result.filter(resident => 
          resident.ho_va_ten.toLowerCase().includes(query)
        )
      }

      // Apply license plate search filter
      if (this.searchLicensePlate) {
        const query = this.searchLicensePlate.toLowerCase()
        result = result.filter(resident => 
          resident.bien_so_xe.toLowerCase().includes(query)
        )
      }

      // Apply status filter
      if (this.statusFilter !== 'all') {
        result = result.filter(resident => resident.trang_thai === parseInt(this.statusFilter))
      }

      // Apply sorting
      result.sort((a, b) => {
        let comparison = 0
        switch (this.sortBy) {
          case 'name':
            comparison = a.ho_va_ten.localeCompare(b.ho_va_ten)
            break
          case 'apartment':
            comparison = a.id_can_ho.localeCompare(b.id_can_ho)
            break
          case 'status':
            comparison = a.trang_thai - b.trang_thai
            break
          case 'date':
            comparison = new Date(a.created_at) - new Date(b.created_at)
            break
        }
        return this.sortOrder === '' ? comparison : -comparison
      })

      return result
    },
    options() {
      return this.apartments.map(apartment => ({
        label: "Toà " + apartment.ten_toa_nha + " - Tầng " + apartment.tang + " - Căn hộ " + apartment.so_can_ho,
        value: apartment.id,
      }));
    }
  },
  methods: {
    toggleFilterMenu() {
      this.showFilterMenu = !this.showFilterMenu
    },
    duyetCuDan(resident) {
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/cu-dan/duyet", { id: resident.id }).then((response) => {
        if (response.data.status) {
          notificationStore.showSuccess(response.data.message)
          this.getCuDan()
        } else {
          notificationStore.showError(response.data.message)
        }
      })
      .catch((res) => {
        var errors = Object.values(res.response.data.errors);
        notificationStore.showError(errors[0]);
      });
    },
    handleClickOutside(event) {
      const filterButton = event.target.closest('.relative')
      if (!filterButton) {
        this.showFilterMenu = false
      }
    },

    sortByColumn(column) {
      if (this.sortBy === column) {
        this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc'
      } else {
        this.sortBy = column
        this.sortOrder = 'asc'
      }
    },
    convertDate(date) {
        try {
          return new Date(date).toLocaleDateString('vi-VN');
        } catch (error) {
          return "";
        }
      },
    getCuDan() {
      baseRequest.get("admin/cu-dan/lay-du-lieu").then((response) => {
        this.residents = response.data.data
      })
    },
    getCanHo() {
      baseRequest.get("admin/can-ho/lay-du-lieu").then((response) => {
        this.apartments = response.data.data
      })
    },
    capNhatCuDan() {
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/cu-dan/thong-tin-cap-nhat", this.update_cu_dan)
        .then((response) => {
          if (response.data.status) {
            this.getCuDan()
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError(response.data.message)
          }
          this.closeResidentModal();
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.closeResidentModal();
        });
    },
    openResidentModal(resident) {
      this.showUpdateResidentModal = true
      Object.assign(this.update_cu_dan, resident)
    },
    openChiTietCuDan(resident) {
      this.showChiTietCuDanModal = true
      Object.assign(this.chi_tiet_cu_dan, resident)
    },
    closeResidentModal() {
      this.showUpdateResidentModal = false
      this.showChiTietCuDanModal = false
    },
    filterOption() {
      return (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
      };
    }
  }
}
</script>

<style>
/* Custom styles can be added here if needed */
</style>