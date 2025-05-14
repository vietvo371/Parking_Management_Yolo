<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Thanh toán phí gửi xe hàng tháng</h1>
    </div>

    <!-- Thông tin thanh toán -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Thông tin chi tiết -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Thông tin cư dân -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
          <h2 class="text-lg font-medium mb-4 flex items-center">
            <User class="h-5 w-5 mr-2 text-blue-600" />
            Thông tin cư dân
          </h2>
          
          <div class="flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex-shrink-0">
              <div class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl font-bold">
                {{ getInitials(userInfo.name) }}
              </div>
            </div>
            
            <div class="flex-1 space-y-2">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Họ và tên</p>
                  <p class="font-medium">{{ userInfo.name }}</p>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Mã cư dân</p>
                  <p class="font-medium">{{ userInfo.id }}</p>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Số điện thoại</p>
                  <p class="font-medium">{{ userInfo.phone }}</p>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                  <p class="font-medium">{{ userInfo.email }}</p>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Căn hộ</p>
                  <p class="font-medium">{{ userInfo.apartment }}</p>
                </div>
                
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Tòa nhà</p>
                  <p class="font-medium">{{ userInfo.building }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Thông tin xe đăng ký -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
          <h2 class="text-lg font-medium mb-4 flex items-center">
            <Car class="h-5 w-5 mr-2 text-blue-600" />
            Thông tin xe đăng ký
          </h2>
          
          <div class="space-y-4">
            <div v-for="(vehicle, index) in userVehicles" :key="index" class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
              <div class="flex items-center">
                <div class="flex-shrink-0 mr-4">
                  <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <component :is="vehicle.type === 'car' ? Car : Bike" class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                  </div>
                </div>
                
                <div class="flex-1">
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Biển số xe</p>
                      <p class="font-medium">{{ vehicle.licensePlate }}</p>
                    </div>
                    
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Loại xe</p>
                      <p class="font-medium">{{ vehicle.type === 'car' ? 'Ô tô' : 'Xe máy' }}</p>
                    </div>
                    
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Hãng xe</p>
                      <p class="font-medium">{{ vehicle.brand }} {{ vehicle.model }}</p>
                    </div>
                  </div>
                </div>
                
                <div class="flex-shrink-0 ml-4">
                  <label class="flex items-center space-x-2 cursor-pointer">
                    <input 
                      type="checkbox" 
                      :value="vehicle.id" 
                      v-model="selectedVehicles"
                      class="rounded border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      @change="calculateTotal"
                    />
                    <span class="text-sm font-medium">Chọn</span>
                  </label>
                </div>
              </div>
              
              <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Gói đăng ký</p>
                    <p class="font-medium">{{ vehicle.package.name }}</p>
                  </div>
                  
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày hết hạn</p>
                    <p class="font-medium">{{ formatDate(vehicle.expiryDate) }}</p>
                  </div>
                  
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phí hàng tháng</p>
                    <p class="font-medium text-blue-600">{{ formatCurrency(vehicle.package.monthlyFee) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div v-if="userVehicles.length === 0" class="text-center py-8">
            <Car class="h-12 w-12 mx-auto text-gray-400 mb-4" />
            <p class="text-gray-500">Bạn chưa đăng ký xe nào</p>
            <button 
              class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-flex items-center"
              @click="goToRegisterVehicle"
            >
              <Plus class="mr-2 h-4 w-4" />
              Đăng ký xe mới
            </button>
          </div>
        </div>
        
        <!-- Phương thức thanh toán -->
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6">
          <h2 class="text-lg font-medium mb-4 flex items-center">
            <CreditCard class="h-5 w-5 mr-2 text-blue-600" />
            Phương thức thanh toán
          </h2>
          
          <div class="space-y-4">
            <div 
              v-for="method in paymentMethods" 
              :key="method.id"
              class="border rounded-lg p-4 cursor-pointer transition-all duration-200"
              :class="[
                selectedPaymentMethod === method.id 
                  ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20' 
                  : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700'
              ]"
              @click="selectedPaymentMethod = method.id"
            >
              <div class="flex items-center">
                <div class="flex-shrink-0 mr-4">
                  <div 
                    class="w-10 h-10 rounded-full flex items-center justify-center"
                    :class="selectedPaymentMethod === method.id ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'"
                  >
                    <component :is="method.icon" class="h-5 w-5" />
                  </div>
                </div>
                
                <div class="flex-1">
                  <p class="font-medium" :class="{'text-blue-600': selectedPaymentMethod === method.id}">{{ method.name }}</p>
                  <p class="text-sm text-gray-500">{{ method.description }}</p>
                </div>
                
                <div class="flex-shrink-0 ml-4">
                  <div 
                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center"
                    :class="[
                      selectedPaymentMethod === method.id 
                        ? 'border-blue-600' 
                        : 'border-gray-300 dark:border-gray-600'
                    ]"
                  >
                    <div 
                      v-if="selectedPaymentMethod === method.id" 
                      class="w-3 h-3 rounded-full bg-blue-600"
                    ></div>
                  </div>
                </div>
              </div>
              
              <!-- Thông tin thêm cho từng phương thức -->
              <div v-if="selectedPaymentMethod === method.id" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div v-if="method.id === 'bank_transfer'">
                  <div class="space-y-2">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Ngân hàng</p>
                        <p class="font-medium">{{ bankInfo.bank }}</p>
                      </div>
                      
                      <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Chủ tài khoản</p>
                        <p class="font-medium">{{ bankInfo.accountName }}</p>
                      </div>
                      
                      <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Số tài khoản</p>
                        <div class="flex items-center">
                          <p class="font-medium">{{ bankInfo.accountNumber }}</p>
                          <button 
                            class="ml-2 text-blue-600 hover:text-blue-800"
                            @click="copyToClipboard(bankInfo.accountNumber)"
                            title="Sao chép"
                          >
                            <Copy class="h-4 w-4" />
                          </button>
                        </div>
                      </div>
                      
                      <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nội dung chuyển khoản</p>
                        <div class="flex items-center">
                          <p class="font-medium">{{ transferContent }}</p>
                          <button 
                            class="ml-2 text-blue-600 hover:text-blue-800"
                            @click="copyToClipboard(transferContent)"
                            title="Sao chép"
                          >
                            <Copy class="h-4 w-4" />
                          </button>
                        </div>
                      </div>
                    </div>
                    
                    <div class="mt-4">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tải lên biên lai chuyển khoản</label>
                      <div class="flex items-center justify-center w-full">
                        <label 
                          class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 border-gray-300 dark:border-gray-600"
                        >
                          <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <Upload class="w-8 h-8 mb-3 text-gray-400" />
                            <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                              <span class="font-medium">Nhấp để tải lên</span> hoặc kéo và thả
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG hoặc PDF (Tối đa 10MB)</p>
                          </div>
                          <input type="file" class="hidden" @change="handleFileUpload" accept="image/png, image/jpeg, application/pdf" />
                        </label>
                      </div>
                      <div v-if="uploadedFile" class="mt-2 flex items-center">
                        <FileText class="h-4 w-4 text-gray-500 mr-2" />
                        <span class="text-sm">{{ uploadedFile.name }}</span>
                        <button 
                          class="ml-2 text-red-600 hover:text-red-800"
                          @click="removeUploadedFile"
                          title="Xóa"
                        >
                          <X class="h-4 w-4" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div v-else-if="method.id === 'credit_card'">
                  <div class="space-y-4">
                    <div>
                      <label for="cardNumber" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Số thẻ</label>
                      <input 
                        id="cardNumber" 
                        type="text" 
                        v-model="creditCardInfo.cardNumber"
                        class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                        placeholder="1234 5678 9012 3456"
                      />
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                      <div>
                        <label for="cardName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tên chủ thẻ</label>
                        <input 
                          id="cardName" 
                          type="text" 
                          v-model="creditCardInfo.cardName"
                          class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                          placeholder="NGUYEN VAN A"
                        />
                      </div>
                      
                      <div class="grid grid-cols-2 gap-2">
                        <div>
                          <label for="expiryDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ngày hết hạn</label>
                          <input 
                            id="expiryDate" 
                            type="text" 
                            v-model="creditCardInfo.expiryDate"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                            placeholder="MM/YY"
                          />
                        </div>
                        
                        <div>
                          <label for="cvv" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CVV</label>
                          <input 
                            id="cvv" 
                            type="text" 
                            v-model="creditCardInfo.cvv"
                            class="w-full px-3 py-2 rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800"
                            placeholder="123"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div v-else-if="method.id === 'e_wallet'">
                  <div class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                      <div 
                        v-for="wallet in eWallets" 
                        :key="wallet.id"
                        class="border rounded-lg p-3 cursor-pointer text-center transition-all duration-200"
                        :class="[
                          selectedWallet === wallet.id 
                            ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20' 
                            : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700'
                        ]"
                        @click="selectedWallet = wallet.id"
                      >
                        <div class="flex flex-col items-center">
                          <div 
                            class="w-12 h-12 rounded-full flex items-center justify-center mb-2"
                            :class="selectedWallet === wallet.id ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'"
                          >
                            <component :is="wallet.icon" class="h-6 w-6" />
                          </div>
                          <p class="text-sm font-medium" :class="{'text-blue-600': selectedWallet === wallet.id}">{{ wallet.name }}</p>
                        </div>
                      </div>
                    </div>
                    
                    <div v-if="selectedWallet" class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                      <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Quét mã QR để thanh toán</p>
                      <div class="flex justify-center">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                          <div class="w-48 h-48 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <QrCode class="h-32 w-32 text-gray-500" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Tóm tắt thanh toán -->
      <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 sticky top-6">
          <h2 class="text-lg font-medium mb-4 flex items-center">
            <Receipt class="h-5 w-5 mr-2 text-blue-600" />
            Tóm tắt thanh toán
          </h2>
          
          <div class="space-y-4">
            <div v-if="selectedVehicles.length > 0">
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Chi tiết phí</p>
              
              <div class="space-y-2">
                <div v-for="vehicle in selectedVehicleDetails" :key="vehicle.id" class="flex justify-between">
                  <div class="flex items-center">
                    <component :is="vehicle.type === 'car' ? Car : Bike" class="h-4 w-4 mr-2 text-gray-500" />
                    <span class="text-sm">{{ vehicle.licensePlate }}</span>
                  </div>
                  <span class="text-sm font-medium">{{ formatCurrency(vehicle.package.monthlyFee) }}</span>
                </div>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
              
              <div class="flex justify-between">
                <span class="text-sm">Tổng phí</span>
                <span class="font-medium">{{ formatCurrency(totalAmount) }}</span>
              </div>
              
              <div v-if="discount > 0" class="flex justify-between text-green-600">
                <span class="text-sm">Giảm giá</span>
                <span class="font-medium">-{{ formatCurrency(discount) }}</span>
              </div>
              
              <div class="border-t border-gray-200 dark:border-gray-700 my-4"></div>
              
              <div class="flex justify-between text-lg font-bold">
                <span>Tổng thanh toán</span>
                <span class="text-blue-600">{{ formatCurrency(finalAmount) }}</span>
              </div>
              
              <button 
                class="w-full mt-6 px-4 py-3 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center justify-center"
                @click="processPayment"
                :disabled="isProcessing || !isFormValid"
                :class="{'opacity-50 cursor-not-allowed': isProcessing || !isFormValid}"
              >
                <span v-if="isProcessing">
                  <Loader2 class="h-5 w-5 mr-2 animate-spin" />
                  Đang xử lý...
                </span>
                <span v-else>
                  <CreditCard class="h-5 w-5 mr-2" />
                  Thanh toán ngay
                </span>
              </button>
              
              <p v-if="!isFormValid" class="mt-2 text-xs text-red-600 text-center">
                {{ validationMessage }}
              </p>
            </div>
            
            <div v-else class="text-center py-8">
              <ShoppingCart class="h-12 w-12 mx-auto text-gray-400 mb-4" />
              <p class="text-gray-500">Vui lòng chọn ít nhất một xe để thanh toán</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal thanh toán thành công -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
        <div class="p-6 text-center">
          <div class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center mx-auto mb-4">
            <CheckCircle class="h-10 w-10 text-green-600" />
          </div>
          
          <h3 class="text-xl font-bold mb-2">Thanh toán thành công!</h3>
          <p class="text-gray-500 dark:text-gray-400 mb-6">Cảm ơn bạn đã thanh toán phí gửi xe hàng tháng.</p>
          
          <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="flex justify-between mb-2">
              <span class="text-sm text-gray-500 dark:text-gray-400">Mã giao dịch</span>
              <span class="text-sm font-medium">{{ paymentInfo.transactionId }}</span>
            </div>
            
            <div class="flex justify-between mb-2">
              <span class="text-sm text-gray-500 dark:text-gray-400">Ngày thanh toán</span>
              <span class="text-sm font-medium">{{ formatDateTime(paymentInfo.paymentDate) }}</span>
            </div>
            
            <div class="flex justify-between mb-2">
              <span class="text-sm text-gray-500 dark:text-gray-400">Phương thức</span>
              <span class="text-sm font-medium">{{ getPaymentMethodName(paymentInfo.method) }}</span>
            </div>
            
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Số tiền</span>
              <span class="text-sm font-medium">{{ formatCurrency(paymentInfo.amount) }}</span>
            </div>
          </div>
          
          <div class="flex flex-col sm:flex-row gap-4">
            <button 
              class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-center"
              @click="downloadReceipt"
            >
              <Download class="h-5 w-5 mr-2" />
              Tải hóa đơn
            </button>
            
            <button 
              class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center justify-center"
              @click="closeSuccessModal"
            >
              <Home class="h-5 w-5 mr-2" />
              Về trang chủ
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue'
import { 
  User, 
  Car, 
  Bike, 
  CreditCard, 
  Receipt, 
  Plus, 
  Copy, 
  Upload, 
  X, 
  FileText, 
  ShoppingCart, 
  CheckCircle, 
  Download, 
  Home, 
  Loader2,
  QrCode,
  Wallet,
  Smartphone,
  CreditCard as CreditCardIcon
} from 'lucide-vue-next'

