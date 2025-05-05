<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý khách vãng lai</h1>
      <div class="flex items-center space-x-2">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="showAddModal = true">
          <Plus class="mr-2 h-4 w-4" />
          Thêm khách vãng lai
        </button>
      </div>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Tổng khách vãng lai</p>
            <h3 class="text-2xl font-bold">{{ stats.total }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
            <Users class="h-5 w-5 text-blue-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Đang trong bãi</p>
            <h3 class="text-2xl font-bold">{{ stats.inParking }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
            <Car class="h-5 w-5 text-green-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Chưa thanh toán</p>
            <h3 class="text-2xl font-bold">{{ stats.unpaid }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
            <AlertCircle class="h-5 w-5 text-red-600" />
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm text-gray-500">Doanh thu hôm nay</p>
            <h3 class="text-2xl font-bold">{{ formatCurrency(stats.todayRevenue) }}</h3>
          </div>
          <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
            <DollarSign class="h-5 w-5 text-purple-600" />
          </div>
        </div>
      </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
      <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
        <div class="flex flex-1 items-center space-x-2">
          <div class="relative flex-1">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="text" 
              placeholder="Tìm kiếm theo tên, số điện thoại..." 
              class="pl-9 h-10 w-full rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="filters.search"
            />
          </div>
          <select 
            v-model="filters.status" 
            class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
          >
            <option value="all">Tất cả trạng thái</option>
            <option value="in">Đang trong bãi</option>
            <option value="out">Đã ra bãi</option>
            <option value="unpaid">Chưa thanh toán</option>
            <option value="paid">Đã thanh toán</option>
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

    <!-- Danh sách khách vãng lai -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Họ và tên</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Số điện thoại</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian vào</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian ra</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Vị trí</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiền thanh toán</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="guest in filteredGuests" :key="guest.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ guest.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ guest.ho_va_ten }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ guest.so_dien_thoai }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ formatDateTime(guest.thoi_gian_vao) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ guest.thoi_gian_ra ? formatDateTime(guest.thoi_gian_ra) : '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ getSpotName(guest.id_vi_tri_trong_bai) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ formatCurrency(guest.tien_thanh_toan) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="getStatusClass(guest)"
                >
                  {{ getStatusText(guest) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <a-button 
                    v-if="!guest.thoi_gian_ra" 
                    type="primary"
                    size="small"
                    @click="checkoutGuest(guest)"
                  >
                    <LogOut class="h-4 w-4" />
                  </a-button>
                  <a-button 
                    v-if="!guest.is_thanh_toan" 
                    type="primary"
                    size="small"
                    @click="markAsPaid(guest)"
                  >
                    <DollarSign class="h-4 w-4" />
                  </a-button>
                  <a-button 
                    type="primary"
                    size="small"
                    @click="viewGuestDetails(guest)"
                  >
                    <Eye class="h-4 w-4" />
                  </a-button>
                  <a-popconfirm
                    title="Bạn có chắc chắn muốn xóa khách vãng lai này không?"
                    ok-text="Có"
                    cancel-text="Không"
                    @confirm="deleteGuest(guest)"
                  >
                    <a-button 
                      type="primary"
                      danger
                      size="small"
                    >
                      <Trash2 class="h-4 w-4" />
                    </a-button>
                  </a-popconfirm>
                </div>
              </td>
            </tr>
            <tr v-if="filteredGuests.length === 0">
              <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                Không tìm thấy khách vãng lai nào
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Phân trang -->
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
              Hiển thị <span class="font-medium">{{ paginationStart }}</span> đến <span class="font-medium">{{ paginationEnd }}</span> của <span class="font-medium">{{ filteredGuests.length }}</span> kết quả
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

    <!-- Modal thêm khách vãng lai -->
    <a-modal v-model:open="showAddModal" title="Thêm khách vãng lai" @ok="addGuest">
      <template #footer>
        <a-button key="back" @click="showAddModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="addGuest">
          {{ isSubmitting ? "Đang lưu..." : "Thêm" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div>
          <label for="guestName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ và tên *</label>
          <input 
            id="guestName" 
            type="text" 
            v-model="newGuest.ho_va_ten"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập họ và tên"
          />
        </div>
        
        <div>
          <label for="guestPhone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại *</label>
          <input 
            id="guestPhone" 
            type="text" 
            v-model="newGuest.so_dien_thoai"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập số điện thoại"
          />
        </div>
        
        <div>
          <label for="guestSpot" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vị trí đỗ xe *</label>
          <a-select 
            v-model:value="newGuest.id_vi_tri_trong_bai"
            placeholder="Chọn vị trí"
            style="width: 100%"
          >
            <a-select-option v-for="spot in availableSpots" :key="spot.id" :value="spot.id">
              {{ spot.code }} - {{ spot.area }}
            </a-select-option>
          </a-select>
        </div>
        
        <div>
          <label for="guestEntryTime" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời gian vào *</label>
          <input 
            id="guestEntryTime" 
            type="datetime-local" 
            v-model="newGuest.thoi_gian_vao"
            required
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
          />
        </div>
        
        <div>
          <label for="guestPayment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiền thanh toán</label>
          <input 
            id="guestPayment" 
            type="number" 
            v-model="newGuest.tien_thanh_toan"
            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
            placeholder="Nhập số tiền"
          />
        </div>
        
        <div>
          <label class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            <input 
              type="checkbox" 
              v-model="newGuest.is_thanh_toan"
              class="rounded border-gray-300 dark:border-gray-600 text-blue-600"
            />
            <span>Đã thanh toán</span>
          </label>
        </div>
      </div>
    </a-modal>

    <!-- Modal chi tiết khách vãng lai -->
    <a-modal v-model:open="showDetailModal" title="Chi tiết khách vãng lai" width="800px">
      <template #footer>
        <div class="flex justify-end space-x-2">
          <a-button v-if="selectedGuest && !selectedGuest.thoi_gian_ra" type="primary" @click="checkoutSelectedGuest">
            Ghi nhận ra bãi
          </a-button>
          <a-button v-if="selectedGuest && !selectedGuest.is_thanh_toan" type="primary" @click="markSelectedAsPaid">
            Đánh dấu đã thanh toán
          </a-button>
          <a-button @click="closeDetailModal">Đóng</a-button>
        </div>
      </template>
      <div v-if="selectedGuest" class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-xl font-bold">{{ selectedGuest.ho_va_ten }}</h2>
            <p class="text-sm text-gray-500">ID: {{ selectedGuest.id }}</p>
          </div>
          <span 
            class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
            :class="getStatusClass(selectedGuest)"
          >
            {{ getStatusText(selectedGuest) }}
          </span>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-sm text-gray-500">Số điện thoại</p>
            <p class="font-medium">{{ selectedGuest.so_dien_thoai }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Vị trí đỗ xe</p>
            <p class="font-medium">{{ getSpotName(selectedGuest.id_vi_tri_trong_bai) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Thời gian vào</p>
            <p class="font-medium">{{ formatDateTime(selectedGuest.thoi_gian_vao) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Thời gian ra</p>
            <p class="font-medium">{{ selectedGuest.thoi_gian_ra ? formatDateTime(selectedGuest.thoi_gian_ra) : 'Chưa ra bãi' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Tiền thanh toán</p>
            <p class="font-medium">{{ formatCurrency(selectedGuest.tien_thanh_toan) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Trạng thái thanh toán</p>
            <p class="font-medium">{{ selectedGuest.is_thanh_toan ? 'Đã thanh toán' : 'Chưa thanh toán' }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Thời gian đỗ xe</p>
            <p class="font-medium">{{ calculateParkingDuration(selectedGuest) }}</p>
          </div>
          <div>
            <p class="text-sm text-gray-500">Người quản lý</p>
            <p class="font-medium">{{ getAdminName(selectedGuest.id_admin) }}</p>
          </div>
        </div>
      </div>
    </a-modal>

    <!-- Modal xác nhận xóa -->
    <a-modal v-model:open="showDeleteModal" title="Xác nhận xóa" @ok="confirmDelete">
      <template #footer>
        <a-button key="back" @click="showDeleteModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" danger @click="confirmDelete">Xóa</a-button>
      </template>
      <p>Bạn có chắc chắn muốn xóa thông tin khách vãng lai này không?</p>
    </a-modal>
  </div>
</template>

<script>
import { 
  Plus, 
  Search, 
  Users, 
  Car, 
  AlertCircle, 
  DollarSign, 
  LogOut, 
  Eye, 
  Trash2, 
  ChevronLeft, 
  ChevronRight, 
  X 
} from 'lucide-vue-next'
import { Modal, Button, Select, Popconfirm } from 'ant-design-vue'

export default {
  name: 'KhachVangLai',
  components: {
    Plus,
    Search,
    Users,
    Car,
    AlertCircle,
    DollarSign,
    LogOut,
    Eye,
    Trash2,
    ChevronLeft,
    ChevronRight,
    X,
    [Modal.name]: Modal,
    [Button.name]: Button,
    [Select.name]: Select,
    [Popconfirm.name]: Popconfirm
  },
  data() {
    return {
      // Thống kê
      stats: {
        total: 0,
        inParking: 0,
        unpaid: 0,
        todayRevenue: 0
      },
      // Danh sách khách vãng lai
      guests: [],
      // Danh sách vị trí đỗ xe
      parkingSpots: [],
      // Danh sách admin
      admins: [],
      // Bộ lọc
      filters: {
        search: '',
        status: 'all',
        dateFrom: '',
        dateTo: ''
      },
      // Phân trang
      pagination: {
        currentPage: 1,
        itemsPerPage: 10,
        totalPages: 1
      },
      // Modal
      showAddModal: false,
      showDeleteModal: false,
      showDetailModal: false,
      selectedGuest: null,
      guestToDelete: null,
      // Khách vãng lai mới
      newGuest: {
        ho_va_ten: '',
        so_dien_thoai: '',
        thoi_gian_vao: '',
        id_vi_tri_trong_bai: '',
        tien_thanh_toan: 0,
        is_thanh_toan: false,
        id_admin: 1
      },
      isSubmitting: false
    }
  },
  computed: {
    filteredGuests() {
      let result = [...this.guests]
      
      // Lọc theo tìm kiếm
      if (this.filters.search) {
        const searchLower = this.filters.search.toLowerCase()
        result = result.filter(guest => 
          guest.ho_va_ten.toLowerCase().includes(searchLower) || 
          guest.so_dien_thoai.includes(this.filters.search)
        )
      }
      
      // Lọc theo trạng thái
      if (this.filters.status !== 'all') {
        switch (this.filters.status) {
          case 'in':
            result = result.filter(guest => !guest.thoi_gian_ra)
            break
          case 'out':
            result = result.filter(guest => guest.thoi_gian_ra)
            break
          case 'unpaid':
            result = result.filter(guest => !guest.is_thanh_toan)
            break
          case 'paid':
            result = result.filter(guest => guest.is_thanh_toan)
            break
        }
      }
      
      // Lọc theo ngày
      if (this.filters.dateFrom) {
        const fromDate = new Date(this.filters.dateFrom)
        result = result.filter(guest => new Date(guest.thoi_gian_vao) >= fromDate)
      }
      
      if (this.filters.dateTo) {
        const toDate = new Date(this.filters.dateTo)
        toDate.setHours(23, 59, 59)
        result = result.filter(guest => new Date(guest.thoi_gian_vao) <= toDate)
      }
      
      // Sắp xếp theo thời gian vào mới nhất
      result.sort((a, b) => new Date(b.thoi_gian_vao) - new Date(a.thoi_gian_vao))
      
      // Cập nhật tổng số trang
      this.pagination.totalPages = Math.ceil(result.length / this.pagination.itemsPerPage)
      
      return result
    },
    paginatedGuests() {
      const start = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage
      const end = start + this.pagination.itemsPerPage
      return this.filteredGuests.slice(start, end)
    },
    paginationStart() {
      return (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1
    },
    paginationEnd() {
      return Math.min(this.pagination.currentPage * this.pagination.itemsPerPage, this.filteredGuests.length)
    },
    paginationPages() {
      const pages = []
      for (let i = 1; i <= this.pagination.totalPages; i++) {
        pages.push(i)
      }
      return pages
    },
    availableSpots() {
      const occupiedSpotIds = this.guests
        .filter(guest => !guest.thoi_gian_ra)
        .map(guest => guest.id_vi_tri_trong_bai)
      
      return this.parkingSpots.filter(spot => !occupiedSpotIds.includes(spot.id))
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    async fetchData() {
      try {
        // Trong thực tế, bạn sẽ gọi API để lấy dữ liệu
        // Ở đây chúng ta sẽ sử dụng dữ liệu mẫu
        this.guests = [
          {
            id: 1,
            id_admin: 1,
            ho_va_ten: 'Nguyễn Văn A',
            so_dien_thoai: '0987654321',
            thoi_gian_vao: '2023-05-01T08:30:00',
            thoi_gian_ra: '2023-05-01T10:45:00',
            tien_thanh_toan: 20000,
            is_thanh_toan: true,
            id_vi_tri_trong_bai: 'A-01'
          },
          {
            id: 2,
            id_admin: 2,
            ho_va_ten: 'Trần Thị B',
            so_dien_thoai: '0912345678',
            thoi_gian_vao: '2023-05-01T09:15:00',
            thoi_gian_ra: null,
            tien_thanh_toan: 15000,
            is_thanh_toan: false,
            id_vi_tri_trong_bai: 'A-02'
          },
          {
            id: 3,
            id_admin: 1,
            ho_va_ten: 'Lê Văn C',
            so_dien_thoai: '0976543210',
            thoi_gian_vao: '2023-05-01T10:00:00',
            thoi_gian_ra: '2023-05-01T11:30:00',
            tien_thanh_toan: 15000,
            is_thanh_toan: true,
            id_vi_tri_trong_bai: 'B-01'
          },
          {
            id: 4,
            id_admin: 3,
            ho_va_ten: 'Phạm Thị D',
            so_dien_thoai: '0965432109',
            thoi_gian_vao: '2023-05-01T11:45:00',
            thoi_gian_ra: null,
            tien_thanh_toan: 0,
            is_thanh_toan: false,
            id_vi_tri_trong_bai: 'B-02'
          },
          {
            id: 5,
            id_admin: 2,
            ho_va_ten: 'Hoàng Văn E',
            so_dien_thoai: '0954321098',
            thoi_gian_vao: '2023-05-02T08:00:00',
            thoi_gian_ra: '2023-05-02T09:30:00',
            tien_thanh_toan: 15000,
            is_thanh_toan: true,
            id_vi_tri_trong_bai: 'A-03'
          }
        ]
        
        this.parkingSpots = [
          { id: 'A-01', code: 'A-01', area: 'Khu A' },
          { id: 'A-02', code: 'A-02', area: 'Khu A' },
          { id: 'A-03', code: 'A-03', area: 'Khu A' },
          { id: 'B-01', code: 'B-01', area: 'Khu B' },
          { id: 'B-02', code: 'B-02', area: 'Khu B' },
          { id: 'B-03', code: 'B-03', area: 'Khu B' },
          { id: 'C-01', code: 'C-01', area: 'Khu C' },
          { id: 'C-02', code: 'C-02', area: 'Khu C' }
        ]
        
        this.admins = [
          { id: 1, name: 'Admin 1' },
          { id: 2, name: 'Admin 2' },
          { id: 3, name: 'Admin 3' }
        ]
        
        this.updateStats()
      } catch (error) {
        console.error('Lỗi khi lấy dữ liệu:', error)
      }
    },
    updateStats() {
      const today = new Date().toISOString().split('T')[0]
      
      this.stats.total = this.guests.length
      this.stats.inParking = this.guests.filter(guest => !guest.thoi_gian_ra).length
      this.stats.unpaid = this.guests.filter(guest => !guest.is_thanh_toan).length
      this.stats.todayRevenue = this.guests
        .filter(guest => {
          const guestDate = new Date(guest.thoi_gian_ra || guest.thoi_gian_vao).toISOString().split('T')[0]
          return guestDate === today && guest.is_thanh_toan
        })
        .reduce((sum, guest) => sum + guest.tien_thanh_toan, 0)
    },
    getSpotName(spotId) {
      const spot = this.parkingSpots.find(s => s.id === spotId)
      return spot ? `${spot.code} - ${spot.area}` : spotId
    },
    getAdminName(adminId) {
      const admin = this.admins.find(a => a.id === adminId)
      return admin ? admin.name : `Admin ID: ${adminId}`
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
    formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(amount || 0)
    },
    calculateParkingDuration(guest) {
      const entryTime = new Date(guest.thoi_gian_vao)
      const exitTime = guest.thoi_gian_ra ? new Date(guest.thoi_gian_ra) : new Date()
      
      const diffMs = exitTime - entryTime
      const diffHrs = Math.floor(diffMs / (1000 * 60 * 60))
      const diffMins = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60))
      
      return `${diffHrs} giờ ${diffMins} phút`
    },
    getStatusText(guest) {
      if (!guest.thoi_gian_ra) {
        return 'Đang trong bãi'
      }
      
      if (!guest.is_thanh_toan) {
        return 'Chưa thanh toán'
      }
      
      return 'Đã hoàn thành'
    },
    getStatusClass(guest) {
      if (!guest.thoi_gian_ra) {
        return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
      }
      
      if (!guest.is_thanh_toan) {
        return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
      }
      
      return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
    },
    resetFilters() {
      this.filters = {
        search: '',
        status: 'all',
        dateFrom: '',
        dateTo: ''
      }
      this.pagination.currentPage = 1
    },
    addGuest() {
      // Trong thực tế, bạn sẽ gọi API để thêm khách vãng lai
      const newId = Math.max(...this.guests.map(g => g.id)) + 1
      
      const guest = {
        id: newId,
        ...this.newGuest
      }
      
      this.guests.push(guest)
      this.updateStats()
      
      // Đặt lại form
      this.newGuest = {
        ho_va_ten: '',
        so_dien_thoai: '',
        thoi_gian_vao: '',
        id_vi_tri_trong_bai: '',
        tien_thanh_toan: 0,
        is_thanh_toan: false,
        id_admin: 1
      }
      
      this.showAddModal = false
    },
    checkoutGuest(guest) {
      // Trong thực tế, bạn sẽ gọi API để cập nhật thông tin
      const index = this.guests.findIndex(g => g.id === guest.id)
      if (index !== -1) {
        this.guests[index].thoi_gian_ra = new Date().toISOString()
        
        // Tính tiền nếu chưa có
        if (!this.guests[index].tien_thanh_toan) {
          const entryTime = new Date(this.guests[index].thoi_gian_vao)
          const exitTime = new Date(this.guests[index].thoi_gian_ra)
          const diffHrs = Math.ceil((exitTime - entryTime) / (1000 * 60 * 60))
          
          // Giả sử phí là 5000đ/giờ
          this.guests[index].tien_thanh_toan = diffHrs * 5000
        }
        
        this.updateStats()
      }
    },
    markAsPaid(guest) {
      // Trong thực tế, bạn sẽ gọi API để cập nhật thông tin
      const index = this.guests.findIndex(g => g.id === guest.id)
      if (index !== -1) {
        this.guests[index].is_thanh_toan = true
        this.updateStats()
      }
    },
    viewGuestDetails(guest) {
      this.selectedGuest = { ...guest }
      this.showDetailModal = true
    },
    closeDetailModal() {
      this.selectedGuest = null
      this.showDetailModal = false
    },
    checkoutSelectedGuest() {
      this.checkoutGuest(this.selectedGuest)
      this.closeDetailModal()
    },
    markSelectedAsPaid() {
      this.markAsPaid(this.selectedGuest)
      this.closeDetailModal()
    },
    deleteGuest(guest) {
      this.guestToDelete = guest
      this.showDeleteModal = true
    },
    confirmDelete() {
      // Trong thực tế, bạn sẽ gọi API để xóa khách vãng lai
      this.guests = this.guests.filter(g => g.id !== this.guestToDelete.id)
      this.updateStats()
      
      this.showDeleteModal = false
      this.guestToDelete = null
    }
  }
}
</script>
