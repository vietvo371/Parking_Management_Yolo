<?php

namespace App\Http\Controllers;

use App\Models\ChiTietBaiXe;
use Illuminate\Http\Request;
use Aws\Rekognition\RekognitionClient;
use Aws\Exception\AwsException;
use App\Models\VehicleEntry;
use App\Models\Xe;
use Carbon\Carbon;

class LicensePlateController extends Controller
{
    protected $rekognition;

    public function __construct()
    {
        $this->rekognition = new RekognitionClient([
            'version' => 'latest',
            'region' => env('AWS_DEFAULT_REGION', 'ap-southeast-1'),
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Quét biển số xe từ ảnh sử dụng AWS Rekognition
     */
    public function scanLicensePlate(Request $request)
    {
        $id_chuc_nang = 8;
        $check = $this->checkQuyen($id_chuc_nang);
        if ($check == false) {
            return response()->json([
                'status'  =>  false,
                'message' =>  'Bạn không có quyền chức năng này'
            ]);
        }
        $request->validate([
            'image' => 'required|image|max:5120', // Max 5MB
        ]);

        try {
            // Đọc file ảnh
            $image = $request->file('image');
            $imageBytes = file_get_contents($image->getRealPath());

            // Gọi Rekognition để nhận diện text
            $result = $this->rekognition->detectText([
                'Image' => [
                    'Bytes' => $imageBytes,
                ],
            ]);

            // Xử lý kết quả
            $detectedTexts = isset($result['TextDetections']) ? $result['TextDetections'] : [];
            $licensePlate = $this->extractLicensePlate($detectedTexts);

            $existingEntry = Xe::join('cu_dans','cu_dans.id','xes.id_cu_dan')
                                ->join('can_hos','can_hos.id','cu_dans.id_can_ho')
                                ->where('bien_so_xe', $licensePlate)
                                ->where('xes.is_con_han', 1)
                                ->select('xes.*','cu_dans.ho_va_ten as ten_cu_dan','can_hos.ten_toa_nha as ten_toa_nha','can_hos.so_can_ho as so_can_ho')
                                ->first();
             return response()->json([
                'status'      => true,
                'message'     => 'Không phát hiện được biển số trong ảnh',
                'is_con_han'  => false,
                'is_vao_bai'   => false,

                // 'plate_number' => $licensePlate,
                'plate_number' => array_map(function($text) {
                    return $text['DetectedText'];
                }, array_filter($detectedTexts, function($text) {
                    return $text['Type'] === 'LINE';
                }))

            ]);
            $check_vao_bai = ChiTietBaiXe::join('lich_su_ra_vao_bai_xes','lich_su_ra_vao_bai_xes.id_vi_tri_trong_bai','chi_tiet_bai_xes.id')
                                        ->where('lich_su_ra_vao_bai_xes.id_xe_cu_dan',$existingEntry->id)
                                        ->where('chi_tiet_bai_xes.trang_thai', ChiTietBaiXe::TRANG_THAI_DA_CO_XE)
                                        ->where('lich_su_ra_vao_bai_xes.thoi_gian_ra',null)
                                        ->first();

            if ($licensePlate) {
                return response()->json([
                    'status'       => true,
                    'message'      => 'Đã tìm thấy xe',
                    'is_con_han'   => $existingEntry ? $existingEntry->is_con_han : false,
                    'data'         =>$existingEntry,
                    'plate_number' => $licensePlate,
                    'is_vao_bai'   => $check_vao_bai ? true : false,
                    'is_valid'     => $this->validateLicensePlate($licensePlate)
                ]);
            }



        } catch (AwsException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi AWS: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Trích xuất biển số xe từ các text được phát hiện
     */
    private function extractLicensePlate($detectedTexts)
    {
        // Pattern biển số Việt Nam - mở rộng để nhận dạng nhiều định dạng hơn
        $pattern = '/\d{2}[A-Z][-\s]?\d{1,2}[-\s]?\d{3,5}|\d{2}[A-Z][-\s]?\d{1,2}[\.]\d{2,3}|\d{2}[A-Z]\d{3,5}/';

        // Thử tìm biển số trên từng dòng trước
        foreach ($detectedTexts as $text) {
            if ($text['Type'] === 'LINE' || $text['Type'] === 'WORD') {
                $detectedText = $text['DetectedText'];

                // Tìm biển số theo pattern
                if (preg_match($pattern, $detectedText, $matches)) {
                    return $matches[0];
                }
            }
        }

        // Biểu thức để tìm phần đầu biển số (29-E1, 29E1, v.v)
        $firstPartPattern = '/\d{2}[-\s]?[A-Z]\d{1,2}|\d{2}[A-Z][-\s]?\d{1,2}/';
        // Biểu thức để tìm phần số cuối (456.78, 45678, v.v)
        $secondPartPattern = '/\d{3}[\.,]\d{2}|\d{3,5}/';

        $firstPart = null;
        $secondPart = null;

        // Tìm các phần của biển số
        foreach ($detectedTexts as $text) {
            $detectedText = $text['DetectedText'];

            // Tìm phần đầu
            if (!$firstPart && preg_match($firstPartPattern, $detectedText, $matches)) {
                $firstPart = $matches[0];
                continue;
            }

            // Tìm phần sau
            if (!$secondPart && preg_match($secondPartPattern, $detectedText, $matches)) {
                $secondPart = $matches[0];
            }
        }

        // Nếu tìm thấy cả hai phần, ghép lại
        if ($firstPart && $secondPart) {
            return $firstPart . ' ' . $secondPart;
        }

        // Thử cách ghép tất cả text lại
        $allWords = '';
        foreach ($detectedTexts as $text) {
            if ($text['Type'] === 'WORD') {
                $allWords .= $text['DetectedText'] . ' ';
            }
        }
        $allWords = trim($allWords);

        // Tìm trong chuỗi tổng hợp
        if (preg_match($pattern, $allWords, $matches)) {
            return $matches[0];
        }

        // Kiểm tra trực tiếp mảng detectedTexts đã chuẩn hóa
        $texts = array_map(function($text) {
            return $text['DetectedText'];
        }, $detectedTexts);

        // Tìm thủ công biển số dạng "29-E1" và "456.78"
        foreach ($texts as $i => $text1) {
            if (preg_match('/^\d{2}[-\s]?[A-Z]\d{1,2}$/', $text1)) {
                foreach ($texts as $j => $text2) {
                    if ($i != $j && preg_match('/^\d{3}[\.,]\d{2}$/', $text2)) {
                        return $text1 . ' ' . $text2;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Kiểm tra tính hợp lệ của biển số
     */
    private function validateLicensePlate($plateNumber)
    {
        // Mở rộng pattern để phù hợp với nhiều định dạng biển số hơn
        $pattern = '/\d{2}[A-Z][-\s]?\d{1,2}[-\s]?\d{3,5}|\d{2}[A-Z][-\s]?\d{1,2}[\s\.]\d{2,3}|\d{2}[A-Z]\d{3,5}/';
        return (bool) preg_match($pattern, $plateNumber);
    }

    /**
     * Cho phép xe vào bãi
     */
    public function allowEntry(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:20',
        ]);

        $plateNumber = $request->plate_number;

        // Kiểm tra xe đã trong bãi chưa
        $existingEntry = Xe::where('bien_so_xe', $plateNumber)
            ->where('is_con_han', 1)
            ->first();

        if ($existingEntry) {
            return response()->json([
                'status' => false,
                'message' => "Xe biển số {$plateNumber} đã có trong bãi"
            ]);
        }

        // // Tạo bản ghi mới
        // $vehicleEntry = new ChiTietBaiXe();
        // $vehicleEntry->id_bai_xe = 1;
        // $vehicleEntry->id_xe = $existingEntry->id;
        // $vehicleEntry->created_at = Carbon::now();
        // $vehicleEntry->trang_thai = 1;
        // $vehicleEntry->save();

        return response()->json([
            'status' => true,
            'message' => "Xe biển số {$plateNumber} đã được cho phép vào bãi",
            // 'entry_time' => $vehicleEntry->created_at
        ]);
    }
}