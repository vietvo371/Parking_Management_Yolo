import { createApp } from "vue"
import { createPinia } from "pinia"
import App from "./App.vue"
import router from "./router"
import "./assets/tailwind.css"
import "ant-design-vue/dist/reset.css";
import { notification , Popconfirm} from "ant-design-vue";

notification.config({
    placement: "topRight", // Default placement
    duration: 2, // Default duration
  });
// Tạo ứng dụng Vue

const app = createApp(App)
// Tạo và sử dụng Pinia cho state management
const pinia = createPinia()
app.use(pinia)
// Sử dụng Vue Router
app.use(router)
app.use(Popconfirm)
// Mount ứng dụng
app.mount("#app")
