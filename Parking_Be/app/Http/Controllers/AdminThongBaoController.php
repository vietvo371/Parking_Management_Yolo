<?php

namespace App\Http\Controllers;

use App\Jobs\MailQueue;
use App\Models\AdminThongBao;
use App\Models\GiaoDich;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminThongBaoController extends Controller
{
    public function getData()
    {
        $today = Carbon::today();
        $nextWeek = Carbon::today()->addDays(7);

        $thongbaos = GiaoDich::join('xes', 'xes.id', '=', 'giao_diches.id_xe')
            ->join('loai_xes', 'xes.id_loai_xe', '=', 'loai_xes.id')
            ->join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
            ->leftJoin('admin_thong_baos', 'xes.id_cu_dan', '=', 'admin_thong_baos.id_cu_dan')
            ->select(
                'admin_thong_baos.*',
                'cu_dans.ho_va_ten as ten_cu_dan',
                'cu_dans.so_dien_thoai',
                'cu_dans.email',
                'xes.bien_so_xe',
                'loai_xes.ten_loai_xe',
                'giao_diches.ngay_het_han',
                'cu_dans.id as id_cu_dan'

            )
            // ->groupBy('cu_dans.id','cu_dans.ho_va_ten','cu_dans.so_dien_thoai','cu_dans.email','xes.bien_so_xe','loai_xes.ten_loai_xe','giao_diches.ngay_het_han','cu_dans.id','th')
            // ->where('admin_thong_baos.trang_thai', 1)
            ->orderBy('giao_diches.ngay_het_han', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $thongbaos,
        ]);
    }
    public function themThongBao(Request $request)
    {
        $user = $this->isAdmin();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này'
            ]);
        }

        $data = $request->all(); // FE gửi lên là array các object
        $thongbaos = [];

        foreach ($data as $item) {
            $thongbaos[] = AdminThongBao::create([
                'id_admin' => $user->id,
                'id_cu_dan' => $item['id_cu_dan'],
                'noi_dung_thong_bao' => $item['noi_dung'],
                'ngay_tao' => Carbon::now(),
                'trang_thai' => 1,
            ]);
            MailQueue::dispatch($item);
        }

        return response()->json([
            'status' => true,
            'message' => 'Thêm thông báo thành công',
            'data' => $thongbaos
        ]);
    }
    public function getDataThongBaoClient()
    {
        $user = $this->isCuDan();
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền thực hiện hành động này'
            ]);
        }
        $thongbaos = AdminThongBao::where('id_cu_dan', $user->id)->orderBy('ngay_tao', 'desc')->get();
        return response()->json([
            'status' => true,
            'message' => 'Lấy dữ liệu thành công',
            'data' => $thongbaos
        ]);
    }


}
