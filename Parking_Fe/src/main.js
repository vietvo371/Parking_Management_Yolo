import { createApp } from "vue"
import { createPinia } from "pinia"
import App from "./App.vue"
import router from "./router"
import "./assets/tailwind.css"

// Tạo ứng dụng Vue
const app = createApp(App)

// Tạo và sử dụng Pinia cho state management
const pinia = createPinia()
app.use(pinia)

// Sử dụng Vue Router
app.use(router)

// Mount ứng dụng
app.mount("#app")