export default {
  name: 'ThanhToanHangThang',
  components: {
    User, 
    Car, 
    Bike, 
    CreditCard, 
    Receipt, 
    Plus, 
    Copy, 
    Upload, 
    X, 
    FileText, 
    ShoppingCart, 
    CheckCircle, 
    Download, 
    Home, 
    Loader2,
    QrCode,
    Wallet,
    Smartphone,
    CreditCardIcon
  },
  setup() {
    // Thông tin người dùng
    const userInfo = ref({
      id: 'CR001',
      name: 'Nguyễn Văn An',
      phone: '0912345678',
      email: 'nguyenvanan@gmail.com',
      apartment: 'A1203',
      building: 'Tòa A'
    })
    
    // Danh sách xe của người dùng
    const userVehicles = ref([
      {
        id: 1,
        type: 'car',
        licensePlate: '51A-12345',
        brand: 'Toyota',
        model: 'Camry',
        package: {
          id: 1,
          name: 'Gói ô tô cơ bản',
          monthlyFee: 1200000,
          description: 'Gói đỗ xe ô tô hàng tháng'
        },
        expiryDate: new Date(2023, 5, 30)
      },
      {
        id: 2,
        type: 'motorbike',
        licensePlate: '59P1-23456',
        brand: 'Honda',
        model: 'SH',
        package: {
          id: 2,
          name: 'Gói xe máy cơ bản',
          monthlyFee: 150000,
          description: 'Gói đỗ xe máy hàng tháng'
        },
        expiryDate: new Date(2023, 5, 15)
      }
    ])
    
    // Danh sách xe đã chọn
    const selectedVehicles = ref([])
    
    // Phương thức thanh toán
    const paymentMethods = [
      {
        id: 'bank_transfer',
        name: 'Chuyển khoản ngân hàng',
        description: 'Chuyển khoản qua tài khoản ngân hàng',
        icon: CreditCardIcon
      },
      {
        id: 'credit_card',
        name: 'Thẻ tín dụng/Ghi nợ',
        description: 'Thanh toán bằng thẻ Visa, Mastercard, JCB',
        icon: CreditCard
      },
      {
        id: 'e_wallet',
        name: 'Ví điện tử',
        description: 'Thanh toán qua ví điện tử MoMo, ZaloPay, VNPay',
        icon: Wallet
      }
    ]
    
    // Danh sách ví điện tử
    const eWallets = [
      {
        id: 'momo',
        name: 'MoMo',
        icon: Smartphone
      },
      {
        id: 'zalopay',
        name: 'ZaloPay',
        icon: Smartphone
      },
      {
        id: 'vnpay',
        name: 'VNPay',
        icon: Smartphone
      },
      {
        id: 'shopeepay',
        name: 'ShopeePay',
        icon: Smartphone
      }
    ]
    
    // Phương thức thanh toán đã chọn
    const selectedPaymentMethod = ref('bank_transfer')
    
    // Ví điện tử đã chọn
    const selectedWallet = ref('')
    
    // Thông tin ngân hàng
    const bankInfo = ref({
      bank: 'Ngân hàng TMCP Ngoại thương Việt Nam (Vietcombank)',
      accountName: 'CÔNG TY QUẢN LÝ BÃI ĐỖ XE ABC',
      accountNumber: '1234567890'
    })
    
    // Nội dung chuyển khoản
    const transferContent = computed(() => {
      return `${userInfo.value.id} THANHTOAN GUIXE`
    })
    
    // Thông tin thẻ tín dụng
    const creditCardInfo = ref({
      cardNumber: '',
      cardName: '',
      expiryDate: '',
      cvv: ''
    })
    
    // File đã tải lên
    const uploadedFile = ref(null)
    
    // Tổng tiền
    const totalAmount = ref(0)
    
    // Giảm giá
    const discount = ref(0)
    
    // Tổng tiền sau giảm giá
    const finalAmount = computed(() => {
      return totalAmount.value - discount.value
    })
    
    // Trạng thái xử lý
    const isProcessing = ref(false)
    
    // Modal thanh toán thành công
    const showSuccessModal = ref(false)
    
    // Thông tin thanh toán
    const paymentInfo = ref({
      transactionId: '',
      paymentDate: null,
      method: '',
      amount: 0
    })
    
    // Khởi tạo khi component được tạo
    onMounted(() => {
      // Tự động chọn tất cả xe
      selectedVehicles.value = userVehicles.value.map(vehicle => vehicle.id)
      
      // Tính tổng tiền
      calculateTotal()
    })
    
    // Chi tiết xe đã chọn
    const selectedVehicleDetails = computed(() => {
      return userVehicles.value.filter(vehicle => selectedVehicles.value.includes(vehicle.id))
    })
    
    // Tính tổng tiền
    const calculateTotal = () => {
      totalAmount.value = selectedVehicleDetails.value.reduce((sum, vehicle) => {
        return sum + vehicle.package.monthlyFee
      }, 0)
      
      // Tính giảm giá (nếu có)
      if (selectedVehicles.value.length > 1) {
        discount.value = totalAmount.value * 0.05 // Giảm 5% nếu thanh toán nhiều hơn 1 xe
      } else {
        discount.value = 0
      }
    }
    
    // Kiểm tra form hợp lệ
    const isFormValid = computed(() => {
      if (selectedVehicles.value.length === 0) {
        return false
      }
      
      if (selectedPaymentMethod.value === 'bank_transfer' && !uploadedFile.value) {
        return false
      }
      
      if (selectedPaymentMethod.value === 'credit_card') {
        return (
          creditCardInfo.value.cardNumber.length > 0 &&
          creditCardInfo.value.cardName.length > 0 &&
          creditCardInfo.value.expiryDate.length > 0 &&
          creditCardInfo.value.cvv.length > 0
        )
      }
      
      if (selectedPaymentMethod.value === 'e_wallet' && !selectedWallet.value) {
        return false
      }
      
      return true
    })
    
    // Thông báo lỗi
    const validationMessage = computed(() => {
      if (selectedVehicles.value.length === 0) {
        return 'Vui lòng chọn ít nhất một xe để thanh toán'
      }
      
      if (selectedPaymentMethod.value === 'bank_transfer' && !uploadedFile.value) {
        return 'Vui lòng tải lên biên lai chuyển khoản'
      }
      
      if (selectedPaymentMethod.value === 'credit_card') {
        if (!creditCardInfo.value.cardNumber) return 'Vui lòng nhập số thẻ'
        if (!creditCardInfo.value.cardName) return 'Vui lòng nhập tên chủ thẻ'
        if (!creditCardInfo.value.expiryDate) return 'Vui lòng nhập ngày hết hạn'
        if (!creditCardInfo.value.cvv) return 'Vui lòng nhập mã CVV'
      }
      
      if (selectedPaymentMethod.value === 'e_wallet' && !selectedWallet.value) {
        return 'Vui lòng chọn ví điện tử'
      }
      
      return ''
    })
    
    // Xử lý tải lên file
    const handleFileUpload = (event) => {
      const file = event.target.files[0]
      if (file) {
        uploadedFile.value = file
      }
    }
    
    // Xóa file đã tải lên
    const removeUploadedFile = () => {
      uploadedFile.value = null
    }
    
    // Sao chép vào clipboard
    const copyToClipboard = (text) => {
      navigator.clipboard.writeText(text)
        .then(() => {
          alert('Đã sao chép vào clipboard')
        })
        .catch(err => {
          console.error('Không thể sao chép: ', err)
        })
    }
    
    // Xử lý thanh toán
    const processPayment = () => {
      if (!isFormValid.value) {
        return
      }
      
      isProcessing.value = true
      
      // Giả lập xử lý thanh toán
      setTimeout(() => {
        isProcessing.value = false
        
        // Tạo thông tin thanh toán
        paymentInfo.value = {
          transactionId: generateTransactionId(),
          paymentDate: new Date(),
          method: selectedPaymentMethod.value,
          amount: finalAmount.value
        }
        
        // Hiển thị modal thành công
        showSuccessModal.value = true
      }, 2000)
    }
    
    // Tạo mã giao dịch
    const generateTransactionId = () => {
      const prefix = 'TX'
      const timestamp = new Date().getTime().toString().slice(-6)
      const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0')
      return `${prefix}${timestamp}${random}`
    }
    
    // Tải hóa đơn
    const downloadReceipt = () => {
      // Trong thực tế, bạn sẽ gọi API để tải hóa đơn
      alert('Tải hóa đơn thành công')
    }
    
    // Đóng modal thành công
    const closeSuccessModal = () => {
      showSuccessModal.value = false
      // Trong thực tế, bạn sẽ chuyển hướng về trang chủ
    }
    
    // Chuyển đến trang đăng ký xe
    const goToRegisterVehicle = () => {
      // Trong thực tế, bạn sẽ chuyển hướng đến trang đăng ký xe
      alert('Chuyển đến trang đăng ký xe')
    }
    
    // Lấy chữ cái đầu của tên
    const getInitials = (name) => {
      return name
        .split(' ')
        .map(word => word.charAt(0))
        .join('')
        .toUpperCase()
        .substring(0, 2)
    }
    
    // Định dạng ngày
    const formatDate = (date) => {
      return new Date(date).toLocaleDateString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
      })
    }
    
    // Định dạng ngày giờ
    const formatDateTime = (date) => {
      return new Date(date).toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
      })
    }
    
    // Định dạng tiền tệ
    const formatCurrency = (amount) => {
      return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
      }).format(amount || 0)
    }
    
    // Lấy tên phương thức thanh toán
    const getPaymentMethodName = (methodId) => {
      const method = paymentMethods.find(m => m.id === methodId)
      return method ? method.name : 'Không xác định'
    }
    
    return {
      userInfo,
      userVehicles,
      selectedVehicles,
      paymentMethods,
      eWallets,
      selectedPaymentMethod,
      selectedWallet,
      bankInfo,
      transferContent,
      creditCardInfo,
      uploadedFile,
      totalAmount,
      discount,
      finalAmount,
      isProcessing,
      showSuccessModal,
      paymentInfo,
      selectedVehicleDetails,
      isFormValid,
      validationMessage,
      handleFileUpload,
      removeUploadedFile,
      copyToClipboard,
      processPayment,
      downloadReceipt,
      closeSuccessModal,
      goToRegisterVehicle,
      calculateTotal,
      getInitials,
      formatDate,
      formatDateTime,
      formatCurrency,
      getPaymentMethodName
    }
  }
}
</script>