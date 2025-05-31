<?php

namespace App\Http\Controllers;

use App\Models\Xe;
use App\Models\CuDan;
use App\Models\GiaoDich;
use App\Models\BaoCaoKhachVangLai;
use App\Models\LoaiXe;
use App\Models\ChiTietBaiXe;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function getOverallStatistics(Request $request)
    {
        $id_chuc_nang = 11;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }

        // Handle time range filtering
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $timeRange = $request->input('timeRange', 'last30days');

        // Set default date range if not provided
        if (!$startDate || !$endDate) {
            switch ($timeRange) {
                case 'today':
                    $startDate = Carbon::today();
                    $endDate = Carbon::today()->endOfDay();
                    break;
                case 'yesterday':
                    $startDate = Carbon::yesterday();
                    $endDate = Carbon::yesterday()->endOfDay();
                    break;
                case 'last7days':
                    $startDate = Carbon::now()->subDays(7)->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
                    break;
                case 'thisMonth':
                    $startDate = Carbon::now()->startOfMonth();
                    $endDate = Carbon::now()->endOfMonth();
                    break;
                case 'lastMonth':
                    $startDate = Carbon::now()->subMonth()->startOfMonth();
                    $endDate = Carbon::now()->subMonth()->endOfMonth();
                    break;
                case 'thisYear':
                    $startDate = Carbon::now()->startOfYear();
                    $endDate = Carbon::now()->endOfYear();
                    break;
                case 'last30days':
                default:
                    $startDate = Carbon::now()->subDays(30)->startOfDay();
                    $endDate = Carbon::now()->endOfDay();
            }
        } else {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
        }

        // Get total vehicles with type breakdown
        $totalVehicles = Xe::where('trang_thai_duyet', 1)->count();
        $carCount = Xe::join('loai_xes', 'loai_xes.id', '=', 'xes.id_loai_xe')
            ->where('xes.trang_thai_duyet', 1)
            ->where('loai_xes.ten_loai_xe', 'like', '%ô tô%')
            ->count();
        $motorbikeCount = $totalVehicles - $carCount;

        // Get total residents and calculate guest count
        $totalResidents = CuDan::count();
        $guestCount = BaoCaoKhachVangLai::whereBetween('created_at', [$startDate, $endDate])
            ->distinct('so_dien_thoai')
            ->count();

        // Calculate revenues
        $currentPeriodRegularRevenue = GiaoDich::where('trang_thai_giao_dich', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('so_tien_giao_dich');

        $currentPeriodVisitorRevenue = BaoCaoKhachVangLai::where('is_thanh_toan', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('tien_thanh_toan');

        // Calculate previous period for comparison
        $previousStartDate = (clone $startDate)->subDays($endDate->diffInDays($startDate));
        $previousEndDate = (clone $startDate)->subDay();

        $previousPeriodRevenue = GiaoDich::where('trang_thai_giao_dich', 1)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->sum('so_tien_giao_dich')
            + BaoCaoKhachVangLai::where('is_thanh_toan', 1)
            ->whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->sum('tien_thanh_toan');

        $currentPeriodRevenue = $currentPeriodRegularRevenue + $currentPeriodVisitorRevenue;

        // Calculate revenue change percentage
        $revenueChange = $previousPeriodRevenue > 0
            ? (($currentPeriodRevenue - $previousPeriodRevenue) / $previousPeriodRevenue) * 100
            : 0;

        // Calculate occupancy rate
        $totalSpaces = ChiTietBaiXe::count();
        $occupiedSpaces = ChiTietBaiXe::whereIn('trang_thai', [
            ChiTietBaiXe::TRANG_THAI_DA_CO_XE,
            ChiTietBaiXe::TRANG_THAI_DA_DAT_TRUOC,
            ChiTietBaiXe::TRANG_THAI_KHACH_VANG_LAI
        ])->count();
        $occupancyRate = $totalSpaces > 0 ? ($occupiedSpaces / $totalSpaces) * 100 : 0;

        // Get daily revenue data for charts
        $dailyRevenue = $this->getDailyRevenue($startDate, $endDate);

        return response()->json([
            'status' => true,
            'message' => 'Thống kê tổng quan',
            'data' => [
                'tong_xe' => $totalVehicles,
                'carCount' => $carCount,
                'motorbikeCount' => $motorbikeCount,
                'tong_cu_dan' => $totalResidents,
                'totalCustomers' => $totalResidents + $guestCount,
                'residentCount' => $totalResidents,
                'guestCount' => $guestCount,
                'tong_doanh_thu' => $currentPeriodRevenue,
                'revenueChange' => round($revenueChange, 2),
                'occupancyRate' => round($occupancyRate, 2),
                'chi_tiet_doanh_thu' => [
                    'doanh_thu_cu_dan' => $currentPeriodRegularRevenue,
                    'doanh_thu_khach_vang_lai' => $currentPeriodVisitorRevenue
                ],
                'daily_revenue' => $dailyRevenue
            ]
        ]);
    }

    private function getDailyRevenue($startDate, $endDate)
    {
        $dailyRevenue = [];
        $currentDate = clone $startDate;

        while ($currentDate <= $endDate) {
            $dayStart = (clone $currentDate)->startOfDay();
            $dayEnd = (clone $currentDate)->endOfDay();

            $regularRevenue = GiaoDich::where('trang_thai_giao_dich', 1)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('so_tien_giao_dich');

            $visitorRevenue = BaoCaoKhachVangLai::where('is_thanh_toan', 1)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->sum('tien_thanh_toan');

            $dailyRevenue[] = [
                'date' => $currentDate->format('Y-m-d'),
                'revenue' => $regularRevenue + $visitorRevenue
            ];

            $currentDate->addDay();
        }

        return $dailyRevenue;
    }
}
