<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <router-link to="/admin/quan-ly-camera" class="mr-4">
          <button class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
            <ArrowLeft class="h-4 w-4" />
          </button>
        </router-link>
        <h1 class="text-2xl font-bold tracking-tight">Thêm Camera mới</h1>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-medium">Thông tin Camera</h2>
        <p class="text-sm text-gray-500">Nhập thông tin chi tiết về Camera mới</p>
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
            <form @submit.prevent="createCamera" class="space-y-6">

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="brand" class="block text-sm font-medium ">Bãi xe</label>
                  <a-select class="w-100" v-model:value="create_camera.id_bai_xe" show-search placeholder="Chọn bãi xe"
                    style="width: 100%" :options="optionsBaiXe" :filter-option="filterOptionBaiXe()" />
                </div>
                <div class="space-y-2">
                  <label for="brand" class="block text-sm font-medium ">Vị trí</label>
                  <a-select class="w-100" v-model:value="create_camera.id_vi_tri" show-search placeholder="Chọn vị trí"
                    style="width: 100%" :options="optionsViTri" :filter-option="filterOptionViTri()" />
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
                  {{ isSubmitting ? "Đang lưu..." : "Lưu camera mới" }}
                </button>
              </div>

              <div v-if="isSuccess" class="bg-green-100 text-green-800 p-3 rounded-md flex items-center">
                <Check class="h-4 w-4 mr-2" />
                Camera đã được thêm thành công!
              </div>
            </form>

          </div>

          <div v-if="activeTab === 'apartment'" class="space-y-4">
            <div class="flex justify-between mb-4">
              <h3 class="text-lg font-medium">Danh sách bãi xe</h3>
              <button @click="openBaiXeModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                <span class="mr-2">+</span> Thêm bãi xe
              </button>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Tên bãi xe
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Sức chứa ô tô
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Sức chứa khác
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Trạng thái
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Ngày tạo
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Hành động
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                <tr v-for="bai_xe in bai_xes" :key="bai_xe.id">
                  <td class="px-6 py-4 whitespace-nowrap"> {{ bai_xe.ten_bai }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ bai_xe.suc_chua_o_to }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ bai_xe.suc_chua_khac }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                      :class="bai_xe.trang_thai == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                      {{ bai_xe.trang_thai == 1 ? 'Đang sử dụng' : 'Trống' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(bai_xe.created_at) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                      <button @click="openUpdateBaiXeModal(bai_xe)"
                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">
                        Chỉnh sửa
                      </button>
                      <a-popconfirm title="Bạn có chắc chắn muốn xóa bãi xe này không?" ok-text="Có" cancel-text="Không"
                        @confirm="xoaBaiXe(bai_xe.id)">
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
            <div class="flex justify-between mb-4">
              <h3 class="text-lg font-medium">Danh sách vị trí</h3>
              <button @click="openViTriModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                <span class="mr-2">+</span> Thêm vị trí
              </button>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    STT
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Vị trí
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Ngày tạo
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
                <tr v-for="(vi_tri, index) in vi_tris" :key="vi_tri.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ index + 1 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ vi_tri.vi_tri_dat }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(vi_tri.created_at) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                      :class="vi_tri.trang_thai == 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                      {{ vi_tri.trang_thai == 1 ? 'Đang sử dụng' : 'Tắt' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                      <button @click="openUpdateViTriModal(vi_tri)"
                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">
                        Chỉnh sửa
                      </button>
                      <a-popconfirm title="Bạn có chắc chắn muốn xóa vị trí này không?" ok-text="Có" cancel-text="Không"
                        @confirm="xoaViTri(vi_tri.id)">
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
        </div>


      </div>
      <div class="p-6 border-t border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">
          Lưu ý: Thông tin cư dân sẽ được kiểm tra và xác nhận trước khi được kích hoạt trong hệ thống.
        </p>
      </div>
    </div>

    <!-- Modal Thêm Bãi Xe/ -->
    <a-modal v-model:open="showCameraModal" title="Thêm Bãi xe">
      <template #footer>
        <a-button key="back" @click="showCameraModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="themBaiXe">Thêm bãi xe</a-button>
      </template>
      <hr class="my-4">
      <div class="space-y-4">
        <div class="space-y-2">
          <label for="ten_bai" class="block text-sm font-medium">Tên Bãi</label>
          <input v-model="create_bai_xe.ten_bai" id="ten_bai" placeholder="Bãi xe A" type="text"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
        <div class="space-y-2">
          <label for="suc_chua_o_to" class="block text-sm font-medium">Sức chứa ô tô</label>
          <input v-model="create_bai_xe.suc_chua_o_to" id="suc_chua_o_to" placeholder="100" type="number"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
        <div class="space-y-2">
          <label for="suc_chua_khac" class="block text-sm font-medium">Sức chứa khác</label>
          <input v-model="create_bai_xe.suc_chua_khac" id="suc_chua_khac" placeholder="100" type="number"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
      </div>
    </a-modal>

    <!-- Modal Cập nhật bãi xe -->
    <a-modal v-model:open="showUpdateCameraModal" title="Cập nhật bãi xe">
      <template #footer>
        <a-button key="back" @click="showUpdateCameraModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="capNhatBaiXe">Cập nhật bãi xe</a-button>
      </template>
      <hr class="my-4">
      <div class="space-y-4">
        <div class="space-y-2">
          <label for="ten_bai" class="block text-sm font-medium">Tên Bãi</label>
          <input v-model="update_bai_xe.ten_bai" id="ten_bai" placeholder="Bãi xe A" type="text"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
        <div class="space-y-2">
          <label for="suc_chua_o_to" class="block text-sm font-medium">Sức chứa ô tô</label>
          <input v-model="update_bai_xe.suc_chua_o_to" id="suc_chua_o_to" placeholder="100" type="number"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
        <div class="space-y-2">
          <label for="suc_chua_khac" class="block text-sm font-medium">Sức chứa khác</label>
          <input v-model="update_bai_xe.suc_chua_khac" id="suc_chua_khac" placeholder="100" type="number"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
      </div>
    </a-modal>

    <!-- Modal Thêm Vị trí/ -->
    <a-modal v-model:open="showViTriModal" title="Thêm Vị trí">
      <template #footer>
        <a-button key="back" @click="showViTriModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="themViTri">Thêm vị trí</a-button>
      </template>
      <hr class="my-4">
      <div class="space-y-4">
        <div class="space-y-2">
          <label for="vi_tri_dat" class="block text-sm font-medium">Vị trí</label>
          <input v-model="create_vi_tri.vi_tri_dat" id="vi_tri_dat" placeholder="Vị trí A" type="text"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
      </div>
    </a-modal>

    <!-- Modal Cập nhật vị trí -->
    <a-modal v-model:open="showUpdateViTriModal" title="Cập nhật vị trí">
      <template #footer>
        <a-button key="back" @click="showUpdateViTriModal = false">Hủy</a-button>
        <a-button key="submit" type="primary" :loading="isSubmitting" @click="capNhatViTri">Cập nhật vị trí</a-button>
      </template>
      <hr class="my-4">
      <div class="space-y-4">
        <div class="space-y-2">
          <label for="vi_tri_dat" class="block text-sm font-medium">Vị trí</label>
          <input v-model="update_vi_tri.vi_tri_dat" id="vi_tri_dat" placeholder="Vị trí A" type="text"
            class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
      </div>
    </a-modal>

  </div>
</template>

<script>
import { ref } from 'vue'
import { ArrowLeft, User, Camera, Upload, Check } from 'lucide-vue-next'
import { useNotificationStore } from '../../stores/notication'
import baseRequest from '../../core/baseRequest'
import { Popconfirm, Modal, Button } from 'ant-design-vue';

export default {
  name: 'AddResident',
  components: {
    ArrowLeft,
    User,
    Camera,
    Upload,
    Check,
    Popconfirm,
    Modal,
    Button
  },
  data() {
    return {
      activeTab: 'personal',
      profileImage: null,
      isSubmitting: false,
      isSuccess: false,
      create_camera: {
        id_vi_tri: '',
        id_bai_xe: ''
      },
      update_camera: {
        id_vi_tri: '',
        id_bai_xe: ''
      },
      create_bai_xe: {
        ten_bai: "",
        suc_chua_o_to: "",
        suc_chua_khac: "",
      },
      update_bai_xe: {
        ten_bai: "",
        suc_chua_o_to: "",
        suc_chua_khac: "",
      },
      delete_bai_xe: {
      },
      create_vi_tri: {
        vi_tri_dat: "",
      },
      update_vi_tri: {
        vi_tri_dat: "",
      },
      delete_vi_tri: {
      },

      tabs: [
        { label: 'Thông tin Camera', value: 'personal' },
        { label: 'Thông tin bãi xe', value: 'apartment' },
        { label: 'Thông tin vị trí', value: 'documents' }
      ],
      showCameraModal: false,
      showUpdateCameraModal: false,
      showViTriModal: false,
      showUpdateViTriModal: false,
      editingCamera: false,
      currentCamera: {
        building: 'A',
        floor: 1,
        number: '',
        owner: '',
        status: 'active'
      },
      cameras: [],
      bai_xes: [],
      vi_tris: [],

    }
  },
  mounted() {
    this.getCamera()
    this.getBaiXe()
    this.getViTri()
  },
  computed: {
    optionsBaiXe() {
      return this.bai_xes.map(bai_xe => ({
        label: bai_xe.ten_bai,
        value: bai_xe.id,
      }));
    },
    optionsViTri() {
      return this.vi_tris.map(vi_tri => ({
        label: vi_tri.vi_tri_dat,
        value: vi_tri.id,
      }));
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('vi-VN')
    },
    filterOptionBaiXe() {
      return (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
      };
    },
    filterOptionViTri(){
      return (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
      };
    },
    getCamera() {
      baseRequest.get("admin/cam-giam-soat/lay-du-lieu").then((response) => {
        this.cameras = response.data.data
      })
    },
    createCamera() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();

      baseRequest.post("admin/cam-giam-soat/them-du-lieu", this.create_camera)
        .then((response) => {
          if (response.data.status) {
            this.getCamera()
            this.showCameraModal = false
            this.create_camera = {}
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Thêm camera thất bại')
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
    capNhatCamera() {
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/cam-giam-soat/thong-tin-cap-nhat", this.update_camera).then((response) => {
        if (response.data.status) {
          this.getCamera()
          notificationStore.showSuccess(response.data.message)
        } else {
          notificationStore.showError('Cập nhật camera thất bại')
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
    xoaCamera(id) {
      baseRequest.delete("admin/cam-giam-soat/thong-tin-xoa/" + id)
        .then((response) => {
          const notificationStore = useNotificationStore();
          if (response.data.status) {
            this.getCamera()
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Xóa camera thất bại')
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false

        });
    },
    getBaiXe() {
      baseRequest.get("admin/bai-xe/lay-du-lieu").then((response) => {
        this.bai_xes = response.data.data
      })
    },
    themBaiXe() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/bai-xe/them-du-lieu", this.create_bai_xe)
        .then((response) => {
          if (response.data.status) {
            this.getBaiXe()
            this.create_bai_xe = {
            }
            notificationStore.showSuccess(response.data.message)
            this.closeCameraModal()
          } else {
            notificationStore.showError('Thêm bãi xe thất bại')
          }
          this.isSubmitting = false
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    capNhatBaiXe() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/bai-xe/thong-tin-cap-nhat", this.update_bai_xe).then((response) => {
        if (response.data.status) {
          this.getBaiXe()
          notificationStore.showSuccess(response.data.message)
          this.closeCameraModal()
        } else {
          notificationStore.showError('Cập nhật bãi xe thất bại')
        }
        this.isSubmitting = false
      })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    xoaBaiXe(id) {
      baseRequest.delete("admin/bai-xe/thong-tin-xoa/" + id)
        .then((response) => {
          const notificationStore = useNotificationStore();
          if (response.data.status) {
            this.getBaiXe()
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Xóa bãi xe thất bại')
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    getViTri() {
      baseRequest.get("admin/vi-tri-dat/lay-du-lieu").then((response) => {
        this.vi_tris = response.data.data
      })
    },
    themViTri() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/vi-tri-dat/them-du-lieu", this.create_vi_tri)
        .then((response) => {
          if (response.data.status) {
            this.getViTri()
            this.create_vi_tri = {
            }
            notificationStore.showSuccess(response.data.message)
            this.closeCameraModal()
          } else {
            notificationStore.showError('Thêm vị trí thất bại')
          }
          this.isSubmitting = false
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    capNhatViTri() {
      this.isSubmitting = true
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/vi-tri-dat/thong-tin-cap-nhat", this.update_vi_tri).then((response) => {
        if (response.data.status) {
          this.getViTri()
          notificationStore.showSuccess(response.data.message)
          this.closeCameraModal()
        } else {
          notificationStore.showError('Cập nhật vị trí thất bại')
        }
        this.isSubmitting = false
      })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    xoaViTri(id) {
      baseRequest.delete("admin/vi-tri-dat/thong-tin-xoa/" + id)
        .then((response) => {
          const notificationStore = useNotificationStore();
          if (response.data.status) {
            this.getViTri()
            notificationStore.showSuccess(response.data.message)
          } else {
            notificationStore.showError('Xóa vị trí thất bại')
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },

    openBaiXeModal(camera) {
      this.showCameraModal = true
    },
    openUpdateBaiXeModal(bai_xe) {
      Object.assign(this.update_bai_xe, bai_xe)
      this.showUpdateCameraModal = true
    },
    openViTriModal(camera) {
      this.showViTriModal = true
    },
    openUpdateViTriModal(vi_tri) {
      Object.assign(this.update_vi_tri, vi_tri)
      this.showUpdateViTriModal = true
    },

    closeCameraModal() {
      this.showCameraModal = false
      this.showUpdateCameraModal = false
      this.showViTriModal = false
      this.showUpdateViTriModal = false
    },
    saveCamera() {
      this.closeCameraModal()
    },
  }
}
</script>

<style>
/* Custom styles can be added here if needed */
</style>