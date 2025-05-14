import { createRouter, createWebHistory } from "vue-router";
import checkLoginAdmin from "./checkLoginAdmin.js";
import checkLoginUser from "./checkLoginUser.js";

const routes = [
  {
    path: "/",
    component: () => import("../layouts/wrapper/index.vue"),
    redirect: "/user/profile",
    children: [
      {
        path: "admin",
        component: () => import("../views/Admin/Dashboard.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-xe",
        name: "Vehicles",
        component: () => import("../views/Admin/Vehicles.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-xe/them-xe",
        name: "AddVehicle",
        component: () => import("../views/Admin/AddVehicle.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-khach-hang",
        name: "Residents",
        component: () => import("../views/Admin/Residents.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-khach-hang/them-khach-hang",
        name: "AddResident",
        component: () => import("../views/Admin/AddResident.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-giao-dich",
        name: "Transactions",
        component: () => import("../views/Admin/Transactions.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-camera",
        name: "Cameras",
        component: () => import("../views/Admin/Cameras.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-bao-cao",
        name: "Reports",
        component: () => import("../views/Admin/Reports.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-lich-su",
        name: "History",
        component: () => import("../views/Admin/History.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-cai-dat",
        name: "Settings",
        component: () => import("../views/Admin/Settings.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-camera/them-camera",
        name: "AddCamera",
        component: () => import("../views/Admin/AddCamera.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-li-bao-cao-su-co",
        name: "IncidentReports",
        component: () => import("../views/Admin/IncidentReports.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-li-bai-giu-xe",
        name: "ParkingManagement",
        component: () => import("../views/Admin/ParkingManagement.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/profile",
        name: "Profile",
        component: () => import("../views/Admin/Profile.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-khach-vang-lai",
        component: () => import("../views/Admin/KhachVangLai.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/phan-quyen",
        component: () => import("../views/Admin/PhanQuyen.vue"),
        beforeEnter: checkLoginAdmin,
      },
      {
        path: "admin/quan-ly-thong-bao",
        component: () => import("../views/Admin/Notification.vue"),
        beforeEnter: checkLoginAdmin,
      },
    ],
  },
  {
    path: "/user",
    component: () => import("../layouts/wrapper/clientLayout.vue"),
    redirect: "/user/profile",
    children: [
      {
        path: "profile",
        component: () => import("../views/User/Profile.vue"),
        beforeEnter: checkLoginUser,
      },
      {
        path: "bao-cao",
        component: () => import("../views/User/Reports.vue"),
        beforeEnter: checkLoginUser,
      },
      {
        path: "lich-su",
        component: () => import("../views/User/History.vue"),
        beforeEnter: checkLoginUser,
      },
      {
        path: "thanh-toan",
        component: () => import("../views/User/Transaction.vue"),
        beforeEnter: checkLoginUser,
      },
      {
        path: "quan-ly-xe",
        component: () => import("../views/User/MyVehicles.vue"),
        beforeEnter: checkLoginUser,
      },
      {
        path: "quan-ly-cho-xe",
        component: () => import("../views/User/ParkingManger.vue"),
        beforeEnter: checkLoginUser,
      },
      {
        path: "thanh-toan-online",
        component: () => import("../views/User/ThanhToanOnl.vue"),
        // beforeEnter: checkLoginUser,
      },
      
    ],
  },
  {
    path: "/login",
    component: () => import("../views/Admin/Login.vue"),
  },
  {
    path: "/user/login",
    component: () => import("../views/User/Login.vue"),
  },
  {
    path: "/user/register",
    component: () => import("../views/User/Register.vue"),
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
