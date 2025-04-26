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
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
          <div class="flex space-x-2 overflow-x-auto mb-4">
            <button v-for="tab in tabs" :key="tab.value" type="button" @click="activeTab = tab.value"
              class="px-4 py-2 text-sm font-medium rounded-md whitespace-nowrap"
              :class="activeTab === tab.value ? 'bg-gray-100 dark:bg-gray-700' : ''">
              {{ tab.label }}
            </button>
          </div>

          <div v-if="activeTab === 'personal'" class="space-y-4">
            <form @submit.prevent="themCuDan" class="space-y-6">

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="full-name" class="block text-sm font-medium">Họ và tên</label>
                  <input v-model="create_cu_da.ho_va_ten" id="full-name" placeholder="Nguyễn Văn A"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    required />
                </div>
                <div class="space-y-2">
                  <label for="id-number" class="block text-sm font-medium">Số CMND/CCCD</label>
                  <input v-model="create_cu_da.so_cccd" id="id-number" placeholder="012345678901"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    required />
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="phone" class="block text-sm font-medium">Số điện thoại</label>
                  <input v-model="create_cu_da.so_dien_thoai" id="phone" placeholder="0901234567"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    required />
                </div>
                <div class="space-y-2">
                  <label for="email" class="block text-sm font-medium">Email</label>
                  <input v-model="create_cu_da.email" id="email" placeholder="example@email.com" type="email"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="gender" class="block text-sm font-medium">Căn hộ</label>
                  <select v-model="create_cu_da.id_can_ho" id="gender"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md">
                    <option value="">Chọn căn hộ</option>
                    <option v-for="apartment in apartments" :key="apartment.id" :value="apartment.id">
                      Toà {{ apartment.ten_toa_nha }} - Tầng {{ apartment.tang }} - Căn hộ {{ apartment.so_can_ho }}
                    </option> 
                  </select>
                </div>
              </div>
              <div class="flex justify-end space-x-2 mt-4">
                <router-link to="/admin/quan-ly-khach-hang">
                  <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
                    Hủy
                  </button>
                </router-link>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  :disabled="isSubmitting">
                  {{ isSubmitting ? "Đang lưu..." : "Lưu cư dân mới" }}
                </button>
              </div>
  
              <div v-if="isSuccess" class="bg-green-100 text-green-800 p-3 rounded-md flex items-center">
                <Check class="h-4 w-4 mr-2" />
                Cư dân đã được thêm thành công!
              </div>
            </form>

          </div>

          <div v-if="activeTab === 'apartment'" class="space-y-4">
            <div class="flex justify-between mb-4">
              <h3 class="text-lg font-medium">Danh sách căn hộ</h3>
              <button @click="openApartmentModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                <span class="mr-2">+</span> Thêm căn hộ
              </button>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Tòa nhà
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Tầng
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Số căn hộ
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Chủ hộ
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Trạng thái
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Hành động
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="apartment in apartments" :key="apartment.id">
                  <td class="px-6 py-4 whitespace-nowrap"> Toà {{ apartment.ten_toa_nha }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ apartment.tang }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ apartment.so_can_ho }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ apartment.chu_ho }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                      :class="apartment.status !== 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                      {{ apartment.status !== 'active' ? 'Đang sử dụng' : 'Trống' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                      <button @click="openUpdateApartmentModal(apartment)"
                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">
                        Chỉnh sửa
                      </button>
                      <a-popconfirm title="Bạn có chắc chắn muốn xóa căn hộ này không?" ok-text="Có" cancel-text="Không"
                        @confirm="xoaCanHo(apartment.id)">
                        <button
                          class="px-3 py-1 text-sm border border-red-300 text-red-600 dark:border-red-600 rounded-md">
                          Xóa
                        </button>
                      </a-popconfirm>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
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
                  <button type="button"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center"
                    @click="handleProfileCapture">
                    <Camera class="mr-2 h-4 w-4" />
                    Chụp ảnh
                  </button>
                  <button type="button"
                    class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center">
                    <Upload class="mr-2 h-4 w-4" />
                    Tải lên
                  </button>
                </div>
              </div>
            </div>

            <div class="space-y-2">
              <label for="id-card-front" class="block text-sm font-medium">CMND/CCCD mặt trước</label>
              <input id="id-card-front" type="file"
                class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>

            <div class="space-y-2">
              <label for="id-card-back" class="block text-sm font-medium">CMND/CCCD mặt sau</label>
              <input id="id-card-back" type="file"
                class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>

            <div class="space-y-2">
              <label for="contract" class="block text-sm font-medium">Hợp đồng thuê/mua (nếu có)</label>
              <input id="contract" type="file"
                class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>
          </div>
        </div>


      </div>
      <div class="p-6 border-t border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">
          Lưu ý: Thông tin cư dân sẽ được kiểm tra và xác nhận trước khi được kích hoạt trong hệ thống.
        </p>
      </div>
    </div>

    <!-- Modal Thêm/ -->
    <div v-if="showApartmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Thêm căn hộ</h3>
          <button @click="closeApartmentModal" class="text-gray-500 hover:text-gray-700">
            <span class="text-xl">&times;</span>
          </button>
        </div>

        <form @submit.prevent="createCanHo" class="space-y-4">
          <div class="space-y-2">
            <label for="apartment-building" class="block text-sm font-medium">Tòa nhà</label>
            <select v-model="create_can_ho.ten_toa_nha" id="apartment-building"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              required>
              <option value="A">Tòa A</option>
              <option value="B">Tòa B</option>
              <option value="C">Tòa C</option>
              <option value="D">Tòa D</option>
              <option value="E">Tòa E</option>
              <option value="F">Tòa F</option>
              <option value="G">Tòa G</option>
              <option value="H">Tòa H</option>
              <option value="I">Tòa I</option>
              <option value="J">Tòa J</option>

            </select>
          </div>

          <div class="space-y-2">
            <label for="apartment-floor" class="block text-sm font-medium">Tầng</label>
            <input v-model.number="create_can_ho.tang" id="apartment-floor" type="number" min="1" max="50"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              required />
          </div>

          <div class="space-y-2">
            <label for="apartment-number" class="block text-sm font-medium">Số căn hộ</label>
            <input v-model="create_can_ho.so_can_ho" id="apartment-number" placeholder="101"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              required />
          </div>

          <div class="space-y-2">
            <label for="apartment-owner" class="block text-sm font-medium">Chủ hộ</label>
            <input v-model="create_can_ho.chu_ho" id="apartment-owner" placeholder="Nguyễn Văn A"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
          </div>
          <div class="flex justify-end space-x-2 pt-4">
            <button type="button" @click="closeApartmentModal"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
              Hủy
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="isSubmitting">
              {{ isSubmitting ? "Đang lưu..." : "Lưu căn hộ mới" }}
            </button>
          </div>
        </form>
      </div>
    </div>
    <!-- Cập nhật căn hộ -->
    <div v-if="showUpdateApartmentModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-medium">Cập nhập căn hộ</h3>
          <button @click="closeApartmentModal" class="text-gray-500 hover:text-gray-700">
            <span class="text-xl">&times;</span>
          </button>
        </div>

        <form @submit.prevent="capNhatCanHo" class="space-y-4">
          <div class="space-y-2">
            <label for="apartment-building" class="block text-sm font-medium">Tòa nhà</label>
            <select v-model="update_can_ho.ten_toa_nha" id="apartment-building"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              required>
              <option value="A">Tòa A</option>
              <option value="B">Tòa B</option>
              <option value="C">Tòa C</option>
              <option value="D">Tòa D</option>
              <option value="E">Tòa E</option>
              <option value="F">Tòa F</option>
              <option value="G">Tòa G</option>
              <option value="H">Tòa H</option>
              <option value="I">Tòa I</option>
              <option value="J">Tòa J</option>

            </select>
          </div>

          <div class="space-y-2">
            <label for="apartment-floor" class="block text-sm font-medium">Tầng</label>
            <input v-model.number="update_can_ho.tang" id="apartment-floor" type="number" min="1" max="50"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              required />
          </div>

          <div class="space-y-2">
            <label for="apartment-number" class="block text-sm font-medium">Số căn hộ</label>
            <input v-model="update_can_ho.so_can_ho" id="apartment-number" placeholder="101"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
              required />
          </div>

          <div class="space-y-2">
            <label for="apartment-owner" class="block text-sm font-medium">Chủ hộ</label>
            <input v-model="update_can_ho.chu_ho" id="apartment-owner" placeholder="Nguyễn Văn A"
              class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
          </div>
          <div class="flex justify-end space-x-2 pt-4">
            <button type="button" @click="closeApartmentModal"
              class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
              Hủy
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              :disabled="isSubmitting">
              {{ isSubmitting ? "Đang lưu..." : "Lưu căn hộ mới" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import { ArrowLeft, User, Camera, Upload, Check } from 'lucide-vue-next'
import { useNotificationStore } from '../stores/notication'
import baseRequest from '../core/baseRequest'
import { Popconfirm } from 'ant-design-vue';
export default {
  name: 'AddResident',
  components: {
    ArrowLeft,
    User,
    Camera,
    Upload,
    Check,
    Popconfirm
  },
  data() {
    return {
      activeTab: 'personal',
      profileImage: null,
      isSubmitting: false,
      isSuccess: false,
      create_cu_da: {
        ho_va_ten: '',
        email: '',
        password: '',
        so_dien_thoai: '',
        so_cccd: '',
        id_can_ho: '',
        so_du: '',
      },
      create_can_ho: {
        tang: '',
        so_can_ho: '',
        chu_ho: ''
      },
      update_can_ho: {
        tang: '',
        so_can_ho: '',
        chu_ho: ''
      },
      delete_can_ho: {
      },
      tabs: [
        { label: 'Thông tin cá nhân', value: 'personal' },
        { label: 'Thông tin căn hộ', value: 'apartment' },
        { label: 'Tài liệu', value: 'documents' }
      ],
      showApartmentModal: false,
      showUpdateApartmentModal: false,
      editingApartment: false,
      currentApartment: {
        building: 'A',
        floor: 1,
        number: '',
        owner: '',
        status: 'active'
      },
      apartments: [],
      cu_dans: []
    }
  },
  mounted() {
    this.getCanHo()
    this.getCuDan()
  },
  methods: {
    getCanHo() {
      baseRequest.get("admin/can-ho/lay-du-lieu").then((response) => {
        this.apartments = response.data.data
      })
    },
    createCanHo() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();

      baseRequest.post("admin/can-ho/them-du-lieu", this.create_can_ho)
        .then((response) => {
          if (response.data.status) {
            this.getCanHo()
            this.create_can_ho = {}
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Thêm căn hộ thất bại')
          }
          this.isSubmitting = false
          this.closeApartmentModal()

        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false

        });
    },
    capNhatCanHo() {
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/can-ho/thong-tin-cap-nhat", this.update_can_ho).then((response) => {
        if (response.data.status) {
          this.getCanHo()
          notificationStore.showSuccess(response.data.message)
        } else {
          notificationStore.showError('Cập nhật căn hộ thất bại')
        }
        this.isSubmitting = false
        this.closeApartmentModal()
      })
      .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false

        });
    },
    xoaCanHo(id) {
      baseRequest.delete("admin/can-ho/thong-tin-xoa/" + id)
        .then((response) => {
          const notificationStore = useNotificationStore();
          if (response.data.status) {
            this.getCanHo()
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Xóa căn hộ thất bại')
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false

        });
    },
    getCuDan() {
      baseRequest.get("admin/cu-dan/lay-du-lieu").then((response) => {
        this.cu_dans = response.data.data
      })
    },
    themCuDan() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/cu-dan/them-du-lieu", this.create_cu_da)
        .then((response) => {
          if (response.data.status) {
            this.getCuDan()
            this.create_cu_da = {
            }
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Thêm cư dân thất bại')
          }
          this.isSubmitting = false
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    openApartmentModal(apartment) {
      this.showApartmentModal = true
    },
    openUpdateApartmentModal(apartment) {
      Object.assign(this.update_can_ho, apartment)
      this.showUpdateApartmentModal = true
    },

    closeApartmentModal() {
      this.showApartmentModal = false
      this.showUpdateApartmentModal = false
    },
    saveApartment() {

      this.closeApartmentModal()
    },
  }
}
</script>

<style>
/* Custom styles can be added here if needed */
</style>