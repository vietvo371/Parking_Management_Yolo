<template>
    <div class="space-y-6">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold tracking-tight">Thông tin cá nhân</h1>
        <div class="flex items-center space-x-2">
          <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center" @click="isEditing = true" v-if="!isEditing">
            <Edit class="mr-2 h-4 w-4" />
            Chỉnh sửa
          </button>
        </div>
      </div>
  
      <!-- Profile Summary -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
          <div class="flex flex-col items-center">
            <div class="relative">
              <div class="h-24 w-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                <img v-if="userProfile.avatar" :src="userProfile.avatar" alt="Avatar" class="h-full w-full object-cover" />
                <User v-else class="h-12 w-12 text-gray-500 dark:text-gray-400" />
              </div>
              <button v-if="isEditing" class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-blue-600 text-white flex items-center justify-center">
                <Camera class="h-4 w-4" />
              </button>
            </div>
            <h2 class="mt-4 text-xl font-bold">{{ userProfile.fullName }}</h2>
            <p class="text-sm text-gray-500">{{ userProfile.email }}</p>
            
            <div class="mt-2 space-y-1">
              <div class="px-3 py-1 text-xs rounded-full"
                   :class="userProfile.approved === 1 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'">
                {{ getApprovalText(userProfile.approved) }}
              </div>
              <div class="px-3 py-1 text-xs rounded-full"
                   :class="userProfile.status === 1 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'">
                {{ getStatusText(userProfile.status) }}
              </div>
            </div>
            
            <div class="mt-4 w-full border-t border-gray-200 dark:border-gray-700 pt-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500">Căn hộ</span>
                <span class="font-medium">{{ userProfile.apartment }}</span>
              </div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500">Số dư</span>
                <span class="font-medium">{{ formatCurrency(userProfile.balance) }}</span>
              </div>
              <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-500">Biển số xe</span>
                <span class="font-medium">{{ userProfile.vehiclePlate || 'Chưa đăng ký xe' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-sm text-gray-500">Loại xe</span>
                <span class="font-medium">{{ userProfile.vehicleType || 'Chưa đăng ký xe' }}</span>
              </div>
              <div class="mt-3 flex justify-center">
                <template v-if="!userProfile.ngay_het_han">
                  <router-link class="w-1/2" to="/user/thanh-toan-online">
                    <button class="w-full px-3 py-1.5 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 text-sm">
                      Chưa đăng ký tháng
                    </button>
                  </router-link>
                </template>
                <template v-else-if="isExpired">
                  <router-link class="w-1/2" to="/user/thanh-toan-online">
                    <button class="w-full px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm">
                      Đã hết hạn - Đăng ký tháng
                    </button>
                  </router-link>
                </template>
                <template v-else>
                  <router-link class="w-1/2" to="/user/thanh-toan-online">
                    <button class="w-full px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                      Nạp tiền
                    </button>
                  </router-link>
                </template>
              </div>
            </div>
          </div>
        </div>
  
        <div class="md:col-span-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
          <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Chỉnh sửa thông tin' : 'Thông tin chi tiết' }}</h3>
          
          <form v-if="isEditing" @submit.prevent="saveProfile">
            <div class="space-y-4">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label for="fullName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Họ và tên</label>
                  <input 
                    id="fullName" 
                    type="text" 
                    v-model="editedProfile.fullName"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  />
                </div>
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                  <input 
                    id="email" 
                    type="email" 
                    v-model="editedProfile.email"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  />
                </div>
                <div>
                  <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số điện thoại</label>
                  <input 
                    id="phone" 
                    type="text" 
                    v-model="editedProfile.phone"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  />
                </div>
                <div>
                  <label for="idNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số CMND/CCCD</label>
                  <input 
                    id="idNumber" 
                    type="text" 
                    v-model="editedProfile.idNumber"
                    class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  />
                </div>
              </div>
              
              <div>
                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Địa chỉ</label>
                <input 
                  id="address" 
                  type="text" 
                  v-model="editedProfile.address"
                  class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                />
              </div>
              
              <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <h4 class="text-md font-medium mb-2">Đổi mật khẩu</h4>
                <div class="space-y-4">
                  <div>
                    <label for="currentPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu hiện tại</label>
                    <div class="relative">
                      <input 
                        id="currentPassword" 
                        :type="showPassword.current ? 'text' : 'password'" 
                        v-model="passwordChange.current"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      />
                      <button 
                        type="button" 
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        @click="togglePasswordVisibility('current')"
                      >
                        <Eye v-if="showPassword.current" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                      </button>
                    </div>
                  </div>
                  
                  <div>
                    <label for="newPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mật khẩu mới</label>
                    <div class="relative">
                      <input 
                        id="newPassword" 
                        :type="showPassword.new ? 'text' : 'password'" 
                        v-model="passwordChange.new"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      />
                      <button 
                        type="button" 
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        @click="togglePasswordVisibility('new')"
                      >
                        <Eye v-if="showPassword.new" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                      </button>
                    </div>
                  </div>
                  
                  <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Xác nhận mật khẩu mới</label>
                    <div class="relative">
                      <input 
                        id="confirmPassword" 
                        :type="showPassword.confirm ? 'text' : 'password'" 
                        v-model="passwordChange.confirm"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                      />
                      <button 
                        type="button" 
                        class="absolute right-3 top-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                        @click="togglePasswordVisibility('confirm')"
                      >
                        <Eye v-if="showPassword.confirm" class="h-5 w-5" />
                        <EyeOff v-else class="h-5 w-5" />
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="flex justify-end space-x-2 pt-4">
                <button 
                  type="button" 
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                  @click="cancelEdit"
                >
                  Hủy
                </button>
                <button 
                  type="submit" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                  Lưu thay đổi
                </button>
              </div>
            </div>
          </form>
          
          <div v-else class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">Họ và tên</p>
                <p class="font-medium">{{ userProfile.fullName }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Email</p>
                <p class="font-medium">{{ userProfile.email }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Số điện thoại</p>
                <p class="font-medium">{{ userProfile.phone }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Số CMND/CCCD</p>
                <p class="font-medium">{{ userProfile.idNumber }}</p>
              </div>
              <div>
                <p class="text-sm text-gray-500">Địa chỉ</p>
                <p class="font-medium">{{ userProfile.address }}</p>
              </div>
            </div>
            
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
              <h4 class="text-md font-medium mb-2">Thông tin bổ sung</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500">Số xe đã đăng ký</p>
                  <p class="font-medium">{{ userProfile.registeredVehicles }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500">Loại cư dân</p>
                  <p class="font-medium">{{ userProfile.residentType }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Account Activity -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-medium">Hoạt động tài khoản gần đây</h3>
        </div>
        <div class="p-4">
          <div class="space-y-4">
            <div v-for="(activity, index) in accountActivities" :key="index" class="flex items-start space-x-3">
              <div :class="`h-8 w-8 rounded-full flex items-center justify-center ${activity.iconBg}`">
                <component :is="activity.icon" class="h-4 w-4" :class="activity.iconColor" />
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium">{{ activity.title }}</p>
                <p class="text-xs text-gray-500">{{ activity.time }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Transaction History -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-medium flex items-center">
            <Receipt class="h-5 w-5 mr-2 text-blue-600" />
            Lịch sử giao dịch
          </h3>
        </div>
        <div class="p-4">
          <div v-if="historyTransaction && historyTransaction.length > 0" class="space-y-4">
            <div v-for="(transaction, index) in historyTransaction" :key="index" 
                 class="border rounded-lg p-4 border-gray-200 dark:border-gray-700">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Biển số xe</p>
                  <p class="font-medium">{{ transaction.bien_so_xe }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Ngày hết hạn</p>
                  <p class="font-medium">{{ transaction.ngay_het_han ? formatDate(transaction.ngay_het_han) : 'Chưa cập nhật' }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Mã giao dịch</p>
                  <p class="font-medium">#{{ transaction.ma_giao_dich }}</p>
                </div>
              </div>
            </div>
          </div>
          <div v-else class="text-center py-8">
            <Receipt class="h-12 w-12 mx-auto text-gray-400 mb-4" />
            <p class="text-gray-500">Chưa có giao dịch nào</p>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import { 
    User, 
    Edit, 
    Camera, 
    Eye, 
    EyeOff, 
    LogIn, 
    Settings, 
    Key, 
    AlertTriangle,
    Receipt
  } from 'lucide-vue-next'
  import baseRequestUser from '../../core/baseRequestUser'
  export default {
    name: 'ResidentProfile',
    components: {
      User,
      Edit,
      Camera,
      Eye,
      EyeOff,
      LogIn,
      Settings,
      Key,
      AlertTriangle,
      Receipt
    },
    data() {
      return {
        isEditing: false,
        userProfile: {
          residentId: 'RES-001',
          fullName: 'Nguyễn Văn A',
          email: 'nguyenvana@example.com',
          phone: '0912345678',
          idNumber: '012345678901',
          address: 'Số 123, Đường ABC, Quận XYZ, Hà Nội',
          apartment: 'A1201',
          registrationDate: '15/01/2023',
          status: 'approved',
          avatar: null,
          registeredVehicles: 2,
          residentType: 'Chủ hộ',
          ngay_het_han: null
        },
        editedProfile: {},
        passwordChange: {
          current: '',
          new: '',
          confirm: ''
        },
        showPassword: {
          current: false,
          new: false,
          confirm: false
        },
        accountActivities: [],
        historyTransaction: []
      }
    },
    computed: {
      isExpired() {
        if (!this.userProfile.ngay_het_han || this.userProfile.ngay_het_han === null) return false;
        
        // Chuyển đổi ngày hết hạn sang định dạng YYYY-MM-DD để so sánh
        const expiryDate = new Date(this.userProfile.ngay_het_han);
        const now = new Date();
        
        // Reset thời gian về 00:00:00 để chỉ so sánh ngày
        expiryDate.setHours(0, 0, 0, 0);
        now.setHours(0, 0, 0, 0);
        
        // Thêm console.log để debug
        console.log('Expiry Date:', expiryDate);
        console.log('Current Date:', now);
        console.log('Is Expired:', now > expiryDate);
        
        // Trả về true nếu ngày hiện tại LỚN HƠN ngày hết hạn
        return now > expiryDate;
      }
    },
    mounted() {
      this.editedProfile = { ...this.userProfile }
      this.getUserProfile()
    },
    methods: {
      getStatusText(status) {
        if (status === 1) return 'Hoạt động'
        if (status === 0) return 'Tạm khóa'
        return 'Không xác định'
      },
      getApprovalText(approved) {
        if (approved === 1) return 'Đã phê duyệt'
        if (approved === 0) return 'Chờ phê duyệt'
        return 'Không xác định'
      },
      async getUserProfile() {
        const res = await baseRequestUser.get('user/profile')
        const data = res.data.data
        this.historyTransaction = res.data.lich_su_thanh_toan
        this.userProfile = {
          fullName: data.ho_va_ten || '',
          email: data.email || '',
          phone: data.so_dien_thoai || '',
          idNumber: data.so_cccd || '',
          address: `${data.ten_toa_nha || ''} - Căn ${data.so_can_ho || ''}`,
          apartment: `${data.ten_toa_nha || ''} - Căn ${data.so_can_ho || ''}`,
          balance: data.so_du ?? 0,
          status: data.trang_thai,
          approved: data.phe_duyet,
          avatar: null,
          registeredVehicles: data.bien_so_xe ? 1 : 0,
          residentType: data.ten_loai_xe || 'Chưa xác định',
          vehiclePlate: data.bien_so_xe || '',
          vehicleType: data.ten_loai_xe || '',
          registrationDate: data.created_at ? new Date(data.created_at).toLocaleDateString('vi-VN') : '',
          ngay_het_han: data.ngay_het_han || null
        }
        this.editedProfile = { ...this.userProfile }

        // Map lịch sử đăng nhập
        if (res.data.lich_su_login && Array.isArray(res.data.lich_su_login)) {
          this.accountActivities = res.data.lich_su_login.map(item => ({
            title: 'Đăng nhập thành công',
            time: item.created_at ? new Date(item.created_at).toLocaleString('vi-VN') : '',
            icon: this.$options.components.LogIn,
            iconBg: 'bg-green-100',
            iconColor: 'text-green-600'
          }))
        } else {
          this.accountActivities = []
        }
      },
      togglePasswordVisibility(field) {
        this.showPassword[field] = !this.showPassword[field]
      },
      cancelEdit() {
        this.editedProfile = { ...this.userProfile }
        this.passwordChange = { current: '', new: '', confirm: '' }
        this.isEditing = false
      },
      saveProfile() {
        this.userProfile = { ...this.editedProfile }
        // Password change logic (if needed)
        if (
          this.passwordChange.current &&
          this.passwordChange.new &&
          this.passwordChange.confirm &&
          this.passwordChange.new === this.passwordChange.confirm
        ) {
          // Password change logic would go here
          // For demo: just clear fields
          this.passwordChange = { current: '', new: '', confirm: '' }
        }
        this.isEditing = false
      },
      formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', {
          style: 'currency',
          currency: 'VND'
        }).format(amount)
      },
      formatDate(date) {
        const formattedDate = new Date(date).toLocaleDateString('vi-VN')
        return formattedDate
      }
    }
  }
  </script>