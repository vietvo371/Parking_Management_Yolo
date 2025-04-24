import { defineStore } from "pinia"
import { ref } from "vue"

export const useAuthStore = defineStore("auth", () => {
  const user = ref(null)
  const isAuthenticated = ref(false)

  // Đăng nhập
  const login = (username, password) => {
    // Mô phỏng xác thực
    return new Promise((resolve) => {
      setTimeout(() => {
        // Trong thực tế sẽ kiểm tra thông tin đăng nhập
        // Ở đây chỉ mô phỏng đơn giản
        user.value = {
          id: 1,
          username: username,
          role: "admin",
          name: "Quản trị viên",
        }
        isAuthenticated.value = true
        resolve({ success: true, user: user.value })
      }, 1000)
    })
  }

  // Đăng xuất
  const logout = () => {
    return new Promise((resolve) => {
      setTimeout(() => {
        user.value = null
        isAuthenticated.value = false
        resolve({ success: true })
      }, 500)
    })
  }

  // Kiểm tra trạng thái xác thực
  const checkAuth = () => {
    // Trong thực tế sẽ kiểm tra token hoặc session
    // Ở đây chỉ mô phỏng đơn giản
    return isAuthenticated.value
  }

  return {
    user,
    isAuthenticated,
    login,
    logout,
    checkAuth,
  }
})
