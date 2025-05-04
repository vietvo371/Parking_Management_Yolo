<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Quản lý báo cáo / sự cố</h1>
        <div class="flex items-center space-x-2">
          <div class="relative">
            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
            <input 
              type="text" 
              placeholder="Tìm kiếm báo cáo..." 
              class="pl-9 h-10 w-full sm:w-[300px] rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
              v-model="searchQuery"
            />
          </div>
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
            <Plus class="mr-2 h-4 w-4" />
            Tạo báo cáo
          </button>
        </div>
      </div>
  
      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">Lọc theo:</span>
            <select 
              v-model="filterType" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả loại</option>
              <option value="incident">Sự cố</option>
              <option value="visitor">Khách vãng lai</option>
              <option value="maintenance">Bảo trì</option>
              <option value="security">An ninh</option>
            </select>
            <select 
              v-model="filterStatus" 
              class="h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
            >
              <option value="">Tất cả trạng thái</option>
              <option value="pending">Chờ xử lý</option>
              <option value="processing">Đang xử lý</option>
              <option value="resolved">Đã giải quyết</option>
              <option value="closed">Đã đóng</option>
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
  
      <!-- Reports Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Tổng số báo cáo</p>
              <h3 class="text-2xl font-bold">{{ reportsSummary.total }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
              <ClipboardList class="h-5 w-5 text-blue-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Chờ xử lý</p>
              <h3 class="text-2xl font-bold">{{ reportsSummary.pending }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
              <Clock class="h-5 w-5 text-yellow-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đang xử lý</p>
              <h3 class="text-2xl font-bold">{{ reportsSummary.processing }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-orange-100 flex items-center justify-center">
              <Loader2 class="h-5 w-5 text-orange-600" />
            </div>
          </div>
        </div>
  
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-500">Đã giải quyết</p>
              <h3 class="text-2xl font-bold">{{ reportsSummary.resolved }}</h3>
            </div>
            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
              <CheckCircle class="h-5 w-5 text-green-600" />
            </div>
          </div>
        </div>
      </div>
  
      <!-- Reports Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tiêu đề</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Người báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ngày báo cáo</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mức độ</th>
                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thao tác</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
              <tr v-for="report in filteredReports" :key="report.id" class="hover:bg-gray-50 dark:hover:bg-gray-800">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  {{ report.id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <component :is="getReportIcon(report.type)" class="h-4 w-4 mr-2" :class="getReportIconColor(report.type)" />
                    <span>{{ report.title }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  {{ getReportTypeText(report.type) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <div class="flex items-center">
                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                      <User class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                    </div>
                    <div class="ml-3">
                      <div class="text-sm font-medium">{{ report.reporter }}</div>
                      <div class="text-xs text-gray-500">{{ report.reporterType }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ report.date }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': report.status === 'pending',
                      'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': report.status === 'processing',
                      'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': report.status === 'resolved',
                      'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300': report.status === 'closed'
                    }">
                    {{ getStatusText(report.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                    :class="{
                      'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': report.priority === 'high',
                      'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': report.priority === 'medium',
                      'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': report.priority === 'low'
                    }">
                    {{ getPriorityText(report.priority) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex justify-end space-x-2">
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400" @click="viewReport(report)">
                      <Eye class="h-4 w-4" />
                    </button>
                    <button class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400">
                      <Edit class="h-4 w-4" />
                    </button>
                    <button class="text-gray-600 hover:text-gray-900 dark:hover:text-gray-400">
                      <MoreVertical class="h-4 w-4" />
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="filteredReports.length === 0">
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                  Không tìm thấy báo cáo nào
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
                Hiển thị <span class="font-medium">1</span> đến <span class="font-medium">10</span> của <span class="font-medium">{{ reports.length }}</span> kết quả
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
                <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300">
                  ...
                </span>
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                  8
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
  
      <!-- Report Detail Modal -->
      <div v-if="selectedReport" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-3xl w-full">
          <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-lg font-medium">Chi tiết báo cáo</h3>
            <button @click="selectedReport = null" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
              <X class="h-5 w-5" />
            </button>
          </div>
          <div class="p-4">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <div class="flex items-center">
                  <component :is="getReportIcon(selectedReport.type)" class="h-5 w-5 mr-2" :class="getReportIconColor(selectedReport.type)" />
                  <h2 class="text-xl font-bold">{{ selectedReport.title }}</h2>
                </div>
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" 
                  :class="{
                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedReport.status === 'pending',
                    'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300': selectedReport.status === 'processing',
                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedReport.status === 'resolved',
                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300': selectedReport.status === 'closed'
                  }">
                  {{ getStatusText(selectedReport.status) }}
                </span>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">ID báo cáo</p>
                  <p class="font-medium">{{ selectedReport.id }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Loại báo cáo</p>
                  <p class="font-medium">{{ getReportTypeText(selectedReport.type) }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Người báo cáo</p>
                  <p class="font-medium">{{ selectedReport.reporter }} ({{ selectedReport.reporterType }})</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Ngày báo cáo</p>
                  <p class="font-medium">{{ selectedReport.date }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Mức độ ưu tiên</p>
                  <p class="font-medium" :class="{
                    'text-red-600': selectedReport.priority === 'high',
                    'text-orange-600': selectedReport.priority === 'medium',
                    'text-blue-600': selectedReport.priority === 'low'
                  }">{{ getPriorityText(selectedReport.priority) }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Người xử lý</p>
                  <p class="font-medium">{{ selectedReport.assignee || 'Chưa phân công' }}</p>
                </div>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2">Mô tả</h4>
                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ selectedReport.description }}</p>
              </div>
              
              <div v-if="selectedReport.images && selectedReport.images.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2">Hình ảnh</h4>
                <div class="grid grid-cols-3 gap-2">
                  <div v-for="(image, index) in selectedReport.images" :key="index" class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                    <img :src="image" alt="Report image" class="w-full h-full object-cover" />
                  </div>
                </div>
              </div>
              
              <div v-if="selectedReport.comments && selectedReport.comments.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2">Bình luận</h4>
                <div class="space-y-4">
                  <div v-for="(comment, index) in selectedReport.comments" :key="index" class="bg-gray-50 dark:bg-gray-900 p-3 rounded-md">
                    <div class="flex justify-between items-start">
                      <div class="flex items-center">
                        <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                          <User class="h-4 w-4 text-gray-500 dark:text-gray-400" />
                        </div>
                        <div class="ml-3">
                          <div class="text-sm font-medium">{{ comment.user }}</div>
                          <div class="text-xs text-gray-500">{{ comment.time }}</div>
                        </div>
                      </div>
                    </div>
                    <p class="text-sm mt-2">{{ comment.text }}</p>
                  </div>
                </div>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                <h4 class="font-medium mb-2">Thêm bình luận</h4>
                <textarea 
                  class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  rows="3"
                  placeholder="Nhập bình luận của bạn..."
                ></textarea>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 flex justify-between">
                <div class="flex space-x-2">
                  <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">
                    Phân công
                  </button>
                  <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">
                    Thay đổi trạng thái
                  </button>
                </div>
                <div class="flex space-x-2">
                  <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                    Lưu bình luận
                  </button>
                  <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                    Đánh dấu đã giải quyết
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref, computed } from 'vue'
  import { 
    Search, 
    Plus, 
    Filter, 
    Calendar, 
    ClipboardList, 
    Clock, 
    Loader2, 
    CheckCircle, 
    User, 
    Eye, 
    Edit, 
    MoreVertical,
    ChevronLeft,
    ChevronRight,
    X,
    AlertTriangle,
    Car,
    Shield,
    // Tool
  } from 'lucide-vue-next'
  
  export default {
    name: 'IncidentReports',
    components: {
      Search,
      Plus,
      Filter,
      Calendar,
      ClipboardList,
      Clock,
      Loader2,
      CheckCircle,
      User,
      Eye,
      Edit,
      MoreVertical,
      ChevronLeft,
      ChevronRight,
      X,
      AlertTriangle,
      Car,
      Shield,
      // Tool
    },
    setup() {
      const searchQuery = ref('')
      const filterType = ref('')
      const filterStatus = ref('')
      const startDate = ref('')
      const endDate = ref('')
      const selectedReport = ref(null)
      
      // Reports summary
      const reportsSummary = ref({
        total: 45,
        pending: 12,
        processing: 18,
        resolved: 15
      })
      
      // Sample reports data
      const reports = ref([
        {
          id: 'REP-001',
          title: 'Xe đỗ sai vị trí',
          type: 'incident',
          reporter: 'Nguyễn Văn A',
          reporterType: 'Cư dân',
          date: '24/04/2023',
          status: 'pending',
          priority: 'medium',
          description: 'Có một chiếc xe ô tô biển số 30A-12345 đỗ sai vị trí tại khu vực A, gây cản trở lối đi.',
          assignee: null,
          images: [
            '/placeholder.svg?height=200&width=300&text=Image 1',
            '/placeholder.svg?height=200&width=300&text=Image 2'
          ],
          comments: [
            {
              user: 'Nguyễn Văn A',
              time: '24/04/2023 - 10:25',
              text: 'Tôi đã báo cáo sự việc này. Xe vẫn đang đỗ sai vị trí.'
            }
          ]
        },
        {
          id: 'REP-002',
          title: 'Khách vãng lai không đăng ký',
          type: 'visitor',
          reporter: 'Trần Thị B',
          reporterType: 'Nhân viên',
          date: '23/04/2023',
          status: 'processing',
          priority: 'low',
          description: 'Có một khách vãng lai đã vào bãi đỗ xe mà không đăng ký với bảo vệ. Biển số xe là 30F-54321.',
          assignee: 'Lê Văn C',
          images: [],
          comments: [
            {
              user: 'Trần Thị B',
              time: '23/04/2023 - 15:30',
              text: 'Tôi đã phát hiện xe này vào lúc 15:20.'
            },
            {
              user: 'Lê Văn C',
              time: '23/04/2023 - 15:45',
              text: 'Tôi đang kiểm tra camera an ninh để xác minh thông tin.'
            }
          ]
        },
        {
          id: 'REP-003',
          title: 'Hệ thống barrier không hoạt động',
          type: 'maintenance',
          reporter: 'Phạm Thị D',
          reporterType: 'Nhân viên',
          date: '22/04/2023',
          status: 'resolved',
          priority: 'high',
          description: 'Barrier tại cổng vào không hoạt động, gây ùn tắc và phải mở cổng thủ công.',
          assignee: 'Hoàng Văn E',
          images: [
            '/placeholder.svg?height=200&width=300&text=Image 1'
          ],
          comments: [
            {
              user: 'Phạm Thị D',
              time: '22/04/2023 - 08:15',
              text: 'Barrier bị lỗi từ 8:00 sáng nay.'
            },
            {
              user: 'Hoàng Văn E',
              time: '22/04/2023 - 09:30',
              text: 'Đã kiểm tra và phát hiện lỗi cảm biến. Đang tiến hành sửa chữa.'
            },
            {
              user: 'Hoàng Văn E',
              time: '22/04/2023 - 11:45',
              text: 'Đã sửa chữa xong và kiểm tra hoạt động bình thường.'
            }
          ]
        },
        {
          id: 'REP-004',
          title: 'Phát hiện người lạ trong bãi đỗ xe',
          type: 'security',
          reporter: 'Vũ Thị F',
          reporterType: 'Cư dân',
          date: '21/04/2023',
          status: 'closed',
          priority: 'high',
          description: 'Tôi phát hiện một người lạ đang đi loanh quanh và nhìn ngó các xe trong bãi đỗ khu vực B.',
          assignee: 'Đặng Văn G',
          images: [],
          comments: [
            {
              user: 'Vũ Thị F',
              time: '21/04/2023 - 20:15',
              text: 'Người này mặc áo đen, quần jean, đeo khẩu trang.'
            },
            {
              user: 'Đặng Văn G',
              time: '21/04/2023 - 20:25',
              text: 'Đã cử nhân viên an ninh kiểm tra khu vực.'
            },
            {
              user: 'Đặng Văn G',
              time: '21/04/2023 - 20:40',
              text: 'Đã xác minh đây là khách của cư dân căn hộ A1105. Không có vấn đề gì.'
            }
          ]
        },
        {
          id: 'REP-005',
          title: 'Xe bị xước',
          type: 'incident',
          reporter: 'Bùi Thị H',
          reporterType: 'Cư dân',
          date: '20/04/2023',
          status: 'processing',
          priority: 'medium',
          description: 'Xe của tôi bị xước ở cửa bên phải khi đỗ tại vị trí B-C08. Tôi muốn xem camera an ninh để xác định nguyên nhân.',
          assignee: 'Ngô Văn I',
          images: [
            '/placeholder.svg?height=200&width=300&text=Image 1',
            '/placeholder.svg?height=200&width=300&text=Image 2',
            '/placeholder.svg?height=200&width=300&text=Image 3'
          ],
          comments: [
            {
              user: 'Bùi Thị H',
              time: '20/04/2023 - 18:30',
              text: 'Tôi đỗ xe lúc 8:00 sáng và phát hiện vết xước lúc 18:00.'
            },
            {
              user: 'Ngô Văn I',
              time: '20/04/2023 - 19:15',
              text: 'Đã nhận báo cáo và đang kiểm tra camera an ninh.'
            }
          ]
        },
        {
          id: 'REP-006',
          title: 'Khách vãng lai không thanh toán phí',
          type: 'visitor',
          reporter: 'Mai Thị K',
          reporterType: 'Nhân viên',
          date: '19/04/2023',
          status: 'resolved',
          priority: 'low',
          description: 'Khách vãng lai biển số 30A-24680 đã rời đi mà không thanh toán phí gửi xe.',
          assignee: 'Lê Văn C',
          images: [
            '/placeholder.svg?height=200&width=300&text=Image 1'
          ],
          comments: [
            {
              user: 'Mai Thị K',
              time: '19/04/2023 - 16:45',
              text: 'Xe rời đi lúc 16:30, đã ghi lại biển số.'
            },
            {
              user: 'Lê Văn C',
              time: '19/04/2023 - 17:30',
              text: 'Đã xác minh thông tin và liên hệ với chủ xe.'
            },
            {
              user: 'Lê Văn C',
              time: '20/04/2023 - 09:15',
              text: 'Chủ xe đã quay lại thanh toán phí. Vấn đề đã được giải quyết.'
            }
          ]
        },
        {
          id: 'REP-007',
          title: 'Đèn chiếu sáng hỏng',
          type: 'maintenance',
          reporter: 'Đỗ Văn L',
          reporterType: 'Nhân viên',
          date: '18/04/2023',
          status: 'pending',
          priority: 'medium',
          description: 'Đèn chiếu sáng tại khu vực C bị hỏng, gây khó khăn cho việc đỗ xe vào buổi tối.',
          assignee: null,
          images: [
            '/placeholder.svg?height=200&width=300&text=Image 1'
          ],
          comments: []
        },
        {
          id: 'REP-008',
          title: 'Camera an ninh bị mờ',
          type: 'maintenance',
          reporter: 'Hoàng Văn M',
          reporterType: 'Nhân viên',
          date: '17/04/2023',
          status: 'processing',
          priority: 'medium',
          description: 'Camera an ninh số 3 tại khu vực A bị mờ, cần vệ sinh hoặc thay thế.',
          assignee: 'Hoàng Văn E',
          images: [],
          comments: [
            {
              user: 'Hoàng Văn E',
              time: '17/04/2023 - 14:30',
              text: 'Đã kiểm tra và lên kế hoạch vệ sinh camera vào ngày mai.'
            }
          ]
        },
        {
          id: 'REP-009',
          title: 'Phát hiện rò rỉ dầu',
          type: 'incident',
          reporter: 'Trần Văn N',
          reporterType: 'Cư dân',
          date: '16/04/2023',
          status: 'resolved',
          priority: 'high',
          description: 'Có vết dầu rò rỉ tại vị trí đỗ xe A-C10, có thể gây nguy hiểm cho người đi bộ.',
          assignee: 'Phạm Thị D',
          images: [
            '/placeholder.svg?height=200&width=300&text=Image 1'
          ],
          comments: [
            {
              user: 'Trần Văn N',
              time: '16/04/2023 - 09:45',
              text: 'Vết dầu khá lớn, cần xử lý ngay.'
            },
            {
              user: 'Phạm Thị D',
              time: '16/04/2023 - 10:15',
              text: 'Đã cử nhân viên vệ sinh đến xử lý.'
            },
            {
              user: 'Phạm Thị D',
              time: '16/04/2023 - 11:00',
              text: 'Đã vệ sinh xong và rắc cát để đảm bảo an toàn.'
            }
          ]
        },
        {
          id: 'REP-010',
          title: 'Cần bổ sung biển báo',
          type: 'maintenance',
          reporter: 'Lý Thị O',
          reporterType: 'Cư dân',
          date: '15/04/2023',
          status: 'pending',
          priority: 'low',
          description: 'Khu vực B thiếu biển báo chỉ dẫn lối ra, gây khó khăn cho khách vãng lai.',
          assignee: null,
          images: [],
          comments: [
            {
              user: 'Lý Thị O',
              time: '15/04/2023 - 16:20',
              text: 'Tôi thấy nhiều khách vãng lai đi lòng vòng tìm lối ra.'
            }
          ]
        }
      ])
      
      // Filter reports based on search query and filters
      const filteredReports = computed(() => {
        return reports.value.filter(report => {
          const matchesSearch = 
            report.id.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            report.reporter.toLowerCase().includes(searchQuery.value.toLowerCase())
          
          const matchesType = filterType.value ? report.type === filterType.value : true
          const matchesStatus = filterStatus.value ? report.status === filterStatus.value : true
          
          // Date filtering would be implemented here in a real app
          
          return matchesSearch && matchesType && matchesStatus
        })
      })
      
      // Get report icon
      function getReportIcon(type) {
        switch (type) {
          case 'incident':
            return AlertTriangle
          case 'visitor':
            return Car
          // case 'maintenance':
          //   return Tool
          case 'security':
            return Shield
          default:
            return ClipboardList
        }
      }
      
      // Get report icon color
      function getReportIconColor(type) {
        switch (type) {
          case 'incident':
            return 'text-orange-600'
          case 'visitor':
            return 'text-blue-600'
          case 'maintenance':
            return 'text-yellow-600'
          case 'security':
            return 'text-red-600'
          default:
            return 'text-gray-600'
        }
      }
      
      // Get report type text
      function getReportTypeText(type) {
        switch (type) {
          case 'incident':
            return 'Sự cố'
          case 'visitor':
            return 'Khách vãng lai'
          case 'maintenance':
            return 'Bảo trì'
          case 'security':
            return 'An ninh'
          default:
            return type
        }
      }
      
      // Get status text
      function getStatusText(status) {
        switch (status) {
          case 'pending':
            return 'Chờ xử lý'
          case 'processing':
            return 'Đang xử lý'
          case 'resolved':
            return 'Đã giải quyết'
          case 'closed':
            return 'Đã đóng'
          default:
            return status
        }
      }
      
      // Get priority text
      function getPriorityText(priority) {
        switch (priority) {
          case 'high':
            return 'Cao'
          case 'medium':
            return 'Trung bình'
          case 'low':
            return 'Thấp'
          default:
            return priority
        }
      }
      
      // View report details
      function viewReport(report) {
        selectedReport.value = { ...report }
      }
      
      return {
        searchQuery,
        filterType,
        filterStatus,
        startDate,
        endDate,
        selectedReport,
        reportsSummary,
        reports,
        filteredReports,
        getReportIcon,
        getReportIconColor,
        getReportTypeText,
        getStatusText,
        getPriorityText,
        viewReport
      }
    }
  }
  </script>