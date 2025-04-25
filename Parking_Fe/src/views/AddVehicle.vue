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

            <div v-if="activeTab === 'info'" class="space-y-4">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="license-plate" class="block text-sm font-medium">Biển số xe</label>
                  <input 
                    v-model="vehicle.licensePlate"
                    id="license-plate" 
                    placeholder="51F-12345" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    required 
                  />
                </div>
                <div class="space-y-2">
                  <label for="vehicle-type" class="block text-sm font-medium">Loại xe</label>
                  <select 
                    v-model="vehicle.type"
                    id="vehicle-type" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  >
                    <option value="Ô tô">Ô tô</option>
                    <option value="Xe máy">Xe máy</option>
                    <option value="Xe đạp">Xe đạp</option>
                  </select>
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="brand" class="block text-sm font-medium">Hãng xe</label>
                  <input 
                    v-model="vehicle.brand"
                    id="brand" 
                    placeholder="Toyota" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  />
                </div>
                <div class="space-y-2">
                  <label for="model" class="block text-sm font-medium">Mẫu xe</label>
                  <input 
                    v-model="vehicle.model"
                    id="model" 
                    placeholder="Camry" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  />
                </div>
              </div>

              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="color" class="block text-sm font-medium">Màu sắc</label>
                  <input 
                    v-model="vehicle.color"
                    id="color" 
                    placeholder="Trắng" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  />
                </div>
                <div class="space-y-2">
                  <label for="status" class="block text-sm font-medium">Trạng thái</label>
                  <select 
                    v-model="vehicle.status"
                    id="status" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  >
                    <option value="active">Hoạt động</option>
                    <option value="inactive">Không hoạt động</option>
                  </select>
                </div>
              </div>
            </div>

            <div v-if="activeTab === 'owner'" class="space-y-4">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                  <label for="resident" class="block text-sm font-medium">Chủ xe</label>
                  <select 
                    v-model="vehicle.owner"
                    id="resident" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  >
                    <option value="Nguyễn Văn A">Nguyễn Văn A - A-101</option>
                    <option value="Trần Thị B">Trần Thị B - B-202</option>
                    <option value="Lê Văn C">Lê Văn C - C-303</option>
                    <option value="Phạm Thị D">Phạm Thị D - A-104</option>
                    <option value="Hoàng Văn E">Hoàng Văn E - B-205</option>
                  </select>
                </div>
                <div class="space-y-2">
                  <label for="apartment" class="block text-sm font-medium">Căn hộ</label>
                  <input 
                    v-model="vehicle.apartment"
                    id="apartment" 
                    placeholder="A-101" 
                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                    disabled 
                  />
                </div>
              </div>

              <div class="space-y-2">
                <label for="notes" class="block text-sm font-medium">Ghi chú</label>
                <textarea
                  v-model="vehicle.notes"
                  id="notes"
                  class="w-full min-h-[100px] p-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md"
                  placeholder="Thông tin bổ sung về chủ xe hoặc xe"
                ></textarea>
              </div>
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
                      <button 
                        type="button" 
                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center"
                        @click="handleLicensePlateCapture"
                      >
                        <Camera class="mr-2 h-4 w-4" />
                        Chụp ảnh
                      </button>
                      <button 
                        type="button" 
                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center"
                      >
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
                      <button 
                        type="button" 
                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center"
                        @click="handleVehicleCapture"
                      >
                        <Camera class="mr-2 h-4 w-4" />
                        Chụp ảnh
                      </button>
                      <button 
                        type="button" 
                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md flex items-center justify-center"
                      >
                        <Upload class="mr-2 h-4 w-4" />
                        Tải lên
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="flex justify-end space-x-2">
            <router-link to="/vehicles">
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
              {{ isSubmitting ? "Đang lưu..." : "Lưu xe mới" }}
            </button>
          </div>

          <div v-if="isSuccess" class="bg-green-100 text-green-800 p-3 rounded-md flex items-center">
            <Check class="h-4 w-4 mr-2" />
            Xe đã được thêm thành công!
          </div>
        </form>
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
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import { ArrowLeft, Car, Camera, Upload, Check } from "lucide-vue-next";
import { useVehiclesStore } from "@/stores/vehicles";

export default {
  name: "AddVehicle",
  components: {
    ArrowLeft,
    Car,
    Camera,
    Upload,
    Check,
  },
  setup() {
    const router = useRouter();
    const vehiclesStore = useVehiclesStore();

    const activeTab = ref("info");
    const licensePlateImage = ref(null);
    const vehicleImage = ref(null);
    const isSubmitting = ref(false);
    const isSuccess = ref(false);

    const vehicle = ref({
      licensePlate: "",
      type: "Ô tô",
      brand: "",
      model: "",
      color: "",
      status: "active",
      owner: "Nguyễn Văn A",
      apartment: "A-101",
      notes: "",
    });

    const tabs = [
      { value: "info", label: "Thông tin cơ bản" },
      { value: "owner", label: "Thông tin chủ xe" },
      { value: "images", label: "Hình ảnh" },
    ];

    const handleLicensePlateCapture = () => {
      // Mô phỏng chụp ảnh biển số
      licensePlateImage.value = "https://via.placeholder.com/300x200?text=51F-12345";
    };

    const handleVehicleCapture = () => {
      // Mô phỏng chụp ảnh xe
      vehicleImage.value = "https://via.placeholder.com/300x200?text=Vehicle+Image";
    };

    const handleSubmit = () => {
      isSubmitting.value = true;

      // Thêm xe mới vào store
      setTimeout(() => {
        vehiclesStore.addVehicle({
          ...vehicle.value,
          licensePlateImage: licensePlateImage.value,
          vehicleImage: vehicleImage.value,
        });

        isSubmitting.value = false;
        isSuccess.value = true;

        // Ẩn thông báo thành công sau 3 giây
        setTimeout(() => {
          isSuccess.value = false;
          // Chuyển hướng về trang danh sách xe
          router.push("/vehicles");
        }, 3000);
      }, 1500);
    };

    return {
      activeTab,
      licensePlateImage,
      vehicleImage,
      isSubmitting,
      isSuccess,
      vehicle,
      tabs,
      handleLicensePlateCapture,
      handleVehicleCapture,
      handleSubmit,
    };
  },
};
</script>

<style>
</style>
