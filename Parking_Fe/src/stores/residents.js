import { defineStore } from "pinia"
import { ref } from "vue"

export const useResidentsStore = defineStore("residents", () => {
  // Dữ liệu mẫu
  const residents = ref([
    {
      id: 1,
      name: "Nguyễn Văn A",
      apartment: "A-101",
      phone: "0901234567",
      email: "nguyenvana@example.com",
      idNumber: "012345678901",
      vehicles: 2,
      status: "active",
      joinDate: "01/01/2025",
    },
    {
      id: 2,
      name: "Trần Thị B",
      apartment: "B-202",
      phone: "0912345678",
      email: "tranthib@example.com",
      idNumber: "012345678902",
      vehicles: 1,
      status: "active",
      joinDate: "05/01/2025",
    },
    {
      id: 3,
      name: "Lê Văn C",
      apartment: "C-303",
      phone: "0923456789",
      email: "levanc@example.com",
      idNumber: "012345678903",
      vehicles: 2,
      status: "active",
      joinDate: "10/01/2025",
    },
    {
      id: 4,
      name: "Phạm Thị D",
      apartment: "A-104",
      phone: "0934567890",
      email: "phamthid@example.com",
      idNumber: "012345678904",
      vehicles: 1,
      status: "inactive",
      joinDate: "15/01/2025",
    },
    {
      id: 5,
      name: "Hoàng Văn E",
      apartment: "B-205",
      phone: "0945678901",
      email: "hoangvane@example.com",
      idNumber: "012345678905",
      vehicles: 1,
      status: "active",
      joinDate: "20/01/2025",
    },
  ])

  // Thêm cư dân mới
  const addResident = (resident) => {
    const newResident = {
      id: residents.value.length + 1,
      ...resident,
      vehicles: resident.vehicles || 0,
      status: resident.status || "active",
      joinDate: new Date().toLocaleDateString("vi-VN"),
    }
    residents.value.push(newResident)
    return newResident
  }

  // Cập nhật cư dân
  const updateResident = (id, updatedData) => {
    const index = residents.value.findIndex((r) => r.id === id)
    if (index !== -1) {
      residents.value[index] = { ...residents.value[index], ...updatedData }
      return residents.value[index]
    }
    return null
  }

  // Xóa cư dân
  const deleteResident = (id) => {
    const index = residents.value.findIndex((r) => r.id === id)
    if (index !== -1) {
      residents.value.splice(index, 1)
      return true
    }
    return false
  }

  // Lấy cư dân theo ID
  const getResidentById = (id) => {
    return residents.value.find((r) => r.id === id) || null
  }

  // Lấy danh sách cư dân theo trạng thái
  const getFilteredResidents = (status = null, search = "") => {
    let result = [...residents.value]

    if (status) {
      result = result.filter((r) => r.status === status)
    }

    if (search) {
      const searchLower = search.toLowerCase()
      result = result.filter(
        (r) =>
          r.name.toLowerCase().includes(searchLower) ||
          r.apartment.toLowerCase().includes(searchLower) ||
          r.phone.includes(search) ||
          r.email.toLowerCase().includes(searchLower),
      )
    }

    return result
  }

  return {
    residents,
    addResident,
    updateResident,
    deleteResident,
    getResidentById,
    getFilteredResidents,
  }
})
