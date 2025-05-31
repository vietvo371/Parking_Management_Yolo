import { defineStore } from "pinia"
import { ref } from "vue"

export const useCamerasStore = defineStore("cameras", () => {
  // Dữ liệu mẫu
  const cameras = ref([
    {
      id: 1,
      name: "Camera cổng chính",
      location: "Cổng vào chính",
      status: "online",
      lastDetection: "2 phút trước",
      imageUrl: "https://via.placeholder.com/480x270?text=Camera+1",
    },
    {
      id: 2,
      name: "Camera cổng phụ",
      location: "Cổng phụ",
      status: "online",
      lastDetection: "5 phút trước",
      imageUrl: "https://via.placeholder.com/480x270?text=Camera+2",
    },
    {
      id: 3,
      name: "Camera bãi đỗ A",
      location: "Khu vực A",
      status: "online",
      lastDetection: "10 phút trước",
      imageUrl: "https://via.placeholder.com/480x270?text=Camera+3",
    },
    {
      id: 4,
      name: "Camera bãi đỗ B",
      location: "Khu vực B",
      status: "offline",
      lastDetection: "1 giờ trước",
      imageUrl: "https://via.placeholder.com/480x270?text=Camera+4",
    },
  ])

  // Thêm camera mới
  const addCamera = (camera) => {
    const newCamera = {
      id: cameras.value.length + 1,
      ...camera,
      status: camera.status || "online",
      lastDetection: "Chưa có",
      imageUrl: camera.imageUrl || `https://via.placeholder.com/480x270?text=Camera+${cameras.value.length + 1}`,
    }
    cameras.value.push(newCamera)
    return newCamera
  }

  // Cập nhật camera
  const updateCamera = (id, updatedData) => {
    const index = cameras.value.findIndex((c) => c.id === id)
    if (index !== -1) {
      cameras.value[index] = { ...cameras.value[index], ...updatedData }
      return cameras.value[index]
    }
    return null
  }

  // Xóa camera
  const deleteCamera = (id) => {
    const index = cameras.value.findIndex((c) => c.id === id)
    if (index !== -1) {
      cameras.value.splice(index, 1)
      return true
    }
    return false
  }

  // Lấy camera theo ID
  const getCameraById = (id) => {
    return cameras.value.find((c) => c.id === id) || null
  }

  // Mô phỏng nhận diện biển số
  const detectLicensePlate = (imageData) => {
    return new Promise((resolve) => {
      setTimeout(() => {
        // Mô phỏng kết quả nhận diện
        const randomConfidence = 95 + Math.random() * 4
        const randomPlate = `51F-${10000 + Math.floor(Math.random() * 90000)}`

        resolve({
          success: true,
          data: {
            licensePlate: randomPlate,
            confidence: randomConfidence.toFixed(2),
            timestamp: new Date().toISOString(),
          },
        })
      }, 1500)
    })
  }

  return {
    cameras,
    addCamera,
    updateCamera,
    deleteCamera,
    getCameraById,
    detectLicensePlate,
  }
})
