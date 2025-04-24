<template>
  <div>
    <apexchart
      type="line"
      height="300"
      :options="chartOptions"
      :series="series"
    ></apexchart>
  </div>
</template>

<script>
import { ref, computed, onMounted, watch } from "vue";
import VueApexCharts from "vue3-apexcharts";

export default {
  name: "DashboardChart",
  components: {
    apexchart: VueApexCharts,
  },
  setup() {
    const isDark = ref(false);

    onMounted(() => {
      isDark.value = document.documentElement.classList.contains("dark");

      // Lắng nghe sự thay đổi theme
      const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
          if (mutation.attributeName === "class") {
            isDark.value = document.documentElement.classList.contains("dark");
          }
        });
      });

      observer.observe(document.documentElement, { attributes: true });

      return () => {
        observer.disconnect();
      };
    });

    const data = [
      { name: "T2", vao: 40, ra: 35 },
      { name: "T3", vao: 45, ra: 42 },
      { name: "T4", vao: 55, ra: 50 },
      { name: "T5", vao: 60, ra: 58 },
      { name: "T6", vao: 75, ra: 70 },
      { name: "T7", vao: 85, ra: 80 },
      { name: "CN", vao: 65, ra: 60 },
    ];

    const series = [
      {
        name: "Lượt vào",
        data: data.map((item) => item.vao),
      },
      {
        name: "Lượt ra",
        data: data.map((item) => item.ra),
      },
    ];

    const chartOptions = computed(() => ({
      chart: {
        type: "line",
        toolbar: {
          show: false,
        },
        fontFamily: "inherit",
        foreColor: isDark.value ? "#9ca3af" : "#6b7280",
      },
      colors: ["#3b82f6", "#10b981"],
      stroke: {
        curve: "smooth",
        width: 3,
      },
      xaxis: {
        categories: data.map((item) => item.name),
        labels: {
          style: {
            colors: isDark.value ? "#9ca3af" : "#6b7280",
          },
        },
      },
      yaxis: {
        labels: {
          style: {
            colors: isDark.value ? "#9ca3af" : "#6b7280",
          },
        },
      },
      grid: {
        borderColor: isDark.value ? "#374151" : "#e5e7eb",
        strokeDashArray: 3,
      },
      markers: {
        size: 5,
        hover: {
          size: 7,
        },
      },
      tooltip: {
        theme: isDark.value ? "dark" : "light",
      },
      legend: {
        labels: {
          colors: isDark.value ? "#ffffff" : "#000000",
        },
      },
    }));

    return {
      series,
      chartOptions,
    };
  },
};
</script>

<style>
</style>
