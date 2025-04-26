<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center">
        <router-link to="/admin/quan-ly-xe" class="mr-4">
          <button class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
            <ArrowLeft class="h-4 w-4" />
          </button>
        </router-link>
        <h1 class="text-2xl font-bold tracking-tight">Thêm xe mới</h1>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-medium">Thông tin xe</h2>
        <p class="text-sm text-gray-500">Nhập thông tin chi tiết về xe và chủ xe</p>
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
          <form @submit.prevent="themXe" class="space-y-6">

            <div v-if="activeTab === 'info'" class="space-y-4">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="license-plate" class="block text-sm font-medium">Biển số xe</label>
                  <input v-model="create_xe.bien_so_xe" id="license-plate" placeholder="51F-12345"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    required />
                </div>
                <div class="space-y-2">
                  <label for="vehicle-type" class="block text-sm font-medium">Loại xe</label>
                  <select v-model="create_xe.id_loai_xe" id="vehicle-type"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md">
                    <option value="">Chọn loại xe</option>
                    <option v-for="loai_xe in loai_xe" :key="loai_xe.id" :value="loai_xe.id">
                      {{ loai_xe.ten_loai_xe }}
                    </option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="brand" class="block text-sm font-medium">Cư dân</label>
                  <a-select v-model:value="create_xe.id_cu_dan" show-search placeholder="Chọn cư dân"
                    style="width: 100%" :options="options" :filter-option="filterOption()" />
                </div>
              </div>
              <div class="flex justify-end space-x-2">
                <router-link to="/admin/quan-ly-xe">
                  <button type="button" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md">
                    Hủy
                  </button>
                </router-link>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                  :disabled="isSubmitting">
                  {{ isSubmitting ? "Đang lưu..." : "Lưu xe mới" }}
                </button>
              </div>

              <div v-if="isSuccess" class="bg-green-100 text-green-800 p-3 rounded-md flex items-center">
                <Check class="h-4 w-4 mr-2" />
                Xe đã được thêm thành công!
              </div>
            </div>

          </form>


          <div v-if="activeTab === 'type'" class="space-y-4">
            <div class="flex justify-between mb-4">
              <h3 class="text-lg font-medium"></h3>
              <button @click="showModal"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                <span class="mr-2">+</span> Thêm loại xe
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
                    Tên loại xe
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Tiền thu giữ xe
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
                <tr v-for="(loai_xe, index) in loai_xe" :key="loai_xe.id">
                  <td class="px-6 py-4 whitespace-nowrap"> {{ index + 1 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap"> {{ loai_xe.ten_loai_xe }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ convertMoney(loai_xe.tien_thu_giu_xe) }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-medium rounded-full"
                      :class="loai_xe.trang_thai !== 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                      {{ loai_xe.trang_thai !== 'active' ? 'Đang sử dụng' : 'Trống' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex space-x-2">
                      <button @click="openUpdateLoaiXeModal(loai_xe)"
                        class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">
                        Chỉnh sửa
                      </button>
                      <a-popconfirm title="Bạn có chắc chắn muốn xóa loại xe này không?" ok-text="Có"
                        cancel-text="Không" @confirm="xoaLoaiXe(loai_xe.id)">
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

            <!-- modall thêm loại xe -->
            <a-modal v-model:open="open" title="Thêm loại xe" @ok="handleOk">
              <template #footer>
                <a-button key="back" @click="open = false">Hủy</a-button>
                <a-button key="submit" type="primary" :loading="isSubmitting" @click="themLoaiXe">Submit</a-button>
              </template>
              <hr class="my-4">
              <div class="space-y-4">
                <div class="space-y-2">
                  <label for="ten_loai_xe" class="block text-sm font-medium">Tên loại xe</label>
                  <input v-model="create_loai_xe.ten_loai_xe" id="ten_loai_xe" placeholder="Toyota"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
                </div>
                <div class="space-y-2">
                  <label for="tien_thu_giu_xe" class="block text-sm font-medium">Tiền thu giữ xe</label>
                  <input v-model="create_loai_xe.tien_thu_giu_xe" id="tien_thu_giu_xe" placeholder="100000"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
                </div>
              </div>
            </a-modal>

            <!-- modall cập nhật loại xe -->
            <a-modal v-model:open="open_update_loai_xe" title="Cập nhật loại xe" @ok="handleOk">
              <template #footer>
                <a-button key="back" @click="open_update_loai_xe = false">Hủy</a-button>
                <a-button key="submit" type="primary" :loading="isSubmitting" @click="capNhatLoaiXe">Cập nhật</a-button>
              </template>
              <hr class="my-4">
              <div class="space-y-4">
                <div class="space-y-2">
                  <label for="ten_loai_xe" class="block text-sm font-medium">Tên loại xe</label>
                  <input v-model="update_loai_xe.ten_loai_xe" id="ten_loai_xe" placeholder="Toyota"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
                </div>
                <div class="space-y-2">
                  <label for="tien_thu_giu_xe" class="block text-sm font-medium">Tiền thu giữ xe</label>
                  <input v-model="update_loai_xe.tien_thu_giu_xe" id="tien_thu_giu_xe" placeholder="100000"
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
                </div>
              </div>
            </a-modal>
          </div>

          <div v-if="activeTab === 'images'" class="space-y-4">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
              <div class="space-y-4">
                <label class="block text-sm font-medium">Ảnh biển số xe</label>
                <div class="border rounded-md p-4 space-y-4">
                  <div class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                    <div v-if="licensePlateImage" class="w-full h-full">
                      <img :src="licensePlateImage" alt="Biển số xe" class="w-full h-full object-contain" />
                    </div>
                    <div v-else class="flex items-center justify-center h-full">
                      <Car class="h-16 w-16 text-gray-300" />
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <button type="button"
                      class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center"
                      @click="handleLicensePlateCapture">
                      <Camera class="mr-2 h-4 w-4" />
                      Chụp ảnh
                    </button>
                    <button type="button"
                      class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center">
                      <Upload class="mr-2 h-4 w-4" />
                      Tải lên
                    </button>
                  </div>
                </div>
              </div>

              <div class="space-y-4">
                <label class="block text-sm font-medium">Ảnh xe</label>
                <div class="border rounded-md p-4 space-y-4">
                  <div class="relative aspect-video bg-gray-100 dark:bg-gray-700 rounded-md overflow-hidden">
                    <div v-if="vehicleImage" class="w-full h-full">
                      <img :src="vehicleImage" alt="Ảnh xe" class="w-full h-full object-contain" />
                    </div>
                    <div v-else class="flex items-center justify-center h-full">
                      <Car class="h-16 w-16 text-gray-300" />
                    </div>
                  </div>
                  <div class="flex space-x-2">
                    <button type="button"
                      class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center"
                      @click="handleVehicleCapture">
                      <Camera class="mr-2 h-4 w-4" />
                      Chụp ảnh
                    </button>
                    <button type="button"
                      class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center">
                      <Upload class="mr-2 h-4 w-4" />
                      Tải lên
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>
      <div class="p-6 border-t border-gray-200 dark:border-gray-700">
        <p class="text-sm text-gray-500">
          Lưu ý: Thông tin xe sẽ được kiểm tra và xác nhận trước khi được kích hoạt trong hệ thống.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { ArrowLeft, Car, Camera, Upload, Check } from "lucide-vue-next";
import baseRequest from "../core/baseRequest";
import { useNotificationStore } from "../stores/notication";
import { Modal, Button } from "ant-design-vue";

export default {
  name: "AddVehicle",
  components: {
    ArrowLeft,
    Car,
    Camera,
    Upload,
    Check,
  },
  data() {
    return {
      vehicle: {
      },
      loai_xe: [],
      create_loai_xe: {
        ten_loai_xe: "",
        trang_thai: "",
        tien_thu_giu_xe: "",
      },
      update_loai_xe: {
        ten_loai_xe: "",
        trang_thai: "",
        tien_thu_giu_xe: "",
      },
      delete_loai_xe: {
      },
      create_xe: {
        bien_so_xe: "",
        id_cu_dan: "",
        id_loai_xe: "",
      },
      update_xe: {
        bien_so_xe: "",
        id_cu_dan: "",
        id_loai_xe: "",
      },
      delete_xe: {
      },
      danh_sach_xe: [],
      danh_sach_cudan: [],
      isSubmitting: false,
      isSuccess: false,
      activeTab: 'info',
      tabs: [
        { label: "Thông tin xe", value: "info" },
        { label: "Loại xe", value: "type" },
        { label: "Ảnh xe", value: "images" },
      ],
      open: false,
      open_update_loai_xe: false,
    };
  },
  mounted() {
    this.getLoaiXe();
    this.getDanhSachCudan();
    this.getDanhSachXe();
  },
  computed: {
    options() {
      return this.danh_sach_cudan.map(cudan => ({
        label: cudan.ho_va_ten + " - Toà " + cudan.ten_toa_nha + " - Phòng " + cudan.so_can_ho,
        value: cudan.id,
      }));
    }
  },
  methods: {
    filterOption() {
      return (input, option) => {
        return option.label.toLowerCase().indexOf(input.toLowerCase()) >= 0;
      };
    },
    convertMoney(money) {
      return money.toLocaleString('vi-VN', { style: 'currency', currency: 'VND' });
    },
    getLoaiXe() {
      baseRequest.get("admin/loai-xe/lay-du-lieu").then((res) => {
        this.loai_xe = res.data.data;
      });
    },
    themLoaiXe() {
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/loai-xe/them-du-lieu", this.create_loai_xe)
        .then((res) => {
          if (res.data.status) {
            this.create_loai_xe = {
              ten_loai_xe: "",
              trang_thai: "",
              tien_thu_giu_xe: "",
            }
            this.getLoaiXe();
            notificationStore.showSuccess(res.data.message);
          }
          else {
            notificationStore.showError(res.data.message);
          }
          this.open = false;
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
          this.isSubmitting = false
        });
    },
    xoaLoaiXe(id) {
      const notificationStore = useNotificationStore();
      baseRequest.delete(`admin/loai-xe/thong-tin-xoa/${id}`).then((res) => {
        if (res.data.status) {
          this.getLoaiXe();
          notificationStore.showSuccess(res.data.message);
        }
        else {
          notificationStore.showError(res.data.message);
        }
      })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
        });
    },
    showModal() {
      this.open = true;
    },
    openUpdateLoaiXeModal(loai_xe) {
      Object.assign(this.update_loai_xe, loai_xe);
      this.open_update_loai_xe = true;
    },
    capNhatLoaiXe() {
      const notificationStore = useNotificationStore();
      baseRequest.post('admin/loai-xe/thong-tin-cap-nhat', this.update_loai_xe)
        .then((res) => {
          if (res.data.status) {
            this.getLoaiXe();
            notificationStore.showSuccess(res.data.message);
          }
          else {
            notificationStore.showError(res.data.message);
          }
          this.open_update_loai_xe = false;
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
        });
    },
    getDanhSachXe() {
      baseRequest.get("admin/xe/lay-du-lieu").then((res) => {
        this.danh_sach_xe = res.data.data;
      });
    },
    getDanhSachCudan() {
      baseRequest.get("admin/cu-dan/lay-du-lieu").then((res) => {
        this.danh_sach_cudan = res.data.data;
      });
    },
    themXe() {
      const notificationStore = useNotificationStore();
      baseRequest.post("admin/xe/them-du-lieu", this.create_xe)
        .then((res) => {
          if (res.data.status) {
            this.create_xe = {}
            this.getDanhSachXe();
            notificationStore.showSuccess(res.data.message);
          }
          else {
            notificationStore.showError(res.data.message);
          }
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
        });
    },
  }

};
</script>

<style></style>
