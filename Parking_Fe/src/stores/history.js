import { defineStore } from "pinia"
import { ref } from "vue"

export const useHistoryStore = defineStore("history", () => {
  // Dữ liệu mẫu
  const historyEntries = ref([
    {
      id: 1,
      licensePlate: "51F-12345",
      resident: "Nguyễn Văn A",
      apartment: "A-101",
      type: "Vào bãi",
      time: "08:30 - 24/04/2025",
      camera: "Camera cổng chính",
      confidence: 98.5,
    },
    {
      id: 2,
      licensePlate: "51F-12346",
      resident: "Trần Thị B",
      apartment: "B-202",
      type: "Ra khỏi bãi",
      time: "09:15 - 24/04/2025",
      camera: "Camera cổng chính",
      confidence: 97.2,
    },
    {
      id: 3,
      licensePlate: "51F-12347",
      resident: "Lê Văn C",
      apartment: "C-303",
      type: "Vào bãi",
      time: "09:45 - 24/04/2025",
      camera: "Camera cổng phụ",
      confidence: 99.1,
    },
    {
      id: 4,
      licensePlate: "51F-12348",
      resident: "Phạm Thị D",
      apartment: "A-104",
      type: "Vào bãi",
      time: "10:00 - 24/04/2025",
      camera: "Camera cổng chính",
      confidence: 96.8,
    },
    {
      id: 5,
      licensePlate: "51F-12349",
      resident: "Hoàng Văn E",
      apartment: "B-205",
      type: "Ra khỏi bãi",
      time: "10:15 - 24/04/2025",
      camera: "Camera cổng phụ",
      confidence: 98.3,
    },
    {
      id: 6,
      licensePlate: "51F-12350",
      resident: "Không xác định",
      apartment: "Không xác định",
      type: "Xe không đăng ký",
      time: "10:30 - 24/04/2025",
      camera: "Camera cổng chính",
      confidence: 95.7,
    },
    {
      id: 7,
      licensePlate: "51F-12345",
      resident: "Nguyễn Văn A",
      apartment: "A-101",
      type: "Ra khỏi bãi",
      time: "17:45 - 24/04/2025",
      camera: "Camera cổng chính",
      confidence: 97.9,
    },
  ])

  // Thêm bản ghi lịch sử mới
  const addHistoryEntry = (entry) => {
    const newEntry = {
      id: historyEntries.value.length + 1,
      ...entry,
      time: `${new Date().toLocaleTimeString("vi-VN", { hour: "2-digit", minute: "2-digit" })} - ${new Date().toLocaleDateString("vi-VN")}`,
      confidence: entry.confidence || 95 + Math.random() * 4,
    }
    historyEntries.value.push(newEntry)
    return newEntry
  }

  // Lấy bản ghi lịch sử theo ID
  const getHistoryEntryById = (id) => {
    return historyEntries.value.find((h) => h.id === id) || null
  }

  // Lấy danh sách lịch sử theo loại
  const getFilteredHistory = (type = null, search = "", startDate = null, endDate = null) => {
    let result = [...historyEntries.value]

    if (type) {
      result = result.filter((h) => h.type === type)
    }

    if (search) {
      const searchLower = search.toLowerCase()
      result = result.filter(
        (h) =>
          h.licensePlate.toLowerCase().includes(searchLower) ||
          h.resident.toLowerCase().includes(searchLower) ||
          h.apartment.toLowerCase().includes(searchLower),
      )
    }

    if (startDate && endDate) {
      // Trong thực tế sẽ cần xử lý chuyển đổi định dạng ngày tháng
      // Đây chỉ là mô phỏng đơn giản
      result = result.filter((h) => h.time >= startDate && h.time <= endDate)
    }

    return result
  }

  return {
    historyEntries,
    addHistoryEntry,
    getHistoryEntryById,
    getFilteredHistory,
  }
})
