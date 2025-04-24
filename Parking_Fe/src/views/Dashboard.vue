<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <h1 class="text-2xl font-bold tracking-tight">Tổng quan</h1>
      <div class="flex items-center gap-2">
        <button class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700">Xuất báo cáo</button>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Quét biển số</button>
      </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
      <div v-for="card in statCards" :key="card.title" class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="flex items-center justify-between mb-2">
          <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ card.title }}</h2>
          <component :is="card.icon" class="h-4 w-4 text-gray-400" />
        </div>
        <div class="text-2xl font-bold">{{ card.value }}</div>
        <p class="text-xs text-gray-500 dark:text-gray-400">{{ card.change }}</p>
      </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm col-span-4">
        <h2 class="text-lg font-medium mb-4">Thống kê lượt xe</h2>
        <DashboardChart />
      </div>
      
      <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm col-span-3">
        <h2 class="text-lg font-medium mb-4">Nhận diện biển số gần đây</h2>
        <div class="space-y-4">
          <div v-for="(detection, i) in recentDetections" :key="i" class="flex items-center space-x-4 rounded-md border p-3">
            <div class="relative h-16 w-24 overflow-hidden rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
              <span class="text-xs text-gray-500">51F-{{ 12345 + i }}</span>
            </div>
            <div class="flex-1 space-y-1">
              <p class="text-sm font-medium leading-none">51F-{{ 12345 + i }}</p>
              <p class="text-sm text-gray-500">
                {{ i === 1 ? "Vào bãi" : "Ra khỏi bãi" }} - {{ i * 5 }} phút trước
              </p>
              <p class="text-xs text-gray-500">Cư dân: Nguyễn Văn {{ String.fromCharCode(65 + i) }}</p>
            </div>
            <div :class="`h-2 w-2 rounded-full ${i === 1 ? 'bg-green-500' : 'bg-blue-500'}`"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
      <div class="border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between p-4">
          <div class="flex space-x-4">
            <button 
              @click="activeTab = 'recent'"
              class="px-4 py-2 text-sm font-medium rounded-md"
              :class="activeTab === 'recent' ? 'bg-gray-100 dark:bg-gray-700' : ''"
            >
              Hoạt động gần đây
            </button>
            <button 
              @click="activeTab = 'pending'"
              class="px-4 py-2 text-sm font-medium rounded-md"
              :class="activeTab === 'pending' ? 'bg-gray-100 dark:bg-gray-700' : ''"
            >
              Chờ xử lý
            </button>
          </div>
          <button class="flex items-center px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-md">
            <Clock class="mr-2 h-4 w-4" />
            Xem tất cả
          </button>
        </div>
      </div>
      <div class="p-4">
        <RecentActivityTable :is-pending="activeTab === 'pending'" />
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import { Car, Users, CreditCard, AlertTriangle, Clock } from "lucide-vue-next";
import DashboardChart from "@/components/DashboardChart.vue";
import RecentActivityTable from "@/components/RecentActivityTable.vue";

export default {
  name: "Dashboard",
  components: {
    DashboardChart,
    RecentActivityTable,
    Clock,
  },
  setup() {
    const activeTab = ref("recent");

    const statCards = [
      {
        title: "Tổng số xe",
        value: "245",
        change: "+12% so với tháng trước",
        icon: Car,
      },
      {
        title: "Cư dân",
        value: "120",
        change: "+4 cư dân mới trong tuần",
        icon: Users,
      },
      {
        title: "Doanh thu",
        value: "12.5M VND",
        change: "+8% so với tháng trước",
        icon: CreditCard,
      },
      {
        title: "Cảnh báo",
        value: "3",
        change: "Xe không xác định trong ngày",
        icon: AlertTriangle,
      },
    ];

    const recentDetections = [{ id: 1 }, { id: 2 }, { id: 3 }];

    return {
      activeTab,
      statCards,
      recentDetections,
    };
  },
};
</script>

<style>
</style>
