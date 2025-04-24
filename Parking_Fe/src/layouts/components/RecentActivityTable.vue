<template>
  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="bg-gray-50 dark:bg-gray-800">
      <tr>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Biển số xe</th>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Thời gian</th>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cư dân</th>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Loại</th>
        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Trạng thái</th>
        <th v-if="isPending" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hành động</th>
      </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
      <tr v-for="activity in activities" :key="activity.id">
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ activity.licensePlate }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ activity.time }}</td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ activity.resident }}</td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span 
            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
            :class="{
              'bg-blue-100 text-blue-800': activity.type.includes('Vào'),
              'bg-green-100 text-green-800': activity.type.includes('Ra'),
              'bg-red-100 text-red-800': !activity.type.includes('Vào') && !activity.type.includes('Ra')
            }"
          >
            {{ activity.type }}
          </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
          <span 
            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
            :class="{
              'bg-green-100 text-green-800': activity.status === 'success',
              'bg-yellow-100 text-yellow-800': activity.status === 'pending'
            }"
          >
            {{ activity.status === 'success' ? 'Thành công' : 'Chờ xử lý' }}
          </span>
        </td>
        <td v-if="isPending" class="px-6 py-4 whitespace-nowrap text-sm font-medium">
          <div class="flex space-x-2">
            <button class="p-1 rounded-full bg-green-100 text-green-600 hover:bg-green-200">
              <CheckCircle class="h-4 w-4" />
            </button>
            <button class="p-1 rounded-full bg-red-100 text-red-600 hover:bg-red-200">
              <XCircle class="h-4 w-4" />
            </button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script>
import { computed } from "vue";
import { CheckCircle, XCircle } from "lucide-vue-next";

export default {
  name: "RecentActivityTable",
  components: {
    CheckCircle,
    XCircle,
  },
  props: {
    isPending: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const activities = computed(() => {
      if (props.isPending) {
        return [
          {
            id: 1,
            licensePlate: "51F-12350",
            time: "10:30 - 24/04/2025",
            resident: "Nguyễn Văn A",
            type: "Xe không đăng ký",
            status: "pending",
          },
          {
            id: 2,
            licensePlate: "51F-12351",
            time: "11:45 - 24/04/2025",
            resident: "Không xác định",
            type: "Xe khách",
            status: "pending",
          },
          {
            id: 3,
            licensePlate: "51F-12352",
            time: "13:15 - 24/04/2025",
            resident: "Trần Thị B",
            type: "Xe hết hạn đăng ký",
            status: "pending",
          },
        ];
      } else {
        return [
          {
            id: 1,
            licensePlate: "51F-12345",
            time: "08:30 - 24/04/2025",
            resident: "Nguyễn Văn A",
            type: "Vào bãi",
            status: "success",
          },
          {
            id: 2,
            licensePlate: "51F-12346",
            time: "09:15 - 24/04/2025",
            resident: "Trần Thị B",
            type: "Ra khỏi bãi",
            status: "success",
          },
          {
            id: 3,
            licensePlate: "51F-12347",
            time: "09:45 - 24/04/2025",
            resident: "Lê Văn C",
            type: "Vào bãi",
            status: "success",
          },
          {
            id: 4,
            licensePlate: "51F-12348",
            time: "10:00 - 24/04/2025",
            resident: "Phạm Thị D",
            type: "Vào bãi",
            status: "success",
          },
          {
            id: 5,
            licensePlate: "51F-12349",
            time: "10:15 - 24/04/2025",
            resident: "Hoàng Văn E",
            type: "Ra khỏi bãi",
            status: "success",
          },
        ];
      }
    });

    return {
      activities,
    };
  },
};
</script>

<style>
</style>
