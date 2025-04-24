import { defineStore } from "pinia"
import { ref } from "vue"

export const useTransactionsStore = defineStore("transactions", () => {
  // Dữ liệu mẫu
  const transactions = ref([
    {
      id: "TX-001",
      resident: "Nguyễn Văn A",
      apartment: "A-101",
      licensePlate: "51F-12345",
      amount: 500000,
      type: "Phí gửi xe tháng",
      date: "01/04/2025",
      status: "completed",
      paymentMethod: "Chuyển khoản",
    },
    {
      id: "TX-002",
      resident: "Trần Thị B",
      apartment: "B-202",
      licensePlate: "51F-12346",
      amount: 500000,
      type: "Phí gửi xe tháng",
      date: "02/04/2025",
      status: "completed",
      paymentMethod: "Tiền mặt",
    },
    {
      id: "TX-003",
      resident: "Lê Văn C",
      apartment: "C-303",
      licensePlate: "51F-12347",
      amount: 300000,
      type: "Phí gửi xe tháng",
      date: "03/04/2025",
      status: "completed",
      paymentMethod: "Chuyển khoản",
    },
    {
      id: "TX-004",
      resident: "Phạm Thị D",
      apartment: "A-104",
      licensePlate: "51F-12348",
      amount: 300000,
      type: "Phí gửi xe tháng",
      date: "05/04/2025",
      status: "pending",
      paymentMethod: "Đang chờ",
    },
    {
      id: "TX-005",
      resident: "Hoàng Văn E",
      apartment: "B-205",
      licensePlate: "51F-12349",
      amount: 500000,
      type: "Phí gửi xe tháng",
      date: "10/04/2025",
      status: "pending",
      paymentMethod: "Đang chờ",
    },
  ])

  // Thêm giao dịch mới
  const addTransaction = (transaction) => {
    const newTransaction = {
      id: `TX-${(transactions.value.length + 1).toString().padStart(3, "0")}`,
      ...transaction,
      date: new Date().toLocaleDateString("vi-VN"),
      status: transaction.status || "pending",
      paymentMethod: transaction.paymentMethod || "Đang chờ",
    }
    transactions.value.push(newTransaction)
    return newTransaction
  }

  // Cập nhật giao dịch
  const updateTransaction = (id, updatedData) => {
    const index = transactions.value.findIndex((t) => t.id === id)
    if (index !== -1) {
      transactions.value[index] = { ...transactions.value[index], ...updatedData }
      return transactions.value[index]
    }
    return null
  }

  // Xóa giao dịch
  const deleteTransaction = (id) => {
    const index = transactions.value.findIndex((t) => t.id === id)
    if (index !== -1) {
      transactions.value.splice(index, 1)
      return true
    }
    return false
  }

  // Lấy giao dịch theo ID
  const getTransactionById = (id) => {
    return transactions.value.find((t) => t.id === id) || null
  }

  // Lấy danh sách giao dịch theo trạng thái
  const getFilteredTransactions = (status = null, search = "", startDate = null, endDate = null) => {
    let result = [...transactions.value]

    if (status) {
      result = result.filter((t) => t.status === status)
    }

    if (search) {
      const searchLower = search.toLowerCase()
      result = result.filter(
        (t) =>
          t.id.toLowerCase().includes(searchLower) ||
          t.resident.toLowerCase().includes(searchLower) ||
          t.licensePlate.toLowerCase().includes(searchLower),
      )
    }

    if (startDate && endDate) {
      // Trong thực tế sẽ cần xử lý chuyển đổi định dạng ngày tháng
      // Đây chỉ là mô phỏng đơn giản
      result = result.filter((t) => t.date >= startDate && t.date <= endDate)
    }

    return result
  }

  return {
    transactions,
    addTransaction,
    updateTransaction,
    deleteTransaction,
    getTransactionById,
    getFilteredTransactions,
  }
})
