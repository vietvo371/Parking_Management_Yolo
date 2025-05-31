import { defineStore } from "pinia"
import { ref } from "vue"

export const useVehiclesStore = defineStore("vehicles", () => {
  // Dữ liệu mẫu
  const vehicles = ref([
    {
      id: 1,
      licensePlate: "51F-12345",
      owner: "Nguyễn Văn A",
      apartment: "A-101",
      type: "Ô tô",
      brand: "Toyota",
      model: "Camry",
      color: "Trắng",
      status: "active",
      registeredDate: "01/01/2025",
    },
    {
      id: 2,
      licensePlate: "51F-12346",
      owner: "Trần Thị B",
      apartment: "B-202",
      type: "Ô tô",
      brand: "Honda",
      model: "Civic",
      color: "Đen",
      status: "active",
      registeredDate: "05/01/2025",
    },
    {
      id: 3,
      licensePlate: "51F-12347",
      owner: "Lê Văn C",
      apartment: "C-303",
      type: "Xe máy",
      brand: "Honda",
      model: "SH",
      color: "Đỏ",
      status: "active",
      registeredDate: "10/01/2025",
    },
    {
      id: 4,
      licensePlate: "51F-12348",
      owner: "Phạm Thị D",
      apartment: "A-104",
      type: "Xe máy",
      brand: "Yamaha",
      model: "Exciter",
      color: "Xanh",
      status: "inactive",
      registeredDate: "15/01/2025",
    },
    {
      id: 5,
      licensePlate: "51F-12349",
      owner: "Hoàng Văn E",
      apartment: "B-205",
      type: "Ô tô",
      brand: "Mazda",
      model: "CX-5",
      color: "Xám",
      status: "active",
      registeredDate: "20/01/2025",
    },
  ])

  // Thêm xe mới
  const addVehicle = (vehicle) => {
    const newVehicle = {
      id: vehicles.value.length + 1,
      ...vehicle,
      status: vehicle.status || "active",
      registeredDate: new Date().toLocaleDateString("vi-VN"),
    }
    vehicles.value.push(newVehicle)
    return newVehicle
  }

  // Cập nhật xe
  const updateVehicle = (id, updatedData) => {
    const index = vehicles.value.findIndex((v) => v.id === id)
    if (index !== -1) {
      vehicles.value[index] = { ...vehicles.value[index], ...updatedData }
      return vehicles.value[index]
    }
    return null
  }

  // Xóa xe
  const deleteVehicle = (id) => {
    const index = vehicles.value.findIndex((v) => v.id === id)
    if (index !== -1) {
      vehicles.value.splice(index, 1)
      return true
    }
    return false
  }

  // Lấy xe theo ID
  const getVehicleById = (id) => {
    return vehicles.value.find((v) => v.id === id) || null
  }

  // Lấy danh sách xe theo loại và trạng thái
  const getFilteredVehicles = (type = null, status = null, search = "") => {
    let result = [...vehicles.value]

    if (type) {
      result = result.filter((v) => v.type === type)
    }

    if (status) {
      result = result.filter((v) => v.status === status)
    }

    if (search) {
      const searchLower = search.toLowerCase()
      result = result.filter(
        (v) =>
          v.licensePlate.toLowerCase().includes(searchLower) ||
          v.owner.toLowerCase().includes(searchLower) ||
          v.apartment.toLowerCase().includes(searchLower),
      )
    }

    return result
  }

  return {
    vehicles,
    addVehicle,
    updateVehicle,
    deleteVehicle,
    getVehicleById,
    getFilteredVehicles,
  }
})
