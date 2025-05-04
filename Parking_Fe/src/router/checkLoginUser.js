//Sau này đổi tên lại thành Middleware nhé

import baseRequest from "../core/baseRequest";
import { useNotificationStore } from "@/stores/notication";

export default function (to, from, next) {
    const notificationStore = useNotificationStore();
    baseRequest
    .get("user/check-token")
    .then((res) => {
      if (res.data.status) {
        next();
      } else {
        notificationStore.showWarning("Bạn cần đăng nhập hệ thống trước");
        next("/");
      }
    })
    .catch(() => {
      notificationStore.showWarning("Bạn cần đăng nhập hệ thống trước");
      next("/");
    });
}
