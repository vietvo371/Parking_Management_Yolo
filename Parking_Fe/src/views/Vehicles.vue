<template>
  <div class="space-y-3">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Quản lý xe</h1>
    </div>

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div class="flex w-full sm:w-auto items-center gap-2">
        <div class="relative w-full sm:w-auto">
          <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-500 dark:text-gray-400" />
          <input v-model="searchQuery" type="search" placeholder="Tìm kiếm biển số..."
            class="pl-9 w-full sm:w-[250px] h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
        </div>
        <button class="p-2 rounded-md border border-gray-300 dark:border-gray-600">
          <Filter class="h-4 w-4" />
        </button>
      </div>
      <router-link to="/admin/quan-ly-xe/them-xe">
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
          <Plus class="mr-2 h-4 w-4" />
          Thêm xe mới
        </button>
      </router-link>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="p-4 border-b border-gray-200 dark:border-gray-700">
        <!-- <h2 class="text-lg font-medium">{{ getActiveTabTitle() }}</h2> -->
        <!-- <p class="text-sm text-gray-500">{{ getActiveTabDescription() }}</p> -->
      </div>
      <div class="p-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Biển số xe</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Chủ xe</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Căn hộ</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Loại xe</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Trạng thái</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Ngày đăng ký</th>
              <th scope="col"
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
            <tr v-for="vehicle in list_xe" :key="vehicle.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center space-x-3">
                  <div
                    class="relative h-10 w-16 overflow-hidden rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                    <span class="text-xs text-gray-500">{{ vehicle.bien_so_xe }}</span>
                  </div>
                  <span class="font-medium">{{ vehicle.bien_so_xe }}</span>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ vehicle.ho_va_ten }}</td>
              <td class="px-6 py-4 whitespace-nowrap"> Toà {{ vehicle.ten_toa_nha }} - Phòng {{ vehicle.so_can_ho }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <Car class="mr-2 h-4 w-4" />
                  {{ vehicle.ten_loai_xe }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="vehicle.trang_thai_duyet === 1 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                  {{ vehicle.trang_thai_duyet === 1 ? 'Đã duyệt' : 'Chưa duyệt' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">{{ convertDate(vehicle.created_at) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex space-x-2">
                  <button
                    class="px-3 py-1 text-sm bg-green-600 text-white border border-gray-300 dark:border-gray-600 rounded-md">Duyệt
                  </button>
                  <button
                    class="px-3 py-1 text-sm bg-blue-600 text-white border border-gray-300 dark:border-gray-600 rounded-md"
                    @click="openUpdateXe(vehicle)">Chỉnh sửa</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <a-modal v-model:open="open_update_xe" title="CẬP NHẬT XE" @ok="capNhatXe">
          <template #footer>
            <a-button key="back" @click="open_update_xe = false">Hủy</a-button>
            <a-button key="submit" type="primary"  @click="capNhatXe">Cập nhật</a-button>
          </template>
          <hr class="my-4">
          <div class="space-y-4">
            <div class="space-y-2">
              <label for="bien_so_xe" class="block text-sm font-medium">Biển số xe</label>
              <input v-model="update_xe.bien_so_xe" id="bien_so_xe" placeholder="Toyota"
                class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>
            <div class="space-y-2">
              <label for="ho_va_ten" class="block text-sm font-medium">Chủ xe</label>
              <input v-model="update_xe.ho_va_ten" id="ho_va_ten" placeholder="Nguyễn Văn A"
                class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>
            <div class="space-y-2">
              <label for="so_can_ho" class="block text-sm font-medium">Căn hộ</label>
              <input v-model="update_xe.so_can_ho" id="so_can_ho" placeholder="100000"
                class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>
            <div class="space-y-2">
              <label for="ten_loai_xe" class="block text-sm font-medium">Loại xe</label>
              <input v-model="update_xe.ten_loai_xe" id="ten_loai_xe" placeholder="Toyota"
                class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md" />
            </div>



          </div>
        </a-modal>
      </div>
    </div>
  </div>
</template>

<script>
import { Plus, Search, Filter, Car } from "lucide-vue-next";
import baseRequest from "../core/baseRequest";
export default {
  name: "Vehicles",
  components: {
    Plus,
    Search,
    Filter,
    Car,
  },
  data() {
    return {
      vehicles: [],
      activeTab: "all",
      searchQuery: "",
      open_update_xe: false,
      update_xe: {
        id: "",
        ten_loai_xe: "",
        tien_thu_giu_xe: "",
      },
      list_loai_xe: [],
      list_cu_dan: [],
    };
  },
  computed: {
    list_xe() {
      const query = this.removeDiacritics(this.searchQuery.toLowerCase());
      return this.vehicles.filter(vehicle =>
        this.removeDiacritics(vehicle.ho_va_ten.toLowerCase()).includes(query) ||
        this.removeDiacritics(vehicle.bien_so_xe.toLowerCase()).includes(query)
      );
    }
  },
  mounted() {
    this.getXe();
    this.getLoaiXe();
    this.getCuDan();
  },
  methods: {
    removeDiacritics(str) {
      return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    },
    convertDate(date) {
      return new Date(date).toLocaleDateString('vi-VN')
    },
    openUpdateXe(vehicle) {
      this.open_update_xe = true;
      Object.assign(this.update_xe, vehicle);
    },
    getXe() {
      baseRequest.get("admin/xe/lay-du-lieu")
        .then((res) => {
          this.vehicles = res.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getLoaiXe() {
      baseRequest.get("admin/loai-xe/lay-du-lieu")
        .then((res) => {
          this.list_loai_xe = res.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    getCuDan() {
      baseRequest.get("admin/cu-dan/lay-du-lieu")
        .then((res) => {
          this.list_cu_dan = res.data.data;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    capNhatXe() {
      baseRequest.post("admin/xe/cap-nhat", this.update_xe)
        .then((res) => {
          this.open_update_xe = false;
        })
        .catch((res) => {
          var errors = Object.values(res.response.data.errors);
          notificationStore.showError(errors[0]);
        });
    },
  },
}
</script>

<style></style>
