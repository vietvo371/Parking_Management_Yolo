<template>
    <div class="max-w-2xl mx-auto p-6">
      <h1 class="text-2xl font-bold mb-4">Thanh toán online</h1>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-2">Số dư tài khoản: <span class="text-blue-600">{{ balance }} VNĐ</span></h2>
        <form @submit.prevent="handlePayment" class="space-y-4">
          <div>
            <label class="block text-sm font-medium mb-1">Số tiền cần thanh toán</label>
            <input v-model="amount" type="number" min="1000" required class="w-full px-4 py-2 rounded border" />
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Mô tả</label>
            <input v-model="description" type="text" class="w-full px-4 py-2 rounded border" />
          </div>
          <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Thanh toán</button>
        </form>
      </div>
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">Lịch sử giao dịch</h2>
        <ul>
          <li v-for="tx in transactions" :key="tx.id" class="mb-2">
            <span>{{ tx.created_at }} - {{ tx.amount }} VNĐ - {{ tx.status }}</span>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue'
  import baseRequest from '../../core/baseRequest'
  import { useNotificationStore } from '@/stores/notication'
  
  const balance = ref(0)
  const amount = ref('')
  const description = ref('')
  const transactions = ref([])
  const notificationStore = useNotificationStore()
  
  const fetchBalance = async () => {
    // Gọi API lấy số dư
    const res = await baseRequest.get('resident/balance')
    balance.value = res.data.balance
  }
  const fetchTransactions = async () => {
    // Gọi API lấy lịch sử giao dịch
    const res = await baseRequest.get('resident/payment/history')
    transactions.value = res.data.transactions
  }
  const handlePayment = async () => {
    if (!amount.value || amount.value < 1000) {
      notificationStore.showError('Số tiền tối thiểu là 1.000 VNĐ')
      return
    }
    const res = await baseRequest.post('resident/payment', {
      amount: amount.value,
      description: description.value
    })
    if (res.data.status) {
      notificationStore.showSuccess('Thanh toán thành công!')
      fetchBalance()
      fetchTransactions()
      amount.value = ''
      description.value = ''
    } else {
      notificationStore.showError(res.data.message || 'Thanh toán thất bại!')
    }
  }
  onMounted(() => {
    fetchBalance()
    fetchTransactions()
  })
  </script>