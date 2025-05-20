<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Thanh toán phí gửi xe hàng tháng</h1>
    </div>

    <!-- Thông báo -->
    <div v-if="expiredVehicles.length > 0"
      class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <InfoIcon class="h-5 w-5 text-blue-600" />
        </div>
        <div class="ml-3">
          <p class="text-sm text-blue-700 dark:text-blue-300">
            Chỉ những xe đã hết hạn mới có thể được chọn để thanh toán. Vui lòng chọn một xe đã hết hạn. Phí gửi xe là
            {{ formatCurrency(monthlyFee) }}/tháng.
          </p>
        </div>
      </div>
    </div>

    <div v-else class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <CheckCircle class="h-5 w-5 text-green-600" />
        </div>
        <div class="ml-3">
          <p class="text-sm text-green-700 dark:text-green-300">
            Tất cả các xe của bạn đều còn hạn sử dụng. Không cần thanh toán vào lúc này.
          </p>
        </div>
      </div>
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
              <div
                class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl font-bold">
                {{ getInitials(userInfo.ho_va_ten) }}
              </div>
            </div>

            <div class="flex-1 space-y-2">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Họ và tên</p>
                  <p class="font-medium">{{ userInfo.ho_va_ten }}</p>
                </div>

                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Mã cư dân</p>
                  <p class="font-medium">{{ userInfo.id }}</p>
                </div>

                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Số điện thoại</p>
                  <p class="font-medium">{{ userInfo.so_dien_thoai }}</p>
                </div>

                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                  <p class="font-medium">{{ userInfo.email }}</p>
                </div>

                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Căn hộ</p>
                  <p class="font-medium">{{ userInfo.so_can_ho }}</p>
                </div>

                <div>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Tòa nhà</p>
                  <p class="font-medium">{{ userInfo.ten_toa_nha }}</p>
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
            <span v-if="expiredVehicles.length > 0" class="ml-2 text-sm font-normal text-red-600">({{
              expiredVehicles.length }} xe đã hết hạn)</span>
          </h2>

          <div class="space-y-4">
            <div v-for="(vehicle, index) in userVehicles" :key="index" class="border rounded-lg p-4"
              :class="[isExpired(vehicle.ngay_het_han) ? 'border-gray-200 dark:border-gray-700' : 'border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50']">
              <div class="flex items-center">
                <div class="flex-shrink-0 mr-4">
                  <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <component :is="vehicle.ten_loai_xe === 'Xe máy' ? Bike : Car"
                      class="h-6 w-6 text-gray-500 dark:text-gray-400" />
                  </div>
                </div>

                <div class="flex-1">
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Biển số xe</p>
                      <p class="font-medium">{{ vehicle.bien_so_xe }}</p>
                    </div>

                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Loại xe</p>
                      <p class="font-medium">{{ vehicle.ten_loai_xe }}</p>
                    </div>
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">Trạng thái</p>
                      <p class="font-medium" :class="{
                        'text-green-600': vehicle.trang_thai_duyet === 1,
                        'text-yellow-600': vehicle.trang_thai_duyet === 0
                      }">
                        {{ vehicle.trang_thai_duyet === 1 ? 'Đã duyệt' : 'Chờ duyệt' }}
                      </p>
                    </div>
                  </div>
                </div>

                <div class="flex-shrink-0 ml-4">
                  <label class="flex items-center space-x-2"
                    :class="{ 
                      'cursor-pointer': isExpired(vehicle.ngay_het_han) && vehicle.trang_thai_duyet === 1, 
                      'cursor-not-allowed opacity-60': !isExpired(vehicle.ngay_het_han) || vehicle.trang_thai_duyet !== 1 
                    }">
                    <input type="radio" 
                      :value="vehicle" 
                      v-model="selectedVehicle"
                      class="rounded-full border-gray-300 dark:border-gray-600 text-blue-600 focus:ring-blue-500"
                      @change="calculateTotal" 
                      :disabled="!isExpired(vehicle.ngay_het_han) || vehicle.trang_thai_duyet !== 1" />
                    <span class="text-sm font-medium">Chọn</span>
                  </label>
                </div>
              </div>

              <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Gói đăng ký</p>
                    <p class="font-medium">Gói hàng tháng (1 tháng)</p>
                  </div>

                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ngày hết hạn</p>
                    <p class="font-medium"
                      :class="{ 
                        'text-red-600': isExpired(vehicle.ngay_het_han), 
                        'text-green-600': !isExpired(vehicle.ngay_het_han) 
                      }">
                      {{ formatDate(vehicle.ngay_het_han) }}
                      <span v-if="isExpired(vehicle.ngay_het_han)" class="ml-1 text-xs text-red-600">(Đã hết hạn)</span>
                      <span v-else class="ml-1 text-xs text-green-600">(Còn hạn)</span>
                    </p>
                  </div>

                  <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Phí hàng tháng</p>
                    <p class="font-medium text-blue-600">{{ formatCurrency(monthlyFee) }}</p>
                  </div>
                </div>
              </div>

              <!-- Thông báo trạng thái -->
              <div v-if="vehicle.trang_thai_duyet !== 1" class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/30 rounded-md">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                  <AlertTriangle class="inline-block h-4 w-4 mr-1" />
                  Xe đang chờ duyệt. Vui lòng đợi quản trị viên phê duyệt trước khi thanh toán.
                </p>
              </div>
            </div>
          </div>

          <div v-if="userVehicles.length === 0" class="text-center py-8">
            <Car class="h-12 w-12 mx-auto text-gray-400 mb-4" />
            <p class="text-gray-500">Bạn chưa đăng ký xe nào</p>
            <button class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 inline-flex items-center"
              @click="goToRegisterVehicle">
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
            <div v-for="method in paymentMethods" :key="method.id"
              class="border rounded-lg p-4 cursor-pointer transition-all duration-200" :class="[
                selectedPaymentMethod === method.id
                  ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20'
                  : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700'
              ]" @click="selectPaymentMethod(method.id)">
              <div class="flex items-center">
                <div class="flex-shrink-0 mr-4">
                  <div class="w-10 h-10 rounded-full flex items-center justify-center"
                    :class="selectedPaymentMethod === method.id ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'">
                    <component :is="method.icon" class="h-5 w-5" />
                  </div>
                </div>

                <div class="flex-1">
                  <p class="font-medium" :class="{ 'text-blue-600': selectedPaymentMethod === method.id }">{{ method.name
                    }}</p>
                  <p class="text-sm text-gray-500">{{ method.description }}</p>
                </div>

                <div class="flex-shrink-0 ml-4">
                  <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="[
                    selectedPaymentMethod === method.id
                      ? 'border-blue-600'
                      : 'border-gray-300 dark:border-gray-600'
                  ]">
                    <div v-if="selectedPaymentMethod === method.id" class="w-3 h-3 rounded-full bg-blue-600"></div>
                  </div>
                </div>
              </div>

              <!-- Thông tin thêm cho từng phương thức -->
              <div v-if="selectedPaymentMethod === method.id"
                class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
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
                          <button class="ml-2 text-blue-600 hover:text-blue-800"
                            @click="copyToClipboard(bankInfo.accountNumber)" title="Sao chép">
                            <Copy class="h-4 w-4" />
                          </button>
                        </div>
                      </div>

                      <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nội dung chuyển khoản</p>
                        <div class="flex items-center">
                          <p class="font-medium">{{ transferContent }}</p>
                          <button class="ml-2 text-blue-600 hover:text-blue-800"
                            @click="copyToClipboard(transferContent)" title="Sao chép">
                            <Copy class="h-4 w-4" />
                          </button>
                        </div>
                      </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                      <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Quét mã QR để thanh toán</p>
                      <div class="flex justify-center">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                          <img v-if="vietQRUrl && !isLoadingQR" :src="vietQRUrl" alt="VietQR"
                            class="w-48 h-48 object-contain" @error="handleQRError" />
                          <div v-else-if="isLoadingQR" class="w-48 h-48 flex items-center justify-center">
                            <Loader2 class="h-12 w-12 text-blue-600 animate-spin" />
                          </div>
                          <div v-else class="w-48 h-48 flex items-center justify-center">
                            <QrCode class="h-12 w-12 text-gray-400" />
                            <p class="text-sm text-gray-500 mt-2">Vui lòng chọn xe để tạo mã QR</p>
                          </div>
                        </div>
                      </div>
                      <p class="text-xs text-center text-gray-500 dark:text-gray-400 mt-2">
                        Sử dụng ứng dụng ngân hàng để quét mã QR và thanh toán
                      </p>
                    </div>
                  </div>
                </div>

                <div v-else-if="method.id === 'e_wallet'">
                  <div class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                      <div v-for="wallet in eWallets" :key="wallet.id"
                        class="border rounded-lg p-3 cursor-pointer text-center transition-all duration-200" :class="[
                          selectedWallet === wallet.id
                            ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/20'
                            : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700'
                        ]" @click="selectedWallet = wallet.id">
                        <div class="flex flex-col items-center">
                          <div class="w-12 h-12 rounded-full flex items-center justify-center mb-2"
                            :class="selectedWallet === wallet.id ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400'">
                            <component :is="wallet.icon" class="h-6 w-6" />
                          </div>
                          <p class="text-sm font-medium" :class="{ 'text-blue-600': selectedWallet === wallet.id }">{{
                            wallet.name }}</p>
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
        <div
          class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm p-6 sticky top-6">
          <h2 class="text-lg font-medium mb-4 flex items-center">
            <Receipt class="h-5 w-5 mr-2 text-blue-600" />
            Tóm tắt thanh toán
          </h2>

          <div class="space-y-4">
            <div v-if="selectedVehicle">
              <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Chi tiết phí</p>

              <div class="space-y-2">
                <div class="flex justify-between">
                  <div class="flex items-center">
                    <component :is="selectedVehicle.ten_loai_xe === 'Xe máy' ? Bike : Car"
                      class="h-4 w-4 mr-2 text-gray-500" />
                    <span class="text-sm">{{ selectedVehicle.bien_so_xe }} (1 tháng)</span>
                  </div>
                  <span class="text-sm font-medium">{{ formatCurrency(monthlyFee) }}</span>
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
                @click="processPayment" :disabled="isProcessing || !isFormValid"
                :class="{ 'opacity-50 cursor-not-allowed': isProcessing || !isFormValid }">
                <span class="flex items-center" v-if="isProcessing">
                  <Loader2 class="h-5 w-5 mr-2 animate-spin" />
                  Đang xử lý...
                </span>
                <span class="flex items-center" v-else>
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
              <p class="text-gray-500">Vui lòng chọn một xe đã hết hạn để thanh toán</p>
              <p class="text-sm text-gray-500 mt-2">Chỉ những xe đã hết hạn mới có thể được thanh toán</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal thanh toán thành công -->
    <div v-if="showSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-md w-full">
        <div class="p-6 text-center">
          <div
            class="w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center mx-auto mb-4">
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
              <span class="text-sm font-medium">Chuyển khoản ngân hàng</span>
            </div>

            <div class="flex justify-between mb-2">
              <span class="text-sm text-gray-500 dark:text-gray-400">Số tiền</span>
              <span class="text-sm font-medium">100.000đ</span>
            </div>

            <div v-if="selectedVehicle" class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Biển số xe</span>
              <span class="text-sm font-medium">{{ selectedVehicle.bien_so_xe }}</span>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row gap-4">
            <button
              class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 flex items-center justify-center"
              @click="downloadReceipt">
              <Download class="h-5 w-5 mr-2" />
              Tải hóa đơn
            </button>

            <button
              class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center justify-center"
              @click="closeSuccessModal">
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
import baseRequestUser from '../../core/baseRequestUser'
import {
  User,
  Car,
  Bike,
  CreditCard,
  Receipt,
  Plus,
  Copy,
  ShoppingCart,
  CheckCircle,
  Download,
  Home,
  Loader2,
  QrCode,
  Wallet,
  Smartphone,
  CreditCard as CreditCardIcon,
  Info as InfoIcon,
  AlertTriangle
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
    ShoppingCart,
    CheckCircle,
    Download,
    Home,
    Loader2,
    QrCode,
    Wallet,
    Smartphone,
    CreditCardIcon,
    InfoIcon,
    AlertTriangle
  },
  data() {
    return {
      userInfo: {},
      userVehicles: [],
      selectedVehicle: null,
      paymentMethods: [
        { id: 'e_wallet', name: 'Ví điện tử', description: 'Thanh toán qua ví điện tử MoMo, ZaloPay, VNPay', icon: Wallet },
        { id: 'bank_transfer', name: 'Chuyển khoản ngân hàng', description: 'Chuyển khoản qua tài khoản ngân hàng', icon: CreditCardIcon }
      ],
      eWallets: [
        { id: 'momo', name: 'MoMo', icon: Smartphone },
        { id: 'zalopay', name: 'ZaloPay', icon: Smartphone },
        { id: 'vnpay', name: 'VNPay', icon: Smartphone },
        { id: 'shopeepay', name: 'ShopeePay', icon: Smartphone }
      ],
      selectedPaymentMethod: 'e_wallet',
      selectedWallet: 'momo',
      bankInfo: {
        bank: 'Ngân hàng quân đội (MB BANK)',
        accountName: 'CÔNG TY QUẢN LÝ BÃI ĐỖ XE ABC',
        accountNumber: '7722222222229'
      },
      creditCardInfo: { cardNumber: '', cardName: '', expiryDate: '', cvv: '' },
      discount: 0,
      isProcessing: false,
      showSuccessModal: false,
      paymentInfo: { transactionId: '', paymentDate: null, method: '', amount: 0 },
      monthlyFee: 100000, // Phí gửi xe hàng tháng: 100.000đ
      vietQRUrl: '', // URL mã QR từ API
      isLoadingQR: false, // Trạng thái đang tải mã QR,
      obj_checkout: {}
    }
  },
  computed: {
    expiredVehicles() {
      return this.userVehicles.filter(v => this.isExpired(v.ngay_het_han))
    },
    totalAmount() {
      return this.selectedVehicle ? this.monthlyFee : 0
    },
    finalAmount() {
      return this.totalAmount - this.discount
    },
    isFormValid() {
      if (!this.selectedVehicle) return false
      if (this.selectedPaymentMethod === 'e_wallet' && !this.selectedWallet) return false
      return true
    },
    validationMessage() {
      if (!this.selectedVehicle) return 'Vui lòng chọn một xe đã hết hạn để thanh toán'
      if (this.selectedPaymentMethod === 'e_wallet' && !this.selectedWallet) return 'Vui lòng chọn ví điện tử'
      return ''
    },
    transferContent() {
      return `${this.userInfo.id} THANHTOAN GUIXE`
    }
  },
  mounted() {
    this.fetchData()
  },
  methods: {
    getXe() {
      baseRequestUser.get('user/lay-du-lieu-xe').then((res) => {
        this.userVehicles = res.data.data
      })
    },
    async fetchData() {
      const userRes = await baseRequestUser.get('user/profile')
      this.userInfo = userRes.data.data
      this.getXe()
      this.selectedVehicle = this.userVehicles.find(v => this.isExpired(v.ngay_het_han))
      this.calculateTotal()
    },
    calculateTotal() {
      if (this.selectedVehicle) {
        this.discount = 0
      }

      // Nếu đang chọn phương thức chuyển khoản, cập nhật mã QR
      if (this.selectedPaymentMethod === 'bank_transfer' && this.selectedVehicle) {
        this.postQrPayment()
      }
    },
    copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(() => {
        alert('Đã sao chép vào clipboard')
      }).catch(err => {
        console.error('Không thể sao chép: ', err)
      })
    },
    postQrPayment() {
      if (!this.selectedVehicle) return

      const payload = {
        id_xe: this.selectedVehicle.id,
        gia_tien: this.finalAmount
      }

      this.isLoadingQR = true
      this.vietQRUrl = '' // Reset URL

      baseRequestUser.post('user/thanh-toan-xe', payload)
        .then(response => {
          console.log('QR payment request successful:', response.data)
          // Cập nhật URL QR từ response
          if (response.data && response.data.link) {
            this.vietQRUrl = response.data.link
            this.obj_checkout = response.data.hoaDon
          }
        })
        .catch(error => {
          console.error('QR payment request failed:', error)
          alert('Không thể tạo mã QR thanh toán. Vui lòng thử lại sau.')
        })
        .finally(() => {
          this.isLoadingQR = false
        })
    },
    setTrangThaiThanhToan() {
      baseRequestUser.post('user/set-transiton', {
        id: this.obj_checkout.id,
      })
        .then(response => {
          console.log('Payment status updated successfully:', response.data)
        })
        .catch(error => {
          console.error('Failed to update payment status:', error)
        })
    },
    processPayment() {
      if (!this.isFormValid) return
      this.isProcessing = true
      setTimeout(() => {
        baseRequestUser.get('user/thanh-toan/index')
        .then(response => {
          if (response.data.status == true) {
            this.showSuccessModal = true
          } else {
            this.setTrangThaiThanhToan()
          }
          // Hiển thị modal thành công
          this.showSuccessModal = true
        })
        .catch(error => {
          this.setTrangThaiThanhToan()
          this.showSuccessModal = true

        })
        .finally(() => {
          this.isProcessing = false
        })
      }, 2000)
      
    },
    generateTransactionId() {
      const prefix = 'TX'
      const timestamp = new Date().getTime().toString().slice(-6)
      const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0')
      return `${prefix}${timestamp}${random}`
    },
    downloadReceipt() {
      this.$router.push('/user/profile')
    },
    closeSuccessModal() {
      this.showSuccessModal = false
      this.$router.push('/user/profile')
    },
    goToRegisterVehicle() {
      alert('Chuyển đến trang đăng ký xe')
    },
    getInitials(name) {
      if (!name) return ''
      return name.split(' ').map(word => word.charAt(0)).join('').toUpperCase().substring(0, 2)
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString('vi-VN', { year: 'numeric', month: '2-digit', day: '2-digit' })
    },
    formatDateTime(date) {
      return new Date(date).toLocaleString('vi-VN', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' })
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount || 0)
    },
    getPaymentMethodName(methodId) {
      const method = this.paymentMethods.find(m => m.id === methodId)
      return method ? method.name : 'Không xác định'
    },
    isExpired(ngay_het_han) {
      return new Date(ngay_het_han) < new Date()
    },
    handleQRError() {
      alert('Không thể tải hình ảnh VietQR')
    },
    selectPaymentMethod(methodId) {
      this.selectedPaymentMethod = methodId

      // Nếu chọn phương thức chuyển khoản, gọi API để tạo mã QR
      if (methodId === 'bank_transfer' && this.selectedVehicle) {
        this.postQrPayment()
      }
    }
  }
}
</script>