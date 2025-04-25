<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <router-link to="/admin/quan-ly-khach-hang" class="mr-4">
            <button class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
              <ArrowLeft class="h-4 w-4" />
            </button>
          </router-link>
          <h1 class="text-2xl font-bold tracking-tight">Thêm cư dân mới</h1>
        </div>
      </div>
  
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-lg font-medium">Thông tin cư dân</h2>
          <p class="text-sm text-gray-500">Nhập thông tin chi tiết về cư dân mới</p>
        </div>
        <div class="p-6">
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
              <div class="flex space-x-2 overflow-x-auto mb-4">
                <button 
                  v-for="tab in tabs" 
                  :key="tab.value"
                  type="button"
                  @click="activeTab = tab.value"
                  class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap"
                  :class="activeTab === tab.value ? 'bg-gray-100 dark:bg-gray-700' : ''"
                >
                  {{ tab.label }}
                </button>
              </div>
  
              <div v-if="activeTab === 'personal'" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="full-name" class="block text-sm font-medium">Họ và tên</label>
                    <input 
                      v-model="resident.name"
                      id="full-name" 
                      placeholder="Nguyễn Văn A" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                      required 
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="id-number" class="block text-sm font-medium">Số CMND/CCCD</label>
                    <input 
                      v-model="resident.idNumber"
                      id="id-number" 
                      placeholder="012345678901" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                      required 
                    />
                  </div>
                </div>
  
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="phone" class="block text-sm font-medium">Số điện thoại</label>
                    <input 
                      v-model="resident.phone"
                      id="phone" 
                      placeholder="0901234567" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                      required 
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input 
                      v-model="resident.email"
                      id="email" 
                      placeholder="example@email.com" 
                      type="email"
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                </div>
  
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="dob" class="block text-sm font-medium">Ngày sinh</label>
                    <input 
                      v-model="resident.dob"
                      id="dob" 
                      type="date" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="gender" class="block text-sm font-medium">Giới tính</label>
                    <select 
                      v-model="resident.gender"
                      id="gender" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    >
                      <option value="male">Nam</option>
                      <option value="female">Nữ</option>
                      <option value="other">Khác</option>
                    </select>
                  </div>
                </div>
              </div>
  
              <div v-if="activeTab === 'apartment'" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="building" class="block text-sm font-medium">Tòa nhà</label>
                    <select 
                      v-model="resident.building"
                      id="building" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    >
                      <option value="A">Tòa A</option>
                      <option value="B">Tòa B</option>
                      <option value="C">Tòa C</option>
                    </select>
                  </div>
                  <div class="space-y-2">
                    <label for="floor" class="block text-sm font-medium">Tầng</label>
                    <select 
                      v-model="resident.floor"
                      id="floor" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    >
                      <option v-for="n in 20" :key="n" :value="n">Tầng {{ n }}</option>
                    </select>
                  </div>
                </div>
  
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="apartment-number" class="block text-sm font-medium">Căn hộ</label>
                    <input 
                      v-model="resident.apartmentNumber"
                      id="apartment-number" 
                      placeholder="101" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                      required 
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="resident-type" class="block text-sm font-medium">Loại cư dân</label>
                    <select 
                      v-model="resident.type"
                      id="resident-type" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    >
                      <option value="owner">Chủ hộ</option>
                      <option value="family">Thành viên gia đình</option>
                      <option value="tenant">Người thuê</option>
                    </select>
                  </div>
                </div>
  
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                  <div class="space-y-2">
                    <label for="move-in-date" class="block text-sm font-medium">Ngày chuyển vào</label>
                    <input 
                      v-model="resident.moveInDate"
                      id="move-in-date" 
                      type="date" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                      required 
                    />
                  </div>
                  <div class="space-y-2">
                    <label for="status" class="block text-sm font-medium">Trạng thái</label>
                    <select 
                      v-model="resident.status"
                      id="status" 
                      class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    >
                      <option value="active">Hoạt động</option>
                      <option value="inactive">Không hoạt động</option>
                    </select>
                  </div>
                </div>
              </div>
  
              <div v-if="activeTab === 'documents'" class="space-y-4">
                <div class="space-y-4">
                  <label class="block text-sm font-medium">Ảnh đại diện</label>
                  <div class="flex items-center space-x-4">
                    <div class="relative h-24 w-24 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-700">
                      <div v-if="profileImage" class="w-full h-full">
                        <img :src="profileImage" alt="Ảnh đại diện" class="w-full h-full object-cover" />
                      </div>
                      <div v-else class="flex items-center justify-center h-full">
                        <User class="h-12 w-12 text-gray-300" />
                      </div>
                    </div>
                    <div class="flex space-x-2">
                      <button 
                        type="button" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center"
                        @click="handleProfileCapture"
                      >
                        <Camera class="mr-2 h-4 w-4" />
                        Chụp ảnh
                      </button>
                      <button 
                        type="button" 
                        class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center"
                      >
                        <Upload class="mr-2 h-4 w-4" />
                        Tải lên
                      </button>
                    </div>
                  </div>
                </div>
  
                <div class="space-y-2">
                  <label for="id-card-front" class="block text-sm font-medium">CMND/CCCD mặt trước</label>
                  <input 
                    id="id-card-front" 
                    type="file" 
                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  />
                </div>
  
                <div class="space-y-2">
                  <label for="id-card-back" class="block text-sm font-medium">CMND/CCCD mặt sau</label>
                  <input 
                    id="id-card-back" 
                    type="file" 
                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  />
                </div>
  
                <div class="space-y-2">
                  <label for="contract" class="block text-sm font-medium">Hợp đồng thuê/mua (nếu có)</label>
                  <input 
                    id="contract" 
                    type="file" 
                    class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  />
                </div>
              </div>
            </div>
  
            <div class="flex justify-end space-x-2">
              <router-link to="/residents">
                <button 
                  type="button"
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md"
                >
                  Hủy
                </button>
              </router-link>
              <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                :disabled="isSubmitting"
              >
                {{ isSubmitting ? "Đang lưu..." : "Lưu cư dân mới" }}
              </button>
            </div>
  
            <div v-if="isSuccess" class="bg-green-100 text-green-800 p-3 rounded-md flex items-center">
              <Check class="h-4 w-4 mr-2" />
              Cư dân đã được thêm thành công!
            </div>
          </form>
        </div>
        <div class="p-6 border-t border-gray-200 dark:border-gray-700">
          <p class="text-sm text-gray-500">
            Lưu ý: Thông tin cư dân sẽ được kiểm tra và xác nhận trước khi được kích hoạt trong hệ thống.
          </p>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { ref } from 'vue'
  import { useRouter } from 'vue-router'
  import { ArrowLeft, User, Camera, Upload, Check } from 'lucide-vue-next'
  import { useResidentsStore } from '@/stores/residents'
  
  export default {
    name: 'AddResident',
    components: {
      ArrowLeft,
      User,
      Camera,
      Upload,
      Check
    },
    setup() {
      const router = useRouter()
      const residentsStore = useResidentsStore()
  
      const activeTab = ref('personal')
      const profileImage = ref(null)
      const isSubmitting = ref(false)
      const isSuccess = ref(false)
  
      const resident = ref({
        name: '',
        idNumber: '',
        phone: '',
        email: '',
        dob: '',
        gender: 'male',
        building: 'A',
        floor: 1,
        apartmentNumber: '',
        type: 'owner',
        moveInDate: '',
        status: 'active',
        vehicles: 0
      })
  
      const tabs = [
        { value: 'personal', label: 'Thông tin cá nhân' },
        { value: 'apartment', label: 'Thông tin căn hộ' },
        { value: 'documents', label: 'Tài liệu & Hình ảnh' }
      ]
  
      const handleProfileCapture = () => {
        // Mô phỏng chụp ảnh
        profileImage.value = 'https://via.placeholder.com/200x200?text=Profile'
      }
  
      const handleSubmit = () => {
        isSubmitting.value = true
  
        // Tạo apartment từ building, floor và apartmentNumber
        const apartment = `${resident.value.building}-${resident.value.apartmentNumber}`
  
        // Thêm cư dân mới vào store
        setTimeout(() => {
          residentsStore.addResident({
            ...resident.value,
            apartment,
            profileImage: profileImage.value
          })
  
          isSubmitting.value = false
          isSuccess.value = true
  
          // Ẩn thông báo thành công sau 3 giây
          setTimeout(() => {
            isSuccess.value = false
            // Chuyển hướng về trang danh sách cư dân
            router.push('/residents')
          }, 3000)
        }, 1500)
      }
  
      return {
        activeTab,
        profileImage,
        isSubmitting,
        isSuccess,
        resident,
        tabs,
        handleProfileCapture,
        handleSubmit
      }
    }
  }
  </script>
  
  <style>
  /* Custom styles can be added here if needed */
  </style>