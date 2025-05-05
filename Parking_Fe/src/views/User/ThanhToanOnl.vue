<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Mua vé gửi xe</h1>
    </div>

    <div class="relative">
      <div class="flex items-center justify-between mb-6">
        <div v-for="(step, index) in steps" :key="index" class="flex flex-col items-center relative z-10">
          <div 
            class="w-10 h-10 rounded-full flex items-center justify-center"
            :class="{
              'bg-blue-600 text-white': currentStep > index,
              'bg-blue-600 text-white': currentStep === index,
              'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400': currentStep < index
            }"
          >
            <component :is="step.icon" v-if="currentStep > index" class="h-5 w-5" />
            <span v-else>{{ index + 1 }}</span>
          </div>
          <p class="text-sm mt-2" :class="{'font-medium': currentStep >= index}">{{ step.name }}</p>
        </div>
        
        <div class="absolute top-5 left-0 right-0 h-0.5 bg-gray-200 dark:bg-gray-700 -z-10"></div>
        <div 
          class="absolute top-5 left-0 h-0.5 bg-blue-600 -z-10 transition-all duration-300"
          :style="{width: `${(currentStep / (steps.length - 1)) * 100}%`}"
        ></div>
      </div>
    </div>

    <div v-if="currentStep === 0" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
      <h2 class="text-lg font-medium mb-4">Chọn xe</h2>
      
      <div class="space-y-4">
        <div v-if="vehicles.length === 0" class="text-center py-8">
          <Car class="h-12 w-12 mx-auto text-gray-400" />
          <p class="mt-2 text-gray-500">Bạn chưa có xe nào</p>
          <button 
            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            @click="showAddVehicleModal = true"
          >
            Thêm xe mới
          </button>
        </div>
        
        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div 
            v-for="vehicle in vehicles" 
            :key="vehicle.id"
            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer transition-all duration-200"
            :class="{
              'border-blue-600 bg-blue-50 dark:bg-blue-900/20': selectedVehicle?.id === vehicle.id,
              'hover:border-blue-600': selectedVehicle?.id !== vehicle.id
            }"
            @click="selectVehicle(vehicle)"
          >
            <div class="flex items-center">
              <div class="flex-shrink-0 mr-4">
                <component 
                  :is="vehicle.type === 'car' ? Car : Bike" 
                  class="h-10 w-10 text-gray-500"
                  :class="{'text-blue-600': selectedVehicle?.id === vehicle.id}"
                />
              </div>
              <div class="flex-1">
                <h3 class="font-medium">{{ vehicle.licensePlate }}</h3>
                <p class="text-sm text-gray-500">{{ vehicle.brand }} {{ vehicle.model }}</p>
              </div>
              <div v-if="vehicle.is_con_han" class="ml-2 px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 text-xs rounded-full">
                Còn hạn
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex justify-between mt-6">
          <button 
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
            @click="showAddVehicleModal = true"
          >
            Thêm xe mới
          </button>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            :disabled="!selectedVehicle"
            :class="{'opacity-50 cursor-not-allowed': !selectedVehicle}"
            @click="goToNextStep"
          >
            Tiếp tục
          </button>
        </div>
      </div>
    </div>

    <div v-if="currentStep === 1" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
      <h2 class="text-lg font-medium mb-4">Chọn gói</h2>
      
      <div class="space-y-4">
        <div class="mb-4">
          <h3 class="text-sm font-medium mb-2">Thông tin xe</h3>
          <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
            <div class="flex items-center">
              <component 
                :is="selectedVehicle.type === 'car' ? Car : Bike" 
                class="h-6 w-6 mr-2 text-gray-500"
              />
              <div>
                <p class="font-medium">{{ selectedVehicle.licensePlate }}</p>
                <p class="text-sm text-gray-500">{{ selectedVehicle.brand }} {{ selectedVehicle.model }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div 
            v-for="(pkg, index) in packages" 
            :key="index"
            class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 cursor-pointer transition-all duration-200"
            :class="{
              'border-blue-600 bg-blue-50 dark:bg-blue-900/20': selectedPackage?.id === pkg.id,
              'hover:border-blue-600': selectedPackage?.id !== pkg.id
            }"
            @click="selectPackage(pkg)"
          >
            <div class="flex flex-col h-full">
              <h3 class="font-medium">{{ pkg.name }}</h3>
              <p class="text-sm text-gray-500 mb-2">{{ pkg.description }}</p>
              <div class="mt-auto">
                <p class="text-lg font-bold">{{ formatCurrency(pkg.price) }}</p>
                <p class="text-xs text-gray-500">{{ pkg.priceDescription }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="flex justify-between mt-6">
          <button 
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
            @click="goToPreviousStep"
          >
            Quay lại
          </button>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            :disabled="!selectedPackage"
            :class="{'opacity-50 cursor-not-allowed': !selectedPackage}"
            @click="goToNextStep"
          >
            Tiếp tục
          </button>
        </div>
      </div>
    </div>

    <div v-if="currentStep === 2" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
      <h2 class="text-lg font-medium mb-4">Thanh toán</h2>
      
      <div class="space-y-4">
        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
          <h3 class="text-sm font-medium mb-2">Thông tin đơn hàng</h3>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-500">Xe:</span>
              <span>{{ selectedVehicle.licensePlate }} - {{ selectedVehicle.brand }} {{ selectedVehicle.model }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Gói:</span>
              <span>{{ selectedPackage.name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Thời hạn:</span>
              <span>{{ selectedPackage.duration }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Ngày bắt đầu:</span>
              <span>{{ formatDate(startDate) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-500">Ngày hết hạn:</span>
              <span>{{ formatDate(expiryDate) }}</span>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-600 my-2 pt-2 flex justify-between font-medium">
              <span>Tổng tiền:</span>
              <span>{{ formatCurrency(selectedPackage.price) }}</span>
            </div>
          </div>
        </div>
        
        <div>
          <h3 class="text-sm font-medium mb-2">Phương thức thanh toán</h3>
          <div class="space-y-2">
            <div 
              v-for="(method, index) in paymentMethods" 
              :key="index"
              class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-md cursor-pointer"
              :class="{
                'border-blue-600 bg-blue-50 dark:bg-blue-900/20': selectedPaymentMethod === method.id
              }"
              @click="selectedPaymentMethod = method.id"
            >
              <input 
                type="radio" 
                :id="`payment-${method.id}`" 
                :value="method.id" 
                v-model="selectedPaymentMethod"
                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
              />
              <label :for="`payment-${method.id}`" class="ml-2 flex items-center">
                <component :is="method.icon" class="h-5 w-5 mr-2" />
                <span>{{ method.name }}</span>
              </label>
            </div>
          </div>
        </div>
        
        <div v-if="selectedPaymentMethod === 'bank'" class="space-y-2">
          <h3 class="text-sm font-medium mb-2">Thông tin chuyển khoản</h3>
          <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md">
            <p class="text-sm">Ngân hàng: <span class="font-medium">Vietcombank</span></p>
            <p class="text-sm">Số tài khoản: <span class="font-medium">1234567890</span></p>
            <p class="text-sm">Chủ tài khoản: <span class="font-medium">CÔNG TY QUẢN LÝ BÃI ĐỖ XE</span></p>
            <p class="text-sm">Nội dung: <span class="font-medium">{{ selectedVehicle.licensePlate }} - {{ selectedPackage.name }}</span></p>
          </div>
        </div>
        
        <div v-if="selectedPaymentMethod === 'momo'" class="space-y-2">
          <h3 class="text-sm font-medium mb-2">Thanh toán qua MoMo</h3>
          <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md flex flex-col items-center">
            <div class="bg-white p-2 rounded-md mb-2">
              <img src="" alt="QR Code MoMo" class="h-32 w-32" />
            </div>
            <p class="text-sm">Quét mã QR để thanh toán</p>
            <p class="text-sm">Hoặc chuyển đến số: <span class="font-medium">0987654321</span></p>
          </div>
        </div>
        
        <div v-if="selectedPaymentMethod === 'vnpay'" class="space-y-2">
          <h3 class="text-sm font-medium mb-2">Thanh toán qua VNPay</h3>
          <div class="bg-gray-50 dark:bg-gray-700 p-3 rounded-md flex flex-col items-center">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 w-full">
              Chuyển đến cổng thanh toán VNPay
            </button>
            <p class="text-sm mt-2">Bạn sẽ được chuyển đến cổng thanh toán VNPay để hoàn tất giao dịch</p>
          </div>
        </div>
        
        <div class="flex justify-between mt-6">
          <button 
            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
            @click="goToPreviousStep"
          >
            Quay lại
          </button>
          <button 
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
            :disabled="!selectedPaymentMethod"
            :class="{'opacity-50 cursor-not-allowed': !selectedPaymentMethod}"
            @click="processPayment"
          >
            Thanh toán
          </button>
        </div>
      </div>
    </div>

    <div v-if="currentStep === 3" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 text-center">
      <div class="mb-4">
        <div class="h-16 w-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto">
          <CheckCircle class="h-8 w-8" />
        </div>
      </div>
      
      <h2 class="text-lg font-medium mb-2">Thanh toán thành công!</h2>
      <p class="text-gray-500 mb-6">Cảm ơn bạn đã mua vé gửi xe. Vé của bạn đã được kích hoạt.</p>
      
      <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md max-w-md mx-auto mb-6">
        <h3 class="text-sm font-medium mb-2">Thông tin vé</h3>
        <div class="space-y-2 text-left">
          <div class="flex justify-between">
            <span class="text-gray-500">Mã giao dịch:</span>
            <span>{{ transactionId }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Xe:</span>
            <span>{{ selectedVehicle.licensePlate }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Gói:</span>
            <span>{{ selectedPackage.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Ngày hết hạn:</span>
            <span>{{ formatDate(expiryDate) }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-gray-500">Trạng thái:</span>
            <span class="text-green-600 font-medium">Đã kích hoạt</span>
          </div>
        </div>
      </div>
      
      <div class="flex justify-center space-x-4">
        <button 
          class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
          @click="printTicket"
        >
          In vé
        </button>
        <button 
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          @click="resetForm"
        >
          Quay lại trang chủ
        </button>
      </div>
    </div>

    <div v-if="showAddVehicleModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
          <h3 class="text-lg font-medium">Thêm xe mới</h3>
          <button @click="showAddVehicleModal = false" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
            <X class="h-5 w-5" />
          </button>
        </div>
        <div class="p-4">
          <form @submit.prevent="addVehicle">
            <div class="space-y-4">
              <div>
                <label for="vehicleType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Loại xe *</label>
                <select 
                  id="vehicleType" 
                  v-model="newVehicle.type"
                  required
                  class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                >
                  <option value="car">Ô tô</option>
                  <option value="motorbike">Xe máy</option>
                </select>
              </div>
              
              <div>
                <label for="vehicleLicensePlate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biển số xe *</label>
                <input 
                  id="vehicleLicensePlate" 
                  type="text" 
                  v-model="newVehicle.licensePlate"
                  required
                  class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  placeholder="Nhập biển số xe"
                />
              </div>
              
              <div>
                <label for="vehicleBrand" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hãng xe *</label>
                <input 
                  id="vehicleBrand" 
                  type="text" 
                  v-model="newVehicle.brand"
                  required
                  class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  placeholder="Nhập hãng xe"
                />
              </div>
              
              <div>
                <label for="vehicleModel" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mẫu xe *</label>
                <input 
                  id="vehicleModel" 
                  type="text" 
                  v-model="newVehicle.model"
                  required
                  class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                  placeholder="Nhập mẫu xe"
                />
              </div>
              
              <div class="pt-4 flex justify-end space-x-2">
                <button 
                  type="button" 
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
                  @click="showAddVehicleModal = false"
                >
                  Hủy
                </button>
                <button 
                  type="submit" 
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                >
                  Thêm xe
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { 
  Car, 
  Bike, 
  CheckCircle, 
  CreditCard, 
  Smartphone, 
  DollarSign, 
  X 
} from 'lucide-vue-next'

export default {
  name: 'MuaVeGuiXe',
  components: {
    Car,
    Bike,
    CheckCircle,
    CreditCard,
    Smartphone,
    DollarSign,
    X
  },
  setup() {
    // Các bước
    const steps = [
      { name: 'Chọn xe', icon: CheckCircle },
      { name: 'Chọn gói', icon: CheckCircle },
      { name: 'Thanh toán', icon: CheckCircle },
      { name: 'Hoàn thành', icon: CheckCircle }
    ]
    
    const currentStep = ref(0)
    
    // Danh sách xe
    const vehicles = ref([
      {
        id: 1,
        type: 'car',
        licensePlate: '59A-12345',
        brand: 'Toyota',
        model: 'Camry',
        is_con_han: false
      },
      {
        id: 2,
        type: 'car',
        licensePlate: '59A-54321',
        brand: 'Honda',
        model: 'Civic',
        is_con_han: true
      },
      {
        id: 3,
        type: 'motorbike',
        licensePlate: '59P1-12345',
        brand: 'Honda',
        model: 'SH',
        is_con_han: false
      }
    ])
    
    // Danh sách gói
    const packages = ref([
      {
        id: 1,
        name: 'Gửi 1 tháng',
        description: 'Gửi xe trong 1 tháng',
        price: 150000,
        priceDescription: '150.000đ/tháng',
        duration: '1 tháng',
        durationInDays: 30
      },
      {
        id: 2,
        name: 'Gửi 3 tháng',
        description: 'Gửi xe trong 3 tháng',
        price: 400000,
        priceDescription: '133.333đ/tháng',
        duration: '3 tháng',
        durationInDays: 90
      },
      {
        id: 3,
        name: 'Gửi 6 tháng',
        description: 'Gửi xe trong 6 tháng',
        price: 750000,
        priceDescription: '125.000đ/tháng',
        duration: '6 tháng',
        durationInDays: 180
      }
    ])
    
    // Phương thức thanh toán
    const paymentMethods = [
      { id: 'bank', name: 'Chuyển khoản ngân hàng', icon: CreditCard },
      { id: 'momo', name: 'Ví MoMo', icon: Smartphone },
      { id: 'vnpay', name: 'VNPay', icon: CreditCard }
    ]
    
    // Dữ liệu form
    const selectedVehicle = ref(null)
    const selectedPackage = ref(null)
    const selectedPaymentMethod = ref('bank')
    const showAddVehicleModal = ref(false)
    const transactionId = ref('')
    
    // Xe mới
    const newVehicle = ref({
      type: 'car',
      licensePlate: '',
      brand: '',
      model: ''
    })
    
    // Ngày bắt đầu và hết hạn
    const startDate = ref(new Date())
    const expiryDate = computed(() => {
      if (!selectedPackage.value) return new Date()
      
      const date = new Date(startDate.value)
      date.setDate(date.getDate() + selectedPackage.value.durationInDays)
      return date
    })
    
    // Chọn xe
    const selectVehicle = (vehicle) => {
      selectedVehicle.value = vehicle
    }
    
    // Chọn gói
    const selectPackage = (pkg) => {
      selectedPackage.value = pkg
    }
    
    // Đi đến bước tiếp theo
    const goToNextStep = () => {
      if (currentStep.value < steps.length - 1) {
        currentStep.value++
      }
    }
    
    // Quay lại bước trước
    const goToPreviousStep = () => {
      if (currentStep.value > 0) {
        currentStep.value--
      }
    }
    
    // Thêm xe mới
    const addVehicle = () => {
      // Trong thực tế, bạn sẽ gọi API để thêm xe
      const newId = Math.max(...vehicles.value.map(v => v.id)) + 1
      
      const vehicle = {
        id: newId,
        ...newVehicle.value,
        is_con_han: false
      }
      
      vehicles.value.push(vehicle)
      
      // Đặt lại form
      newVehicle.value = {
        type: 'car',
        licensePlate: '',
        brand: '',
        model: ''
      }
      
      showAddVehicleModal.value = false
      
      // Chọn xe mới thêm
      selectVehicle(vehicle)
    }
    
    // Xử lý thanh toán
    const processPayment = () => {
      // Trong thực tế, bạn sẽ gọi API để xử lý thanh toán
      
      // Tạo mã giao dịch
      transactionId.value = `TX${Date.now().toString().slice(-6)}`
      
      // Cập nhật trạng thái xe
      const vehicleIndex = vehicles.value.findIndex(v => v.id === selectedVehicle.value.id)
      if (vehicleIndex !== -1) {
        vehicles.value[vehicleIndex].is_con_han = true
      }
      
      // Chuyển đến bước hoàn thành
      goToNextStep()
    }
    
    // In vé
    const printTicket = () => {
      // Trong thực tế, bạn sẽ tạo một trang in và gọi window.print()
      console.log('In vé:', {
        transactionId: transactionId.value,
        vehicle: selectedVehicle.value,
        package: selectedPackage.value,
        expiryDate: expiryDate.value
      })
    }
    
    // Đặt lại form
    const resetForm = () => {
      currentStep.value = 0
      selectedVehicle.value = null
      selectedPackage.value = null
      selectedPaymentMethod.value = 'bank'
      transactionId.value = ''
    }
    
    // Định dạng tiền tệ
    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(amount || 0)
    }
    
    // Định dạng ngày
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      })
    }
    
    return {
      steps,
      currentStep,
      vehicles,
      packages,
      paymentMethods,
      selectedVehicle,
      selectedPackage,
      selectedPaymentMethod,
      showAddVehicleModal,
      newVehicle,
      startDate,
      expiryDate,
      transactionId,
      selectVehicle,
      selectPackage,
      goToNextStep,
      goToPreviousStep,
      addVehicle,
      processPayment,
      printTicket,
      resetForm,
      formatCurrency,
      formatDate
    }
  }
}
</script>