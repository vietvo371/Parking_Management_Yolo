import { createRouter, createWebHistory } from "vue-router"
import checkLoginAdmin from "./checkLoginAdmin.js";


const routes = [

  {
    path: "/admin",
    component: () => import("../layouts/wrapper/index.vue"),
    children: [
        {
            path : '',
            component: ()=>import('../views/Dashboard.vue'),
            beforeEnter: checkLoginAdmin,

        },
        {
          path: "quan-ly-xe",
          name: "Vehicles",
          component: () => import("../views/Vehicles.vue"), 
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-xe/them-xe",
          name: "AddVehicle",
          component: () => import("../views/AddVehicle.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-khach-hang",
          name: "Residents",
          component: () => import("../views/Residents.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-khach-hang/them-khach-hang",
          name: "AddResident",
          component: () => import("../views/AddResident.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-giao-dich",
          name: "Transactions",
          component: () => import("../views/Transactions.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-camera",
          name: "Cameras",
          component: () => import("../views/Cameras.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-bao-cao",
          name: "Reports",
          component: () => import("../views/Reports.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-lich-su",
          name: "History",
          component: () => import("../views/History.vue"),
          beforeEnter: checkLoginAdmin,
        },
        {
          path: "quan-ly-cai-dat",
          name: "Settings",
          component: () => import("../views/Settings.vue"),
          beforeEnter: checkLoginAdmin,
        },
    ],
  },
  {
    path: "/dang-nhap",
    component: () => import("../views/Login.vue"),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
