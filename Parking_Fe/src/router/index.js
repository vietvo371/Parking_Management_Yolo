import { createRouter, createWebHistory } from "vue-router"

const routes = [
  {
    path: "/",
    name: "Dashboard",
    component: () => import("../views/Dashboard.vue"),
  },
  {
    path: "/vehicles",
    name: "Vehicles",
    component: () => import("../views/Vehicles.vue"),
  },
  {
    path: "/vehicles/add",
    name: "AddVehicle",
    component: () => import("../views/AddVehicle.vue"),
  },
  {
    path: "/residents",
    name: "Residents",
    component: () => import("../views/Residents.vue"),
  },
  {
    path: "/residents/add",
    name: "AddResident",
    component: () => import("../views/AddResident.vue"),
  },
  {
    path: "/transactions",
    name: "Transactions",
    component: () => import("../views/Transactions.vue"),
  },
  {
    path: "/cameras",
    name: "Cameras",
    component: () => import("../views/Cameras.vue"),
  },
  {
    path: "/reports",
    name: "Reports",
    component: () => import("../views/Reports.vue"),
  },
  {
    path: "/history",
    name: "History",
    component: () => import("../views/History.vue"),
  },
  {
    path: "/settings",
    name: "Settings",
    component: () => import("../views/Settings.vue"),
  },
  {
    path: "/login",
    name: "Login",
    component: () => import("../views/Login.vue"),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
