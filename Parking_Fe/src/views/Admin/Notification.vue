<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý thông báo gia hạn</h1>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center"
        @click="showCreateNotificationModal = true">
        <Plus class="mr-2 h-4 w-4" />
        Tạo thông báo mới
      </button>
    </div>

    <!-- Thống kê -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div
            class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 flex items-center justify-center mr-4">
            <Users class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Tổng số cư dân</p>
            <p class="text-2xl font-bold">{{ statistics.totalResidents }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div
            class="w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 flex items-center justify-center mr-4">
            <Clock class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Sắp hết hạn (30 ngày)</p>
            <p class="text-2xl font-bold">{{ statistics.expiringIn30Days }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div
            class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400 flex items-center justify-center mr-4">
            <AlertTriangle class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Sắp hết hạn (7 ngày)</p>
            <p class="text-2xl font-bold">{{ statistics.expiringIn7Days }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex items-center">
          <div
            class="w-12 h-12 rounded-full bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400 flex items-center justify-center mr-4">
            <CheckCircle class="h-6 w-6" />
          </div>
          <div>
            <p class="text-sm text-gray-500 dark:text-gray-400">Đã gửi thông báo</p>
            <p class="text-2xl font-bold">{{ statistics.notificationsSent }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Bộ lọc -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
      <div class="flex flex-col md:flex-row md:items-center gap-4">
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Thời gian hết hạn</label>
            <a-select v-model:value="filters.expiryRange" class="w-full">
              <a-select-option value="all">Tất cả</a-select-option>
              <a-select-option value="7">Sắp hết hạn trong 7 ngày</a-select-option>
              <a-select-option value="15">Sắp hết hạn trong 15 ngày</a-select-option>
              <a-select-option value="30">Sắp hết hạn trong 30 ngày</a-select-option>
              <a-select-option value="expired">Đã hết hạn</a-select-option>
            </a-select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Trạng thái thông báo</label>
            <a-select v-model:value="filters.notificationStatus" class="w-full">
              <a-select-option value="all">Tất cả</a-select-option>
              <a-select-option value="not_sent">Chưa gửi thông báo</a-select-option>
              <a-select-option value="sent">Đã gửi thông báo</a-select-option>
              <a-select-option value="read">Đã đọc thông báo</a-select-option>
              <a-select-option value="renewed">Đã gia hạn</a-select-option>
            </a-select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe</label>
            <a-select v-model:value="filters.vehicleType" class="w-full">
              <a-select-option value="all">Tất cả</a-select-option>
              <a-select-option value="car">Ô tô</a-select-option>
              <a-select-option value="motorbike">Xe máy</a-select-option>
            </a-select>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tìm kiếm</label>
            <div class="relative">
              <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
              <input type="text" placeholder="Tên, biển số, căn hộ..."
                class="pl-9 w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                v-model="filters.search" />
            </div>
          </div>
        </div>

        <div class="flex-shrink-0 flex space-x-2">
          <a-button @click="resetFilters">
            <template>
              <RefreshCw class="mr-2 h-4 w-4" />
            </template>
            Đặt lại
          </a-button>
          <a-button type="primary" @click="applyFilters">
            <template>
              <Filter class="mr-2 h-4 w-4" />
            </template>
            Lọc
          </a-button>
        </div>
      </div>
    </div>

    <!-- Danh sách cư dân sắp hết hạn -->
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div
        class="p-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <h2 class="text-lg font-medium">Danh sách cư dân sắp hết hạn</h2>
        <div class="flex items-center space-x-2">
          <a-button type="primary" @click="sendBulkNotifications" :disabled="selectedResidents.length === 0">
            <template>
              <Send class="mr-2 h-4 w-4" />
            </template>
            Gửi thông báo ({{ selectedResidents.length }})
          </a-button>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                <div class="flex items-center">
                  <a-checkbox :checked="isAllSelected" :indeterminate="isSomeSelected && !isAllSelected"
                    @change="toggleSelectAll" />
                </div>
              </th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Cư
                dân</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Thông tin xe</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Gói đăng ký</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Ngày hết hạn</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Trạng thái</th>
              <th scope="col"
                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Thao tác</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="resident in paginatedResidents" :key="resident.id"
              class="hover:bg-gray-50 dark:hover:bg-gray-800">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <a-checkbox :checked="selectedResidents.some(selected => selected.id_cu_dan === resident.id_cu_dan && selected.bien_so_xe === resident.vehicle.licensePlate)"
                    @change="() => toggleResidentSelection(resident)" />
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div
                      class="h-10 w-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300">
                      {{ getInitials(resident.name) }}
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium">{{ resident.name }}</div>
                    <div class="text-sm text-gray-500">
                      <div class="flex items-center">
                        <Phone class="h-3 w-3 mr-1" />
                        {{ resident.phone }}
                      </div>
                      <div class="flex items-center">
                        <Mail class="h-3 w-3 mr-1" />
                        {{ resident.email }}
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <component :is="resident.vehicle.type === 'car' ? Car : Bike" class="h-5 w-5 mr-2 text-gray-500" />
                  <div>
                    <div class="text-sm font-medium">{{ resident.vehicle.licensePlate }}</div>
                    <div class="text-sm text-gray-500">{{ resident.ten_loai_xe }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm">{{ resident.package.name }}</div>
                <div class="text-sm text-gray-500">{{ formatCurrency(resident.package.price) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm">{{ formatDate(resident.expiryDate) }}</div>
                <div class="text-xs px-2 py-1 rounded-full mt-1 inline-flex items-center"
                  :class="getExpiryStatusClass(resident.expiryDate)">
                  {{ getExpiryStatus(resident.expiryDate) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="getNotificationStatusClass(resident.notificationStatus)">
                  {{ getNotificationStatusText(resident.notificationStatus) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <a-button type="primary" size="small" @click="viewNotificationHistory(resident)">
                   <History class="h-4 w-4" />
                  </a-button>


                  <!-- 
                    <a-button 
                      type="primary"
                      size="small"
                      @click="markAsRenewed(resident)"
                    >
                      <template ><CheckCircle class="h-4 w-4" /></template>
                    </a-button> -->
                </div>
              </td>
            </tr>
            <tr v-if="filteredResidents.length === 0">
              <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                Không tìm thấy cư dân nào sắp hết hạn
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Phân trang -->
      <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
        <div class="flex-1 flex justify-between sm:hidden">
          <a-button :disabled="pagination.currentPage === 1" @click="changePage(pagination.currentPage - 1)">
            Trước
          </a-button>
          <a-button :disabled="pagination.currentPage === pagination.totalPages"
            @click="changePage(pagination.currentPage + 1)">
            Sau
          </a-button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700 dark:text-gray-300">
              Hiển thị <span class="font-medium">{{ pagination.startItem }}</span> đến <span class="font-medium">{{
                pagination.endItem }}</span> của <span class="font-medium">{{ pagination.totalItems }}</span> kết quả
            </p>
          </div>
          <div>
            <a-pagination v-model:current="pagination.currentPage" :total="pagination.totalItems"
              :pageSize="pagination.itemsPerPage" show-size-changer @change="changePage" />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal tạo thông báo mới -->
    <a-modal v-model:open="showCreateNotificationModal" title="Tạo thông báo mới" @ok="createNotification">
      <template #footer>
        <a-button key="back" @click="closeCreateNotificationModal">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="createNotification">
          {{ isSubmitting ? "Đang lưu..." : "Tạo thông báo" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề thông báo *</label>
          <a-input v-model:value="notificationForm.title" placeholder="Nhập tiêu đề thông báo" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung thông báo *</label>
          <a-textarea v-model:value="notificationForm.content" :rows="4" placeholder="Nhập nội dung thông báo" />
          <p class="text-xs text-gray-500 mt-1">
            Bạn có thể sử dụng các biến sau: {TEN_CU_DAN}, {BIEN_SO_XE}, {GOI_DANG_KY}, {NGAY_HET_HAN},
            {SO_NGAY_CON_LAI}
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Đối tượng nhận thông báo
            *</label>
          <a-select v-model:value="notificationForm.target" class="w-full">
            <a-select-option value="all">Tất cả cư dân sắp hết hạn</a-select-option>
            <a-select-option value="7days">Cư dân hết hạn trong 7 ngày</a-select-option>
            <a-select-option value="15days">Cư dân hết hạn trong 15 ngày</a-select-option>
            <a-select-option value="30days">Cư dân hết hạn trong 30 ngày</a-select-option>
            <a-select-option value="expired">Cư dân đã hết hạn</a-select-option>
          </a-select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức gửi *</label>
          <div class="space-y-2">
            <a-checkbox v-model:checked="notificationForm.methods.email">Email</a-checkbox>
            <a-checkbox v-model:checked="notificationForm.methods.sms">SMS</a-checkbox>
            <a-checkbox v-model:checked="notificationForm.methods.app">Thông báo ứng dụng</a-checkbox>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lịch gửi thông báo</label>
          <div class="space-y-2">
            <a-radio-group v-model:value="notificationForm.scheduleType">
              <a-radio value="now">Gửi ngay</a-radio>
              <a-radio value="scheduled">Đặt lịch gửi</a-radio>
            </a-radio-group>
          </div>
        </div>

        <div v-if="notificationForm.scheduleType === 'scheduled'" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày gửi *</label>
            <a-date-picker v-model:value="notificationForm.scheduleDate" class="w-full" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Giờ gửi *</label>
            <a-time-picker v-model:value="notificationForm.scheduleTime" class="w-full" />
          </div>
        </div>
      </div>
    </a-modal>

    <!-- Modal lịch sử thông báo -->
    <a-modal v-model:open="showHistoryModal" title="Lịch sử thông báo" width="800px">
      <template #footer>
        <a-button @click="closeHistoryModal">Đóng</a-button>
      </template>
      <div v-if="notificationHistory.length === 0" class="text-center py-8">
        <Bell class="h-12 w-12 mx-auto text-gray-400 mb-4" />
        <p class="text-gray-500">Chưa có thông báo nào được gửi</p>
      </div>

      <div v-else class="space-y-4">
        <div v-for="(notification, index) in notificationHistory" :key="index"
          class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
          <div class="flex items-start">
            <div class="w-10 h-10 rounded-full flex items-center justify-center mr-4 flex-shrink-0"
              :class="getNotificationTypeClass(notification.type)">
              <component :is="getNotificationTypeIcon(notification.type)" class="h-5 w-5" />
            </div>
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <h4 class="font-medium">{{ notification.title }}</h4>
                <span class="text-xs text-gray-500">{{ formatDateTime(notification.sentAt) }}</span>
              </div>
              <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ notification.content }}</p>
              <div class="mt-2 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                    :class="getNotificationStatusClass(notification.status)">
                    {{ getNotificationStatusText(notification.status) }}
                  </span>
                  <span v-if="notification.readAt" class="text-xs text-gray-500">
                    Đã đọc: {{ formatDateTime(notification.readAt) }}
                  </span>
                </div>
                <div class="text-xs text-gray-500">
                  Phương thức: {{ getNotificationMethodText(notification.method) }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </a-modal>

    <!-- Modal gửi thông báo -->
    <a-modal v-model:open="showSendNotificationModal" title="Gửi thông báo" @ok="sendNotificationToSelected">
      <template #footer>
        <a-button key="back" @click="closeSendNotificationModal">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="sendNotificationToSelected">
          {{ isSubmitting ? "Đang gửi..." : "Gửi thông báo" }}
        </a-button>
      </template>
      <div class="space-y-4">
        <div class="mb-4">
          <h4 class="font-medium mb-2">Danh sách người nhận ({{ selectedResidentsForNotification.length }})</h4>
          <div class="max-h-32 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-2">
            <div v-for="resident in selectedResidentsForNotification" :key="resident.id"
              class="flex items-center justify-between py-1">
              <div class="flex items-center">
                <div
                  class="h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-600 dark:text-gray-300 mr-2">
                  {{ getInitials(resident.name) }}
                </div>
                <span class="text-sm">{{ resident.name }}</span>
              </div>
              <div class="text-xs text-gray-500">{{ resident.vehicle.licensePlate }}</div>
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tiêu đề thông báo *</label>
          <a-input v-model:value="sendNotificationForm.title" placeholder="Nhập tiêu đề thông báo" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nội dung thông báo *</label>
          <a-textarea v-model:value="sendNotificationForm.content" :rows="4" placeholder="Nhập nội dung thông báo" />
          <p class="text-xs text-gray-500 mt-1">
            Bạn có thể sử dụng các biến sau: {TEN_CU_DAN}, {BIEN_SO_XE}, {GOI_DANG_KY}, {NGAY_HET_HAN},
            {SO_NGAY_CON_LAI}
          </p>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phương thức gửi *</label>
          <div class="space-y-2">
            <a-checkbox v-model:checked="sendNotificationForm.methods.email">Email</a-checkbox>
            <a-checkbox v-model:checked="sendNotificationForm.methods.sms">SMS</a-checkbox>
            <a-checkbox v-model:checked="sendNotificationForm.methods.app">Thông báo ứng dụng</a-checkbox>
          </div>
        </div>
      </div>
    </a-modal>
  </div>
</template>

<script>
import {
  Plus,
  Search,
  Filter,
  RefreshCw,
  Download,
  Send,
  CheckCircle,
  AlertCircle,
  X,
  Users,
  Clock,
  AlertTriangle,
  Car,
  Bike,
  Phone,
  Mail,
  History,
  Bell,
  ChevronLeft,
  ChevronRight,
  ChevronsLeft,
  ChevronsRight
} from 'lucide-vue-next'
import { Modal, Button, Select, Checkbox, Input, Textarea, Radio, DatePicker, TimePicker, Pagination, Popconfirm } from 'ant-design-vue'
import baseRequest from '../../core/baseRequest'
import { useNotificationStore } from '../../stores/notication'


export default {
  name: 'QuanLyThongBao',
  components: {
    Plus,
    Search,
    Filter,
    RefreshCw,
    Download,
    Send,
    CheckCircle,
    AlertCircle,
    X,
    Users,
    Clock,
    AlertTriangle,
    Car,
    Bike,
    Phone,
    Mail,
    History,
    Bell,
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
    [Modal.name]: Modal,
    [Button.name]: Button,
    [Select.name]: Select,
    [Checkbox.name]: Checkbox,
    [Input.name]: Input,
    [Textarea.name]: Textarea,
    [Radio.name]: Radio,
    [Radio.Group.name]: Radio.Group,
    [DatePicker.name]: DatePicker,
    [TimePicker.name]: TimePicker,
    [Pagination.name]: Pagination,
    [Popconfirm.name]: Popconfirm
  },
  data() {
    return {
      // Thống kê
      statistics: {
        totalResidents: 0,
        expiringIn30Days: 0,
        expiringIn7Days: 0,
        notificationsSent: 0
      },

      // Bộ lọc
      filters: {
        expiryRange: 'all',
        notificationStatus: 'all',
        vehicleType: 'all',
        search: ''
      },

      // Danh sách cư dân
      residents: [],
      selectedResidents: [],

      // Phân trang
      pagination: {
        currentPage: 1,
        itemsPerPage: 10,
        totalItems: 0,
        totalPages: 1,
        startItem: 1,
        endItem: 10
      },

      // Modals
      showCreateNotificationModal: false,
      showHistoryModal: false,
      showSendNotificationModal: false,
      selectedResident: null,
      notificationHistory: [],

      // Toast
      showToast: false,
      toastType: 'success',
      toastTitle: '',
      toastMessage: '',

      // Forms
      notificationForm: {
        title: '',
        content: '',
        target: 'all',
        methods: {
          email: true,
          sms: true,
          app: true
        },
        scheduleType: 'now',
        scheduleDate: '',
        scheduleTime: ''
      },

      sendNotificationForm: {
        content: 'Kính gửi {TEN_CU_DAN},\n\nGói gửi xe của xe {BIEN_SO_XE} sẽ hết hạn vào ngày {NGAY_HET_HAN} (còn {SO_NGAY_CON_LAI} ngày).\n\nVui lòng gia hạn để tiếp tục sử dụng dịch vụ.\n\nTrân trọng,\nBan quản lý'
      },

      isSubmitting: false
    }
  },

  computed: {
    filteredResidents() {
      let result = [...this.residents]
      const today = new Date()

      // Lọc theo thời gian hết hạn
      if (this.filters.expiryRange !== 'all') {
        if (this.filters.expiryRange === 'expired') {
          result = result.filter(resident => new Date(resident.expiryDate) < today)
        } else {
          const days = parseInt(this.filters.expiryRange)
          const futureDate = new Date(today)
          futureDate.setDate(futureDate.getDate() + days)

          result = result.filter(resident => {
            const expiryDate = new Date(resident.expiryDate)
            return expiryDate >= today && expiryDate <= futureDate
          })
        }
      }

      // Lọc theo trạng thái thông báo
      if (this.filters.notificationStatus !== 'all') {
        result = result.filter(resident => resident.notificationStatus === this.filters.notificationStatus)
      }

      // Lọc theo loại xe
      if (this.filters.vehicleType !== 'all') {
        result = result.filter(resident => resident.vehicle.type === this.filters.vehicleType)
      }

      // Lọc theo tìm kiếm
      if (this.filters.search) {
        const searchLower = this.filters.search.toLowerCase()
        result = result.filter(resident =>
          resident.name.toLowerCase().includes(searchLower) ||
          resident.vehicle.licensePlate.toLowerCase().includes(searchLower) ||
          resident.phone.includes(searchLower) ||
          resident.email.toLowerCase().includes(searchLower)
        )
      }

      // Cập nhật phân trang
      this.pagination.totalItems = result.length
      this.pagination.totalPages = Math.ceil(result.length / this.pagination.itemsPerPage)

      // Phân trang
      const startIndex = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage
      const endIndex = startIndex + this.pagination.itemsPerPage

      return result.slice(startIndex, endIndex)
    },

    selectedResidentsForNotification() {
      return this.residents.filter(resident => 
        this.selectedResidents.some(selected => 
          selected.id_cu_dan === resident.id_cu_dan && 
          selected.bien_so_xe === resident.vehicle.licensePlate
        )
      )
    },

    isAllSelected() {
      const currentPageResidents = this.filteredResidents
      return currentPageResidents.length > 0 &&
        currentPageResidents.every(resident => 
          this.selectedResidents.some(selected => 
            selected.id_cu_dan === resident.id_cu_dan && 
            selected.bien_so_xe === resident.vehicle.licensePlate
          )
        )
    },

    isSomeSelected() {
      const currentPageResidents = this.filteredResidents
      return currentPageResidents.some(resident => 
        this.selectedResidents.some(selected => 
          selected.id_cu_dan === resident.id_cu_dan && 
          selected.bien_so_xe === resident.vehicle.licensePlate
        )
      )
    },

    paginatedResidents() {
      const startIndex = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage
      const endIndex = startIndex + this.pagination.itemsPerPage
      return this.filteredResidents.slice(startIndex, endIndex)
    },

    displayedPages() {
      const totalPages = this.pagination.totalPages
      const currentPage = this.pagination.currentPage

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
    }
  },

  mounted() {
    this.fetchData()

    // Thiết lập ngày mặc định cho lịch gửi thông báo
    const today = new Date()
    const formattedDate = today.toISOString().split('T')[0]
    const formattedTime = `${today.getHours().toString().padStart(2, '0')}:${today.getMinutes().toString().padStart(2, '0')}`

    this.notificationForm.scheduleDate = formattedDate
    this.notificationForm.scheduleTime = formattedTime
  },

  methods: {
    fetchData() {
      baseRequest.get("admin/thong-bao/lay-du-lieu").then((response) => {
        if (response.data.status) {
          // Chuyển đổi dữ liệu từ API sang định dạng phù hợp với component
          this.residents = response.data.data.map(item => ({
            id: item.id || `temp_${Math.random().toString(36).substr(2, 9)}`, // Tạo ID tạm thời nếu null
            id_cu_dan: item.id_cu_dan, // Thêm id_cu_dan vào object
            name: item.ten_cu_dan,
            phone: item.so_dien_thoai,
            email: item.email,
            ten_loai_xe: item.ten_loai_xe,
            vehicle: {
              type: item.ten_loai_xe.toLowerCase().includes('xe ô tô') ? 'car' :
                item.ten_loai_xe.toLowerCase().includes('xe máy') ? 'motorbike' : 'other',
              licensePlate: item.bien_so_xe,
              brand: 'Không xác định',
              model: 'Không xác định'
            },
            package: {
              name: 'Gói tháng',
              price: 100000,
              duration: 30
            },
            expiryDate: item.ngay_het_han,
            notificationStatus: this.getNotificationStatusFromTrangThai(item.trang_thai),
            notification: {
              id: item.id,
              adminId: item.id_admin,
              residentId: item.id_cu_dan,
              content: item.noi_dung_thong_bao,
              createdAt: item.ngay_tao,
              status: item.trang_thai,
              created_at: item.created_at,
              updated_at: item.updated_at
            }
          }))

          // Cập nhật thống kê
          this.updateStatistics()

          // Cập nhật phân trang
          this.pagination.totalItems = this.residents.length
          this.pagination.totalPages = Math.ceil(this.residents.length / this.pagination.itemsPerPage)
          this.updatePaginationInfo()
        } else {
          this.showToastMessage('error', 'Lỗi', response.data.message || 'Không thể tải dữ liệu')
        }
      }).catch(error => {
        console.error('Lỗi khi lấy dữ liệu:', error)
        this.showToastMessage('error', 'Lỗi', 'Không thể tải dữ liệu. Vui lòng thử lại sau.')
      })
    },

    // Chuyển đổi trạng thái từ API sang định dạng của component
    getNotificationStatusFromTrangThai(trangThai) {
      // Nếu trangThai là null, có nghĩa là chưa có thông báo
      if (trangThai === null) {
        return 'not_sent'
      }

      switch (trangThai) {
        case 0:
          return 'not_sent'
        case 1:
          return 'sent'
        case 2:
          return 'read'
        case 3:
          return 'renewed'
        default:
          return 'not_sent'
      }
    },

    // Cập nhật thống kê
    updateStatistics() {
      const today = new Date()
      const thirtyDaysLater = new Date(today)
      thirtyDaysLater.setDate(today.getDate() + 30)
      const sevenDaysLater = new Date(today)
      sevenDaysLater.setDate(today.getDate() + 7)

      this.statistics = {
        totalResidents: this.residents.length,
        expiringIn30Days: this.residents.filter(resident => {
          const expiryDate = new Date(resident.expiryDate)
          return expiryDate >= today && expiryDate <= thirtyDaysLater
        }).length,
        expiringIn7Days: this.residents.filter(resident => {
          const expiryDate = new Date(resident.expiryDate)
          return expiryDate >= today && expiryDate <= sevenDaysLater
        }).length,
        notificationsSent: this.residents.filter(resident =>
          resident.notificationStatus === 'sent' ||
          resident.notificationStatus === 'read'
        ).length
      }
    },

    updatePaginationInfo() {
      const startItem = (this.pagination.currentPage - 1) * this.pagination.itemsPerPage + 1
      const endItem = Math.min(startItem + this.pagination.itemsPerPage - 1, this.pagination.totalItems)

      this.pagination.startItem = startItem
      this.pagination.endItem = endItem
    },

    toggleSelectAll() {
      const currentPageResidents = this.filteredResidents

      if (this.isAllSelected) {
        // Nếu tất cả đã được chọn, bỏ chọn tất cả trong trang hiện tại
        this.selectedResidents = this.selectedResidents.filter(
          selected => !currentPageResidents.some(resident => 
            resident.id_cu_dan === selected.id_cu_dan && 
            resident.vehicle.licensePlate === selected.bien_so_xe
          )
        )
      } else {
        // Nếu chưa chọn tất cả, thêm tất cả trong trang hiện tại vào danh sách đã chọn
        const newSelected = currentPageResidents
          .filter(resident => !this.selectedResidents.some(selected => 
            selected.id_cu_dan === resident.id_cu_dan && 
            selected.bien_so_xe === resident.vehicle.licensePlate
          ))
          .map(resident => ({
            id_cu_dan: resident.id_cu_dan,
            bien_so_xe: resident.vehicle.licensePlate
          }))

        this.selectedResidents = [...this.selectedResidents, ...newSelected]
      }
    },

    toggleResidentSelection(resident) {
      const selectedIndex = this.selectedResidents.findIndex(
        selected => selected.id_cu_dan === resident.id_cu_dan && 
                   selected.bien_so_xe === resident.vehicle.licensePlate
      )

      if (selectedIndex === -1) {
        // Nếu chưa được chọn, thêm vào danh sách
        this.selectedResidents.push({
          id_cu_dan: resident.id_cu_dan,
          bien_so_xe: resident.vehicle.licensePlate
        })
      } else {
        // Nếu đã được chọn, xóa khỏi danh sách
        this.selectedResidents.splice(selectedIndex, 1)
      }
    },

    resetFilters() {
      this.filters = {
        expiryRange: 'all',
        notificationStatus: 'all',
        vehicleType: 'all',
        search: ''
      }
      // Reset selection khi thay đổi bộ lọc
      this.selectedResidents = []
      this.applyFilters()
    },

    applyFilters() {
      this.pagination.currentPage = 1
      // Reset selection khi áp dụng bộ lọc mới
      this.selectedResidents = []
      this.updatePaginationInfo()
    },

    changePage(page) {
      this.pagination.currentPage = page
      this.updatePaginationInfo()
    },

    exportData() {
      // Trong thực tế, bạn sẽ gọi API để xuất dữ liệu
      this.showToastMessage('success', 'Xuất dữ liệu thành công', 'Dữ liệu đã được xuất ra file Excel')
    },

    sendBulkNotifications() {
      if (this.selectedResidents.length === 0) {
        this.showToastMessage('error', 'Lỗi', 'Vui lòng chọn ít nhất một cư dân để gửi thông báo')
        return
      }
      
      // // Lọc ra các cư dân sắp hết hạn
      // const expiringResidents = this.selectedResidentsForNotification.filter(resident => {
      //   const expiryDate = new Date(resident.expiryDate)
      //   const today = new Date()
      //   const diffTime = expiryDate - today
      //   const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
      //   return diffDays > 0 // Chỉ gửi cho cư dân chưa hết hạn
      // })

      // if (expiringResidents.length === 0) {
      //   this.showToastMessage('error', 'Lỗi', 'Không có cư dân nào sắp hết hạn để gửi thông báo')
      //   return
      // }

      this.sendNotificationToSelected()
    },

    sendNotificationToSelected() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      
      try {
        // Lọc ra các cư dân sắp hết hạn
        const expiringResidents = this.selectedResidentsForNotification.filter(resident => {
          const expiryDate = new Date(resident.expiryDate)
          const today = new Date()
          const diffTime = expiryDate - today
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
          return diffDays > 0 // Chỉ gửi cho cư dân chưa hết hạn
        })

        // Chuẩn bị dữ liệu gửi thông báo
        const notifications = expiringResidents.map(resident => {
          const expiryDate = new Date(resident.expiryDate)
          const today = new Date()
          const diffTime = expiryDate - today
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

          // Thay thế các biến trong nội dung thông báo
          let content = this.sendNotificationForm.content
          content = content.replace('{TEN_CU_DAN}', resident.name)
          content = content.replace('{BIEN_SO_XE}', resident.vehicle.licensePlate)
          content = content.replace('{NGAY_HET_HAN}', this.formatDate(resident.expiryDate))
          content = content.replace('{SO_NGAY_CON_LAI}', diffDays.toString())

          return {
            id_cu_dan: resident.id_cu_dan,
            bien_so_xe: resident.vehicle.licensePlate,
            noi_dung: content
          }
        })
        // Gọi API để gửi thông báo
        baseRequest.post("admin/thong-bao/them-du-lieu", notifications)
          .then(response => {
            if (response.data.status) {
              notificationStore.showSuccess(response.data.message)
              this.showSendNotificationModal = false
              // Cập nhật trạng thái thông báo
              expiringResidents.forEach(resident => {
                const index = this.residents.findIndex(r => 
                  r.id_cu_dan === resident.id_cu_dan && 
                  r.vehicle.licensePlate === resident.vehicle.licensePlate
                )
                if (index !== -1) {
                  this.residents[index].notificationStatus = 'sent'
                }
              })
              
              // Cập nhật thống kê
              this.statistics.notificationsSent += notifications.length
              
              // Đặt lại danh sách đã chọn
              this.selectedResidents = []
            } else {
              notificationStore.showError(response.data.message || 'Không thể gửi thông báo')
            }
          })
          .catch(error => {
            console.error('Lỗi khi gửi thông báo:', error)
            notificationStore.showError('Không thể gửi thông báo. Vui lòng thử lại sau.')
          })
          .finally(() => {
            this.isSubmitting = false
          })
      } catch (error) {
        console.error('Lỗi:', error)
        this.showToastMessage('error', 'Lỗi', 'Có lỗi xảy ra khi gửi thông báo')
        this.isSubmitting = false
      }
    },

    viewNotificationHistory(resident) {
      this.selectedResident = resident

      // Trong thực tế, bạn sẽ gọi API để lấy lịch sử thông báo
      this.notificationHistory = this.generateMockNotificationHistory(resident)

      this.showHistoryModal = true
    },

    generateMockNotificationHistory(resident) {
      const history = []
      const today = new Date()

      // Tạo 0-5 thông báo ngẫu nhiên
      const count = Math.floor(Math.random() * 6)

      for (let i = 0; i < count; i++) {
        const daysAgo = Math.floor(Math.random() * 30)
        const sentDate = new Date(today)
        sentDate.setDate(sentDate.getDate() - daysAgo)

        const status = Math.random() > 0.3 ? 'read' : 'sent'

        let readDate = null
        if (status === 'read') {
          readDate = new Date(sentDate)
          readDate.setHours(readDate.getHours() + Math.floor(Math.random() * 24))
        }

        const method = ['email', 'sms', 'app'][Math.floor(Math.random() * 3)]

        history.push({
          id: i + 1,
          title: 'Thông báo gia hạn gói gửi xe',
          content: `Kính gửi ${resident.name},\n\nGói gửi xe ${resident.package.name} của xe ${resident.vehicle.licensePlate} sẽ hết hạn vào ngày ${this.formatDate(resident.expiryDate)}.\n\nVui lòng gia hạn để tiếp tục sử dụng dịch vụ.\n\nTrân trọng,\nBan quản lý`,
          type: 'expiry',
          status: status,
          method: method,
          sentAt: sentDate,
          readAt: readDate
        })
      }

      // Sắp xếp theo thời gian gửi giảm dần
      return history.sort((a, b) => new Date(b.sentAt) - new Date(a.sentAt))
    },

    sendNotification(resident) {
      this.selectedResidents = [resident.id_cu_dan]
      this.sendBulkNotifications()
    },

    markAsRenewed(resident) {
      const index = this.residents.findIndex(r => r.id_cu_dan === resident.id_cu_dan)
      if (index !== -1) {
        // Cập nhật trạng thái
        this.residents[index].notificationStatus = 'renewed'

        // Cập nhật ngày hết hạn
        const newExpiryDate = new Date(resident.expiryDate)
        newExpiryDate.setDate(newExpiryDate.getDate() + resident.package.duration)
        this.residents[index].expiryDate = newExpiryDate

        this.showToastMessage('success', 'Cập nhật thành công', `Đã đánh dấu ${resident.name} đã gia hạn gói gửi xe`)
      }
    },

    createNotification() {
      // Kiểm tra phương thức gửi
      if (!this.notificationForm.methods.email && !this.notificationForm.methods.sms && !this.notificationForm.methods.app) {
        this.showToastMessage('error', 'Lỗi', 'Vui lòng chọn ít nhất một phương thức gửi thông báo')
        return
      }

      this.isSubmitting = true

      try {
        // Trong thực tế, bạn sẽ gọi API để tạo thông báo

        let message = ''
        if (this.notificationForm.scheduleType === 'now') {
          message = 'Đã gửi thông báo thành công'

          // Cập nhật thống kê
          this.statistics.notificationsSent += this.getTargetResidentsCount()
        } else {
          const scheduleDateTime = `${this.notificationForm.scheduleDate} ${this.notificationForm.scheduleTime}`
          message = `Đã đặt lịch gửi thông báo vào ${scheduleDateTime}`
        }

        this.showCreateNotificationModal = false
        this.showToastMessage('success', 'Thành công', message)

        // Đặt lại form
        this.resetNotificationForm()
      } catch (error) {
        this.showToastMessage('error', 'Lỗi', 'Có lỗi xảy ra khi tạo thông báo')
      } finally {
        this.isSubmitting = false
      }
    },

    resetNotificationForm() {
      const today = new Date()
      const formattedDate = today.toISOString().split('T')[0]
      const formattedTime = `${today.getHours().toString().padStart(2, '0')}:${today.getMinutes().toString().padStart(2, '0')}`

      this.notificationForm = {
        title: '',
        content: '',
        target: 'all',
        methods: {
          email: true,
          sms: true,
          app: true
        },
        scheduleType: 'now',
        scheduleDate: formattedDate,
        scheduleTime: formattedTime
      }
    },

    getTargetResidentsCount() {
      const today = new Date()

      switch (this.notificationForm.target) {
        case '7days':
          const sevenDaysLater = new Date(today)
          sevenDaysLater.setDate(sevenDaysLater.getDate() + 7)
          return this.residents.filter(resident => {
            const expiryDate = new Date(resident.expiryDate)
            return expiryDate >= today && expiryDate <= sevenDaysLater
          }).length
        case '15days':
          const fifteenDaysLater = new Date(today)
          fifteenDaysLater.setDate(fifteenDaysLater.getDate() + 15)
          return this.residents.filter(resident => {
            const expiryDate = new Date(resident.expiryDate)
            return expiryDate >= today && expiryDate <= fifteenDaysLater
          }).length
        case '30days':
          const thirtyDaysLater = new Date(today)
          thirtyDaysLater.setDate(thirtyDaysLater.getDate() + 30)
          return this.residents.filter(resident => {
            const expiryDate = new Date(resident.expiryDate)
            return expiryDate >= today && expiryDate <= thirtyDaysLater
          }).length
        case 'expired':
          return this.residents.filter(resident => new Date(resident.expiryDate) < today).length
        default:
          return this.residents.length
      }
    },

    getInitials(name) {
      return name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .substring(0, 2)
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      })
    },

    formatDateTime(date) {
      return new Date(date).toLocaleString('vi-VN', {
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

    getExpiryStatus(date) {
      const today = new Date()
      const expiryDate = new Date(date)
      const diffTime = expiryDate - today
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

      if (diffDays < 0) {
        return 'Đã hết hạn'
      } else if (diffDays === 0) {
        return 'Hết hạn hôm nay'
      } else if (diffDays <= 7) {
        return `Còn ${diffDays} ngày`
      } else if (diffDays <= 30) {
        return `Còn ${diffDays} ngày`
      } else {
        return `Còn ${diffDays} ngày`
      }
    },

    getExpiryStatusClass(date) {
      const today = new Date()
      const expiryDate = new Date(date)
      const diffTime = expiryDate - today
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))

      if (diffDays < 0) {
        return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
      } else if (diffDays <= 7) {
        return 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300'
      } else if (diffDays <= 30) {
        return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
      } else {
        return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
      }
    },

    getNotificationStatusText(status) {
      switch (status) {
        case 'not_sent':
          return 'Chưa gửi thông báo'
        case 'sent':
          return 'Đã gửi thông báo'
        case 'read':
          return 'Đã đọc thông báo'
        case 'renewed':
          return 'Đã gia hạn'
        default:
          return 'Không xác định'
      }
    },

    getNotificationStatusClass(status) {
      switch (status) {
        case 'not_sent':
          return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        case 'sent':
          return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
        case 'read':
          return 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-300'
        case 'renewed':
          return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        default:
          return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
      }
    },

    getNotificationMethodText(method) {
      switch (method) {
        case 'email':
          return 'Email'
        case 'sms':
          return 'SMS'
        case 'app':
          return 'Ứng dụng'
        default:
          return 'Không xác định'
      }
    },

    getNotificationTypeClass(type) {
      switch (type) {
        case 'expiry':
          return Clock
        case 'payment':
          return CheckCircle
        case 'system':
          return Bell
        default:
          return Bell
      }
    },

    getNotificationTypeIcon(type) {
      switch (type) {
        case 'expiry':
          return Clock
        case 'payment':
          return CheckCircle
        case 'system':
          return Bell
        default:
          return Bell
      }
    },

    closeCreateNotificationModal() {
      this.showCreateNotificationModal = false
      this.resetNotificationForm()
    },

    closeHistoryModal() {
      this.showHistoryModal = false
      this.selectedResident = null
      this.notificationHistory = []
    },

    closeSendNotificationModal() {
      this.showSendNotificationModal = false
      this.sendNotificationForm = {
        content: 'Kính gửi {TEN_CU_DAN},\n\nGói gửi xe của xe {BIEN_SO_XE} sẽ hết hạn vào ngày {NGAY_HET_HAN} (còn {SO_NGAY_CON_LAI} ngày).\n\nVui lòng gia hạn để tiếp tục sử dụng dịch vụ.\n\nTrân trọng,\nBan quản lý'
      }
    },

    showToastMessage(type, title, message) {
      this.toastType = type
      this.toastTitle = title
      this.toastMessage = message
      this.showToast = true

      // Tự động ẩn sau 3 giây
      setTimeout(() => {
        this.showToast = false
      }, 3000)
    }
  }
}
</script>