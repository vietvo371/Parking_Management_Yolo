<?php

namespace App\Http\Controllers;

use App\Models\GiaoDich;
use App\Models\Transaction;
use App\Models\Xe;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ThanhToanController extends Controller
{
    public function index()
    {
        $payload = [
            "USERNAME"   => "khoianhhuynh",
            "PASSWORD"   => "Hakn04082003",
            "DAY_BEGIN" => Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y'),
            "DAY_END"   => Carbon::now('Asia/Ho_Chi_Minh')->format('d/m/Y'),
            "NUMBER_MB"  => "0708585120"
        ];

        try {
            // Gửi yêu cầu đến API
            $client = new Client();
            $response = $client->post("https://api-mb.dzmid.io.vn/api/transactions", ['json' => $payload]);
            $responseData = json_decode($response->getBody(), true);
            $data = $responseData['data']['transactionHistoryList'] ?? [];
            // dd($data['transactionHistoryList']);
            foreach ($data as $value) {
                // Bỏ qua nếu `creditAmount` <= 0 hoặc thiếu key cần thiết
                if (empty($value['creditAmount']) || $value['creditAmount'] <= 0) {
                    continue;
                }

                $ma_hoa_don = $this->convertToId($value['description']);
                $hoaDon = GiaoDich::where('ma_giao_dich', $ma_hoa_don)->first();

                // Kiểm tra hóa đơn hợp lệ
                if (!$hoaDon || $hoaDon->trang_thai_giao_dich == 1) {
                    continue;
                }

                $xe = Xe::find($hoaDon->id_xe);
                $this->handleTransaction($hoaDon, $xe, $value, $ma_hoa_don);
            }
            return response()->json([
                'status' => true,
                'message' => 'Cập nhật giao dịch thành công.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Lỗi khi gọi API: ' . $e->getMessage(), [
                'payload' => $payload,
            ]);
            return response()->json([
                'status' => false,
            ], 500);
        }
    }
    private function handleTransaction($hoaDon, $xe, $value, $ma_hoa_don)
    {
        try {
            // Tạo hoặc cập nhật giao dịch
            $transaction = Transaction::updateOrCreate(
                ['ma_giao_dich' => $ma_hoa_don],
                [
                    'creditAmount' => $value['creditAmount'],
                    'description'  => $value['description'],
                    'pos'          => $value['pos'],
                ]
            );

            // Kiểm tra nếu giao dịch đã tồn tại
            if (!$transaction->wasRecentlyCreated) {
                Log::info('Giao dịch đã tồn tại, không xử lý lại.', [
                    'ma_hoa_don' => $ma_hoa_don,
                ]);
                return;
            }

            // Cộng dồn số tiền đã thanh toán
            $hoaDon->so_tien_giao_dich += $value['creditAmount'];
            $hoaDon->ngay_het_han = Carbon::now('Asia/Ho_Chi_Minh')->addMonths(1);
            $hoaDon->save();

            // // Thông tin email cơ bản
            // $data = [
            //     'ho_ten'                => $xe->ho_va_ten,
            //     'ma_hoa_don'            => $ma_hoa_don,
            //     'so_tien_chuyen_khoan'  => $value['creditAmount'],
            //     'so_tien_da_thanh_toan' => $hoaDon->so_tien_da_thanh_toan,
            //     'email'                 => $xe->email,
            // ];

            // Kiểm tra trạng thái thanh toán
            if ($hoaDon->so_tien_giao_dich >= 100000) {
                // Đã thanh toán đủ
                $hoaDon->update(['trang_thai_giao_dich' => 1]);
                $xe->update([
                    'is_con_han' => 1
                ]);
                $xe->save();
                $data['so_tien_du'] = $hoaDon->so_tien_giao_dich - 100000; // Tiền thừa (nếu có)
                // MailThanhToanQueue::dispatch($data);
            } else {
                // Chưa thanh toán đủ
                // $soTienThieu = $hoaDon->tong_tien - $hoaDon->so_tien_da_thanh_toan;
                // $data['so_tien_can_thanh_toan'] = $hoaDon->tong_tien;
                // $data['tien_thieu'] = $soTienThieu;
                // $data['qr_url'] = 'https://img.vietqr.io/image/mb-0708585120-compact2.jpg?amount='
                //     . $soTienThieu
                //     . '&addInfo=' . $hoaDon->ma_hoa_don
                //     . '&accountName=VO_VAN_VIET';
                // MailThanhToanLoiQueue::dispatch($data);
                return response()->json([
                    'status' => false,
                    'message' => 'Chưa thanh toán đủ'
                ], 400);
            }
        } catch (\Exception $e) {
            // Ghi log nếu không thể tạo giao dịch
            Log::error('Lỗi khi tạo giao dịch: ' . $e->getMessage(), [
                'ma_hoa_don' => $ma_hoa_don,
                'value'      => $value,
            ]);
        }
    }
    public function convertToId($description)
    {
        preg_match('/(Hd[a-zA-Z0-9]+)-/i', $description, $matches);
        return $matches[1] ?? null;
    }
    public function getQrPayMent(Request $request)
    {
        $user = $this->isCuDan();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }

        // Kiểm tra xem user đã có gói  hoạt động chưa
        $existingVip = GiaoDich::where('id_xe', $request->id_xe)
            ->where('ngay_het_han', '>=', Carbon::now('Asia/Ho_Chi_Minh'))
            ->first();

        if ($existingVip) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn đã có gói đang hoạt động. Vui lòng chờ đến khi hết hạn để đăng ký gói mới.'
            ]);
        }

        $hoaDon = $this->createHoaDon($request->id_xe, $request->gia_tien);
        $link = $this->generatePaymentLink($hoaDon);

        return response()->json([
            'status' => true,
            'link' => $link,
            'hoaDon' => $hoaDon,
            'user' => $user,
        ]);
    }

    private function createHoaDon($id_xe, $gia_tien)
    {
        $hoaDon = GiaoDich::create([
            'so_tien_giao_dich' => $gia_tien,
            'ma_giao_dich' => 'HD0' . substr(md5($id_xe . time()), 0, 5),
            'id_xe' => $id_xe,
            'phuong_thuc_thanh_toan' => GiaoDich::PHUONG_THUC_THANH_TOAN_TIEN_MAT,
            'ngay_het_han' => null,
            'trang_thai_giao_dich' => GiaoDich::TRANG_THAI_GIAO_DICH_CHUA_THANH_TOAN
        ]);

        $hoaDon->ma_giao_dich = 'HD0' . substr(md5($id_xe . time()), 0, 5);
        $hoaDon->save();

        return $hoaDon;
    }

    private function generatePaymentLink($hoaDon)
    {
        return 'https://img.vietqr.io/image/mb-7722222222229-compact2.jpg?amount='
            . $hoaDon->so_tien_giao_dich
            . '&addInfo=' . $hoaDon->ma_giao_dich
            . '&accountName=HUYNH_ANH_KHOI';
    }
    public function setTransiton(Request $request)
    {
        $hoaDon = GiaoDich::find($request->id);
        $xe = Xe::find($hoaDon->id_xe);
        $hoaDon->update(['trang_thai_giao_dich' => 1, 'ngay_het_han' => Carbon::now('Asia/Ho_Chi_Minh')->addMonths(1)]);
        $xe->update(['is_con_han' => 1]);
        $xe->save();
        return response()->json([
            'status' => true,
            'message' => 'Cập nhật giao dịch thành công.'
        ], 200);
    }
    public function getDataThanhToanClient(Request $request)
    {
        $user = $this->isCuDan();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Không tìm thấy thông tin người dùng.'
            ]);
        }
        $hoaDon = GiaoDich::join('xes', 'giao_diches.id_xe', '=', 'xes.id')
            ->join('cu_dans', 'xes.id_cu_dan', '=', 'cu_dans.id')
            ->where('xes.id_cu_dan', $user->id)
            ->select('giao_diches.*', 'xes.bien_so_xe', 'cu_dans.ho_va_ten')
            ->get();
        return response()->json([
            'status' => true,
            'data' => $hoaDon,
        ]);
    }
}
